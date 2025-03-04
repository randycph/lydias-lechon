<?php

namespace App\Http\Controllers\Settings;
use App\Helpers\Webfocus\Setting;
use App\Mail\UpdatePasswordMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Hash;
use Auth;
use Illuminate\Support\Facades\Validator;


use App\User;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
//        $this->middleware('checkPermission:admin/account/edit', ['only' => ['show','edit','update']]);
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('admin.settings.account.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        Validator::make($request->all(), [
            'firstname' => 'required|max:150',
            'lastname' => 'required|max:150',
        ],[],[
            'firstname' => 'first name',
            'lastname' => 'last name',
        ])->validate();

        $updateData = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname
        ];

        if ($request->hasFile('avatar')) {
            Storage::disk('public')->delete(auth()->user()->get_image_url_storage_path());
            $updateData['avatar'] = $this->upload_file_to_storage('avatars', $request->file('avatar'), 'url');
        }

        $is_updated = auth()->user()->update($updateData);

        if($is_updated){
            return back()->with('success', __('standard.settings.account.update_success'));
        } else {
            return back()->with('error', __('standard.settings.account.update_failed'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function update_email(Request $request)
    {

        Validator::make($request->all(), [
            'email' => 'required|email|max:191|unique:users,email,'.auth()->id(),
        ])->validate();

        $is_updated = auth()->user()->update([
            'email'   => $request->email
        ]);

        if($is_updated){
            return redirect(route('account.logout'))->with('success', __('standard.settings.account.update_email'));
        } else {
            return back()->with('error', __('standard.settings.account.update_email_failed'));
        }
    }

    public function update_password(Request $request)
    {
        Validator::make($request->all(), [
            'new_password' => [
                'required',
                'min:8',
                'regex:/[a-z]/', // must contain at least one lowercase letter
                'regex:/[A-Z]/', // must contain at least one uppercase letter
                'regex:/[0-9]/', // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'required|same:new_password',
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }]
        ])->validate();

        $user = auth()->user();

        $is_updated = $user->update(['password' => \Hash::make($request->confirm_password, array('rounds'=>12))]);

        if ($is_updated) {
            \Mail::to($user->email)->send(new UpdatePasswordMail(Setting::info(), $user));
            Auth::logout();
            return redirect('login')->with('success', 'Password successfully change. To login again, please use your new password!');
        } else {
            return back()->with('error', __('standard.settings.account.update_password_failed'));
        }

    }

    public function ajax_upload_avatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|dimensions:width=300,height=300',
        ]);

        if ($validator->passes()) {
            // Storage::delete(Setting::select('company_logo')->where('id',$request->user_id)->get());
            $data = $this->update_avatar($request->file('avatar'),$request->user_id);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function update_avatar($file, $id){
        $fileName = time().'_'.$file->getClientOriginalName();
        $user = User::find($id)->update([
            'avatar' => $fileName,
            'user_id' => Auth::id()
        ]);

        if($user){
            $image_url = Storage::putFileAs('/public/avatars', $file, $fileName);
        }
    }

    public function upload_file_to_storage($folder, $file, $key = '')
    {
        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($folder.'/'.$fileName)) {
            $fileNames = explode(".", $fileName);
            $count = 2;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
                $count += 1;
                $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            }

            $fileName = $newFilename;
        }

        $path = Storage::disk('public')->putFileAs($folder, $file, $fileName);
        $url = Storage::disk('public')->url($path);
        $returnArr = [
            'name' => $fileName,
            'url' => $url
        ];

        if ($key == '') {
            return $returnArr;
        } else {
            return $returnArr[$key];
        }
    }
}
