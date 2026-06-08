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
                                <a class="btn btn-info" href="{{route('admin.cross-products.create')}}" title="Nouveau Magasin">Nouveau Cross Selling Produit</a>
                            </div>
                        </div>
                        <div class="col-md-9">
                         
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
                                    <th>{{trans('N.Jours')}}</th>
                                    <th>{{trans('Nombre sms')}}</th>
                                    <th>{{trans('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                             @foreach($crossProducts as $product)
                             <tr>
                             <td>{{$product->product->label}}</td>
                                <td>{{$product->product->store->name}}</td>
                                <td>{{$product->days}}</td>
                                <td>{{$product->sms->count()}}</td>
                                <td>
                                        <ul>
                                            <li><a href="{{route('admin.cross-products.edit',$product->id)}}" class="btn btn-primary"><i class="fa fa-pencil" title="{{trans('Modifier')}}"></i></a></li>
                                            <li><a href="{{route('admin.cross-products.delete',$product->id)}}" class="btn btn-danger"><i class="fa fa-trash" title="{{trans('Modifier')}}"></i></a></li>
                                        </ul>
                                </td>
                             </tr>
                                
                             @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
@endsection