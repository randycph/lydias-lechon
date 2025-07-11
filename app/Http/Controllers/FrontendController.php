<?php

namespace App\Http\Controllers;

use App\EcommerceModel\Branch;
use App\EcommerceModel\Cart;
use App\EcommerceModel\DeliveryStatus;
use App\EcommerceModel\GiftCertificate;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesPayment;
use App\Mail\WelcomeEmail;
use App\Models\Album;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Deliverablecities;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Colors\Rgb\Channels\Red;

class FrontendController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::where('status', 'PUBLISHED')->get();
        $blogs = Article::with('category')
            ->where('is_blog', 1)
            ->where('status', 'Published')
            ->where('category_id', '>', 0)
            ->latest()
            ->limit(10)
            ->get();
        $albums = Album::with('banners')->where('name', 'Home Banner')->first();
        return view('v2.home', compact('categories', 'blogs', 'albums'));
    }

    public function home()
    {
        $categories = ProductCategory::where('status', 'PUBLISHED')->get();
        $blogs = Article::with('category')
            ->where('is_blog', 1)
            ->where('status', 'Published')
            ->where('category_id', '>', 0)
            ->latest()
            ->limit(10)
            ->get();
        $albums = Album::with('banners')->where('name', 'Home Banner')->first();
        return view('v2.home', compact('categories', 'blogs', 'albums'));
    }

    public function our_story()
    {
        return view('v2.our-story');
    }

    public function our_stores()
    {
        $headOffices = Branch::where('is_head_office', 1)->get();
        $branches = Branch::with('numbers')->where('is_head_office', 0)->get();
        $outlets = Branch::where('branch_type', 'Restaurant')->where('is_head_office', 0)->get();
        $malls = Branch::where('branch_type', 'Mall Based Foodcourt')->where('is_head_office', 0)->get();
        $kiosks = Branch::where('branch_type', 'Kiosk')->where('is_head_office', 0)->get();

        $page = Page::where('slug', 'stores')->first();

        return view('v2.our-stores', compact('headOffices', 'branches', 'outlets', 'malls', 'kiosks', 'page'));
    }

    public function lechon_pricelist()
    {
        $products = Product::with([
            'photos',
            'addonProducts' => function ($q) {
                $q->with(['photos']);
            }
        ])
        ->where('category_id', 1)
        ->where('status', 'PUBLISHED')->get();

        foreach ($products as $product) {
            if ($product->addonProducts->isEmpty()) {
                $addonProductIds = DB::table('ecommerce_sales_details')
                    ->select('product_id', DB::raw('COUNT(id) as total'))
                    ->whereIn('sales_header_id', function ($query) use ($product) {
                        $query->select('sales_header_id')
                                ->from('ecommerce_sales_details')
                                ->where('product_id', $product->id);
                    })
                    ->where('product_id', '!=', $product->id)
                    ->groupBy('product_id')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->pluck('product_id');

                $fallbackAddons = Product::whereIn('id', $addonProductIds)
                    ->where('status', 'PUBLISHED')
                    ->with(['photos'])
                    ->get();

                $product->setRelation('addonProducts', $fallbackAddons);
            }
        }

        return view('v2.lechon-pricelist', compact('products'));
    }

    public function lechon_menu()
    {
        $categories = ProductCategory::with(['products' => function ($query) {
            $query->where('status', 'PUBLISHED')
                ->with([
                    'photos',
                    'addonProducts' => function ($q) {
                        $q->with(['photos']);
                    }
                ]);
        }])
        ->where('status', 'PUBLISHED')
        ->get();

        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                if ($product->addonProducts->isEmpty()) {
                    $addonProductIds = DB::table('ecommerce_sales_details')
                        ->select('product_id', DB::raw('COUNT(id) as total'))
                        ->whereIn('sales_header_id', function ($query) use ($product) {
                            $query->select('sales_header_id')
                                    ->from('ecommerce_sales_details')
                                    ->where('product_id', $product->id);
                        })
                        ->where('product_id', '!=', $product->id)
                        ->groupBy('product_id')
                        ->orderByDesc('total')
                        ->limit(5)
                        ->pluck('product_id');
        
                    $fallbackAddons = Product::whereIn('id', $addonProductIds)
                        ->where('status', 'PUBLISHED')
                        ->with(['photos'])
                        ->get();
        
                    $product->setRelation('addonProducts', $fallbackAddons);
                }
            }
        }

        return view('v2.lechon-menu', compact('categories'));
    }

    public function checkout()
    {
        $page = 'checkout';

        if (Auth::check() && Auth()->user()->role_id != 6) {
            return redirect()->route('my-account')->with('error', 'You are not allowed to access this page. Please contact support for assistance.');
        }

        if (Auth::check()) {
            $carts = Cart::where('user_id', Auth::id())->with('product.photos')->get();
        } else {
            $carts = collect(session('cart', [])); 
        }

        $pickupBranches = Branch::orderBy('name', 'asc')->where('pickup_branch', 1)->get();

        $deliveryBranches = Branch::orderBy('name', 'asc')->where('delivery_branch', 1)->get();

        $locations = Deliverablecities::distinct()->orderBy('name')->get(['name']);

        $setting = Setting::first();

        $disabledPickupDates = explode(',', $setting->disable_pickup_dates ?? '');
        $disabledDeliveryDates = explode(',', $setting->disable_delivery_dates ?? '');

        $haslechon  = $carts->contains(function ($cart) {
            return $cart->product->category_id == 1;
        });

        $hasbaka = $carts->contains(function ($cart) {
            return $cart->product->slug == 'lechon-baka';
        });

        return view('v2.checkout', compact('page', 'carts', 'pickupBranches', 'locations', 'deliveryBranches', 'disabledPickupDates', 'disabledDeliveryDates', 'haslechon', 'hasbaka'));
    }

    public function confirmation($id)
    {
        $page = 'confirmation';

        $undecodeId = $id;
        
        if (ctype_digit($id)) {
            $id = $undecodeId;
        } else {
            $id = base64_decode($id);
        }

        $sales = SalesHeader::where('id',$id)->with('deliveryAddress', 'items', 'couponUsed')->first();
        $gc = GiftCertificate::where('sales_header_id',$id)->get();
        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails = SalesDetail::with('product.photos')->where('sales_header_id',$id)->get();
        $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        $deliveries = DeliveryStatus::where('order_id',$id)->get();
        $totalNet = SalesHeader::where('id',$id)->sum('net_amount');

        if($totalNet <= $totalPayment) {
            $status = 'PAID';
        } else {
            $status = 'UNPAID';
            if($totalPayment > 0){
                $status = 'PARTIAL';
            }
        }

        return view('v2.confirmation', compact('page', 'sales', 'salesPayments', 'salesDetails', 'status', 'deliveries', 'gc', 'totalPayment', 'totalNet'));
    }

    public function login()
    {
        $page = 'login';

        if (Auth::check()) {
            return redirect()->intended(route('my-account'));
        }

        return view('v2.login', compact('page'));
    }

    public function logout()
    {
        Auth::logout();

        return redirect(route('home'));
    }

    public function forgot_password()
    {
        $page = 'forgot-password';
        return view('v2.forgot-password', compact('page'));
    }

    public function signup()
    {
        $page = 'signup';
        return view('v2.signup', compact('page'));
    }

    public function my_account(Request $request)
    {
        $page = 'my-account';

        $request->session()->forget('redirect_after_login');
        
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => $request->fullUrl()]);
        }

        return view('v2.my-account', compact('page'));
    }

    public function my_cart(Request $request)
    {
        $page = 'my-cart';
        
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => $request->fullUrl()]);
        }

        if (Auth::check() && Auth()->user()->role_id != 6) {
            return redirect()->route('my-account')->with('error', 'You are not allowed to access this page. Please contact support for assistance.');
        }

        return view('v2.my-cart', compact('page'));
    }

    public function order_history(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => $request->fullUrl()]);
        }

        if (Auth::check() && Auth()->user()->role_id != 6) {
            return redirect()->route('my-account')->with('error', 'You are not allowed to access this page. Please contact support for assistance.');
        }

        $page = 'order-history';

        $sales = SalesHeader::where('user_id', Auth::id())
                            ->with([
                                'couponUsed',
                                'items.product.photos',
                                'payments' => function ($query) {
                                    $query->where('status', 'PAID');
                                },
                                'deliveryStatus' => function ($query) {
                                    $query->orderBy('created_at', 'asc');
                                },
                            ])
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('v2.order-history', compact('page', 'sales'));
    }

    public function change_password(Request $request)
    {
        $page = 'change-password';

        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => $request->fullUrl()]);
        }

        return view('v2.change-password', compact('page'));
    }

    public function carrers()
    {
        return view('v2.careers');
    }

    public function privacyPolicy()
    {
        return view('v2.privacy-policy');
    }

    public function termsOfService()
    {
        return view('v2.terms-of-use');
    }


    public function blogs()
    {
        $featuredArticle = Article::with('category')
                                ->where('is_featured', 1)
                                ->where('category_id', '>', 0)
                                ->where('status', 'Published')
                                ->latest()
                                ->first();
        $featuredArticle->image_url = empty($featuredArticle->image_url) ? $featuredArticle->thumbnail_url : $featuredArticle->image_url;

        $categories = ArticleCategory::get();
        $blogs = Article::with('category')
                        ->where('is_blog', 1)
                        ->where('status', 'Published')
                        ->where('category_id', '>', 0)
                        ->latest()
                        ->paginate(4);
        return view('v2.blogs', compact('featuredArticle', 'categories', 'blogs'));
    }

    public function blogCategory()
    {
        $featuredArticle = Article::with('category')
                                ->where('is_featured', 1)
                                ->where('category_id', '>', 0)
                                ->where('status', 'Published')
                                ->latest()
                                ->first();
        $featuredArticle->image_url = empty($featuredArticle->image_url) ? $featuredArticle->thumbnail_url : $featuredArticle->image_url;

        $categories = ArticleCategory::get();

        $category = ArticleCategory::where('slug', request()->category)->first();

        if (!$category) {
            abort(404);
        }

        $blogs = Article::with('category')
                        ->where('category_id', $category->id)
                        ->where('is_blog', 1)
                        ->where('status', 'Published')
                        ->where('category_id', '>', 0)
                        ->latest()
                        ->paginate(4);
        return view('v2.blogs-category', compact('featuredArticle', 'categories', 'blogs', 'category'));
    }

    public function article($category, $slug)
    {
        $article = Article::with('category')
            ->where('slug', $slug)
            ->where('status', 'Published')
            ->firstOrFail();

        // Get next article (newer)
        $next = Article::where('status', 'Published')
            ->where('id', '>', $article->id)
            ->orderBy('id', 'asc')
            ->first();

        // Get previous article (older)
        $previous = Article::where('status', 'Published')
            ->where('id', '<', $article->id)
            ->orderBy('id', 'desc')
            ->first();

        $relatedNews = Article::with('category')
            ->where('category_id', $article->category_id)
            ->where('status', 'Published')
            ->where('id', '!=', $article->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('v2.article', compact('category', 'slug', 'article', 'next', 'previous', 'relatedNews'));
    }

    public function userLogout(Request $request)
    {
        $request->session()->forget('redirect_after_login');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::logout();

        return redirect()->route('home');
    }

    public function signupStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email:rfc,dns|unique:users,email',
                'password' => 'required|confirmed|min:6',
                'account_type' => 'required|in:individual,organization',
                'first_name' => [
                    'required_if:account_type,individual',
                    'nullable',
                    'regex:/^[A-Za-z\s\-]+$/'
                ],
                'last_name' => [
                    'required_if:account_type,individual',
                    'nullable',
                    'regex:/^[A-Za-z\s\-]+$/'
                ],
                'birth_date' => 'nullable|date',
                'org_name' => [
                    'required_if:account_type,organization',
                    'nullable',
                    'regex:/^[A-Za-z\s\-]+$/'
                ],
                'country' => 'required|string',
                'address_street' => 'required_if:country,Philippines|nullable|string',
                'address_city' => 'required_if:country,Philippines|nullable|string',
                'address_municipality' => 'required_if:country,Philippines|nullable|string',
                'address_region' => 'required_if:country,Philippines|nullable|string',
                'address_brgy' => 'required_if:country,Philippines|nullable|string',
                'international_address' => 'nullable|required_unless:country,Philippines|nullable|string',
                'mobile' => [
                    'required',
                    'regex:/^(09|\+639)\d{9}$/'
                ],
            ], [
                'contact_mobile.regex' => 'The mobile number must start with 09 or +639 and be followed by 9 digits.',
            ]);

            if ($request->has('country') && $request->input('country') == 'Philippines') {
                $request['international_address'] = null;
            }

            if ($request->has('country') && $request->input('country') != 'Philippines') {
                $request['address_street'] = null;
                $request['address_city'] = null;
                $request['address_municipality'] = null;
                $request['address_region'] = null;
                $request['address_brgy'] = null;
            }
        
            if ($request->account_type == 'organization') {
                $user = User::create([
                    'name' => $request->org_name,
                    'firstname' => $request->org_name,
                    'lastname' => $request->org_name,
                    'password' => Hash::make($request->password),
                    'email' => $request->email,
                    'organization' => $request->org_name ?? $request->organization,
                    'address_street' => $request->address_street,
                    'address_municipality' => $request->address_municipality,
                    'country' => $request->country,
                    'address_city' => $request->address_city,
                    'address_region' => $request->address_region,
                    'address_brgy' => $request->address_brgy,
                    'international_address' => $request->international_address,
                    'contact_person' => $request->contact_person,
                    'contact_tel' => $request->tel,
                    'contact_mobile' => $request->mobile,
                    'contact_fax' => $request->fax,
                    'registration_source' => 'web',
                    'agent_code' => $request->agent_code,
                    'remember_token' => Str::random(10),
                    'is_active' => 1,
                    'is_org' => $request->input('account_type') === 'organization' ? 1 : 0,
                    'is_subscribe' => $request->is_subscribe ?? 0,
                    'role_id' => 6
                ]);
            } elseif ($request->account_type == 'individual') {
                $user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'firstname' => $request->first_name,
                    'lastname' => $request->last_name,
                    'password' => Hash::make($request->password),
                    'email' => $request->email,
                    'birthday' => $request->birth_date,
                    'country' => $request->country,
                    'address_street' => $request->address_street,
                    'address_municipality' => $request->address_municipality,
                    'address_city' => $request->address_city,
                    'address_region' => $request->address_region,
                    'address_brgy' => $request->address_brgy,
                    'international_address' => $request->international_address,
                    'contact_person' => null,
                    'organization' => null,
                    'contact_tel' => $request->tel,
                    'contact_mobile' => $request->mobile,
                    'contact_fax' => $request->fax,
                    'registration_source' => 'web',
                    'agent_code' => $request->agent_code,
                    'remember_token' => Str::random(10),
                    'is_active' => 1,
                    'is_subscribe' => $request->is_subscribe ?? 0,
                    'role_id' => 6
                ]);
            }

            Auth::login($user);
            
            try {
                Mail::to($user->email)->send(new WelcomeEmail($user));
            } catch (\Exception $th) {
                //throw $th;
            }

            $redirectTo = $request->input('redirect') ?? route('my-account');
            return redirect()->intended($redirectTo);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function savePersonalInformation(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'organization' => [
                'required_if:account_type,organization',
                'nullable',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'firstname' => [
                'required_if:account_type,individual',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'lastname' => [
                'required_if:account_type,individual',
                'regex:/^[A-Za-z\s\-]+$/'
            ],
            'birthday' => 'nullable|date',
            'contact_mobile' => [
                'required',
                'regex:/^(09|\+639)\d{9}$/'
            ],
            'receive_updates' => 'nullable',
            'email' => 'required|email:rfc,dns|max:191|unique:users,email,' . Auth::id(), 

        ], [
            'contact_mobile.regex' => 'The mobile number must start with 09 or +639 and be followed by 9 digits.',
        ]);

        $user = Auth::user();

        if ($request->has('receive_updates')) {
            $validated['receive_updates'] = $request->receive_updates ? 1 : 0;
        } else {
            $validated['receive_updates'] = 0;
        }

        if ($request->account_type == 'organization') {
            $validated['name'] = $request->organization;
        } elseif ($request->account_type == 'individual') {
            $validated['name'] = $request->firstname . ' ' . $request->lastname;
        }
        $user->update($validated);

        return redirect(route('my-account'))->with('success', 'Personal information updated successfully!');
    }

    public function saveDeliveryAddress(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($request->has('country') && $request->input('country') == 'Philippines') {
            $request['international_address'] = null;
        }

        if ($request->has('country') && $request->input('country') != 'Philippines') {
            $request['address_street'] = null;
            $request['address_city'] = null;
            $request['address_municipality'] = null;
            $request['address_region'] = null;
            $request['address_brgy'] = null;
        }

        $validated = $request->validate([
            'country' => 'required|string',
            'international_address' => 'required_unless:country,Philippines|nullable|string',
            'address_street' => 'required_if:country,Philippines|nullable|string',
            'address_municipality' => 'required_if:country,Philippines|nullable|string',
            'address_city' => 'required_if:country,Philippines|nullable|string',
            'address_brgy' => 'required_if:country,Philippines|nullable|string',
            'address_region' => 'required_if:country,Philippines|nullable|string',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return redirect(route('my-account'))->with('success', 'Delivery address updated successfully!');
    }

    public function signupValidateFields(Request $request)
    {
        $step = $request->input('step');

        $rules = [];

        switch ($step) {
            case 1:
                $rules = [
                    'email' => 'required|email:rfc,dns|max:191|unique:users,email',
                    'password' => 'required|min:6|confirmed',
                ];
                break;

            case 2:
                $rules = [
                    'account_type' => 'required|in:individual,organization',
                ];
                break;

            case 3:
                $rules = $request->input('account_type') === 'individual'
                    ? [
                        'first_name' => [
                            'required',
                            'string',
                            'regex:/^[A-Za-z\s\-]+$/'
                        ],
                        'last_name' => [
                            'required',
                            'string',
                            'regex:/^[A-Za-z\s\-]+$/'
                        ],
                        'birth_date' => 'nullable|date',
                        'country' => 'required|string',
                        'address_street' => 'required_if:country,Philippines|string',
                        'address_city' => 'required_if:country,Philippines|string',
                        'address_municipality' => 'required_if:country,Philippines|string',
                        'address_region' => 'required_if:country,Philippines|string',
                        'address_brgy' => 'required_if:country,Philippines|string',
                        'international_address' => 'required_unless:country,Philippines|string',
                    ]
                    : [
                        'country' => 'required|string',
                        'org_name' => [
                            'required',
                            'string',
                            'regex:/^[A-Za-z\s\-]+$/'
                        ],
                        'contact_person' => [
                            'required',
                            'string',
                            'regex:/^[A-Za-z\s\-]+$/'
                        ],
                        'address_street' => 'required_if:country,Philippines|string',
                        'address_city' => 'required_if:country,Philippines|string',
                        'address_municipality' => 'required_if:country,Philippines|string',
                        'address_region' => 'required_if:country,Philippines|string',
                        'address_brgy' => 'required_if:country,Philippines|string',
                        'international_address' => 'required_unless:country,Philippines|string',
                    ];
                break;

            case 4:
                $rules = [
                    'mobile' => [
                        'required',
                        'regex:/^(09|\+639)\d{9}$/'
                    ]
                ];
                break;
        }

        $validator = Validator::make($request->all(), $rules, [
            'contact_mobile.regex' => 'The mobile number must start with 09 or +639 and be followed by 9 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }


        return response()->json(['success' => true]);
    }

    public function articleLoadMore(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 4);

        $articles = Article::with('category')
            ->where('is_blog', 1)
            ->where('status', 'Published')
            ->where('category_id', '>', 0)
            ->latest()
            ->paginate($limit, ['*'], 'page', $page);

        $html = view('v2.partials.articles', compact('articles'))->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $articles->hasMorePages()
        ]);
    }

    public function articleCategoryLoadMore(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 4);
        $category = $request->input('category');
        $category = ArticleCategory::where('slug', $category)->first();

        $articles = Article::with('category')
            ->where('category_id', $category->id)
            ->where('is_blog', 1)
            ->where('status', 'Published')
            ->where('category_id', '>', 0)
            ->latest()
            ->paginate($limit, ['*'], 'page', $page);

        $html = view('v2.partials.articles', compact('articles'))->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $articles->hasMorePages()
        ]);
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();

        if (!$page) {
            abort(404);
        }

        return view('v2.page', compact('page'));
    }
}
