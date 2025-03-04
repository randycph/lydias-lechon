<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        	<th>Remove</th>
            <th>Name</th>
            <th>Qty</th>
            <th>Paella</th>
            <th>Price</th>
            <th>Total</th>
        </thead>
        <tbody id="ui_body">        
			@foreach($items as $item)
			    <tr id="ui_tr{{$item->id}}">
			    	<td><a href="#" class="btn btn-xs btn-danger" onclick="ui_removeitem('ui_tr{{$item->id}}');">x</a></td>
			    	<td>{{$item->product_name}}<input name="uia_product{{$item->id}}" value="{{$item->product_id}}" type="hidden"></td>
			        <td>
			        	<input type="number" class="form-control uiu_qty" title="{{$item->id}}" onchange="ui_change_qty('uiu',{{$item->id}});" name="uiu_qty{{$item->id}}" min="0" id="uiu_qty{{$item->id}}" value="{{number_format($item->qty,0)}}">
			        </td>
			        <td>
			        	@if($item->product->paella_price > 0)
			        		<input type="checkbox" onchange="ui_change_qty('uiu',{{$item->id}});" value="{{$item->product->paella_price}}" name="uiu_paella{{$item->id}}" id="uiu_paella{{$item->id}}" @if($item->paella_price > 0) checked="checked" @endif> {{number_format($item->product->paella_price,2)}}
			        	@endif
			        </td>   
			        <td>
			        	{{number_format($item->price,2)}}
			        	<input type="hidden" name="uiu_price{{$item->id}}" id="uiu_price{{$item->id}}" value="{{$item->price}}">
			        </td> 
			        <td>
			        	<span id="uiu_total{{$item->id}}">{{number_format($item->gross_amount,2)}}</span>
			        	<input type="hidden" name="uiu_subtotal{{$item->id}}" id="uiu_subtotal{{$item->id}}" value="{{$item->gross_amount}}">
			        </td> 
			    </tr>
			@endforeach
		</tbody>
    </table>
</div>
<h4>Add Item</h4>
<table width="50%">
	<tr>
		<td>
			<select class="form-control" id="ui_product">
                <option>- Select -</option>
                @foreach($products as $product)
                    <option value="{{$product->name}}|{{$product->price}}|{{$product->paella_price}}|{{$product->id}}|{{$product->production_item}}">{{$product->name}} - {{number_format($product->price,2)}}</option>
                @endforeach
            </select>
		</td>
		<td><a href="#" class="btn btn-sm btn-info ml-3" onclick="ui_add_product($('#ui_product').val());">Add</a></td>
	</tr>
</table>
