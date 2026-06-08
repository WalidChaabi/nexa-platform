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
    <li class="breadcrumb-item active">{{trans('Campagnes')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="{{trans('Nouvelle Campagne')}}">Nouveau</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">{{trans('Campagne')}}</th>
                                    <th scope="col">{{trans('Message')}}</th>
                                    <th scope="col">{{trans('Pour')}}</th>
                                    <th scope="col">{{trans('Lancement')}}</th>
                                    <th scope="col">{{trans('Date de Création')}}</th>
                                    <th scope="col">{{trans('Statut')}}</th>
                                    <th scope="col">{{trans('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Campagne 1</td>
                                    <td>Message de test</td>
                                    <td>Liste des clients</td>
                                    <td>2021-02-13 16:39</td>
                                    <td>2021-02-13 16:39</td>
                                    <td>Oui</td>
                                    <td>Supprimer</td>
                                </tr>
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
             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Nouveau Modéle')}}</h4>
             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Nom Campagne')}}</label>
                            <input class="form-control is-valid" id="validationServer01" type="text" value="Mark" required="" data-original-title="" title="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Nature Envoie')}}</label>
                            <select class="form-control digits" id="exampleFormControlSelect9">
                                <option>Information</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Modèle')}}</label>
                            <select class="form-control digits" id="exampleFormControlSelect9">
                                <option>Modèle 1</option>
                                <option>Modèle 2</option>
                                <option>Modèle 3</option>
                                <option>Modèle 5</option>
                            </select>

                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Message')}}</label>
                            <textarea class="form-control is-valid" id="validationServer02" required="" data-original-title="" title=""></textarea>
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
