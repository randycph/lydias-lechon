<?php

namespace App\Console\Commands;

use App\EcommerceModel\CouponCart;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class checkForUnpaidPaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-for-unpaid-payment-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // check if receipt_number is not null or empty and created_at starts at nov 1, 2025
        $payments = SalesPayment::where('payment_type', 'Paymaya')
            ->where('status', 'PENDING')
            ->where('receipt_number', '!=', null)
            ->where('receipt_number', '!=', '')
            ->whereDate('created_at', '>=', '2025-11-01')
            ->get();

        foreach ($payments as $salesPayment) {

            $salesHeader  = $salesPayment->sales;

            // Already paid? Do nothing
            if ($salesPayment->status === 'PAID') {
                continue;
            }

            try {
                $res = Http::withOptions(['verify' => false])
                    ->withHeaders([
                        'Authorization' => 'Basic ' . base64_encode($this->sk() . ':'), // sk:
                        'Content-Type'  => 'application/json',
                    ])
                    ->get($this->paymaya_url() . '/' . $salesPayment->receipt_number);

                if (!$res->successful()) {
                    logger('PAYMAYA CHECK FAILED', [
                        'status' => $res->status(),
                        'body'   => $res->body(),
                    ]);
                    continue;
                }

                $data = $res->json();
                logger('PAYMAYA CHECK RESPONSE', $data);

                /**
                 * FFICIAL SUCCESS CONDITIONS
                 */
                if (
                    ($data['status'] ?? null) !== 'COMPLETED' ||
                    ($data['paymentStatus'] ?? null) !== 'PAYMENT_SUCCESS'
                ) {
                    continue;
                }

                /**
                 * CORRECT AMOUNT SOURCE (post-payment)
                 */
                $paidAmount = (float)
                    $data['paymentDetails']['responses']['efs']['amount']['total']['value'];

                // =========================
                // UPDATE PAYMENT RECORD
                // =========================
                $salesPayment->update([
                    'amount' => $paidAmount,
                    'status' => 'PAID',
                ]);

                // =========================
                // CONFIRM MAIN SALES
                // =========================
                $salesHeader->update([
                    'isConfirm'       => 1,
                    'confirmed_by'    => 'Customer',
                    'confirmed_on'    => now(),
                    'confirm_remarks' => 'Auto confirm via Maya checkout',
                    'updated_at'      => $salesHeader->created_at,
                ]);

                // =========================
                // CONFIRM SUB-SALES
                // =========================
                $subSales = SalesHeader::where('parent_sales_header_id', $salesHeader->id)->get();
                foreach ($subSales as $sub) {
                    $sub->update([
                        'isConfirm'       => 1,
                        'confirmed_by'    => 'Customer',
                        'confirmed_on'    => now(),
                        'confirm_remarks' => 'Auto confirm via Maya checkout',
                        'updated_at'      => $sub->created_at,
                    ]);

                    $sub->assign_to_production_branch($sub, 1);
                }

                // Assign production branch (main)
                $salesHeader->assign_to_production_branch($salesHeader, 1);

                // Mark coupon as used
                if ($salesHeader->discount_amount > 0) {
                    CouponCart::where('sales_header_id', $salesHeader->id)
                        ->update(['status' => 1]);
                }

                return true;

            } catch (\Throwable $th) {
                logger('PAYMAYA CHECK ERROR', [
                    'error' => $th->getMessage(),
                ]);
                continue;
            }
        }
    }
}
