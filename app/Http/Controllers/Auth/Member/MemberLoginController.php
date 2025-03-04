<?php

namespace App\Http\Controllers\Auth\Member;

use App\EcommerceModel\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberLoginController extends Controller
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.member.login');
    }

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
        $cart = session('cart', []);

        if (Auth::attempt($userCredentials) && Auth::user()->is_a_member_user()) {
            foreach ($cart as $order) {
                $cart = Cart::where('product_id', $order['product_id'])
                    ->where('user_id', Auth::id())
                    ->first();

                if (!empty($cart)) {
                    $newQty = $cart->qty + $order['qty'];
                    $cart->update([
                        'qty' => $newQty,
                        'price' => $order['price'],
                        'with_installation' => $order['with_installation']
                    ]);
                } else {
                    Cart::create([
                        'product_id' => $order['product_id'],
                        'user_id' => Auth::id(),
                        'qty' => $order['qty'],
                        'price' => $order['price'],
                        'with_installation' => $order['with_installation']
                    ]);
                }
            }

            return redirect(route('member.dashboard'));
        }

        Auth::logout();

        return redirect()->back()->with('error', __('auth.login.incorrect_input'));
    }

    protected function loggedOut()
    {
        return redirect(route('member.login'));
    }
}
