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
    <h3 class="text-center">{{trans('Mes magasins')}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">@lang('dashboard.dashboard')</li>
    <li class="breadcrumb-item active">{{trans('Magasin')}}</li>
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
									<th>{{trans('Nom')}}</th>
									<th>{{trans('Propriétaire')}}</th>
									<th>{{trans('Activitée')}}</th>
									<th>{{trans('TVA')}}</th>
									<th>{{trans('Téléphone')}}</th>
									<th>{{trans('Action')}}</th>
								</tr>
							</thead>
                            <tbody>
                                @foreach ($stores as $item)
                                <tr>
                                    <th scope="row">{{$item->name}}</th>
                                    <td>{{$item->owner->firstname}}</td>
                                    <td>{{$item->activity->name}}</td>
                                    <td>{{$item->tva}}</td> 
                                    <td>{{$item->phone}}</td>
                                    <td>
                                        <ul>
                                            <li><a class="btn" style="background-color: #ea2087;width:25px" href="#"><i   class="fa fa-eye" data-toggle="modal" data-target=".bd-example-modal-lg-store-{{$item->id}}"    title="{{trans('Consulter')}}"></i></a></li>
                                            <li><a class="btn" style="background-color: #a927f9;width:25px" href="{{url('/owner/stores/'.$item->id.'/edit')}}"><i class="fa fa-pencil" title="{{trans('Modifier')}}"></i></a></li>
                                            @if($item->status==1)
                                            <li><a class="btn" style="background-color: #7366ff;width:25px" href="{{url('/owner/stores/'.$item->id.'/delete')}}"><i  class="fa fa-trash" title="{{trans('Supprimer')}}"></i></a></li>
                                            @else
                                            <li><a href="{{url('/owner/stores/'.$item->id.'/active')}}"><i class="fa fa-check" title="{{trans('Activer')}}"></i></a></li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                <div id="modal-owner" class="modal fade bd-example-modal-lg-store-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h4 class="modal-title "  id="myLargeModalLabel">{{trans('Détail Magasin')}}</h4>
                                             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                                          </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-group">
                                                                <label for="exampleFormControlSelect9">{{trans('Activité')}}</label>
                                                                {{$item->activity->name}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-group">
                                                                <label for="exampleFormControlSelect9">Propriétaire</label>
                                                               {{$item->owner->firstname}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer01">{{trans('Nom')}}</label>
                                                            <input readonly class="form-control " id="validationServer01" type="text" value="{{$item->name}}"  required="" data-original-title="" title="{{trans('Nom du Magasin')}}">
                                                            
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('Contact')}}</label>
                                                            <input readonly class="form-control " id="validationServer02" type="text" value="{{$item->contact}}" name="contact" data-original-title="" title="{{trans('Contact du Magasin')}}">
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer01">{{trans('Addresse')}}</label>
                                                            <textarea class="form-control " id="validationServer01" type="text" disabled  data-original-title="" title="{{trans('Addresse Magasin')}}">{{$item->address}}</textarea>
                                                           
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('Téléphone')}}</label>
                                                            <input readonly class="form-control " id="validationServer02" type="text" disabled name="phone" value="{{$item->phone}}" data-original-title="" title="{{trans('Téléphone Magasin')}}">
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="validationServer01">{{trans('Logo')}}</label>
                                                            <img src="{{($item->logo)!='' ? asset(getImageByModel($item->id,'stores',$item->logo)):'xxxxx' }}" width="25" height="25">
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('Base de calcul de la réduction')}}</label>
                                                            <input readonly class="form-control " id="validationServer02" type="text" disabled name="base_calcul" value="{{$item->base}}"  required="" data-original-title="" title="Confirmation du mot de passe">
                                                           
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('Réduction suivant la base')}}</label>
                                                            <input readonly class="form-control " id="validationServer02" type="text" disabled name="base_profit" value="{{$item->base_profit}}" data-original-title="" title="Confirmation du mot de passe">
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('Coéfficient (points)')}}</label>
                                                            <input class="form-control " id="validationServer02" type="text" disabled name="coeff"  value="{{$item->coeff}}" data-original-title="" title="Confirmation du mot de passe">
                                                           
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('TVA')}}</label>
                                                            <input class="form-control " id="validationServer02" type="text" disabled name="tva" value="{{$item->tva}}"  data-original-title="" title="Confirmation du mot de passe">
                                                           
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary"  data-dismiss="modal" aria-label="Close" data-original-title="" title="">{{trans('Fermer')}}</button>
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
