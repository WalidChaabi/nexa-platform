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
    <li class="breadcrumb-item"><a href="{{url('/dashboard/index')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item">{{trans('rapports')}}</li>
    <li class="breadcrumb-item active">{{trans('Envois SMS')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				
				<div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Magasin')}}</label>
                                <select class="form-control digits" id="exampleFormControlSelect9">
                                    <option>-- Choisir --</option>
                                    <option>Magasin 1</option>
                                    <option>Magasin 2</option>
                                    <option>Magasin 3</option>
                                    <option>Magasin 4</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Statut')}}</label>
                                    <select class="form-control digits" id="exampleFormControlSelect9">
                                        <option>-- Choisir --</option>
                                        <option>Envoyé</option>
                                        <option>Reçu</option>
                                        <option>Echoué</option>
                                    </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Debut')}}</label>
                                <input class="form-control digits" id="example-datetime-local-input" type="datetime-local" value="2018-01-19T18:45:00" data-original-title="" title="">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Fin')}}</label>
                                <input class="form-control digits" id="example-datetime-local-input-1" type="datetime-local" value="2018-01-19T18:45:00" data-original-title="" title="">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('Rechercher')}}">{{trans('Rechercher')}}</button>
                    </form>
				</div>
			</div>
		</div>
        <div class="col-sm-12">
			<div class="card">
				
				<div class="card-body">
                    <div id="area-spaline"></div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/chart-custom.js')}}"></script>
@endsection
