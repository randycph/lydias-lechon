<?php

namespace App\Http\Controllers;

use App\Models\Deliverablecities;
use App\Helpers\ListingHelper;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DeliverablecitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        Permission::module_init($this, 'delivery_rate');
    }

    public function index()
    {
        $searchFields = ['city', 'province', 'item_type', 'barangay'];

        $listing = new ListingHelper();

        $address = $listing->simple_search(Deliverablecities::class, $searchFields);

        // Simple search init data
        $filter = $listing->get_filter($searchFields);

        $searchType = 'simple_search';

        return view('admin.deliverablelocations.index', compact('address', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.deliverablelocations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'       => 'nullable',
            'rate'       => 'required|numeric',
            'area'       => 'nullable',
            'barangay'   => 'nullable',
            'province'   => 'required',
            'city'       => 'required',
            'item_type'  => 'required',
            'region'     => 'required',

            'is_active'      => 'nullable',
            'control_mode'   => 'nullable|in:manual,scheduled',
            'override_state' => 'nullable|boolean',
            'override_until' => 'nullable|date|after:now',
            'auto_on_at'     => 'nullable|date|after_or_equal:now',
            'auto_off_at'    => 'nullable|date|after_or_equal:now',
        ]);

        $toUtc = function (?string $dt) {
            if (!$dt) return null;
            return Carbon::parse($dt, 'Asia/Manila');
        };

        $row = Deliverablecities::create([
            'rate'          => $request->rate,
            'item_type'     => $request->item_type,
            'province'      => $request->province,
            'city'          => $request->city,
            'barangay'      => $request->barangay,
            'region'        => $request->region,
            'outside_manila'=> $request->boolean('outside_manila') ? 1 : 0,
            'user_id'       => Auth::id(),

            // scheduling fields
            'is_active'      => $request->has('is_active') ? 1 : 0,
            'control_mode'   => $request->input('control_mode'),

            // If override_until provided, keep override_state as checkbox (true if checked, false if unchecked but present)
            'override_state' => $request->has('override_state') ? (int)$request->boolean('override_state') : null,
            'override_until' => $toUtc($request->input('override_until')),

            'auto_on_at'     => $toUtc($request->input('auto_on_at')),
            'auto_off_at'    => $toUtc($request->input('auto_off_at')),
        ]);

        return back()->with('success', 'Successfully saved new location!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function show(Deliverablecities $deliverablecities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rate = Deliverablecities::findOrFail($id);
        // dd($rate);
        return view('admin.deliverablelocations.edit',compact('rate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Base validation
        $request->validate([
            'name'       => 'nullable',
            'rate'       => 'required|numeric',
            'region'     => 'required',
            'area'       => 'nullable',
            'barangay'   => 'nullable',
            'province'   => 'required',
            'city'       => 'required',
            'item_type'  => 'required',

            // Simplified scheduler fields
            'is_active'    => 'nullable',
            'control_mode' => 'nullable|in:auto_on,auto_off',
            // require the correct datetime depending on selected mode
            'auto_on_at'   => 'nullable|required_if:control_mode,auto_on|date',
            'auto_off_at'  => 'nullable|required_if:control_mode,auto_off|date',
        ]);

        if ($request->input('control_mode') === 'auto_on') {
            $request->validate([
                'auto_on_at' => 'date|after:now',
            ]);
        } elseif ($request->input('control_mode') === 'auto_off') {
            $request->validate([
                'auto_off_at' => 'date|after:now',
            ]);
        }

        $row = Deliverablecities::findOrFail($id);
        $before = $row->is_active;

        $toUtc = function (?string $dt) {
            if (!$dt) return null;
            return Carbon::parse($dt, 'Asia/Manila');
        };

        $mode = $request->input('control_mode'); // null | auto_on | auto_off

        // Only keep the relevant schedule; clear the other
        $autoOnAt  = $mode === 'auto_on'  ? $toUtc($request->input('auto_on_at'))  : null;
        $autoOffAt = $mode === 'auto_off' ? $toUtc($request->input('auto_off_at')) : null;

        // Build updates
        $updates = [
            'rate'           => $request->rate,
            'province'       => $request->province,
            'city'           => $request->city,
            'barangay'       => $request->barangay,
            'region'         => $request->region,
            'item_type'      => $request->item_type,
            'outside_manila' => $request->boolean('outside_manila') ? 1 : 0,
            'user_id'        => Auth::id(),

            'is_active'    => $request->has('is_active') ? 1 : 0,

            'control_mode' => $mode,
            'auto_on_at'   => $autoOnAt,
            'auto_off_at'  => $autoOffAt,

            // We’re not using override_* in this simplified UI
            'override_state' => null,
            'override_until' => null,
        ];

        $row->update($updates);

        if ($row->is_active !== $before) {
            $row->last_changed_at = now();
            $row->save();
        }

        

        return redirect()
            ->route('admin.locations.index')
            ->with('success', 'Successfully updated delivery rate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deliverablecities  $deliverablecities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deliverablecities $deliverablecities)
    {
        //dd($deliverablecities);
    }

    public function delete(Request $request)
    {
        Deliverablecities::whereId($request->add_id)->delete();
        return back()->with('success','Successfully deleted location');
    }
}
