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

        $page = new Page();
        $page->name = 'Login';

        return view('theme.lydias.ecommerce.customer.login');

    }

    public function customer_login(Request $request)
    {
        $insert_logs = ActivityLog::create([
                'created_by' => $request->email ?? 'no record',
                'activity_type' => 'login',
                'dashboard_activity' => 'login',
                'activity_desc' => $request->ip(),
                'activity_date' => date('Y-m-d H:i:s'),
                'reference' => url()->current()
            ]);
        
        $userCredentials = [
            'email'    => $request->email,
            'password' => $request->password
        ];

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            unset($userCredentials['username']);
            $userCredentials['email'] = $request->email;
        }

        $cart = session('cart', []);

        // dd($userCredentials);


        try {
            $user = Auth::attempt($userCredentials);
            dd($user);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', __('auth.login.incorrect_input'));
        }
        
        if (Auth::attempt($userCredentials) && (Auth::user()->is_a_customer_user())) {

            dd('here');

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

            return redirect(route('cart.front.show'));
        } else {
            // Auth::logout();

            return back()->with('error', __('auth.login.incorrect_input'));
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('customer-front.login'));
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
