<?php

namespace App\Services;

use App\EcommerceModel\SalesHeader;
use App\Jobs\SendSmsJob;
use App\Models\ProductDeliveryAddress;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SendNotification
{
    public function process($notificationService, $salesHeader, $user, $request)
    {
        try {
            $notificationService->send($salesHeader, $user, $request);
        } catch (\Exception $e) {
            logger('notification_error', [$e->getMessage()]);
        }

        $multipleDeliveries = ProductDeliveryAddress::where('sales_header_id', $salesHeader->id)->get();

        foreach ($multipleDeliveries as $delivery) {
            // SMS (QUEUED JOB)
            if ($delivery?->receive_sms == 1 && strlen($delivery->contact_tel) > 1) {
                try {
                    SendSmsJob::dispatch(
                        $delivery->contact_tel,
                        'new_order_delivery',
                        $salesHeader
                    );
                } catch (\Exception $e) {
                    logger('sms_notification_error', [$e->getMessage()]);
                }
            }
        }
    }
}