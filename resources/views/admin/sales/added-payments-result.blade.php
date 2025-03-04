@php                            
    $alls = explode(",",auth()->user()->allowed_payments);
    if(in_array("Credit/Debit Card", $alls)){
        array_push($alls,"Debit/Credit Card");
    }
@endphp
@foreach($payments as $payment)
    @php
        $approval_code = '';
        if($payment->status == 'PAID'){
            $payment_approval = \App\Approvals::where('reference_id',$payment->sales_header_id)->where('approval_type','Payment')->first();
            if($payment_approval){
                $approval_code = '<br>('.$payment_approval->approval_code.')';
            }
        }
    @endphp   
        <tr>
            <td>{{$payment->receipt_number}}</td>
            <td>{{$payment->payment_date}}</td>
            <td>{{$payment->payment_type}}</td>
            <td><a href="{{$payment->file_url}}" target="_blank">@if(!empty($payment->file_url)) View @endif</a></td>
            <td class="text-right">{{number_format($payment->amount,2)}}</td>
            <td align="center">{{$payment->status}} {!!$approval_code!!}</td>
            <td>@if($payment->status<>'PAID')
                    @if(in_array(strtolower($payment->payment_type),array_map('strtolower',$alls)) || auth()->user()->role_id == 1)
                        @if (auth()->user()->has_access_to_route('approve_payment'))
                            <a href="#" onclick="confirm_sales_payment({{$payment->id}},'{{$payment->payment_type}}','{{preg_replace( "/\r|\n/", "", $payment->receipt_number )}}')" class="btn btn-primary btn-xs">Approve</a>
                        @endif

                        @if(auth()->user()->has_access_to_route('disapprove_payment'))
                            <a href="#" class="btn btn-danger btn-xs" onclick='
                                var txt;
                                var r = confirm("Are you sure you want cancel this payment?");
                                if (r == true) {
                                  window.location.href = "{{route('disapprove_payment',$payment->id)}}";
                                }
                                else {
                                    return false;
                                }
                            '>Cancel</a>
                        @endif
                    @endif
                @endif
            </td>
        </tr>
    
@endforeach
<tr style="font-weight:bold;">
    <td colspan="4">Total</td>
    <td>{{number_format($payments->sum('amount'),2)}}</td>
</tr>