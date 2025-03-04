<select class="form-control">
	@foreach($products as $product)
    <option>{{$product->name}}</option>
    @endforeach
</select>