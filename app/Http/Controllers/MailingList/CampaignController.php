<?php

namespace App\Http\Controllers\MailingList;

use App\Helpers\ListingHelper;
use App\Models\Permission;
use App\Jobs\SendCampaignToSubscriberJob;
use App\Jobs\SendEmailJob;
use App\MailingListModel\Campaign;
use App\MailingListModel\Group;
use App\MailingListModel\Subscriber;
use App\SentCampaign;
use App\SentCampaignSubscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class CampaignController extends Controller
{

    public function __construct()
    {
        Permission::module_init($this, 'campaign');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchFields = ['name'];

        $listing = new ListingHelper();

        $campaigns = $listing->simple_search(Campaign::class, $searchFields);

        // Simple search init data
        $filter = $listing->get_filter($searchFields);

        $searchType = 'simple_search';

        return view('admin.mailing-list.campaigns.index', compact('campaigns','filter', 'searchType'));
    }

    public function sent_campaigns(Request $request)
    {
        $searchFields = ['name'];

        $listing = new ListingHelper();

        $sentCampaigns = $listing->simple_search(SentCampaign::class, $searchFields);

        // Simple search init data
        $filter = $listing->get_filter($searchFields);

        $searchType = 'simple_search';

        return view('admin.mailing-list.campaigns.sent-campaigns', compact('sentCampaigns', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $subscribers = Subscriber::all();

        return view('admin.mailing-list.campaigns.create', compact('groups', 'subscribers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newCampaign = $request->validate([
            'name' => 'max:150|required',
            'from_name' => 'max:150|required',
            'from_email' => 'max:150|email|required',
            'subject' => 'max:150|required',
            'content' => 'required',
            'submit' => 'required',
            'recipients' => 'array|required_if:submit,==,save_send|required_without:recipient_groups',
            'recipients.*' => 'exists:subscribers,id',
            'recipient_groups' => 'array|required_if:submit,==,save_send|required_without:recipients',
            'recipient_groups.*' => 'exists:groups,id'
        ]);

        $campaign = Campaign::create($newCampaign);

        if ($request->submit == 'save and send') {

            $newCampaign['sender_id'] = auth()->id();
            $newCampaign['campaign_id'] = $campaign->id;

            $sentCampaign = SentCampaign::create($newCampaign);

            $recipients = $request->has('recipients') ? $request->recipients : [];
            foreach ($recipients as $recipientId) {
                $subscriber = Subscriber::find($recipientId);
                try {
                    $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign);
                } catch (\Swift_TransportException $e) {
                    continue;
                }
            }

            $recipientGroups = $request->has('recipient_groups') ? $request->recipient_groups : [];
            foreach ($recipientGroups as $groupId) {
                $group = Group::find($groupId);
                foreach ($group->subscribers as $subscriber) {
                    try {
                        $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign, $groupId);
                    } catch (\Swift_TransportException $e) {
                        continue;
                    }
                }
            }
        }

        if ($campaign) {
            return redirect()->route('mailing-list.campaigns.index')->with(['success' => 'The campaign has been added and send to the recipient/s.']);
        } else {
            return redirect()->route('mailing-list.campaigns.create')->with(['error' => 'Failed to add campaign. Please try again.']);
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Campaign $campaign)
    {
        $groups = Group::all();
        $subscribers = Subscriber::all();

        return view('admin.mailing-list.campaigns.edit', compact('campaign', 'groups', 'subscribers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        $campaignData = $request->validate([
            'name' => 'max:150|required',
            'from_name' => 'max:150|required',
            'from_email' => 'max:150|email|required',
            'subject' => 'max:150|required',
            'content' => 'required',
            'submit' => 'required',
            'recipients' => 'array|required_if:submit,==,save_send|required_without:recipient_groups',
            'recipients.*' => 'exists:subscribers,id',
            'recipient_groups' => 'array|required_if:submit,==,save_send|required_without:recipients',
            'recipient_groups.*' => 'exists:groups,id'
        ]);

        $campaign->update($campaignData);

        if ($request->submit == 'save and send') {

            $campaignData['sender_id'] = auth()->id();
            $campaignData['campaign_id'] = $campaign->id;

            $sentCampaign = SentCampaign::create($campaignData);

            $recipients = $request->has('recipients') ? $request->recipients : [];
            foreach ($recipients as $recipientId) {
                $subscriber = Subscriber::find($recipientId);
                try {
                    $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign);
                } catch (\Swift_TransportException $e) {
                    continue;
                }
            }

            $recipientGroups = $request->has('recipient_groups') ? $request->recipient_groups : [];
            foreach ($recipientGroups as $groupId) {
                $group = Group::find($groupId);
                foreach ($group->subscribers as $subscriber) {
                    try {
                        $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign, $groupId);
                    } catch (\Swift_TransportException $e) {
                        continue;
                    }
                }
            }
        }

        if ($campaign) {
            return redirect()->route('mailing-list.campaigns.index')->with(['success' => 'The campaign has been added and send to the recipient/s.']);
        } else {
            return redirect()->route('mailing-list.campaigns.create')->with(['error' => 'Failed to add campaign. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Campaign $campaign
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Campaign $campaign)
    {
        if ($campaign->delete()) {
            return back()->with('success', 'The campaign has been deleted');
        } else {
            return back()->with('error', 'Failed to delete a campaign. Please try again.');
        }
    }

    public function destroy_many()
    {
        $deleteIds = explode(',', request('ids'));
        if (sizeof($deleteIds) > 0 ) {
            $delete = Campaign::whereIn('id', $deleteIds)->delete();
            if ($delete) {
                return back()->with('success', 'The download manager category\s has been deleted');
            }
        }

        return back()->with('error', 'Failed to delete download manager category\s.');
    }

    public function restore($id)
    {
        Campaign::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'The download manager category has been restored');
    }

    public function validate_data(Request $request)
    {
        return $request->validate([
            'name' => 'required|max:100',
            'description' => '',
            'is_active' => ''
        ]);
    }
    /**
     * @param Campaign $campaign
     * @param Subscriber $subscriber
     * @param SentCampaign $sentCampaign
     * @param null $groupId
     */
    public function send_campaign_to_subscriber(Campaign $campaign, Subscriber $subscriber, SentCampaign $sentCampaign, $groupId = null): void
    {
        $campaignHistory = new SentCampaignSubscriber();
        $campaignHistory->sent_campaign_id = $sentCampaign->id;
        $campaignHistory->group_id = $groupId;
        $campaignHistory->subscriber_id = $subscriber->id;
        $campaignHistory->mailing_type = $groupId ? "group" : "individual";
        $campaignHistory->save();

        dispatch(new SendCampaignToSubscriberJob($subscriber, $campaign, $campaignHistory));
    }

}
