<?php

namespace App\Http\Controllers\Settings;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Setting;
use App\Models\MediaAccounts;
use App\Models\DeliveryFeePromo;
use App\Models\ProductCategory;

class WebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        Permission::module_init($this, 'website_settings');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $web = Setting::find($id);
        $medias = MediaAccounts::get();
        $deliveryfees = DeliveryFeePromo::get();
        $categories = ProductCategory::where('status','PUBLISHED')->get();

        return view('admin.settings.website.index',compact('web','medias','deliveryfees','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'website_name' => 'required',
            'company_name' => 'required',
            'copyright'    => 'required',
            'g_analytics_code' => 'nullable',
            'web_favicon'  => 'mimes:ico|max:100',
            'company_logo' => 'image|mimes:jpeg,png,jpg,svg|max:1000',
        ]);


        $web = Setting::find($id);
        $web->website_name = $request->website_name;
        $web->company_name = $request->company_name;
        $web->copyright = $request->copyright;
        $web->google_analytics = $request->g_analytics_code;
        $web->google_map = $request->g_map;
        $web->user_id = Auth::id();
        $web->google_recaptcha_sitekey = $request->g_recaptcha_sitekey;
        $web->save();


        if($web){
            if($request->has('web_favicon')) {
                $this->upload_favicons($request->file('web_favicon'),$id);
            }

            if($request->has('company_logo')) {
                $this->upload_logo($request->file('company_logo'),$id);
            }
            return back()->with('success', __('standard.settings.website.update_success'));
        } else {
            return back()->with('error', __('standard.settings.website.update_failed'));
        }
    }

    public function upload_favicons($favicon,$id)
    {
        $fileName = time().'_'.$favicon->getClientOriginalName();
        $web = Setting::find($id)->update([
            'website_favicon' => $fileName,
            'user_id' => Auth::id()
        ]);

        if($web){
            $image_url = Storage::putFileAs('/public/icons', $favicon, $fileName);
        }

    }

    public function upload_logo($logo,$id)
    {
        $fileName = time().'_'.$logo->getClientOriginalName();
        $web = Setting::find($id)->update([
            'company_logo' => $fileName,
            'user_id' => Auth::id()
         ]);

        if($web){
            $image_url = Storage::putFileAs('/public/logos', $logo, $fileName);
        }

    }

    public function remove_logo($id){

        $web = Setting::find($id);
        $web->company_logo = '';
        $web->user_id = Auth::id();
        $web->save();

        Storage::delete(Setting::select('company_logo')->where('id',$id)->get());

        return back()->with('success', __('standard.settings.website.remove_logo_success'));
    }

    public function remove_icon($id){

        $web = Setting::find($id);
        $web->website_favicon = '';
        $web->user_id = Auth::id();
        $web->save();

        Storage::delete(Setting::select('website_favicon')->where('id',$id)->get());

        return back()->with('success', __('standard.settings.website.remove_favicon_success'));
    }


    public function update_contacts(Request $request, $id)
    {
        $contacts = Setting::find($id);
        $contacts->company_address = $request->company_address;
        $contacts->mobile_no = $request->mobile_no;
        $contacts->fax_no = $request->fax_no;
        $contacts->tel_no = $request->tel_no;
        $contacts->email = $request->email;
        $contacts->user_id = Auth::id();
        $contacts->save();

        if($contacts){
            return back()->with('success', __('standard.settings.website.contact_update_success'));
        } else {
            return back()->with('error', __('standard.settings.website.contact_update_failed'));
        }
    }

    public function update_ecommerce_setting(Request $request, $id)
    {
        $ecommerce = Setting::find($id);
        $ecommerce->minimum_order = $request->minimum_order;      
        $ecommerce->minimum_order_pickup = $request->minimum_order_pickup; 
        $ecommerce->order_message = $request->order_message;  
        $ecommerce->disable_order = isset($request->disable_order) ? 1 : 0;
        $ecommerce->disable_delivery = isset($request->disable_delivery) ? 1 : 0;  
        $ecommerce->minimum_processing_hours = $request->minimum_processing_hours; 
        $ecommerce->minimum_processing_hours_misc = $request->minimum_processing_hours_misc;  
        $ecommerce->announcement = $request->announcement;
        $ecommerce->cutoff = $request->cutoff;

        $ecommerce->disable_pickup_dates='';
        $ecommerce->disable_delivery_dates = '';
        if(isset($request->disable_pickup_dates)){
            $ecommerce->disable_pickup_dates = implode(",",$request->disable_pickup_dates);
        }
        if(isset($request->disable_delivery_dates)){
            $ecommerce->disable_delivery_dates = implode(",",$request->disable_delivery_dates);
        }

        if(!isset($request->disable_order)){
            $ecommerce->disable_pickup_dates = '';
        }
        if(!isset($request->disable_delivery)){
            $ecommerce->disable_delivery_dates = '';
        }
        $ecommerce->save();

        if($ecommerce){
            return back()->with('success', 'Successfully Update Ecommerce Settings');
        } else {
            return back()->with('error', 'Updating Failed');
        }
    }

    public function update_media_accounts(Request $request)
    {
        $data   = $request->all();

        $mid   = $data['mid'];
        $urls   = $data['url'];
        $medias = $data['social_media'];

        foreach($medias as $key => $i){
            if($urls[$key] <> null){
                if($mid[$key] == null){
                    MediaAccounts::create([
                        'name' => $i,
                        'media_account' => $urls[$key],
                        'user_id' => Auth::id()
                    ]);
                } else {
                    MediaAccounts::where('id',$mid[$key])->update([
                        'name' => $i,
                        'media_account' => $urls[$key],
                        'user_id' => Auth::id()
                    ]);
                }
            }
        }

        return back()->with('success', __('standard.settings.website.social_updates_success'));
    }

    public function remove_media(Request $request)
    {
        $media = MediaAccounts::whereId($request->id);

        $media->update([ 'user_id' => Auth::id() ]);
        $media->delete();

        return back()->with('success', __('standard.settings.website.social_remove_success'));
    }

    public function update_ecommerce(Request $request)
    {
        $media = Setting::whereId('1')->update([
            'is_rating_need_approval' => ((isset($request->is_rating_need_approval)) ? 1 : 0),
            'is_rating_allow_anonymous' => ((isset($request->is_rating_allow_anonymous)) ? 1 : 0),
            'is_display_review_date' => ((isset($request->is_display_review_date)) ? 1 : 0),
            'convenience_fee' => ((isset($request->convenience_fee)) ? $request->convenience_fee : 0)
        ]);

        return back()->with('success', 'Ecommerce settings has been updated!');
    }

    public function update_data_privacy(Request $request, $id)
    {
        $privacy = Setting::find($id);
        $privacy->data_privacy_title = $request->privacy_title;
        $privacy->data_privacy_popup_content = $request->pop_up_content;
        $privacy->data_privacy_content = $request->content;
        $privacy->user_id = Auth::id();
        $privacy->save();

        return back()->with('success', __('standard.settings.website.privacy_updates_success'));
    }

    public function update_deliveryfee(Request $request)
    {
        $deliveryfee = DeliveryFeePromo::truncate(); 
        
        if(!empty($request->products)){
            foreach($request->products as $p){
                $ins = DeliveryFeePromo::create([
                    'ref_id' => $p,
                    'user_id' => Auth::id(),
                    'type' => 'product'
                ]);
            }
        }

        if(!empty($request->customers)){
            foreach($request->customers as $c){
                $ins = DeliveryFeePromo::create([
                    'ref_id' => $c,
                    'user_id' => Auth::id(),
                    'type' => 'customer'
                ]);
            }
        }

        return back()->with('success', 'Delivery Fee Promo has been updated');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function update_kiosk_setting(Request $request, $id)
    {
        $data = $request->all();
        $categories = $data['categories'];

        $cat = '';
        foreach($categories as $category){
            $cat .= $category.'|';
        }

        Setting::find($id)->update(['kiosk_express_categories' => $cat]);

        return back()->with('success', 'Successfully Update Kiosk Settings');
    }

}
