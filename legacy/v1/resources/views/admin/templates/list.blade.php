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
    <li class="breadcrumb-item active">{{trans('Models')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="{{trans('Nouvelle Template (Modéle)')}} ">{{trans('Nouveau')}}</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">{{trans('Nom')}}</th>
                                    <th scope="col">{{trans('Contenu')}}</th>
                                    <th scope="col">{{trans('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($templates as $item)
                                <tr>
                                    <td>{{$item->template_name}}</td>
                                    <td>{{$item->template_content}}</td>
                                    <td>
                                        <ul>
                                            {{--                                             
                                            @if($item->status==1)
                                            <li><a href="{{url('/admin/templates/'.$item->id.'/delete')}}"><i class="fa fa-trash" title="{{trans('Supprimer')}}"></i></a></li>
                                            @else
                                            <li><a href="{{url('/admin/stores/'.$item->id.'/active')}}"><i class="fa fa-check" title="{{trans('Activer')}}"></i></a></li>
                                            @endif --}}
                                            <a title="{{trans('Modifier')}}" class="btn" style="background-color: #ea2087" href="{{url('/admin/templates/'.$item->id.'/edit')}}"><i class="fa fa-pencil" alt="{{trans('Modifier')}}" title="{{trans('Modifier')}}"></i></a>
                                            <a title="{{trans('Consulter')}}" class="btn" style="background-color: #a927f9" href="#" data-toggle="modal" data-target=".bd-example-modal-lg-template-{{$item->id}}"><i class="fa fa-eye" alt="{{trans('Consulter')}}" title="{{trans('Consulter')}}"></i></a>
                                        </ul>
                                    </td>
                                </tr>
                                <div id="modal-owner" class="modal fade bd-example-modal-lg-template-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Détail Template')}}</h4>
                                             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                                          </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                       
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer01">{{trans('Nom')}}</label>
                                                            <input readonly class="form-control " id="validationServer01" type="text" value="{{$item->template_name}}"  required="" data-original-title="" title="{{trans('Nom du Magasin')}}">
                                                            
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('Contenu')}}</label>
                                                            <textarea readonly class="form-control " id="validationServer02" type="text" name="contact" data-original-title="" title="{{trans('Contenu de template')}}">{{$item->template_content}}</textarea>
                                                           
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
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <div class="modal-header">
             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Nouvelle Template')}}</h4>
             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
                <form action="{{route('admin.templates.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Nom du Template')}}</label>
                            <input class="form-control is-valid" id="validationServer01" type="text" name="template_name" required="{{trans('Nom du Template')}}" title="">
                            <div class="valid-feedback">[NOM] pour inclure le nom du client</div>
                            <div class="valid-feedback">[CONTRAT] pour inclure le numéro du contrat du client
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Contenu')}}</label>
                            <textarea class="form-control is-valid" id="validationServer02" name="template_content" required=""  title="{{trans('Contenu')}}"></textarea>
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
