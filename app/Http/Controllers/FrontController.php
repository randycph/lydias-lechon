<?php

namespace App\Http\Controllers;

use App\Helpers\ListingHelper;
use App\Helpers\Webfocus\Setting;
use App\Mail\CareerMail;
use App\Mail\InquiryAdminMail;
use App\Mail\InquiryMail;
use App\Models\User;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Album;
use App\Models\Article;

use App\Models\Category;
use App\Http\Requests\ContactUsRequest;
use Illuminate\Support\Facades\Mail;
use Response;
use Storage;
use App\EcommerceModel\GiftCertificate;
use App\EcommerceModel\SalesHeader;
use App\Helpers\Shortcode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\PagePost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FrontController extends Controller
{

    public function home()
    {
        return $this->page('home');
    }

    public function privacy_policy(){

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        return view('theme.'.config('app.frontend_template').'.pages.privacy-policy', compact('page', 'footer'));

    }

    public function set_delivery_option(Request $request){

        session(['delivery_option' => $request->delivery_option]);

        return;

    }

    public function page($slug)
    {

        if(Auth::guest()) {
            $page = Page::where('slug', $slug)->where('status', 'PUBLISHED')->first();
        } else {
            $page = Page::where('slug', $slug)->first();
        }

        if($page == null) {
            abort(404);
        }

        $breadcrumb = $this->breadcrumb($page);

        $footer = Page::where('slug', 'footer')->where('name', 'footer')->first();

        $content = Shortcode::process($page->content);

        if (!empty($page->template)) {
            return view('theme.'.config('app.frontend_template').'.pages.'.$page->template, compact('footer','page', 'breadcrumb', 'content'));
        }

        $parentPage = null;
        if ($page->has_parent_page() || $page->has_sub_pages())
        {
            if ($page->has_parent_page()) {
                $parentPage = $page->parent_page;
                while($parentPage->has_parent_page()) {
                    $parentPage = $parentPage->parent_page;
                }
            } else {
                $parentPage = $page;
            }
        }

        return view('theme.'.config('app.frontend_template').'.page', compact('footer', 'page', 'parentPage','breadcrumb'));
    }

public function contact_us(Request $request)
{
    try {
        $request->validate([
            'g-recaptcha-response' => ['required', new \App\Rules\RecaptchaRule()],
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $client = $request->all();

        Mail::to($client['email'])->send(new InquiryMail(Setting::info(), $client));

        $admin = (object) ['firstname' => 'Lydias Support'];

        Mail::to(Setting::info()->email)->send(new InquiryAdminMail(Setting::info(), $client, $admin));

        return redirect()->to(url()->previous())->with('form_success', 'Email sent!');
    } catch (ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('contact_form_has_error', true);
    } catch (\Exception $e) {
        return redirect()->to(url()->previous())->withInput()->with('form_error', 'Something went wrong. Please try again later.');
    }
}

    // ==========================

    public function careers() {
        $careers = Page::where('slug', 'careers')->where('name', 'Careers')->first();
        $page = $careers;
        return view('theme.'.config('app.frontend_template').'.pages.careers', compact( 'careers','page'));

    }

    public function applicant(Request $request, Page $page)
    {

        try {
            $request->validate([
                'g-recaptcha-response' => ['required', new \App\Rules\RecaptchaRule()],
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'contact' => 'required|string|max:255',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            ]);

            $emailReceiver = 'wsiprod.demo@gmail.com';
            $applicant = $request->all();
            $resume = $request->resume;

            Mail::to($emailReceiver)->send(new CareerMail(Setting::info(), $applicant, $resume));

            return redirect()->to(url()->previous())->with('success', 'Your application has been sent!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', true);
        } catch (\Exception $e) {
            return redirect()->to(url()->previous())->withInput()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function show_sales_summary($id)
    {
        $undecodeId = $id;

        if (ctype_digit($id)) {
            $id = $undecodeId;
        }

        $sales = SalesHeader::where('id',$id)->with('deliveryAddress')->first();
        $gc = GiftCertificate::where('sales_header_id',$id)->get();
        $salesPayments = \App\EcommerceModel\SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails = \App\EcommerceModel\SalesDetail::where('sales_header_id',$id)->get();
        $totalPayment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$id)->sum('amount');
        $deliveries = \App\EcommerceModel\DeliveryStatus::where('order_id',$id)->get();
        $totalNet = \App\EcommerceModel\SalesHeader::where('id',$id)->sum('net_amount');
        if($totalNet <= $totalPayment)
        $status = 'PAID';
        else $status = 'UNPAID';

        return view('theme.'.config('app.frontend_template').'.pages.ecommerce.sales_summary',compact('sales','salesPayments','salesDetails','status','deliveries','gc'));
    }

    public function show_sales_summary_guest($id)
    {
        $undecodeId = $id;

        if (ctype_digit($id)) {
            $id = $undecodeId;
        } else {
            $id = base64_decode($id);
        }
        
        $sales = SalesHeader::where('id',$id)->with('deliveryAddress')->first();          
        
        $gc = GiftCertificate::where('sales_header_id',$id)->get();
        $salesPayments = \App\EcommerceModel\SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails = \App\EcommerceModel\SalesDetail::where('sales_header_id',$id)->get();
        $totalPayment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$id)->sum('amount');
        $deliveries = \App\EcommerceModel\DeliveryStatus::where('order_id',$id)->get();
        $totalNet = \App\EcommerceModel\SalesHeader::where('id',$id)->sum('net_amount');
        if($totalNet <= $totalPayment){
            $status = 'PAID';
        }
        else {
            $status = 'UNPAID';
            if($totalPayment > 0){
                $status = 'PARTIAL';
            }
        }

        return view('theme.'.config('app.frontend_template').'.pages.ecommerce.sales_summary_guest',compact('sales','salesPayments','salesDetails','status','deliveries','gc'));
    }

    public function show_salessummary_admin($id)
    {
        $sales = \App\EcommerceModel\SalesHeader::where('order_number',$id)->first();
        $id=$sales->id;
        $gc = GiftCertificate::where('sales_header_id',$id)->get();
        $salesPayments = \App\EcommerceModel\SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails = \App\EcommerceModel\SalesDetail::where('sales_header_id',$id)->get();
        $totalPayment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$id)->sum('amount');
        $deliveries = \App\EcommerceModel\DeliveryStatus::where('order_id',$id)->get();
        $totalNet = \App\EcommerceModel\SalesHeader::where('id',$id)->sum('net_amount');
        if($totalNet <= $totalPayment){
            $status = 'PAID';
        }
        else {
            $status = 'UNPAID';
            if($totalPayment > 0){
                $status = 'PARTIAL';
            }
        }

        return view('theme.'.config('app.frontend_template').'.pages.ecommerce.sales_summary_admin',compact('sales','salesPayments','salesDetails','status','deliveries','gc'));
    }


    // ======================================

    public function breadcrumb($page)
    {
        return [
            'home' => url('/'),
            $page->name => url('/').'/'.$page->slug
        ];
    }
}
