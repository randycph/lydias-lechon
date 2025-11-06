<?php

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Role;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('checkPermission:admin/role', ['only' => ['index']]);
        $this->middleware('checkPermission:admin/role/create', ['only' => ['create','store']]);
        $this->middleware('checkPermission:admin/role/edit', ['only' => ['show','edit','update']]);
        $this->middleware('checkPermission:admin/role/delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::orderby('updated_at', 'desc')->paginate(10);

        return view('admin.settings.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.settings.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $has_branches = $request->has('has_branches') ? 1 : 0;
        $can_approve_payment = $request->has('can_approve_payment') ? 1 : 0;
        $has_production_branch = $request->has('has_production_branch') ? 1 : 0;

        if(Role::where('name',$request->role)->exists()){
            return back()->with('duplicate', __('standard.account_management.roles.duplicate_role'));
        } else {
            Role::create([
                'name' 		  => $request->role,
                'description' => $request->description,
                'created_by'  => Auth::user()->id,
                'has_branches' => $has_branches,
                'can_approve_payment' => $can_approve_payment,
                'has_production_branch' => $has_production_branch
            ]);
            return redirect()->route('role.index')->with('success', __('standard.account_management.roles.create_success'));
        }
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
        $role = Role::where('id',$id)->first();

        return view('admin.settings.role.edit',compact('role'));
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
        $has_branches = $request->has('has_branches') ? 1 : 0;
        $can_approve_payment = $request->has('can_approve_payment') ? 1 : 0;
        $has_production_branch = $request->has('has_production_branch') ? 1 : 0;

        Role::find($id)->update([
            'name'        => $request->role,
            'description' => $request->description,
            'created_by'  => Auth::user()->id,
            'has_branches' => $has_branches,
            'can_approve_payment' => $can_approve_payment,
            'has_production_branch' => $has_production_branch

        ]);

        return redirect()->route('role.index')->with('success', __('standard.account_management.roles.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->role_id != 1) {
            Role::find($request->role_id)->delete();
        }

        return back()->with('success',  __('standard.account_management.roles.delete_success'));
    }
}
