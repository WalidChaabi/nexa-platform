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
    <li class="breadcrumb-item"><a href="{{url('dashboard/index')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active"><a href="{{url('admin/owners')}}">{{trans('Propriétataires')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Editer')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h2>{{trans('Modification du propriétaire')}} : {{$owner->getFullNameAttribute()}} </h2>
				</div>
				<div class="card-body">
                    <form action="{{route('admin.update.owner',$owner->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Nom')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="lastname" value="{{$owner->lastname}}"  required="" data-original-title="" title="{{trans('Nom')}}">
                                @if ($errors->has('firstname'))
                                    <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('Prénom')}}</label>
                                <input class="form-control " id="validationServer02" type="text" name="firstname" value="{{$owner->firstname}}"  required="" data-original-title="" title="{{trans('Prénom')}}">
                                @if ($errors->has('lastname'))
                                    <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('E-mail')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="email" value="{{$owner->email}}"  required="" data-original-title="" title="{{trans('E-mail')}}">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('Téléphone')}}</label>
                                <input class="form-control" id="validationServer02" type="text" name="phone" value="{{$owner->phone}}"  required="" data-original-title="" title="{{trans('Téléphone')}}">
                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Mot de passe')}}</label>
                                <input class="form-control" id="validationServer01" type="password" name="password" data-original-title="" title="Mot de passe">
                                <div class="show-hide"><span class="show"></span></div>
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('Confirmation')}}</label>
                                <input class="form-control" id="validationServer02" type="password" name="password-confirmation"  data-original-title="" title="Confirmation du mot de passe">
                                <div class="show-hide"><span class="show"></span></div>
                                @if ($errors->has('password-confirmation'))
                                    <div class="invalid-feedback">{{ $errors->first('password-confirmation') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                @if($owner->allow_share==0)
                                <input class="form-check-input is-invalid" name="allow_share" id="invalidCheck3" type="checkbox" value="1" data-original-title="" >
                                @else 
                                <input class="form-check-input is-invalid" name="allow_share" id="invalidCheck3" type="checkbox" value="1" data-original-title="" checked >
                                @endif
                                <label class="form-check-label" for="invalidCheck3">{{trans('Permettre le partage des clients entre mes magasins')}} ?</label>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('Modifier')}}">{{trans('Modifier')}}</button>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
@endsection
