@foreach($products as $product)
<option value="{{$product->id}}">{{$product->label}}</option>
@endforeach