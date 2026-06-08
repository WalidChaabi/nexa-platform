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
    <li class="breadcrumb-item active">{{trans('Activitées')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="Nouveau Propriétaire">Nouveau</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        
                                        <th scope="col">{{trans('Nom')}}</th>
                                        <th scope="col">{{trans('Description')}}</th>
                                        <th scope="col">{{trans('Action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activities as $item)
                                    <tr>
                                        <th scope="row">{{$item->name}}</th>
                                        <td>{{$item->description}}</td>
                                        <td>
                                            <ul>
                                                <li><i class="fa fa-eye" data-toggle="modal" data-target=".bd-example-modal-lg-owner-{{$item->id}}"    title="{{trans('Consulter')}}"></i></li>
                                                <li><a href="{{url('/admin/activities/'.$item->id.'/edit')}}"><i class="fa fa-pencil" title="{{trans('Modifier')}}"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <div id="modal-owner" class="modal fade bd-example-modal-lg-owner-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                           <div class="modal-content">
                                              <div class="modal-header">
                                                 <h4 class="modal-title" id="myLargeModalLabel">{{trans('Détail Activitée')}}</h4>
                                                 <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                                              </div>
                                              <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="validationServer01">{{trans('Nom')}}</label>
                                                                <input class="form-control"  readonly value="{{$item->name}}" title="{{trans('Nom')}}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="validationServer02">{{trans('Description')}}</label>
                                                                <textarea class="form-control " readonly >{{$item->description}}</textarea>
                                                            </div>
                                                        </div>
                                              </div>
                                              <button class="btn btn-primary"  data-dismiss="modal" aria-label="Close" data-original-title="" title="">{{trans('Fermer')}}</button>
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
</div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <div class="modal-header">
             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Nouvelle activitée')}}</h4>
             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
                <form action="{{route('admin.store.activities')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Nom')}}</label>
                            <input class="form-control" id="validationServer01" name="name" type="text" value="" required="" data-original-title="" title="{{trans('name')}}">
                           
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Description')}}</label>
                            <textarea class="form-control" name="description" type="text" data-original-title="" title="{{trans('description')}}"></textarea>
                            
                        </div>
                    </div>
                   
                    <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('Enregistrer')}}">{{trans('Enregistrer')}}</button>
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
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
@endsection
