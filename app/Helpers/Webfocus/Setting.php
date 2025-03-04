<?php

namespace App\Helpers\Webfocus;

use App\EcommerceModel\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Setting {

    public static function info() {

        $setting = DB::table('settings')->first();
        $setting->menu = DB::table('menus')->where('is_active', 1)->first();
        return $setting;

	}

	public static function getFaviconLogo()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return $settings;
    }

    public static function getFooter()
    {

        $footer = DB::table('pages')->where('slug', 'footer')->where('name', 'footer')->first();

        return $footer;
    }

    public static function getPromoAds()
    {

        $promoads = DB::table('pages')->where('slug', 'promo-ads')->where('name', 'Promo Ads')->first();

        return $promoads;
    }

    public static function getAds()
    {

        $ads = DB::table('pages')->where('slug', 'home-ads')->where('name', 'home ads')->first();

        return $ads;
    }

    public static function getCareers()
    {

        $careers = DB::table('pages')->where('slug', 'careers')->where('name', 'careers')->first();

        return $careers;
    }

    public static function getImportantReminders()
    {

        $careers = DB::table('pages')->where('slug', 'important-reminders')->where('name', 'Important Reminders')->first();

        return $careers;
    }

   public static function EcommerceCartTotalItems()
   {
       if (\Auth::check()) {
           $qty = DB::table('ecommerce_shopping_cart')->where('user_id', \Auth::id())->sum('qty');
       }
       else{
           $cart = session('cart', []);

           if (empty($cart)) {
               return 0;
           } else {
               return array_sum(array_column($cart, 'qty'));
           }
       }

       return $qty;
   }
//
//    public static function EcommerceCartTotalCost()
//    {
//        if (\Auth::check()) {
//            $cart = Cart::where('user_id', auth()->id())->get();
//            $totalCost = 0;
//            foreach($cart as $order) {
//                $totalCost += $order->price * $order->qty;
//                if ($order->with_installation) {
//                    $totalCost += $order->installation * $order->qty;
//                }
//            }
////            $total_cost = DB::table('ecommerce_shopping_cart')->where('user_id', \Auth::id())->sum(DB::raw('price * qty'));
//            $cost = number_format($totalCost,2);
//        } else{
//            $cart = session('cart', []);
//
//            if (empty($cart)) {
//                return 0.00;
//            } else {
//                $sum = 0;
//                foreach ($cart as $order) {
//                    $sum += $order['price'] * $order['qty'];
//                    if ($order['with_installation']) {
//                        $sum += $order['installation_fee'] * $order['qty'];
//                    }
//                }
//                return number_format($sum, 2);
//            }
//        }
//
//        return $cost;
//    }

    public static function social_account($sm)
    {
        $account = DB::table('social_media')->where('name','=',$sm)->first();

        if($account === null){
            return false;
        }
        else{
            return $account;
        }

    }


    public static function date_for_listing($date) {
        if ($date == null || trim($date) == '') {
            return "-";
        }
        else if ($date != null && strtotime($date) < strtotime('-1 day')) {
            return Carbon::parse($date)->isoFormat('lll');
        }

        return Carbon::parse($date)->diffForHumans();
	}

	public static function date_for_news_list($date) {
        if ($date != null && strtotime($date) > strtotime('-1 day')) {
            return Carbon::parse($date)->diffForHumans();
        } else {
			return ' '.date('M d, Y h:i A', strtotime($date));
		}

    }

    public function social($page,$account){
    	if($page == 'facebook')
    		return '
				jsSocials.shares.facebook = {
	                logo: "fa fa-facebook-f",
	                shareUrl: "https://facebook.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'twitter')
    		return '
				jsSocials.shares.twitter = {
	                logo: "fa fa-twitter",
	                shareUrl: "https://twitter.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'instagram')
    		return '
				jsSocials.shares.instagram = {
	                logo: "fa fa-instagram",
	                shareUrl: "https://instagram.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'google')
    		return '
				jsSocials.shares.googleplus = {
	                logo: "fa fa-google-plus",
	                shareUrl: "https://plus.google.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    	elseif($page == 'dribble')
    		return '
				jsSocials.shares.dribbble = {
	                logo: "fa fa-dribbble",
	                shareUrl: "https://dribbble.com/'.$account.'",
	                getCount: function(data) {
	                    return data.count;
	                }
	            };
    		';
    }

    public static function get_company_logo_storage_path()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return asset('storage').'/logos/'.$settings->company_logo;
    }

    public static function get_company_favicon_storage_path()
    {
        $settings = DB::table('settings')->where('id',1)->first();

        return asset('storage').'/icons/'.$settings->website_favicon;
    }

    public static function kilo_fee($kilograms, $zip){

        if($kilograms <= 3)
            return 0;

        $rate = DB::table('ecommerce_delivery_rate')->where('zip',$zip)->first();

        $rate = $rate->excess_fee;

        $total_fee = $rate * ($kilograms - 3);

        return $total_fee;

    }

    public static function distance_fee($zip){

        $rate = DB::table('ecommerce_delivery_rate')->where('zip',$zip)->first();

        if(!$rate)
            return 0;
        else
            return $rate->rate;

    }

    public static function convenience_fee(){

        $settings = DB::table('settings')->where('id',1)->first();

        return $settings->convenience_fee;
    }
}
