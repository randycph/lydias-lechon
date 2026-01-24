<?php

namespace App\Http\Controllers;

use App\Helpers\ListingHelper;
use App\Models\Permission;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SizeController extends Controller
{
    private $searchFields = ['name'];

    /**
     * Constructor to initialize permissions.
     */
    public function __construct()
    {
        Permission::module_init($this, 'size');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listing = new ListingHelper();

        $sizes = $listing->simple_search(ProductSize::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.products.size_index',compact('sizes', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sizes = ProductSize::all();

        return view('admin.products.size_create', compact('sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:product_sizes,name|max:255',
        ]);

        $status = 'PRIVATE';

        if ($request->has('visibility')) {
            $status = 'PUBLISHED';
        }

        $size = new ProductSize();
        $size->name = $request->input('name');
        $size->description = $request->input('description', '');
        $size->status = $status;
        $size->added_by = Auth::id();
        $size->save();

        return redirect()->route('sizes.index')->with('success', 'Product Size created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductSize $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductSize $size)
    {
        return view('admin.products.size_edit',compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductSize $size)
    {
        $request->validate([
            'name' => 'required|max:255|unique:product_sizes,name,'.$size->id,
        ]);

        $status = 'PRIVATE';

        if ($request->has('visibility')) {
            $status = 'PUBLISHED';
        }

        $size->name = $request->input('name');
        $size->description = $request->input('description', '');
        $size->status = $status;
        $size->updated_by = Auth::id();
        $size->save();

        return redirect()->route('sizes.index')->with('success', 'Product Size updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductSize $size)
    {
        $size->update([ 'updated_by' => Auth::id() ]);
        $size->delete();
        return back()->with('success', 'Product Size deleted successfully.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function update_status($id,$status)
    {
        ProductSize::where('id', $id)->update([
            'status' => $status,
            'updated_by' => Auth::id()
        ]);

        return back()->with('success', 'Product Size status updated successfully to ' . $status . '.');
    }

    /**
     * Update the status of multiple resources.
     */
    public function multiple_change_status(Request $request)
    {
        $sizes = explode("|", $request->sizes);

        foreach ($sizes as $size) {
            $publish = ProductSize::where('status', '!=', $request->status)->whereId($size)->update([
                'status'  => $request->status,
                'updated_by' => Auth::id()
            ]);
        }

        return back()->with('success', 'Product Sizes status updated successfully to ' . $request->status . '.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function multiple_delete(Request $request)
    {
        $sizes = explode("|",$request->sizes);

        foreach($sizes as $size){
            ProductSize::whereId($size)->update(['updated_by' => Auth::id() ]);
            ProductSize::whereId($size)->delete();
        }

        return back()->with('success', 'Product Sizes deleted successfully.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($size){
        ProductSize::withTrashed()->find($size)->update(['updated_by' => Auth::id() ]);
        ProductSize::whereId($size)->restore();

        return back()->with('success', 'Product Size restored successfully.');
    }
}
