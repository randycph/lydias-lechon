
@foreach($delivery as $d)
    <tr>
    	<td>{{$d->created_at}}</td>
        <td>{{$d->status}}</td>
        <td>{{$d->remarks}}</td>   
        <td>{{$d->delivered_by}}</td> 
        <td>
            @if ($d->image == null)
                No Image
            @else
                <a href="{{ config('app.url') . '/images/proof-of-delivery/' . $d->image }}" target="_blank">View</a>
            @endif
        </td> 
    </tr>
@endforeach