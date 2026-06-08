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
    <h3 class="text-center">{{trans('Objectifs des employés')}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/owner')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Objectifs des employés')}}</li>
@endsection

@section('content')

 




<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="display datatables" id="render-datatable">
							<thead>
								<tr>
									<th>{{trans('Magasin')}}</th>
									<th>{{trans('Prénom')}}</th>
									<th>{{trans('Nom')}}</th>
									<th>{{trans('O.Q')}}Mad</th>
									<th>{{trans('O.H')}}Mad</th>
									<th>{{trans('O.M')}}Mad</th>
									<th>{{trans('Téléphone')}}</th>
									<th>{{trans('Action')}}</th>
								</tr>
							</thead>
                            @foreach ($objectives as $item)
                                <tr>
                                    <th scope="row">{{$item->user->store->name}}</th>
                                    <td>{{$item->user->firstname}}</td>
                                    <td>{{$item->user->lastname}}</td>
                                    <td>{{$item->daily}}</td> 
                                    <td>{{$item->weekly}}</td> 
                                    <td>{{$item->monthly}}</td> 
                                    <td>{{$item->user->phone}}</td> 
                                    <td>
                                        <ul>
                                            <li><i class="fa fa-eye" data-toggle="modal" data-target=".bd-example-modal-lg-employe-{{$item->id}}"    title="{{trans('Consulter')}}"></i></li>
                                            {{--<li><a href="{{url('/admin/employees/'.$item->id.'/edit')}}"><i class="fa fa-pencil" title="{{trans('Modifier')}}"></i></a></li>--}}
                                        </ul>
                                    </td>
                                </tr>
                             
                            @endforeach
							
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
