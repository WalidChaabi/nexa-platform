@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
     <h3 class="text-center">{{auth()->user()->getFullNameAttribute()}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/index')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active"><a href="{{route('admin.products')}}">{{trans('Produits')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Modification')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
                    <h2>{{trans('Creation Cross-Selling Produit')}}</h2>
				</div>
				<div class="card-body">
                    <form action="{{route('admin.cross-products.update',$product->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect9">{{trans('Magasin')}}</label>
                                    <select class="form-control digits" name="store_id" id="exampleFormControlSelect9" onchange="getProducts(this.value)">
                                        <option>-- {{trans('Choisir')}} --</option>
                                        @foreach ($stores as $item)
                                            <option value="{{$item->id}}" {{ ($item->id === $product->product->store_id) ? 'selected' :'' }}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect9">{{trans('Produits')}}</label>
                                    <select class="form-control digits" name="product_id" id="products">
                                        <option value="{{$product->id}}" selected>{{$product->product->label}} --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Message')}}</label>
                                <textarea class="form-control " id="validationServer01" type="text" name="message" value=""  required="" title="{{trans('Message associe au produit')}}">{{$product->message}}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Nombre de jours')}}</label>
                                <input class="form-control " id="validationServer01" type="number" name="days" value="{{$product->days}}"  required="" title="{{trans('Nombre de jour pour commencer le cross-selling')}}">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="">{{trans('Sauvgarder')}}</button>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
<script>
    function getProducts(store_id){
        $.ajax({
                    url:"{{route('admin.stores.products')}}",
                    type:'GET',
                    data:{store_id:store_id},
                    success:function(data)
                    {
                        $('#products').html(data.html);
                    }
                });
    }
</script>