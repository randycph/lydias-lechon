<?php

namespace App\Http\Controllers\Product;

use App\Leftovers;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class LeftoversController extends Controller
{
    
    public function index()
    {
        $branches = \App\UserBranch::accessBranch();
        $array = [];
        //dd($branches);
        if(isset($_GET['branch'])){
            array_push($array, $_GET['branch']);
        }
        else{
            foreach($branches as $b){            
                array_push($array, $b->branch_id);
            }
        }     

        
        if(isset($_GET['search'])){
            $dates = Leftovers::whereIn('branch_id',$array);
            if(isset($_GET['date'])){
                $dates->where('date',$_GET['date']);
            }
            $dates->distinct()->get(['date','branch_id']);            
        }
        else{
            $dates = Leftovers::whereIn('branch_id',$array)->distinct()->get(['date','branch_id']);
        }
        return view('admin.leftover.index',compact('dates','branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = \App\UserBranch::accessBranch();
        $products = Product::withTrashed()->get();
        return view('admin.leftover.create',compact('branches','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        for($x = 1; $x<=100; $x++){
            if(!empty($request->input('prod'.$x))){
                $save = Leftovers::create([
                    'qty' => $request->input('qty'.$x),
                    'product_id' => $request->input('prod'.$x),
                    'remarks' => $request->input('remark'.$x),
                    'uom' => $request->input('uom'.$x),
                    'date' => $request->input('date'),
                    'branch_id' => $request->input('branch'),
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('leftover.index')->with('success','Successfully added new records!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leftovers  $leftovers
     * @return \Illuminate\Http\Response
     */
    public function show(Leftovers $leftovers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leftovers  $leftovers
     * @return \Illuminate\Http\Response
     */
    public function edit($leftovers)
    {       
       
    }

    public function print($date, $branch)
    {               
        $branched = \App\EcommerceModel\Branch::whereId($branch)->first();        
        $los = Leftovers::where('branch_id',$branch)->where('date',$date)->get();
  
        
        return view('admin.leftover.print',compact('los','branched','date'));
    }

    public function edit_all($date, $branch)
    {               
        $branched = \App\EcommerceModel\Branch::whereId($branch)->first();        
        $los = Leftovers::where('branch_id',$branch)->where('date',$date)->get();
        $products = Product::all();
        
        return view('admin.leftover.edit',compact('los','branched','date','products'));
    }

    public function update_all(Request $request)
    {
        $los = Leftovers::where('branch_id',$request->branch)->where('date',$request->date)->forceDelete();
        for($x = 1; $x<=200; $x++){
            if(!empty($request->input('prod'.$x))){
                $save = Leftovers::create([
                    'qty' => $request->input('qty'.$x),
                    'product_id' => $request->input('prod'.$x),
                    'remarks' => $request->input('remark'.$x),
                    'uom' => $request->input('uom'.$x),
                    'date' => $request->input('date'),
                    'branch_id' => $request->input('branch'),
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('leftover.index')->with('success','Successfully Updated Records!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leftovers  $leftovers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leftovers $leftovers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leftovers  $leftovers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leftovers $leftovers)
    {
        //
    }
}
