
@foreach($delivery as $d)
    <tr>
    	<td>{{$d->created_at}}</td>
        <td>{{$d->status}}</td>
        <td>{{$d->remarks}}</td>   
        <td>{{$d->delivered_by}}</td> 
    </tr>
@endforeach