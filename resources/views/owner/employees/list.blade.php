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
    <h3 class="text-center">{{trans('Mes Employés')}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/owner')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Employés')}}</li>
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
						<table class="display datatables" id="render-datatable">
							<thead>
								<tr>
									<th>{{trans('Magasin')}}</th>
									<th>{{trans('Prénom')}}</th>
									<th>{{trans('Nom')}}</th>
									<th>{{trans('E-mail')}}</th>
									<th>{{trans('Téléphone')}}</th>
									<th>{{trans('Action')}}</th>
								</tr>
							</thead>
                            @foreach ($employees as $item)
                                <tr>
                                    <th scope="row">{{$item->store->name}}</th>
                                    <td>{{$item->firstname}}</td>
                                    <td>{{$item->lastname}}</td>
                                    <td>{{$item->email}}</td> 
                                    <td>{{$item->phone}}</td> 
                                    <td>
                                        <ul>
                                            <li><i class="fa fa-eye" data-toggle="modal" data-target=".bd-example-modal-lg-employe-{{$item->id}}"    title="{{trans('Consulter')}}"></i></li>
                                           {{-- <li><a href="{{route('/admin/employees/'.$item->id.'/edit')}}"><i class="fa fa-pencil" title="{{trans('Modifier')}}"></i></a></li>--}}
                                        </ul>
                                    </td>
                                </tr>
                                <div id="modal-owner" class="modal fade bd-example-modal-lg-employe-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Détail D\'employer')}}</h4>
                                             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                                          </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-group">
                                                                <label for="exampleFormControlSelect9">{{trans('Nom')}}</label>
                                                                <input readonly class="form-control " id="validationServer02" type="text" value="{{$item->firstname}}" data-original-title="" title="{{trans('Libellé')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02">{{trans('Prénom')}}</label>
                                                            <input readonly class="form-control " id="validationServer02" type="text" value="{{$item->lastname}}" data-original-title="" title="{{trans('Magasin')}}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-group">
                                                                <label for="exampleFormControlSelect9">{{trans('E-mail')}}</label>
                                                                <input readonly class="form-control " id="validationServer01" type="text" value="{{$item->email}}"  required="" data-original-title="" title="{{trans('Prix Produit')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer01">{{trans('Téléphone')}}</label>
                                                            <input readonly class="form-control " id="validationServer01" type="text" value="{{$item->phone}}"  required="" data-original-title="" title="{{trans('Prix Promotion')}}">
                                                        </div>
                                                        
                                                    </div>
                                                    <button class="btn btn-primary"  data-dismiss="modal" aria-label="Close" data-original-title="" title="">{{trans('Fermer')}}</button>
                                                </div>
                                       </div>
                                    </div>
                                </div>
                                @endforeach
							
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
             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Nouveau Employé')}}</h4>
             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
                <form action="{{route('owner.employees.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Nom')}}</label>
                            <input class="form-control" id="validationServer01" type="text" name="firstname" value="{{old('firstname')}}"  required="" data-original-title="" title="{{trans('Prénom')}}">
                            @if ($errors->has('firstname'))
                                <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Prénom')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="lastname" value="{{old('lastname')}}"  required="" data-original-title="" title="{{trans('Nom')}}">
                            @if ($errors->has('lastname'))
                                <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('E-mail')}}</label>
                            <input class="form-control" id="validationServer01" type="text" name="email" value="{{old('email')}}"  required="" data-original-title="" title="{{trans('E-mail')}}">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Téléphone')}}</label>
                            <input class="form-control" id="validationServer02" type="text" name="phone" value="{{old('phone')}}"  required="" data-original-title="" title="{{trans('Téléphone')}}">
                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Mot de passe')}}</label>
                            <input class="form-control" id="validationServer01" type="password" name="password" required="" data-original-title="" title="Mot de passe">
                            <div class="show-hide"><span class="show"></span></div>
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Confirmation')}}</label>
                            <input class="form-control" id="validationServer02" type="password" name="password-confirmation"  required="" data-original-title="" title="Confirmation du mot de passe">
                            <div class="show-hide"><span class="show"></span></div>
                            @if ($errors->has('password-confirmation'))
                                <div class="invalid-feedback">{{ $errors->first('password-confirmation') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Magasin')}}</label>
                            <select class="form-control digits" name="store_id" id="store_id">
                                <option>-- {{trans('Choisir Magasin')}} --</option>
                                @foreach ($stores as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="col-md-6 mb-3 row">
                            <div class="col-md-4 mb-4">
                                <label for="validationServer01">{{trans('O.Quotidien')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="daily" value="{{old('daily')}}"  required="" data-original-title="" title="{{trans('Ojective Quotidien ')}}">
                                @if ($errors->has('firstname'))
                                    <div class="invalid-feedback">{{ $errors->first('daily') }}</div>
                                @endif
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationServer01">{{trans('O.Hébdomadaire')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="weekly" value="{{old('weekly')}}"  required="" data-original-title="" title="{{trans('Objective Hébdomadiare')}}">
                                @if ($errors->has('firstname'))
                                    <div class="invalid-feedback">{{ $errors->first('weekly') }}</div>
                                @endif
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationServer01">{{trans('O.Mensuel')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="monthly" value="{{old('monthly')}}"  required="" data-original-title="" title="{{trans('Objective Mensuel')}}">
                                @if ($errors->has('firstname'))
                                    <div class="invalid-feedback">{{ $errors->first('monthly') }}</div>
                            @endif
                            </div>
                            
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
