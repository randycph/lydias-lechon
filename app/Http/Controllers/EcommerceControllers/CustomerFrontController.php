<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\EcommerceModel\Member;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Webfocus\Setting;
use App\Models\ActivityLog;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Support\Str;

class CustomerFrontController extends Controller
{
    public function sign_up(Request $request) {

        $page = new Page();
        $page->name = 'Sign Up';

        return view('theme.lydias.ecommerce.customer.sign-up');

    }

    public function customer_sign_up(Request $request) {

        $request->validate([
            'email' => 'required|email|max:191|unique:users,email',
            'address_street' => 'required',
            'address_municipality' => 'required',
            'address_city' => 'required',
            'address_region' => 'required',
            'contact_person' => '',
            'contact_tel' => '',
            'contact_mobile' => 'required',
            'contact_fax' => '',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8'
        ]);

        if($request->is_org == 1){ // for organization
            $user = User::create([
                'name' => $request->organization,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'valid_email' => $request->email ?? null,
                'lastname' => $request->organization,                
                'address_street' => $request->address_street,
                'address_municipality' => $request->address_municipality,
                'address_city' => $request->address_city,
                'address_region' => $request->address_region,
                'contact_person' => $request->contact_person,
                'organization' => $request->organization,
                'contact_tel' => $request->contact_tel,
                'contact_mobile' => $request->contact_mobile,
                'contact_fax' => $request->contact_fax,
                'registration_source' => 'web',
                'agent_code' => $request->agent_code,
                'remember_token' => Str::random(10),
                'is_active' => 1,
                'is_org' => 1,
                'is_subscribe' => $request->issubscribe ?? 0
            ]);
        } else {
            $user = User::create([
                'name' => $request->fname.' '.$request->lname,
                'password' => Hash::make($request->password),
                'firstname' => $request->fname,
                'lastname' => $request->lname,
                'birthday' => $request->birthday,
                'email' => $request->email,
                'address_street' => $request->address_street,
                'address_municipality' => $request->address_municipality,
                'address_city' => $request->address_city,
                'address_region' => $request->address_region,
                'contact_person' => '',
                'organization' => '',
                'contact_tel' => $request->contact_tel,
                'contact_mobile' => $request->contact_mobile,
                'contact_fax' => $request->contact_fax,
                'registration_source' => 'web',
                'agent_code' => $request->agent_code,
                'remember_token' => Str::random(10),
                'is_active' => 1,
                'is_subscribe' => $request->issubscribe ?? 0
            ]);   
        }

        //Auth::login($user);

        return redirect(route('customer-front.login'))->with('success_registration','Your registration has been successfull');
    }

    public function get_random_code($length = 6)
    {
        $token = "";
        $codeAlphabet= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        $member = \App\EcommerceModel\Member::where('code', $token)->first();

        while($token == "" || $member) {
            $token = "";
            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[random_int(0, $max-1)];
            }
            $member = \App\EcommerceModel\Member::where('code', $token)->first();
        }

        return $token;
    }

    public function login(Request $request) {

        // $user = User::where('email', 'olivet@webfocus.ph')->get();

        // $user = User::find(29918);
        // $user->password = Hash::make('password');
        // $user->save();

        $page = new Page();
        $page->name = 'Login';

        return view('theme.lydias.ecommerce.customer.login');

    }

    public function customer_login(Request $request)
    {
    
        $userCredentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            unset($userCredentials['username']);
            $userCredentials['email'] = $request->email;
        }

        $remember = $request->has('remember');

        $cart = session('cart', []);

        
        if (Auth::attempt($userCredentials, $remember)) {

            if ((Auth::user()->is_a_customer_user())) {
                foreach ($cart as $order) {
                    $product = Product::find($order['product_id']);
                    $cart = Cart::where('product_id', $order['product_id'])
                        ->where('user_id', Auth::id())
                        ->first();
    
                    if (!empty($cart)) {
                        $newQty = $cart->qty + $order['qty'];
                        $cart->update([
                            'qty' => $newQty,
                            'price' => $product->price,
                            'paella_price' => $order['paella_price']
                        ]);
                    } else {
                        Cart::create([
                            'product_id' => $order['product_id'],
                            'user_id' => Auth::id(),
                            'qty' => $order['qty'],
                            'price' => $product->price,
                            'paella_price' => $order['paella_price']
                        ]);
                    }
                }
    
                session()->forget('cart');
            } else {
                if (Auth::user()->role_id == config('auth.driver_role_id') ) {
                    return redirect()->route('sales-transaction.driver_sales_transaction');
                }

                return redirect()->route('dashboard');
            }


            ActivityLog::create([
                'created_by' => Auth::id(),
                'activity_type' => 'login',
                'dashboard_activity' => 'login',
                'activity_desc' => $request->ip(),
                'activity_date' => date('Y-m-d H:i:s'),
                'reference' => url()->current()
            ]);

            $sessionId = $request->session()->getId();

            ActivityLog::where('session_id', $sessionId)
                ->whereNull('session_owner_id')
                ->update([
                    'session_owner_id' => Auth::id(),
                    'created_by' => Auth::id(),
                ]);

            $redirectTo = $request->input('redirect') ?? route('my-account');
            return redirect()->intended($redirectTo);
        } else {
            // Auth::logout();
            return back()->with('error', __('auth.login.incorrect_input'))->withErrors(['email' => 'Invalid credentials.']);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('home'));
    }

    public function forgot_password(Request $request) {

        $page = new Page();
        $page->name = 'Forgot Password';

        return view('theme.lydias.ecommerce.customer.forgot-password');

    }

    public function customer_forgot_password(Request $request) {

        return back();

    }

    public function register_guest(Request $request) {

        $page = new Page();
        $page->name = 'Forgot Password';

        return view('theme.lydias.ecommerce.customer.register-guest');

    }
}
