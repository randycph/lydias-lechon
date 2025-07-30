<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\Cart;
use App\EcommerceModel\SalesHeader;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ManualOrderCancelledMail;
use App\Mail\OrderCancelledMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class MyAccountController extends Controller
{
    public function cancel_order(Request $request) {
        $salesHeader = SalesHeader::whereId($request->sales_id)->first();

        if (!$salesHeader) {
            return back()->with('error_cancelled', "Your order cannot be cancelled at this time");
        }

        $email_act = Mail::to(Auth::user()->email)->send(new ManualOrderCancelledMail($salesHeader));

        $salesHeader->status = 'CANCELLED';
        $salesHeader->save();

        return back()->with('success_cancelled',"Your order has been successfully cancelled");
    }
    public function manage_account(Request $request)
    {
        $member = auth()->user();
        $user = auth()->user();
        $selectedTab = 0;

        if ($request->has('tab')) {
            $selectedTab = ($request->tab == 'contact-information') ? 1 : 0;
            $selectedTab = ($request->tab == 'my-address') ? 2 : $selectedTab;
        }

        return view('theme.lydias.ecommerce.my-account.manage-account', compact('member', 'user', 'selectedTab'));
    }

    public function update_personal_info(Request $request)
    {   
        if($request->is_org==1){
            $user_add = User::whereId(Auth::id())->update([
                'organization' => $request->organization,
                'birthday' => $request->birthday,
                'contact_person' => $request->contact_person
            ]);
        }
        else{
            $user_add = User::whereId(Auth::id())->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'birthday' => $request->birthday
            ]);
        }
        return redirect()->back()->with('success-personal', 'Personal information has been updated');
    }

    public function update_contact_info(Request $request)
    {
        $route = route('my-account.manage-account').'?tab=contact-information';
        $user_add = User::whereId(Auth::id())->update([
            'contact_tel' => $request->tel,
            'contact_mobile' => $request->mobile,
            'contact_fax' => $request->fax,
        ]);

        return redirect($route)->with('success-contact', 'Personal information has been updated');
    }

    public function update_address_info(Request $request)
    {
        $route = route('my-account.manage-account').'?tab=tab=my-address';
        $user_add = User::whereId(Auth::id())->update([
            'address_street' => $request->address_delivery_street,
            'address_city' => $request->address_delivery_city,
            'address_region' => $request->address_delivery_province,
        ]);

        return redirect($route)->with('success-address', 'Personal information has been updated');
    }

    public function change_password()
    {
        $page = new Page();
        $page->name = 'Change Password';

        return view('theme.lydias.ecommerce.my-account.change-password',compact('page'));
    }

    public function update_password(Request $request)
    {
        try {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                ],
            ]);
        
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
        
            return back()->with('success', 'Your password has been changed successfully.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function edit_order(Request $request) {

        session()->forget('old_sales_header_id');

        $salesHeader = SalesHeader::whereId($request->sales_id)->first();

        if (Auth::user()->id != $salesHeader->user_id) {
            return back()->with('error_cancelled', "You are not authorized to edit this order");
        }

        if (!$salesHeader) {
            return back()->with('error_cancelled', "Your order cannot be edited at this time");
        }

        $cart = Cart::where('user_id', Auth::user()->id)->get();

        if (!$cart) {
            return back()->with('error_cancelled', "Your order cannot be edited at this time");
        }

        // Clear existing cart items
        foreach ($cart as $item) {
            $item->delete();
        }

        // Re-add items to cart
        foreach ($salesHeader->items as $item) {
            Cart::create([
                'user_id' => Auth::user()->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'paella_price' => $item->paella_price,
                'price' => $item->price,
            ]);
        }

        session([
            'old_sales_header_id' => $salesHeader->id,
        ]);

        return redirect()->route('page', ['slug' => 'menu'])->with('success_edit', 'Your order has been successfully edited. Please review your cart.');
    }
}
// corpuz.randy@webfocus.ph
