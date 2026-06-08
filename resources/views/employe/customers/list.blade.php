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
<h3 class="text-left">{{auth()->user()->store->name}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/employe')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active">{{trans('communs.Clients')}}</li>
@endsection

@section('content')
<div class="container-fluid">
    @if($errors->any())
    {{ implode('', $errors->all(':message')) }}
    @endif
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
			     <div class="card-header">
                       
                        <form id="" action="{{route('employe.customers')}}" method="GET" >
                            @csrf
                            <div class="row">
                              <div class="col-md-2">
                             	<button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="{{trans('communs.Nouveau Client')}}">{{trans('communs.Nouveau')}}</button>
                              </div>
                              <div class="col-md-2">
                                <input type="date" name="start_date" value="{{ (isset($_GET['start_date']) ? $_GET['start_date'] : '' )    }}" class="form-control">
                              </div>
                              <div class="col-md-2">
                                <input type="date" name="end_date" value="{{ (isset($_GET['end_date']) ? $_GET['end_date'] : '' )    }}" class="form-control">
                              </div>
                              <!--@if(auth()->user()->store->id)-->
                              <!--<div class="col-md-2">-->
                              <!--  <select type="date" name="sexe" class="form-control">-->
                              <!--      <option value="0">{{trans('Choisir le sexe')}}</option>-->
                              <!--    @if(isset($_GET['sexe']))-->
                              <!--    <option value="1" @if($_GET['sexe']==1) selected @endif>Homme</option>-->
                              <!--    <option value="2" @if($_GET['sexe']==2) selected @endif >Femme</option>-->
                              <!--    <option value="3" @if($_GET['sexe']==3) selected @endif>Mix</option>-->
                              <!--    @else  -->
                              <!--    <option value="1">Homme</option>-->
                              <!--    <option value="2">Femme</option>-->
                              <!--    <option value="3">Mix</option>-->
                              <!--    @endif-->
                              <!--  </select>-->
                              <!--</div>-->
                              <!--@endif-->
                              <div class="col-md-2">
                                <button type="submit" class="form-control btn btn-primary">{{trans('Rechercher')}}</button>
                              </div>
                            </div>
                        </form>    
                    </div>
			
                
				<div class="card-body row">
                    <div class="col-md-8"></div>
                        <!--<div class="col-md-4">-->
                        <!--    <select style="width:80px;margin-left:auto" onchange="ShowItems($(this).val(),'{{url('/employe/customers')}}')" class="form-control digits">-->
                        <!--        @foreach(Config::get('constant.items') as $item)-->
                        <!--        <option value="{{$item}}" {{ ( isset($_GET['items']) && $_GET['items']==$item  ) ? 'selected':'' }}  >{{$item}}</option>-->
                        <!--    @endforeach-->
                        <!--    </select>-->
                        <!--</div>-->
				
                    <div class="table-responsive">
                        
                        	<table class="display" id="render-datatable">
                        <thead>
                            <tr>
                                <th class="text-center">{{trans('communs.Magasin')}}</th>
                                <th class="text-center">{{trans('communs.Prénom')}}</th>
                                <th class="text-center">{{trans('communs.Nom')}}</th>
                                <th class="text-center">{{trans('communs.Téléphone')}}</th>
                                <th class="text-center">{{trans('communs.E-mail')}}</th>
                                 <th class="text-center">{{trans('Réd.Mad')}}</th>
                                <th class="text-center">{{trans('communs.P.Réd')}}</th>
                                <th class="text-center">{{trans('communs.Action')}}</th>
                            </tr>
                        </thead>
                       <tbody>
                           @foreach ($customers as $item)
                               <tr>
                                <td>{{$item->store->name}}</td>
                                <td>{{$item->firstname}}</td>
                                <td>{{$item->lastname}}</td>
                                <td>{{$item->phone}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->points ? $item->points: 0 }}</td>
                                 <td>{{convertToPoints($item->points, auth()->user()->store->coeff)}}</td>
                                <td>
                                    <a class="btn" style="background-color: #7366ff" data-toggle="modal" data-target=".bd-example-modal-lg-customer-{{$item->id}}"    title="{{trans('Consulter')}}"><i class="fa fa-eye"></i></a>
                                    <a class="btn" style="background-color: #a927f9" href="{{url('/employe/customers/'.$item->id.'/edit')}}"><i class="fa fa-edit"></i></a>
                                </td>
                               </tr>

                               <div id="modal-owner" class="modal fade bd-example-modal-lg-customer-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg">
                                   <div class="modal-content">
                                      <div class="modal-header">
                                         <h4 class="modal-title" id="myLargeModalLabel">{{trans('customer.Détail Client')}}</h4>
                                         <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                                      </div>
                                      <div class="modal-body">
                                           
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer01">{{trans('communs.Nom')}}</label>
                                                        <input class="form-control" id="validationServer01" type="text" name="firstname" value="{{$item->firstname}}"  data-original-title="" title="{{trans('communs.Nom')}}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer02">{{trans('communs.Prénom')}}</label>
                                                        <input class="form-control " id="validationServer02" type="text" name="lastname" value="{{$item->lastname}}"  data-original-title="" title="{{trans('communs.Prénom')}}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer01">{{trans('communs.E-mail')}}</label>
                                                        <input class="form-control" id="validationServer01" type="text" name="email" value="{{$item->email}}"  data-original-title="" title="{{trans('E-mail')}}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer02">{{trans('communs.Téléphone')}}</label>
                                                        <input class="form-control" id="validationServer02" type="tel"  name="phone"  value="{{$item->phone}}"  required="" data-original-title="" title="{{trans('Téléphone')}}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer01">{{trans('communs.Date de Naissance')}}</label>
                                                        <input class="datepicker-here form-control" value="{{$item->birth}}" type="text" name="birth" data-language="en">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="validationServer01">{{trans('communs.Sexe')}}</label>
                                                        <select class="form-control digits" name="sexe" id="store_id">
                                                            <option value="-1">-- {{trans('communs.Choisir Sexe')}} --</option>
                                                            <option value="1" @if($item->sexe==1) selected @endif >{{trans('communs.Homme')}}</option>
                                                            <option value="0" @if($item->sexe==0) selected @endif>{{trans('communs.Femme')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary"  data-dismiss="modal" title="{{trans('communs.Fermer')}}">{{trans('communs.Fermer')}}</button>
                                            
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
<div id="modal-owner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <div class="modal-header">
             <h4 class="modal-title" id="myLargeModalLabel">{{trans('communs.Nouveau Client')}}</h4>
             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
                <form action="{{route('employe.customers.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('communs.Nom')}}</label>
                            <input class="form-control" id="validationServer01" type="text" name="firstname" value="{{old('firstname')}}"  data-original-title="" title="{{trans('communs.Prénom')}}">
                            @if ($errors->has('firstname'))
                                <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('communs.Prénom')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="lastname" value="{{old('lastname')}}"  data-original-title="" title="{{trans('communs.Nom')}}">
                            @if ($errors->has('lastname'))
                                <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('communs.E-mail')}}</label>
                            <input class="form-control" id="validationServer01" type="text" name="email" value="{{old('email')}}"  data-original-title="" title="{{trans('communs.E-mail')}}">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('communs.Téléphone')}}</label>
                            <input class="form-control" id="validationServer02" type="text" name="phone" value="212{{old('phone')}}"  required="" data-original-title="" title="{{trans('communs.Téléphone')}}">
                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('communs.Date de Naissance')}}</label>
                            <input class="form-control" type="date" name="birth" data-language="en">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('birth') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('communs.Sexe')}}</label>
                            <select class="form-control digits" name="sexe" id="store_id">
                                <option value="-1">-- {{trans('communs.Choisir Sexe')}} --</option>
                                <option value="1">{{trans('communs.Homme')}}</option>
                                <option value="0">{{trans('communs.Femme')}}</option>
                                
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('communs.Enregistrer')}}">{{trans('communs.Enregistrer')}}</button>
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
<script>
    function ShowItems(value,url)
        {
           window.location=url+"?items="+value
        }
</script>
