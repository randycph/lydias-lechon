<?php

namespace App\Http\Controllers\Settings;

use App\Helpers\ListingHelper;
use App\Http\Requests\UserRequest;
use App\Mail\AddNewUserMail;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Helpers\Webfocus\Setting;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

use App\EcommerceModel\ProductionBranch;
use App\EcommerceModel\Branch;
use App\Mail\UpdatePasswordMail;
use App\Models\UserBranch;
use App\Models\Role;
use App\Models\User;
use App\Models\Logs;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use SendsPasswordResetEmails;

    private $searchFields = ['name'];

    public function __construct()
    {
        Permission::module_init($this, 'user');
    }

    public function index($param = null)
    {
        $customConditions = [
            [
                'field' => 'is_active',
                'operator' => '=',
                'value' => 1,
                'apply_to_deleted_data' => false
            ],
            [
                'field' => 'user_type',
                'operator' => '=',
                'value' => 'cms',
                'apply_to_deleted_data' => false
            ]
        ];

        $listing = new ListingHelper('desc', 10, 'updated_at', $customConditions);

        $users = $listing->simple_search(User::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.users.index',compact('users','filter', 'searchType'));
    }

    public function create()
    {
        $branches = Branch::where('status', 1)->orderBy('name','asc')->get();
        $roles = Role::orderBy('name','asc')->get();
        $production_branches = ProductionBranch::orderBy('name','asc')->get();

        return view('admin.users.create',compact('roles','branches', 'production_branches'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validate([
            'fname' => ['required','string','regex:/^[\p{L}\s\-]+$/u'],
            'lname' => ['required','string','regex:/^[\p{L}\s\-]+$/u'],
            'email' => 'required|email|max:191|unique:users,email',
            'role' => 'required|exists:role,id',
            'production_branch_id' => 'nullable|exists:production_branches,id',
        ]);

        $request->merge([
            'payment_types'        => is_array($request->payment_types) ? $request->payment_types : [],
            'production_branch_id' => is_array($request->production_branch_id) ? $request->production_branch_id : [],
            'branches'             => is_array($request->branches) ? $request->branches : [],
        ]);

        $request->merge([
            'payment_types'        => array_filter($request->payment_types),
            'production_branch_id' => array_filter($request->production_branch_id),
            'branches'             => array_filter($request->branches),
        ]);

        $data = $request->all();

        $role = Role::where('id',$request->role)->first();

        if ($role->has_production_branch == 1) {
            $request->validate([
                'production_branch_id' => ['required','integer','exists:production_branches,id'],
            ]);
        } else {
            $request->validate([
                'production_branch_id' => ['nullable','integer','exists:production_branches,id'],
            ]);
        }

        if ($role->has_branches == 1) {
            $request->validate([
                'branches'   => 'required|array|min:1',
                'branches.*' => 'integer|exists:branches,id',
            ]);
        }

        $paytypes='';
        if(isset($request->payment_types) && $role->can_approve_payment == 1){
            $paytypes= implode(",",$request->payment_types ?? []);
        }

        $productionBranches = [];
        if(isset($request->production_branch_id)){
            $productionBranches = implode(",",$request->production_branch_id ?? []);
        }
    
        $user = User::create([
            'firstname'      => $request->fname,
            'lastname'       => $request->lname,
            'name'           => $request->fname.' '.$request->lname,
            'password'       => Hash::make('password'),
            'email'          => $request->email,
            'role_id'        => $request->role,
            'user_type'      => 'cms',
            'is_active'      => 1,
            'user_id'        => Auth::id(),
            'allowed_payments'  => $paytypes,
            'remember_token' => Str::random(10),

            'address_street' => '',
            'address_municipality' => '',
            'address_city' => '',
            'address_region' => '',

            'production_branch_id' => $productionBranches,
        ]);

        if($user){
            if($role->has_branches == 1){
                $branches = $data['branches'] ?? [];

                foreach ($branches as $id) {
                    UserBranch::create([
                        'user_id' => $user->id,
                        'branch_id' => $id
                    ]);
                }
            }
        }

        try {
            $user->send_reset_temporary_password_email();
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to send email. Please contact the administrator.');
        }

        return redirect()->route('users.index')->with('success', 'Pending for activation. Please remind the user to check the email and activate the account.');
    }

    public function edit($id)
    {
        $branches = Branch::where('status', 1)->orderBy('name','asc')->get();
        $userbranch=UserBranch::where('user_id',$id)->get();
        $roles    = Role::orderBy('name','asc')->get();
        $user     = User::where('id',$id)->first();
        $production_branches = ProductionBranch::orderBy('name','asc')->get();

        return view('admin.users.edit',compact('user','roles','branches','userbranch', 'production_branches'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'fname' => ['required','string','regex:/^[\p{L}\s\-]+$/u'],
            'lname' => ['required','string','regex:/^[\p{L}\s\-]+$/u'],
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'role' => 'required|exists:role,id',
            'production_branch_id' => 'nullable|exists:production_branches,id',
        ]);

        $request->merge([
            'payment_types'        => is_array($request->payment_types) ? $request->payment_types : [],
            'production_branch_id' => is_array($request->production_branch_id) ? $request->production_branch_id : [],
            'branches'             => is_array($request->branches) ? $request->branches : [],
        ]);

        $request->merge([
            'payment_types'        => array_filter($request->payment_types),
            'production_branch_id' => array_filter($request->production_branch_id),
            'branches'             => array_filter($request->branches),
        ]);

        $role = Role::where('id',$request->role)->first();

        if ($role->has_production_branch == 1) {
            $request->validate([
                'production_branch_id' => 'required|exists:production_branches,id',
            ]);
        }

        if ($role->has_branches == 1) {
            $request->validate([
                'branches'   => 'required|array|min:1',
                'branches.*' => 'integer|exists:branches,id',
            ]);
        }

        $paytypes='';
        if(isset($request->payment_types)){
            $paytypes= implode(",",$request->payment_types ?? []);
        }

        $productionBranches = [];
        if(isset($request->production_branch_id)){
            $productionBranches = implode(",",$request->production_branch_id ?? []);
        }

        $user->update([
            'firstname'=> $request->fname,
            'lastname' => $request->lname,
            'name'     => $request->fname.' '.$request->lname,
            'email'    => $request->email,
            'role_id'  => $request->role,
            'allowed_payments'  => $paytypes,
            'user_id'  => Auth::id(),
            'user_type'      => 'cms',
            'production_branch_id' => $request->production_branch_id ?? null,
        ]);
        UserBranch::where('user_id',$user->id)->delete();
        if($user){
            if($role->has_branches == 1){
                $data = $request->all();
                $branches = $data['branches'] ?? [];

                foreach ($branches as $id) {
                    UserBranch::create([
                        'user_id' => $user->id,
                        'branch_id' => $id
                    ]);
                }
            } 
        }
        return back()->with('success', __('standard.users.update_success'));

        return redirect()->route('users.index')->with('success', __('standard.users.update_success'));
    }

    public function deactivate(Request $request)
    {
    	User::find($request->user_id)->update([
            'is_active' => 0,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', __('standard.users.status_success', ['status' => 'deactivated']));
    }

    public function activate(Request $request)
    {
    	User::find($request->user_id)->update([
            'is_active' => 1,
            'user_id'   => Auth::id(),
        ]);

        return back()->with('success', __('standard.users.status_success', ['status' => 'activated']));
    }


    public function show($id, $param = null)
    {
        $user = User::where('id',$id)->first();
        $logs = Logs::where('created_by',$id)->orderBy('id','desc')->paginate(10);

        return view('admin.users.profile',compact('user','logs','param'));
    }

    public function filter(Request $request)
    {
        $params = $request->all();

        return $this->apply_filter($params);
    }

    public function apply_filter($param = null)
    {
        $user = User::where('id',$param['id'])->first();

        if(isset($param['order'])){
            $logs = Logs::where('created_by',$param['id'])->orderBy($param['sort'],$param['order'])->paginate($param['pageLimit']);
        } else {
            $logs = Logs::where('created_by',$param['id'])->paginate($param['pageLimit']);
        }

        return view('admin.users.profile',compact('user','logs','param'));
    }

}
