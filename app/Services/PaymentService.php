<?php

namespace App\Services;

class PaymentService
{
    public function generate($salesHeader, $deposit)
    {
        $merchantkey = '2amqVf04H9';
        $merchantcode = 'PH00125';

        $refno = $salesHeader->order_number;
        $amount = str_replace(".", "", number_format($deposit,2,'.',''));
        $currency = strtoupper($salesHeader->currency);

        $signature = sha1($merchantkey.$merchantcode.$refno.$amount.$currency);

        return [
            'amount' => $amount,
            'signature' => $signature
        ];
    }
}