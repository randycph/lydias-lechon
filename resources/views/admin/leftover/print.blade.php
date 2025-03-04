<style>
    #customers {
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    #customers td, #customers th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    #customers tr:nth-child(even){background-color: #f2f2f2;}

    #customers tr:hover {background-color: #ddd;}

    #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      background-color: #4CAF50;
      color: white;
    }


</style>
<table width="100%" style="text-align:center;font-family:Arial;">
    <tr><td><h1>Daily Leftover Report</h1><h5>{{$branched->name}} - {{date('F d, Y', strtotime($date))}}</h5></td></tr>
   
</table>
<br><br>
<table class="customers" id="customers">
    <thead>
        <tr>
          
            <th>Product</th>
            <th>Qty</th>
            <th>UoM</th>
            <th>Remark</th>                                
        </tr>
    </thead>
    <tbody>
        @forelse($los as $lo)
        <tr>
            <td width="40%">
                {{$lo->product->name}}
            </td>                                    
            <td width="10%">
                {{$lo->qty}}                
            </td>
            <td width="10%">
                {{$lo->uom}}
            </td>
            <td width="25%">
                {!!$lo->remarks!!}
            </td>
        </tr>
        @empty
        @endforelse        
    </tbody>
</table>
