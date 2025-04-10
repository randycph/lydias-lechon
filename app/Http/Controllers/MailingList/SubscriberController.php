<?php

namespace App\Http\Controllers\MailingList;

use App\Helpers\ListingHelper;
use App\Models\Permission;
use App\Helpers\Webfocus\Setting;
use App\Mail\MailingList\WelcomeMail;
use App\MailingListModel\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    private $searchFields = ['email'];

    public function __construct()
    {
        Permission::module_init($this, 'subscriber');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $listing = new ListingHelper('desc', 10, 'created_at');

        $subscribers = $listing->simple_search(Subscriber::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.mailing-list.subscribers.index', compact('subscribers','filter', 'searchType'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe()
    {
        $listing = new ListingHelper('desc', 10, 'deleted_at');

        $unsubscribers = $listing->simple_search_trash_only(Subscriber::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $searchType = 'simple_search';

        return view('admin.mailing-list.subscribers.unsubscribe', compact('unsubscribers','filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.mailing-list.subscribers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newSubscriber = $request->validate([
            'first_name' => 'max:150',
            'last_name' => 'max:150',
            'email' => 'email|required|unique:subscribers,email'
        ]);

        $newSubscriber['code'] = Subscriber::generate_unique_code();
        $newSubscriber['is_active'] = ($request->has('is_active')) ? 1 : 0;

        $subscriber = Subscriber::create($newSubscriber);
        if (!empty($subscriber)) {
            \Mail::to($subscriber->email)->send(new WelcomeMail(Setting::info(), $subscriber));
            return redirect()->route('mailing-list.subscribers.index')->with(['success' => 'The subscriber has been added.']);
        } else {
            return redirect()->route('mailing-list.subscribers.create')->with(['error' => 'Failed to add subscriber. Please try again.']);
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
     * @param Request $request
     * @param Subscriber $subscriber
     * @return void
     */
    public function edit(Request $request, Subscriber $subscriber)
    {
        return view('admin.mailing-list.subscribers.edit', compact('subscriber'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Subscriber $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        $subscriberData = $request->validate([
            'first_name' => 'max:150',
            'last_name' => 'max:150',
            'email' => 'email|required|unique:subscribers,email,'.$subscriber->id,
        ]);

        $subscriberData['is_active'] = ($request->has('is_active')) ? 1 : 0;

        if ($subscriber->update($subscriberData)) {
            return redirect()->route('mailing-list.subscribers.index')->with(['success' => 'The subscriber has been added.']);
        } else {
            return redirect()->route('mailing-list.subscribers.create')->with(['error' => 'Failed to add subscriber. Please try again.']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change_status(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required',
            'is_active' => 'required'
        ]);

        $ids = explode(',', $data['ids']);
        $status = (empty($data['is_active'])) ? 0 : 1;

        Subscriber::whereIn('id', $ids)->update(['is_active' => $status]);

        return redirect()->route('mailing-list.subscribers.index')->with('success', 'The subscriber/s has been updated.');
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
}

