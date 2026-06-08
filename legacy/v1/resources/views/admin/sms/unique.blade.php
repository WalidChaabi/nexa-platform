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
    <li class="breadcrumb-item active">{{trans('SMS Unique')}}</li>
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
                                <label for="validationServer01">{{trans('Téléphone')}}</label>
                                <input class="form-control" id="validationServer01" type="text" required="" data-original-title="" title="">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Nom')}}</label>
                                <input class="form-control" id="validationServer01" type="text" required="" data-original-title="" title="">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Prénom')}}</label>
                                <input class="form-control" id="validationServer01" type="text" required="" data-original-title="" title="">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Date de Naissance')}}</label>
                                <input class="form-control" id="validationServer01" type="text" required="" data-original-title="" title="">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Message')}}</label>
                                <textarea class="form-control" id="validationServer01" type="text"  required="" data-original-title="" title=""></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('Campagne')}}</label>
                                <select class="form-control digits" id="exampleFormControlSelect9">
                                    <option>A</option>
                                    <option>B</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('Envoyer')}}">{{trans('Envoyer')}}</button>
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
