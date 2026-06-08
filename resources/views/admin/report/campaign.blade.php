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
    <li class="breadcrumb-item active">{{trans('rapports Campagnes')}}</li>
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
                                    <option>Campagne 1</option>
                                    <option>Campagne 2</option>
                                    <option>Campagne 3</option>
                                    <option>Campagne 4</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Campagne')}}</label>
                                    <select class="form-control digits" id="exampleFormControlSelect9">
                                        <option>Magasin 1</option>
                                        <option>Oppo</option>
                                        <option>XBEL</option>
                                        <option>Magasin N</option>
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
                    <div id="column-chart"></div>
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
