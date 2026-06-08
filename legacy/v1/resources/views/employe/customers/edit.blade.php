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
    <li class="breadcrumb-item active"><a href="{{url('employe.customers')}}">{{trans('communs.Clients')}}</a></li>
    <li class="breadcrumb-item active">{{trans('communs.Modification')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					{{trans('communs.Modification du Client')}} {{$customer->getFullNameAttribute()}}
				</div>
				<div class="card-body">
					<form action="{{route('employe.customers.update',$customer->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('communs.Nom')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="firstname" value="{{$customer->firstname}}"  data-original-title="" title="{{trans('communs.Nom')}}">
                                @if ($errors->has('firstname'))
                                    <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('communs.Prénom')}}</label>
                                <input class="form-control " id="validationServer02" type="text" name="lastname" value="{{$customer->lastname}}"  data-original-title="" title="{{trans('communs.Prénom')}}">
                                @if ($errors->has('lastname'))
                                    <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('communs.E-mail')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="email" value="{{$customer->email}}"  data-original-title="" title="{{trans('communs.E-mail')}}">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('communs.Téléphone')}}</label>
                                <input class="form-control" id="validationServer02" type="text" name="phone" value="{{$customer->phone}}"  required="" data-original-title="" title="{{trans('communs.Téléphone')}}">
                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('communs.Date de Naissance')}}</label>
                                <input class="datepicker-here form-control" type="text" value="{{$customer->birth}}" name="birth" data-language="en">
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('birth') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('communs.Sexe')}}</label>
                                <select class="form-control digits" name="sexe" id="store_id">
                                    <option>-- {{trans('communs.Choisir Sexe')}} --</option>
                                    <option value="1" @if($customer->sexe==1) selected @endif >{{trans('communs.Homme')}}</option>
                                    <option value="0" @if($customer->sexe==0) selected @endif>{{trans('communs.Femme')}}</option>
                                    
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('communs.Modifier')}}">{{trans('communs.Modifier')}}</button>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection




@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
@endsection
