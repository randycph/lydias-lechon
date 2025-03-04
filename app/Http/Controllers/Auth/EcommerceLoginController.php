<?php

namespace App\Http\Controllers\Auth;

use App\Page;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EcommerceLoginController extends Controller
{

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

    public function showLoginForm()
    {
        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        return view('theme.'.env('FRONTEND_TEMPLATE').'.pages.login', compact('footer'));
    }
}
