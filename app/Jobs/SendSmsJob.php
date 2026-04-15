<?php

namespace App\Jobs;

use App\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $type;
    public $salesHeader;

    public function __construct($phone, $type, $salesHeader)
    {
        $this->phone = $phone;
        $this->type = $type;
        $this->salesHeader = $salesHeader;
    }

    public function handle()
    {
        $sms = new Sms();
        $sms->send_sms($this->phone, $this->type, $this->salesHeader);
    }
}