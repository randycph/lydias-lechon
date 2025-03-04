<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ListingHelper;
use Auth;

use App\ServiceType;

class ServiceTypeController extends Controller
{
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = new ListingHelper();

        $services = $listing->simple_search(ServiceType::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.service-request.service-type-index',compact('services', 'filter', 'searchType'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.service-request.service-type-create');
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
            'name' => 'required'
        ])->validate();

        ServiceType::create([
           'name' => $request->name,
           'status' => (isset($request->visibility) ? 'PUBLISHED' : 'PRIVATE'),
           'created_by' => Auth::id()
        ]);

        return redirect()->route('service-type.index')->with('success', __('standard.services.service_type.create_success'));
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
        $service = ServiceType::findOrFail($id);

        return view('admin.service-request.service-type-edit',compact('service'));
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
        Validator::make($request->all(), [
            'name' => 'required'
        ])->validate();
        
        $service = ServiceType::findOrFail($id);

        $service->update([
            'name' => $request->name,
            'status' => (isset($request->visibility) ? 'PUBLISHED' : 'PRIVATE'),
            'created_by' => auth()->user()->id
        ]);

        return redirect()->route('service-type.index')->with('success', __('standard.services.service_type.update_success'));
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

    public function single_delete(Request $request)
    {
        $service = ServiceType::findOrFail($request->services);
        $service->update([ 'created_by' => Auth::id() ]);
        $service->delete();

        return back()->with('success', __('standard.services.service_type.single_delete_success'));

    }

    public function multiple_delete(Request $request)
    {
        $services = explode("|",$request->services);

        foreach($services as $service){
            ServiceType::whereId($service)->update(['created_by' => Auth::id() ]);
            ServiceType::whereId($service)->delete();
        }

        return back()->with('success', __('standard.services.service_type.multiple_delete_success'));
    }

    public function restore($service){
        ServiceType::withTrashed()->find($service)->update(['created_by' => Auth::id() ]);
        ServiceType::whereId($service)->restore();

        return back()->with('success', __('standard.services.service_type.restore_success'));
    }

    public function update_status($id,$status)
    {
        ServiceType::where('id',$id)->update([
            'status' => $status,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', __('standard.services.service_type.status_update_success', ['STATUS' => $status]));
    }

    public function multiple_change_status(Request $request)
    {
        $services = explode("|", $request->services);

        foreach ($services as $service) {
            $publish = ServiceType::where('status', '!=', $request->status)->whereId($service)->update([
                'status'  => $request->status,
                'created_by' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.services.service_type.multiple_status_update_success', ['STATUS' => $request->status]));
    }
}
