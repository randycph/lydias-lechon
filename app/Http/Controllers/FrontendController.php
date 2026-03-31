<?php

namespace App\Http\Controllers;

use App\EcommerceModel\Branch;
use App\EcommerceModel\Cart;
use App\EcommerceModel\Coupon;
use App\EcommerceModel\CouponCart;
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
use App\Models\Sms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Intervention\Image\Colors\Rgb\Channels\Red;

class FrontendController extends Controller
{
    public function index()
    {
        // return response()->file(public_path('maintenance.html'));

        $page = Page::where('slug', 'home')->first();

        if (!$page) {
            abort(404);
        }

        $albums = Album::with('banners')->where('name', 'Home Banner')->first();

        return view('v2.page', compact('page', 'albums'));

        // $categories = ProductCategory::where('status', 'PUBLISHED')->get();
        // $blogs = Article::with('category')
        //     ->where('is_blog', 1)
        //     ->where('status', 'Published')
        //     ->where('category_id', '>', 0)
        //     ->latest()
        //     ->limit(10)
        //     ->get();
        // $albums = Album::with('banners')->where('name', 'Home Banner')->first();
        // return view('v2.home', compact('categories', 'blogs', 'albums'));
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
        return redirect()->route('home');
        
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
        $categories = ProductCategory::query()
            ->where('status', 'PUBLISHED')
            ->whereHas('products', function ($q) {
                $q->where('status', 'PUBLISHED');
            })
            ->with([
                'products' => function ($q) {
                    $q->where('status', 'PUBLISHED')
                    ->with([
                        'photos',
                        'addonProducts.photos',
                    ]);
                },
            ])
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

<<<<<<< Updated upstream
    public function checkout()
=======
     public function checkout()
>>>>>>> Stashed changes
    {
$page = 'checkout';

if (Auth::check() && Auth()->user()->role_id != 6) {
    return redirect()->route('my-account')->with('error', 'You are not allowed to access this page. Please contact support for assistance.');
}

// Initialize carts with consistent structure
$cartItems = [];
$haslechon = false;
$hasbaka = false;
$hasMisc = false;
$hasCochinillo = false;

if (Auth::check()) {
    $dbCarts = Cart::where('user_id', Auth::id())->with('product.photos')->get();

    foreach ($dbCarts as $cart) {
        $cartItem = [
            'id' => $cart->id,
            'product_id' => $cart->product_id,
            'qty' => $cart->qty,
            'price' => $cart->price,
            'paella_price' => $cart->paella_price,
            'is_free_product' => $cart->is_free_product ?? false,
            'product' => [
                'id' => $cart->product->id,
                'name' => $cart->product->name,
                'slug' => $cart->product->slug,
                'category_id' => $cart->product->category_id,
                'is_misc' => $cart->product->is_misc ?? 0,
                'paella_price' => $cart->product->paella_price ?? 0,
                'photos' => $cart->product->photos ?? [],
            ],
        ];

        $cartItems[] = $cartItem;

<<<<<<< Updated upstream
        $table = (new Deliverablecities)->getTable();

        $pickOnePerName = Deliverablecities::selectRaw('MAX(id) AS id')->where('is_active', 1)
            ->groupBy('name');

        $locations = Deliverablecities::query()
            ->where('is_active', 1)
            ->joinSub($pickOnePerName, 'p', "$table.id", '=', 'p.id')
            ->orderBy("$table.name")
            ->get(); // full rows, unique by name

        $provinces = Deliverablecities::query()
            ->select('province')
            ->where('is_active', 1)
            ->whereNotNull('province')->where('province', '!=', '')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        // $cities = Deliverablecities::query()
        //     ->select('city')
        //     ->whereNotNull('city')->where('city', '!=', '')
        //     ->distinct()
        //     ->orderBy('city')
        //     ->pluck('city');

        $cities = Deliverablecities::query()
            ->select('city', 'province')
            ->where('is_active', 1)
            ->whereNotNull('city')->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')->get();

        $triples = Deliverablecities::query()
            ->select('city', 'province')
            ->where('is_active', 1)
            ->whereNotNull('city')->where('city', '!=', '')
            ->whereNotNull('province')->where('province', '!=', '')
            ->distinct()
            ->orderBy('city')->orderBy('province')->orderBy('barangay')
            ->get();

        $setting = Setting::first();

        $disabledPickupDates = explode(',', $setting->disable_pickup_dates ?? '');
        $disabledDeliveryDates = explode(',', $setting->disable_delivery_dates ?? '');

        $haslechon  = $carts->contains(function ($cart) {
            return $cart->product->category_id == 1;
        });

        $hasbaka = $carts->contains(function ($cart) {
            return $cart->product->slug == 'lechon-baka';
        });

        $hasMisc = $carts->contains(function ($cart) {
            return $cart->product->is_misc == 1;
        });

        $dataPrivacy = Page::where('slug', 'data-privacy')->first();

        $dataPrivacyRender = view('v2.data-privacy', compact('dataPrivacy'))->render();

        $now = now()->toDateTimeString();
        $uid = Auth::id();

        $eligibleCoupons = Coupon::query()
            ->where('status', 'ACTIVE')
            ->whereRaw("CONCAT(start_date, ' ', start_time) <= ?", [$now])
            ->whereRaw("CONCAT(end_date, ' ', end_time) >= ?", [$now])
            ->where(function ($q) use ($uid) {
                // visible to everyone
                $q->whereNull('customer_scope')->orWhere('customer_scope', 'all')
                // visible only if user's ID is in the list
                ->orWhere(function ($x) use ($uid) {
                    $x->where('customer_scope', 'specific')
                        ->whereRaw(
                            "FIND_IN_SET(?, REPLACE(REPLACE(scope_customer_id, ' ', ''), '|', ','))",
                            [$uid]
                        );
                });
            })
            ->get();

        // dd($eligibleCoupons);

        return view('v2.checkout', compact('triples', 'provinces', 'cities', 'page', 'dataPrivacyRender', 'carts', 'pickupBranches', 'locations', 'deliveryBranches', 'disabledPickupDates', 'disabledDeliveryDates', 'haslechon', 'hasbaka', 'hasMisc', 'eligibleCoupons'));
=======
        if ($cart->product->category_id == 1) $haslechon = true;
        if ($cart->product->slug == 'lechon-baka') $hasbaka = true;
        if ($cart->product->is_misc == 1) $hasMisc = true;
        if ($cart->product_id === 165) $hasCochinillo = true;
    }

    $carts = collect($cartItems);
} else {
    $sessionCarts = session('cart', []);

    foreach ($sessionCarts as $key => $cart) {
        $cartItem = [
            'id' => $key,
            'product_id' => $cart['product_id'] ?? $key,
            'qty' => $cart['qty'] ?? 1,
            'price' => $cart['price'] ?? 0,
            'paella_price' => $cart['paella_price'] ?? 0,
            'is_free_product' => $cart['is_free_product'] ?? false,
            'product' => [
                'id' => $cart['product']['id'] ?? $key,
                'name' => $cart['product']['name'] ?? 'Product',
                'slug' => $cart['product']['slug'] ?? '',
                'category_id' => $cart['product']['category_id'] ?? null,
                'is_misc' => $cart['product']['is_misc'] ?? 0,
                'paella_price' => $cart['product']['paella_price'] ?? 0,
                'photos' => $cart['product']['photos'] ?? [],
            ],
        ];

        $cartItems[] = $cartItem;

        if (($cart['product']['category_id'] ?? null) == 1) $haslechon = true;
        if (($cart['product']['slug'] ?? '') == 'lechon-baka') $hasbaka = true;
        if (($cart['product']['is_misc'] ?? 0) == 1) $hasMisc = true;
        if (($cart['product_id'] ?? null) === 165) $hasCochinillo = true;
    }

    $carts = collect($cartItems);
}

$pickupBranches = Branch::where('status', 1)->where('pickup_branch', 1)->orderBy('name', 'asc')->get();
$deliveryBranches = Branch::where('status', 1)->where('delivery_branch', 1)->orderBy('name', 'asc')->get();

$table = (new Deliverablecities)->getTable();

$pickOnePerName = Deliverablecities::selectRaw('MAX(id) AS id')
    ->where('is_active', 1)
    ->groupBy('name');

$locations = Deliverablecities::query()
    ->where('is_active', 1)
    ->joinSub($pickOnePerName, 'p', "$table.id", '=', 'p.id')
    ->orderBy("$table.name")
    ->get();

$provinces = Deliverablecities::query()
    ->select('province')
    ->where('is_active', 1)
    ->whereNotNull('province')
    ->where('province', '!=', '')
    ->distinct()
    ->orderBy('province')
    ->pluck('province');

$cities = Deliverablecities::query()
    ->select('city', 'province')
    ->where('is_active', 1)
    ->whereNotNull('city')
    ->where('city', '!=', '')
    ->distinct()
    ->orderBy('city')
    ->get();

$triples = Deliverablecities::query()
    ->select('city', 'province')
    ->where('is_active', 1)
    ->whereNotNull('city')
    ->where('city', '!=', '')
    ->whereNotNull('province')
    ->where('province', '!=', '')
    ->distinct()
    ->orderBy('city')
    ->orderBy('province')
    ->orderBy('barangay')
    ->get();

$setting = Setting::first();

$disabledPickupDates = explode(',', $setting->disable_pickup_dates ?? '');
$disabledDeliveryDates = explode(',', $setting->disable_delivery_dates ?? '');
$disabledDeliveryMiscDates = explode(',', $setting->disable_delivery_misc_dates ?? '');

$dataPrivacy = Page::where('slug', 'data-privacy')->first();
$dataPrivacyRender = view('v2.data-privacy', compact('dataPrivacy'))->render();

$now = now()->toDateTimeString();
$uid = Auth::id();

$formatCoupon = function ($coupon, $uid) {
    $totalUsed = CouponCart::where('coupon_id', $coupon->id)
        ->where('status', 1)
        ->sum('total_usage');

    $customerUsed = 0;
    if (Auth::check()) {
        $customerUsed = CouponCart::where('coupon_id', $coupon->id)
            ->where('status', 1)
            ->where('customer_id', $uid)
            ->sum('total_usage');
    }

    $free_products = collect([]);
    if ($coupon->free_product_id) {
        $freeProductIds = explode('|', $coupon->free_product_id);
        $freeProductIds = array_filter($freeProductIds, fn ($val) => !is_null($val) && $val !== '');
        $free_products = Product::with('photos')->whereIn('id', $freeProductIds)->get();
    }

    return [
        'id' => $coupon->id,
        'code' => $coupon->coupon_code,
        'name' => $coupon->name,
        'description' => $coupon->description,
        'terms' => $coupon->terms_and_conditions,
        'type' => $coupon->amount_discount_type == 1 ? 'amount' : 'product',
        'discount_type' => $coupon->reward == 'free-shipping-optn'
            ? ($coupon->location_discount_type == 'partial' ? 'amount' : 'percent')
            : ($coupon->percentage > 0 ? 'percent' : 'amount'),
        'discount' => $coupon->percentage > 0 ? $coupon->percentage : $coupon->amount,
        'applies_to' => $coupon->free_product_id
            ? 'free_product'
            : ($coupon->purchase_product_id ? 'product' : 'cart'),
        'purchase_product_id' => $coupon->purchase_product_id,
        'free_products' => $free_products,
        'combination_allowed' => $coupon->combination == 1,
        'total_usage_limit' => $coupon->usage_limit,
        'total_usage_used' => $totalUsed,
        'customer_limit' => $coupon->customer_limit,
        'customer_usage_used' => $customerUsed,
        'status' => 'valid',
        'location' => $coupon->location,
        'reward' => $coupon->reward,
        'free_shipping' => $coupon->reward == 'free-shipping-optn',
        'free_shipping_discount_amount' => $coupon->reward == 'free-shipping-optn'
            ? ($coupon->location_discount_type == 'partial' ? $coupon->location_discount_amount : 100)
            : 0,
        'activation_type' => $coupon->activation_type,
        'shipping_method' => $coupon->shipping_method,
        'purchase_amount' => $coupon->purchase_amount,
        'purchase_qty' => $coupon->purchase_qty,
        'purchase_qty_type' => $coupon->purchase_qty_type,
        'exclude_category_id' => $coupon->exclude_category_id,
        'auto_applied' => $coupon->activation_type === 'auto',
        'start_date' => $coupon->start_date,
        'start_time' => $coupon->start_time,
        'end_date' => $coupon->end_date,
        'end_time' => $coupon->end_time,
    ];
};

$eligibleGiftCheques = DB::table('gift_certificate')
    ->whereNull('deleted_at')
    ->whereRaw('LOWER(TRIM(status)) = ?', ['unused'])
    ->get()
    ->map(function ($gc) {
        return [
            'id' => $gc->id,
            'code' => $gc->code,
            'amount' => (float) $gc->amount,
            'gc_type' => $gc->gc_type,
            'status' => $gc->status,
        ];
    })
    ->values();
    

// Regular coupons
$eligibleCoupons = Coupon::query()
    ->withoutGlobalScope(SoftDeletingScope::class)
    ->from('coupons as c')
    ->whereNull('c.deleted_at')
    ->where('c.activation_type', '<>', 'auto')
    ->select('c.*')
    ->selectRaw("
        CASE
            WHEN c.status <> 'ACTIVE' THEN 0
            WHEN COALESCE(
                    STR_TO_DATE(CONCAT(c.start_date,' ', c.start_time), '%Y-%m-%d %H:%i:%s'),
                    STR_TO_DATE(CONCAT(c.start_date,' ', c.start_time, ':00'), '%Y-%m-%d %H:%i:%s')
                 ) > NOW() THEN 0
            WHEN COALESCE(
                    STR_TO_DATE(CONCAT(c.end_date,' ', c.end_time), '%Y-%m-%d %H:%i:%s'),
                    STR_TO_DATE(CONCAT(c.end_date,' ', c.end_time, ':00'), '%Y-%m-%d %H:%i:%s')
                 ) < NOW() THEN 0
            ELSE 1
        END AS is_currently_valid
    ")
    ->get()
    ->filter(function ($coupon) use ($uid) {
        if ($coupon->is_currently_valid != 1) {
            return false;
        }

        if (Auth::check()) {
            $alreadyUsed = DB::table('coupon_sales')
                ->where('coupon_id', $coupon->id)
                ->where('customer_id', $uid)
                ->exists();

            if ($alreadyUsed) {
                return false;
            }
        }

        return true;
    })
    ->values();

    

$formattedEligibleCoupons = $eligibleCoupons
    ->map(fn ($coupon) => $formatCoupon($coupon, $uid))
    ->values();

$eligibleCoupons = $formattedEligibleCoupons;

// Auto coupons
$autoCoupons = Coupon::query()
    ->where('activation_type', 'auto')
    ->where('status', 'ACTIVE')
    ->whereRaw("CONCAT(start_date, ' ', start_time) <= ?", [$now])
    ->whereRaw("CONCAT(end_date, ' ', end_time) >= ?", [$now])
    ->where(function ($q) use ($uid) {
        $q->whereNull('customer_scope')
            ->orWhere('customer_scope', 'all')
            ->orWhere(function ($x) use ($uid) {
                $x->where('customer_scope', 'specific')
                    ->whereRaw(
                        "FIND_IN_SET(?, REPLACE(REPLACE(scope_customer_id, ' ', ''), '|', ','))",
                        [$uid]
                    );
            });
    })
    ->get();

$eligibleAutoCoupons = collect([]);

if ($carts->count() > 0) {
    $cartQty = $carts->sum('qty');

    $cartTotal = $carts->sum(function ($item) {
        $price = $item['price'] ?? 0;
        $qty = $item['qty'] ?? 1;
        $paellaPrice = ($item['paella_price'] > 0) ? ($item['product']['paella_price'] ?? 0) : 0;

        return (($price + $paellaPrice) * $qty);
    });

    $cartProductIds = $carts->pluck('product_id')->map(fn ($id) => (string) $id)->toArray();

    $cartHasExcludedCategory = $carts->contains(function ($item) {
        return isset($item['product']['category_id']) && $item['product']['category_id'] == 1;
    });

    if (!$cartHasExcludedCategory) {
        foreach ($autoCoupons as $coupon) {
            if (Auth::check()) {
                $alreadyUsed = DB::table('coupon_sales')
                    ->where('coupon_id', $coupon->id)
                    ->where('customer_id', $uid)
                    ->exists();

                if ($alreadyUsed) {
                    continue;
                }
            }

            $totalUsed = CouponCart::where('coupon_id', $coupon->id)->where('status', 1)->sum('total_usage');

            $customerUsed = 0;
            if (Auth::check()) {
                $customerUsed = CouponCart::where('coupon_id', $coupon->id)
                    ->where('status', 1)
                    ->where('customer_id', $uid)
                    ->sum('total_usage');
            }

            if ($coupon->usage_limit !== null && $totalUsed >= $coupon->usage_limit) continue;
            if ($coupon->customer_limit !== null && $customerUsed >= $coupon->customer_limit) continue;

            $shouldSkip = false;

            if ($coupon->purchase_combination) {
                if ($coupon->purchase_qty && $coupon->purchase_qty > 0) {
                    if ($coupon->purchase_qty_type === 'min' && $cartQty < $coupon->purchase_qty) {
                        $shouldSkip = true;
                    }
                    if ($coupon->purchase_qty_type === 'max' && $cartQty > $coupon->purchase_qty) {
                        $shouldSkip = true;
                    }
                }

                if (!$shouldSkip) {
                    $combi = explode('|', $coupon->purchase_combination ?? '');

                    if (in_array('amount', $combi) && $coupon->purchase_amount && $coupon->purchase_amount > 0) {
                        if ($cartTotal < $coupon->purchase_amount) {
                            $shouldSkip = true;
                        }
                    }

                    if (!$shouldSkip && in_array('product', $combi) && $coupon->purchase_product_id) {
                        $requiredIds = explode('|', $coupon->purchase_product_id);
                        $hasRequiredProduct = false;

                        foreach ($requiredIds as $requiredId) {
                            if (in_array((string) $requiredId, $cartProductIds)) {
                                $hasRequiredProduct = true;
                                break;
                            }
                        }

                        if (!$hasRequiredProduct) {
                            $shouldSkip = true;
                        }
                    }
                }
            }

            if ($shouldSkip) continue;

            $eligibleAutoCoupons->push($formatCoupon($coupon, $uid));
        }
>>>>>>> Stashed changes
    }
}

$minimum_order_amount_door_to_door = $setting ? $setting->minimum_order : 0;
$minimum_order_amount_pickup = $setting ? $setting->minimum_order_pickup : 0;
$minimum_processing_hours = $setting ? $setting->minimum_processing_hours : 24;
$minimum_processing_hours_misc = $setting ? $setting->minimum_processing_hours_misc : 12;
$minimum_processing_hours_baka = $setting ? $setting->minimum_processing_hours_baka : 72;
$minimum_order_misc = $setting ? $setting->minimum_order_misc : 0;

$allCoupons = $formattedEligibleCoupons
    ->merge($eligibleAutoCoupons->values())
    ->values();
//dd($allCoupons);
return view('v2.checkout.checkout', compact(
    'triples',
    'provinces',
    'cities',
    'page',
    'dataPrivacyRender',
    'carts',
    'pickupBranches',
    'locations',
    'deliveryBranches',
    'disabledPickupDates',
    'disabledDeliveryDates',
    'disabledDeliveryMiscDates',
    'haslechon',
    'hasbaka',
    'hasMisc',
    'eligibleCoupons',
    'eligibleAutoCoupons',
    'allCoupons',
    'minimum_order_amount_door_to_door',
    'minimum_order_amount_pickup',
    'minimum_processing_hours',
    'minimum_processing_hours_misc',
    'minimum_processing_hours_baka',
    'minimum_order_misc',
    'hasCochinillo',
    'eligibleGiftCheques'
));
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

        if (!$sales) {
            abort(404);
        }

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
        $dataPrivacy = Page::where('slug', 'data-privacy')->first();

        $dataPrivacyRender = view('v2.data-privacy', compact('dataPrivacy'))->render();
        return view('v2.signup', compact('page', 'dataPrivacyRender'));
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

    public function my_coupons(Request $request)
    {
       $page = 'my-coupons';

$request->session()->forget('redirect_after_login');

if (!Auth::check()) {
    return redirect()->route('login', ['redirect' => $request->fullUrl()]);
}

        $uid = Auth::id();


    DB::table('coupon_new')
        ->where('status', 1)
        ->whereRaw("CONCAT(end_date, ' ', end_time) <= NOW()")
        ->update(['status' => 0]);

    $eligibleCoupons = DB::table('coupon_new as c')
    
        ->where('c.status', 1)
        ->whereNotExists(function ($query) use ($uid) {
            $query->select(DB::raw(1))
                ->from('coupon_redemptions')
                ->whereColumn('coupon_redemptions.coupon_id', 'c.id')
                ->where('coupon_redemptions.user_id', $uid);
        })
        ->get();

return view('v2.my-coupons', compact('page', 'eligibleCoupons'));

        return view('v2.my-coupons', compact('page', 'eligibleCoupons'));

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
                            ->where('is_sub', 0)
                            ->with([
                                'couponUsed',
                                'items.product.photos',
                                'payments' => function ($query) {
                                    $query->where('status', 'PAID');
                                },
                                'deliveryStatus' => function ($query) {
                                    $query->orderBy('created_at', 'asc');
                                },
                                'subHeaders' => function ($query) {
                                    $query->with(['deliveryStatus']);
                                }
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
        $page = Page::where('slug', 'blogs')->first();

        if (!$page) {
            abort(404);
        }

        return view('v2.page', compact('page'));

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
                if ($user->contact_mobile) {
                    $sms = new Sms();
                    $sms->send_sms($user->contact_mobile, 'welcome', $user);
                }
            } catch (\Exception $th) {
                //throw $th;
            }

            $redirectTo = $request->input('redirect') ?? route('my-account');
            return redirect()->intended($redirectTo)->with('success', 'Account created successfully! Welcome to our website! Please check your email inbox or spam folder for the welcome email.');
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
            'email' => 'required|email:rfc,dns|max:191|unique:users,email,' . Auth::id(), 
        ], [
            'contact_mobile.regex' => 'The mobile number must start with 09 or +639 and be followed by 9 digits.',
        ]);

        $user = Auth::user();
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
        $page = Page::with('album.banners')->where('slug', $slug)->first();

        if (!$page) {
            abort(404);
        }

        if ($page->status == 'PRIVATE') {
            if (!Auth::check() || (Auth::check() && Auth()->user()->user_type == 'customer')) {
                abort(404);
            }
        }

        $albums = Album::with('banners')->where('name', 'Home Banner')->first();

        return view('v2.page', compact('page', 'albums'));
    }
}
