<?php

namespace App\Http\Controllers;

use App\Helpers\ListingHelper;
use App\Models\PopupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PopupMessageController extends Controller
{
    private $searchFields = ['title'];

    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $popup_messages = $listing->simple_search(PopupMessage::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.popup-message.index',compact('popup_messages', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.popup-message.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
            'button_text' => 'required',
            'start_to_show' => 'nullable',
            'button_text_url' => 'nullable',
            'close_button_text' => 'required',
            'url' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ])->validate();

        $data = $request->all();

        if ($request->has('is_active')) {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('popup_images', 'public');
        } else {
            $data['image'] = null;
        }

        $data['user_id'] = Auth::id();
        PopupMessage::create($data);

        return redirect(route('popup-message.index'))->with('success', __('standard.coupons.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(PopupMessage $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(PopupMessage $popup_message)
    {
        return view('admin.popup-message.edit', compact('popup_message'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
            'button_text' => 'required',
            'start_to_show' => 'nullable',
            'button_text_url' => 'nullable',
            'close_button_text' => 'required',
            'url' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ])->validate();

        $data = $request->all([
            'title',
            'message',
            'button_text',
            'start_to_show',
            'button_text_url',
            'close_button_text',
            'url',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('popup_images', 'public');
        }

        if ($request->has('is_active')) {
            $data['is_active'] = 1;
        } else {
            $data['is_active'] = 0;
        }

        $data['user_id'] = Auth::id();

        PopupMessage::where('id', $id)->update($data);

        return redirect(route('popup-message.index'))->with('success', __('standard.coupons.update_success'));
    }

    public function update_status($id,$status)
    {
        PopupMessage::find($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.coupons.status_update_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $message = PopupMessage::findOrFail($request->coupons);
        $message->update([ 'user_id' => Auth::id() ]);
        $message->delete();

        return back()->with('success', __('standard.coupons.single_delete_success'));
    }

    public function restore($id){
        PopupMessage::withTrashed()->find($id)->update(['is_active' => 0, 'user_id' => Auth::id() ]);
        PopupMessage::whereId($id)->restore();

        return back()->with('success', __('standard.coupons.restore_promo_success'));
    }

    public function multiple_change_status(Request $request)
    {
        $popup_messages = explode("|", $request->coupons);

        $status = $request->status == 'ACTIVE' ? 1 : 0;

        foreach ($popup_messages as $message) {
            $id = (int) $message;
            PopupMessage::where('is_active', '!=', $status)->whereId($id)->update([
                'is_active'  => $status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.coupons.multiple_status_update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $popup_messages = explode("|",$request->coupons);

        foreach($popup_messages as $message){
            PopupMessage::whereId($message)->update(['user_id' => Auth::id() ]);
            PopupMessage::whereId($message)->delete();
        }

        return back()->with('success', __('standard.coupons.multiple_delete_success'));
    }
}
