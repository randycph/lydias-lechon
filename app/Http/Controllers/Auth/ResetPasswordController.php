<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $credentials =  $request->only('email');

        if (is_null($user = $this->broker()->getUser($credentials))) {
            return abort(401);
        }

        if (!$this->broker()->tokenExists($user, $token)) {
            return redirect()->route('password.request')->with('error','Your link is expired. Please reset your password again.');
        }

        if ($request->has('user') && $request->user == 'new') {
            return view('auth.passwords.new-user')->with(
                ['token' => $token, 'email' => $request->email]
            );
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function sendResetResponse(Request $request, $response)
    {
        // if (auth()->user()->is_a_cms_user()) {
        //     return redirect()->route('dashboard')
        //         ->with('success','Password reset successfully!');
        // }

        // if (auth()->user()->is_a_member_user()) {
        //     return redirect()->route('member.dashboard')
        //         ->with('success','Password reset successfully!');
        // }
        return redirect()->route('home')
                ->with('success','Password reset successfully!');

       // return redirect()->route('member-front.sign-up');
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                //'regex:/[a-z]/', // must contain at least one lowercase letter
                //'regex:/[A-Z]/', // must contain at least one uppercase letter
                //'regex:/[0-9]/', // must contain at least one digit
                //'regex:/[@$!%*#?&]/', // must contain a special character
            ]
        ];
    }
}
