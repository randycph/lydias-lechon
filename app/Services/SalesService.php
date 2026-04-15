<?php

namespace App\Services;

use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\Cart;
use App\Models\Product;
use App\Models\User;
use App\EcommerceModel\SalesDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SalesService
{
    public function handle(Request $request)
    {
        $this->validateRequest($request);

        $user = $this->resolveUser($request);

        $carts = $this->getCart($user);

        $settings = Setting::first();

        $minHours = $this->resolveMinHours($carts, $settings);

        $this->validateDelivery($request, $minHours, $settings);

        $totals = $this->calculateTotals($carts, $request);

        $salesHeader = $this->createSalesHeader($user, $request, $totals);

        $this->createSalesDetails($salesHeader, $carts);

        return $this->buildResponse($salesHeader, $request);
    }

    private function validateRequest(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required','regex:/^(09|\+639)\d{9}$/'],
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
        ]);

        if ($validator->fails()) {
            abort(response()->json([
                'errors' => $validator->errors()
            ], 422));
        }
    }

    private function resolveUser(Request $request): User
    {
        if (auth()->check()) {
            return auth()->user();
        }

        $firstName = explode(' ', trim($request->name))[0] ?? 'Guest';
        $lastName = trim(str_replace($firstName, '', $request->name)) ?: 'Guest';

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_mobile' => $request->mobile,
            'password' => Hash::make(Str::random(10)),
            'firstname' => $firstName,
            'lastname' => $lastName,
            'registration_type' => 'guest',
            'registration_source' => 'Guest',
            'is_active' => 1,
            'role_id' => 6
        ]);
    }

    private function getCart(User $user): Collection
    {
        if (auth()->check()) {
            return Cart::where('user_id', $user->id)
                ->with('product')
                ->get();
        }

        return collect(session('cart', []));
    }

    private function resolveMinHours(Collection $carts, $settings): int
    {
        $default = $settings->minimum_processing_hours ?? 24;
        $misc = $settings->minimum_processing_hours_misc ?? 12;
        $baka = $settings->minimum_processing_hours_baka ?? 72;

        foreach ($carts as $cart) {
            if ($cart->product->slug === 'lechon-baka') return $baka;
            if ($cart->product->is_misc == 1) return $misc;
        }

        return $default;
    }

    private function validateDelivery(Request $request, int $minHours, $settings): void
    {
        if (!$request->need_date || !$request->need_time) {
            abort(response()->json([
                'errors' => [
                    'need_date' => ['Date and time required']
                ]
            ], 422));
        }

        if (!$this->validateProcessingHours(
            $request->need_date,
            $request->need_time,
            $minHours
        )) {
            abort(response()->json([
                'errors' => [
                    'need_date' => ['Does not meet processing hours']
                ]
            ], 422));
        }
    }

    public function validateProcessingHours($date, $time, $minHours): bool
    {
        $requested = Carbon::parse("$date $time");
        return now()->diffInHours($requested, false) >= $minHours;
    }

    private function calculateTotals(Collection $carts, Request $request): array
    {
        $gross = 0;

        foreach ($carts as $cart) {
            $gross += $cart->product->price * $cart->qty;
        }

        $delivery = $request->delivery_fee ?? 0;

        return [
            'gross' => $gross,
            'net' => $gross + $delivery,
            'delivery_fee' => $delivery
        ];
    }

    private function createSalesHeader(User $user, Request $request, array $totals)
    {
        return SalesHeader::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_contact_number' => $request->mobile,
            'gross_amount' => $totals['gross'],
            'net_amount' => $totals['net'],
            'delivery_fee_amount' => $totals['delivery_fee'],
            'status' => 'active',
            'currency' => 'PHP',
        ]);
    }

    private function createSalesDetails(SalesHeader $header, Collection $carts): void
    {
        foreach ($carts as $cart) {
            SalesDetail::create([
                'sales_header_id' => $header->id,
                'product_id' => $cart->product->id,
                'product_name' => $cart->product->name,
                'price' => $cart->product->price,
                'qty' => $cart->qty,
                'gross_amount' => $cart->product->price * $cart->qty,
                'net_amount' => $cart->product->price * $cart->qty,
            ]);
        }
    }

    private function buildResponse(SalesHeader $salesHeader, Request $request)
    {
        return response()->json([
            'success' => true,
            'sales_header_id' => $salesHeader->id,
            'customer_name' => $salesHeader->customer_name,
            'amount' => number_format($salesHeader->net_amount,2)
        ]);
    }
}