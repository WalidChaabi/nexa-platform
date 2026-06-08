@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
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
                    <h2>{{trans('Modification Produit')}} {{$product->label}} </h2>
                </div>
                <div class="card-body">
                    @if(auth()->user()->is_admin==1)
                    <form action="{{route('admin.products.update',$product->id)}}" method="POST" enctype='multipart/form-data'>
                        @elseif(auth()->user()->is_admin==2)
                        <form action="{{route('owner.products.update',$product->id)}}" method="POST" enctype='multipart/form-data'>
                            @endif
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect9">{{trans('Magasin')}}</label>
                                        <select class="form-control digits" name="store_id" id="store_id" class="js-example-basic-single" data-original-title="{{trans('Magasin')}}" title="{{trans('Magasin')}}">
                                            <option>-- {{trans('Choisir')}} --</option>
                                            @foreach ($stores as $item)
                                            @if($product->store_id==$item->id)
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                            @else
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer01">{{trans('libellé')}}</label>
                                    <input class="form-control " id="validationServer01" type="text" name="label" value="{{$product->label}}" required="" data-original-title="{{trans('libellé')}}" title="{{trans('libellé')}}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer01">{{trans('Prix de vente')}}</label>
                                    <input class="form-control " id="validationServer01" type="text" name="price" value="{{$product->price}}" required="" data-original-title="{{trans('Prix de vente')}}" title="{{trans('Prix de vente')}}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer02">{{trans('Prix d\'achat')}}</label>
                                    <input class="form-control " id="validationServer02" type="text" name="promotion_price" value="{{$product->promotion_price}}" data-original-title="" title="{{trans('Prix Promotion')}}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer02">{{trans('Quantité du stock')}}</label>
                                    <input readonly class="form-control " id="quantite" type="text" name="quantite" value="{{$product->quantite}}" data-original-title="" title="{{trans('Quantité du stock')}}">
                                </div>
                    
                                <div class="col-md-3 mb-3">
                                    <label for="validationServer02">{{trans('Modifier Qté final de stock')}}</label>
                                    <input id="quantityInput" class="form-control" type="number" {{trans('Modifier quantité ')}} min="0"
                                            oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null">
                                </div>

                                <div class="col-md-3 mb-3 d-flex" style="align-items: flex-end;">
                                    <div class="btn btn-info" id="change"onclick="changeQuantite({{ $product->id }})">{{trans('Modifier Q')}}</div>
                                    {{-- <div class="btn btn-primary" onclick="addQuantite({{$product->id}})">{{trans('Ajouter')}}</div> --}}
                                </div>
                                @if($product->store->POS == 1)
                                    <div class="col-md-6 mb-3">
                                        <label for="validationServer02">{{trans('Image du Produit')}}</label>
                                        <input class="form-control " id="validationServer02" type="file" name="image" value="{{$product->image}}" data-original-title="{{trans('Image du Produit')}}" title="{{trans('Image du Produit')}}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationServer02">{{trans('Apercu')}}</label>
                                        <br>
                                        <img class="form-control" style="height: 100px;width: 100px;" src="{{asset('uploads/products/'.$product->store_id.'/'.$product->image)}}" 
                                            data-original-title="{{trans('Apercu')}}" title="{{trans('Apercu')}}" height="100">
                                    </div>
                                @endif
                            </div>
                            <button class="btn btn-primary" type="submit" data-original-title="" title="">{{trans('Modifier')}}</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
<script>

    function changeQuantite(productId) {
        var quantite = document.getElementById('quantityInput').value;
        var store_id = document.getElementById('store_id').value;
        var errorMessage = document.getElementById('error_message');

        $.ajax({
            url: "{{ route('owner.products.change.quantite') }}",
            data: {
                productId: productId,
                quantite: quantite,
                store_id: store_id
            },
            type: 'POST',
            success: function(data) {
                console.log(data)
                $('#quantite' ).val(data);
                $('#quantityInput').val('');
            },
            error: function(error) {
                console.log(error)
            }
        });
    }

    // function addQuantite(product_id,store_id){
    //     var quantite = $('#quantite').val();
    //     var store_id = $('#store_id').val();
    //     var sup_Qt = $('#sup_Qt').val();
    //     $.ajax({
    //         url: "{{ route('owner.products.add.quantite') }}",
    //         type: 'POST',
    //         data: {
    //             sup_Qt: sup_Qt,
    //             quantite: quantite,
    //             product_id:product_id,
    //             store_id: store_id
    //         },
    //         success: function(data) {
    //             $('#quantite').val(data);
    //             $('#sup_Qt').val('');
    //         },
    //         error: function(error) {
    //             console.error('Error fetching products:', error);
    //         }
    //     })
    //     return false; 
    // }
</script>
@endsection