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
<li class="breadcrumb-item">{{trans('dashboard.dashboard')}}</li>
<li class="breadcrumb-item active">{{trans('Infos Personnelles')}}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Ajax Deferred rendering for speed start-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{route('personal.info.update')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="validationServer01">{{trans(' Prénom')}}</label>
                                <input class="form-control" id="validationServer02" type="text" name="firstname" value="{{auth()->user()->firstname}}" required="" data-original-title="" title="{{trans('Nom')}}">
                                
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationServer02">{{trans('Nom')}}</label>
                                <input class="form-control" id="validationServer02" type="text" name="lastname" value="{{auth()->user()->lastname}}" required="" data-original-title="" title="{{trans('Prénom')}}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationServer02">{{trans('Téléphone')}}</label>
                                <input class="form-control" id="validationServer02" type="text" name="phone" value="{{auth()->user()->phone}}" required="" data-original-title="" title="{{trans('Téléphone')}}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationServer02">{{trans('E-mail')}}</label>
                                <input class="form-control" id="validationServer02" type="text" name="email" value="{{auth()->user()->email}}" required="" data-original-title="" title="{{trans('E-mail')}}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="validationServer02">{{trans('Address du Propriétaire')}}</label>
                                <textarea name="address" id="" class="form-control" cols="30" rows="10">{{auth()->user()->address}}</textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="">{{trans('Enregistrer')}}</button>
                    </form>
                </div>
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
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
@endsection