<?php

namespace App\Http\Controllers\MailingList;

use App\Helpers\Webfocus\Setting;
use App\Mail\MailingList\UnsubscribedMail;
use App\Mail\MailingList\WelcomeMail;
use App\MailingListModel\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberFrontController extends Controller
{
    public function subscribe(Request $request)
    {
        $newSubscriber = $request->validate([
            'email' => 'required|email',
            'first_name' => '',
            'last_name' => ''
        ]);

        $subscriber = Subscriber::withTrashed()->where('email', $request->email)->first();
        if ($subscriber) {
            if ($subscriber->trashed()) {
                $subscriber->restore();
                return response()->json(['success' => true, 'message' => 'Thank you for subscribing again.']);
            } else {
                return response()->json(['failed' => true, 'message' => 'Your email is already in our list.']);
            }
        }

        $newSubscriber['code'] = Subscriber::generate_unique_code();

        $subscriber = Subscriber::create($newSubscriber);

        if (!empty($subscriber)) {
            \Mail::to($request->email)->send(new WelcomeMail(Setting::info(), $subscriber));
            return response()->json(['success' => true, 'message' => 'Thank you for subscribing.']);
        } else {
            return response()->json(['failed' => true, 'message' => 'Failed to subscribe. Please try again later.']);
        }

    }

    public function unsubscribe(Request $request, Subscriber $subscriber, $code)
    {
        if ($subscriber->code == $code) {

            \Mail::to($subscriber->email)->send(new UnsubscribedMail(Setting::info()));

            $subscriber->delete();

            return view('components.unsubscribed');

//            return "You’ve been successfully removed from our mailing list. <script>window.setTimeout(function(){window.location.href = '".url('/')."'}, 3000);";
        }

        abort(404);

    }
}
