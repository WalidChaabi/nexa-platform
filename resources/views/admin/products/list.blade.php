@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')l
<h3 class="text-left">{{auth()->user()->getFullNameAttribute()}}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">{{trans('dashboard.dashboard')}}</li>
<li class="breadcrumb-item active">{{trans('Magasin')}}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Ajax Deferred rendering for speed start-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label></label>
                                <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="Nouveau Produit">Nouveau</button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <form id="importList" action="{{route('admin.import.products')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" id="store_id_hidden" name="store">
                                        <div class="col-md-4">
                                            <select class="form-control digits" name="store_id" id="store_id">
                                                <option value="0">-- {{trans('Choisir Magasin')}} --</option>
                                                @foreach ($stores as $item)
                                                <option value="{{$item->id}}" @if(isset($_GET['storeId']) && $_GET['storeId'] == $item->id ) selected @endif>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="custom-file">
                                                <input type="file" id="file" name="products" class="form-control" required>
                                                <span class="custom-file-control"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" onclick="importProducts($('#store_id').val())" class="btn btn-info">{{trans('Importer')}}</button>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" onclick="search($('#store_id').val())" class="btn btn-info">{{trans('rechercher')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display datatables" id="render-datatable">
                            <thead>
                                <tr>
                                    <th>{{trans('libellé')}}</th>
                                    <th>{{trans('Magasin')}}</th>
                                    <th>{{trans('Prix')}}</th>
                                    <th>{{trans('Promotion')}}</th>
                                    <th>{{trans('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $item)
                                <tr>
                                    <th scope="row">{{$item->label}}</th>
                                    <td>{{  !empty($item->store)? $item->store->name : ''}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>{{$item->promotion_price}}</td>
                                    <td>
                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg-product-{{$item->id}}"> <i class="fa fa-eye"  title="{{trans('Consulter')}}"></i></a>
                                    <a href="{{route('admin.products.edit',$item->id)}}" class="btn" style="background-color: #a927f9"><i class="fa fa-pencil" title="{{ trans('Modifier') }}"></i></a>
                                </td>
                                </tr>
                                <div id="modal-owner" class="modal fade bd-example-modal-lg-product-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myLargeModalLabel">{{trans('Détail Produit')}}</h4>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlSelect9">{{trans('Libellé')}}</label>
                                                            <input readonly class="form-control " id="validationServer02" type="text" value="{{$item->label}}" data-original-title="" title="{{trans('Libellé')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer02">{{trans('Magasin')}}</label>
                                                        <input readonly class="form-control " id="validationServer02" type="text" value="{{ !empty($item->store) ?  $item->store->name : ''}}" data-original-title="" title="{{trans('Magasin')}}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-group">
                                                            <label for="exampleFormControlSelect9">{{trans('Prix')}}</label>
                                                            <input readonly class="form-control " id="validationServer01" type="text" value="{{$item->price}}" required="" data-original-title="" title="{{trans('Prix Produit')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer01">{{trans('Prix d\'achat')}}</label>
                                                        <input readonly class="form-control " id="validationServer01" type="text" value="{{$item->promotion_price}}" required="" data-original-title="" title="{{trans('Prix Promotion')}}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer01">{{trans('Quantité')}}</label>
                                                        <input readonly class="form-control " id="validationServer01" type="text" name="quantite" value="{{$item->quantite}}" required="" data-original-title="" title="{{trans('Prix Promotion')}}">
                                                    </div>

                                                </div>
                                                <button class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-original-title="" title="">{{trans('Fermer')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">{{trans('Nouveau Produit')}}</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.products.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlSelect9">{{trans('Magasin')}}</label>
                                <select class="form-control digits" name="store_id" id="exampleFormControlSelect9"  data-original-title="{{trans('Magasin')}}" title="{{trans('Magasin')}}">
                                    <option value="0">-- {{trans('Choisir')}} --</option>
                                    @foreach ($stores as $item)
                                    <option value="{{$item->id}}"  @if(isset($_GET['storeId']) && $_GET['storeId'] == $item->id ) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('libelle')}}</label>
                            <input class="form-control " id="validationServer01" type="text" name="label" required="" data-original-title="{{trans('Libellé')}}" title="{{trans('Libellé')}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Prix')}}</label>
                            <input class="form-control " id="validationServer01" type="text" name="price" required="" data-original-title="{{trans('Prix')}}" title="{{trans('Prix')}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Prix d\'achat')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="promotion_price" data-original-title="{{trans('Prix d\'achat')}}" title="{{trans('Prix d\'achat')}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Quantité')}}</label>
                            <input readonly class="form-control " id="validationServer01" type="text" name="quantite"  required="" data-original-title="{{trans('La quantité')}}" title="{{trans('La quantité')}}">
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" data-original-title="" title="">{{trans('Enregistrer')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
<script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js²') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
<script>
    function search(storeId){
        if (storeId != 0) {
                window.location.href = '{{route("admin.products")}}?storeId='+storeId;
        } else {
            alert('Vous devez selectionnez un magasin!')
        }
    }
    function importProducts(store_id) {
        if (store_id != 0) {
            $('#store_id_hidden').attr('value', store_id);
            $('#importList').submit();
        } else {
            alert('Vous devez selectionnez un magasin!')
        }

    }
</script>
@endsection