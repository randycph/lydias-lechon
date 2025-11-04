
@foreach($delivery as $d)
    <tr>
    	<td>{{$d->created_at}}</td>
        <td>{{$d->status}}</td>
        <td>{{$d->remarks}}</td>   
        <td>
            @php $user = App\Models\User::where('id', $d->delivered_by)->orWhere('name', $d->delivered_by)->first() ?? null; @endphp
            {{$user?->name ?? $d->delivered_by}}</td> 
        <td>
            @if ($d->image == null && $d->images == null)
                No Image
            @else
                @if ($d->images != null && count($d->images) > 0)
                    @php
                        $images = json_decode($d->images);
                    @endphp
                    @foreach ($d->images as $key => $image)
                        <a href="{{ config('app.url') . '/images/proof-of-delivery/' . $image->image }}" target="_blank">Image {{ $key + 1 }}</a>
                    @endforeach
                @else
                    <a href="{{ config('app.url') . '/images/proof-of-delivery/' . $d->image }}" target="_blank">View</a>
                @endif
            @endif
        </td> 
    </tr>
@endforeach