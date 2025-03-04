<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Page;
use App\ViewPermissions;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Session;
use Cookie;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
//    public function showLoginForm()
//    {
//        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();
//
//        return view('theme.'.env('FRONTEND_TEMPLATE').'.pages.login', compact('footer'));
//    }

    public function login(Request $request)
    {
        $userCredentials = [
            'username'    => $request->email,
            'password' => $request->password
        ];

        if(filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            unset($userCredentials['username']);
            $userCredentials['email'] = $request->email;
        }
        
        $insert_logs = \App\ActivityLog::create([
                'created_by' => $request->email ?? 'no record',
                'activity_type' => 'login',
                'dashboard_activity' => 'login',
                'activity_desc' => \Request::ip(),
                'activity_date' => date('Y-m-d H:i:s'),
                'reference' => url()->current()
            ]);

        //return redirect(route('dashboard'));     
        if (Auth::attempt($userCredentials)) {
            if (auth()->user()->is_active) {
                if($request->has('is_kiosk')){
                    Cookie::queue('branch', $request->branch, 60);
                    return redirect(route('kiosk.home'));
                } else {
                   Session::put('branch',$request->branch);
                    return redirect(route('dashboard')); 
                }
            }
            else {
                auth()->logout();
                return back()->with('error', __('auth.login.inactive_user'));
            }
        }

        Auth::logout();

        return back()->with('error', __('auth.login.incorrect_input'));
    }

    protected function logout()
    {
        $msg = Session::get('success');

        Auth::logout();
        Session::flush();

        return redirect()->route('login')->with('msg', $msg);
    }
}
