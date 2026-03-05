<?php

namespace App\Imports;

use App\EcommerceModel\GiftCertificate;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesPayment;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\Customer;
use App\Models\Product;
use App\Models\JobOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class JobOrdersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $validator = Validator::make($row->toArray(), [
                'product_slug' => 'required',
                'quantity' => 'required',
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'row_' . ($index + 1) => $validator->errors()->all()
                ]);
            }

            // Handle existing or new customer
            if ((int) $row['existing_customer'] === 2) {
                $validated = Validator::make($row->toArray(), [
                    'first_name' => 'required|regex:/^[A-Za-z\s\-]+$/',
                    'last_name' => 'required|regex:/^[A-Za-z\s\-]+$/',
                    'contact_number' => 'required|string',
                ])->validate();

                $todayd = getdate();
                $email_cs = $row['email_address'] ?? 'lydtmp_'.$todayd[0].substr(microtime(), 2,6).'@lydias.com';
                $check_if_exist = User::where('email', '=', $email_cs)->first();
                if ($check_if_exist === null) {
                    $customer = User::create([
                        'firstname'         => $row['first_name'],
                        'lastname'          => $row['last_name'],
                        'email'             => $row['email_address'],
                        'birthday'          => $row['birthdate'],
                        'address'           => $row['address'],
                        'contact_mobile'    => $row['contact_number'],
                    ]);
                } else {
                    $id = $check_if_exist->id;
                }

                $contact_pers   = $row['first_name'] . ' ' . $row['last_name'];
                $name           = $row['first_name'] . ' ' . $row['last_name'];
                $mobile         = $row['contact_number'];
                $address        = $row['address'];
            } elseif ((int) $row['existing_customer'] === 1) {
                $customer = User::where('email', $row['email_address'])->first();
                if (!$customer) continue;

                $name       = $customer->name;
                $contact_pers = $customer->name;

                $id         = $customer->id;            
                $mobile     = $customer->contact_mobile;
                $address    = $customer->address_street . ', ' . $customer->address_brgy . ', ' . $customer->address_city . ', ' . $customer->municipality . ', ' . $customer->address_region;
            }

            if($row['delivery_type'] == 'Delivery'){
                $outlet = $row['outlet'];
                $delivery_add = $address;
                $pickup_store = '';
                $customer_location = $address;
            }
            else{
                $outlet = $row['outlet'];
                $delivery_add = $outlet;
                $pickup_store = $outlet;
                $customer_location = '';
            }

            $gross_amount = $row['gross_amount'];
            $net_amount = $row['net_amount'];
            $discount_amount = 0;
            $totalCouponDiscount = 0;

            // Handle products
            $slugs = explode(',', $row['product_slug']);
            $quantities = explode(',', $row['quantity']);
            $products = [];
            $paella_qty = $row['paella_qty'];

            if ($slugs && count($slugs) > 0) {
                foreach ($slugs as $i => $slug) {
                    $product = Product::where('slug', trim($slug))->first();
    
                    if (!$product) {
                        continue;
                    }
    
                    $products[] = [
                        'product_id' => $product->id,
                        'quantity'   => (int) $quantities[$i],
                        'paella_qty' => 0
                    ];
                }
            }

            if ($products && count($products) == 0) {
                continue;
            }

            $ran   = microtime();
            $today = getdate();
            $order_number = $today[0].substr($ran, 2,6);

            $bs = $row['branch'] ?? 'Forecaster';

            $salesHeader = SalesHeader::create([
                'user_id' => $id,
                'order_number' => $order_number,
                'response_code' => 'success',
                'order_source' => $bs,
                'customer_name' => $name,
                'customer_contact_number' => $mobile,
                'customer_address' => $address,
                'customer_delivery_adress' => $delivery_add,
                'delivery_tracking_number' => '',
                'delivery_fee_amount' => $row['delivery_charge'],
                'gross_amount' => $gross_amount,
                'tax_amount' => 0,
                'discount_amount' => $discount_amount,
                'net_amount' => $net_amount,
                'payment_status' => $row['payment_method'] == 'Deposit' ? 'UNPAID' : 'PAID',
                'delivery_status' => '',
                'status' => 'active',
                'payment_date' => Carbon::today(),
                'delivery_type' => $row['delivery_type'] == 1 ? 'Door to door delivery' : 'Store Pickup',
                'outlet' => $pickup_store,
                'order_type' => $row['order_type'],
                'currency' => 'PHP',
                'instruction' => $row['remarks'],
                'payment_used' => $row['payment_method'],
                'payment_remarks' => '',
                'customer_location' => $customer_location,
                'agent' => $row['agent'] ?? auth()->user()->name,
                'delivery_branch' => $row['delivery_type'] == 1 ? $row['delivery_branch'] : NULL,
                'contact_person' => $contact_pers
            ]);

            if($salesHeader){
                if ($salesHeader) {
                    $lastOrder = SalesHeader::whereNull('parent_sales_header_id')
                        ->orderByDesc('id')
                        ->first();

                    if ($lastOrder) {
                        $nextOrder = intval($lastOrder->order_number) + 1;
                    } else {
                        $nextOrder = 1;
                    }

                    $orderNumber = sprintf('%07d', $nextOrder);

                    $salesHeader->update([
                        'order_number' => $orderNumber
                    ]);
                }

                $data = $row->all();

                foreach ($products as $item) {
                    $productModel = Product::find($item['product_id']);

                    if (!$productModel) {
                        continue;
                    }

                    $order_qty = $item['quantity'];
                    $paella_qty = $item['paella_qty'];
                    
                    $this->save_product_to_sales_detail($salesHeader->id, $productModel, $order_qty, $paella_qty, $row->toArray());
                }
            }

            $misc = explode(',', $row['misc_product_slug']);
            $misc_qty = explode(',', $row['misc_product_quantity']);

            $miscellaneous = [];

            if ($misc && count($misc) > 0) {
                foreach ($misc as $i => $slug) {
                    $product = Product::where('slug', trim($slug))->first();
    
                    if (!$product) {
                        continue;
                    }
    
                    $miscellaneous[] = [
                        'product_id' => $product->id,
                        'quantity'   => (int) $misc_qty[$i],
                        'paella_qty' => 0
                    ];
                }
            }

            if ($miscellaneous && count($miscellaneous) > 0) {

                $data = $row->all();

                foreach ($miscellaneous as $i => $prod) {
                    $product = Product::where('id', $prod['product_id'])->first();

                    $this->save_product_to_sales_detail($salesHeader->id, $product, $misc_qty[$i], 0, $data);
                }
            }

            $is_cod = 'PENDING';

            $add_special_payment = SalesPayment::create([
                'sales_header_id' => $salesHeader->id,
                'payment_type' => $row['payment_method'] ?? 'Paymaya',
                'amount' => $row['gross_amount'],
                'status' => $is_cod,
                'payment_date' => date('Y-m-d'),
                'receipt_number' => '',
                'created_by' => $salesHeader->user_id,
                'file_url' => '',
                'order_number' => $salesHeader->order_number
            ]);
        }

        if(auth()->user()->role_id == 3) {
            return redirect()->route('joborders.index')->with('success', __('standard.joborders.create_success'));
        } else {
            return redirect()->route('sales-transaction.index')->with('success', __('standard.joborders.create_success'));
        }
    }

    public function verify_coupon($code){

        $coupon = GiftCertificate::whereCode($code)->whereStatus('Unused')->first();

        if(empty($coupon)){
            return 0;
        }

        return 1;
    }

    public function save_product_to_sales_detail($salesHeaderID, $product, $qty, $paella_qty, $data)
    {
        SalesDetail::create([
            'sales_header_id' => $salesHeaderID,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_category' => $product->category_id,
            'price' => $product->price,
            'paella_price' => $paella_qty > 0 ? ($product->paella_price * $paella_qty) : 0,
            'cost' => 0,
            'tax_amount' => 0,
            'promo_id' => 0,
            'promo_description' => '',
            'discount_amount' => 0,
            'gross_amount' => ($product->price * $qty) + ($product->paella_price * $paella_qty), // need further clarification about paella qty
            'net_amount' => ($product->price *$qty) + ($product->paella_price * $paella_qty),
            'qty' => $qty,
            'uom' => 'PC',
            'penalty' => 0,
            'status' => '',
            'delivery_date' => $data['delivery_date'].' '.$data['delivery_time'],
            'size' => 0,
            'no_of_pax' => 0,
            'paella_qty' => $paella_qty,
            'joborder_id' => 0,
            'created_by' => auth()->user()->id,
        ]);
    }
}
