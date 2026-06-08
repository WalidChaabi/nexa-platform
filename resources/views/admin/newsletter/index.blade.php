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
    <li class="breadcrumb-item active">{{trans('Models NewsLetter')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				
				<div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h3>{{trans('Model 1')}}</h3>
                            <iframe src="{{route('admin.model-1')}}" title="W3Schools Free Online Web Tutorials"></iframe>
                        </div>
                        <div class="col-md-4">
                            <h3>{{trans('Model 2')}}</h3>
                            <iframe src="{{route('admin.model-1')}}" title="W3Schools Free Online Web Tutorials"></iframe>
                        </div>
                        <div class="col-md-4">
                            <h3>{{trans('Model 3')}}</h3>
                            <iframe src="{{route('admin.model-1')}}" title="W3Schools Free Online Web Tutorials"></iframe>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h3>{{trans('Model 4')}}</h3>
                            <iframe src="{{route('admin.model-1')}}" title="W3Schools Free Online Web Tutorials"></iframe>
                        </div>
                        <div class="col-md-4">
                            <h3>{{trans('Model 5')}}</h3>
                            <iframe src="{{route('admin.model-1')}}" title="W3Schools Free Online Web Tutorials"></iframe>
                        </div>
                        <div class="col-md-4">
                            <h3>{{trans('Model 6')}}</h3>
                            <iframe src="{{route('admin.model-1')}}" title="W3Schools Free Online Web Tutorials"></iframe>
                        </div>
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
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
    <script src="{{ asset('assets/js/notify/index.js') }}"></script>
    <script src="{{asset('assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{asset('assets/js/datepicker/date-time-picker/datetimepicker.custom.js')}}"></script>
@endsection
