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
    <li class="breadcrumb-item active"><a href="{{url('admin/activities')}}">{{trans('Activitées')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Editer')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h2>{{trans('Modification d\'Activitée')}} : {{$activity->name}} </h2>
				</div>
				<div class="card-body">
                    <form action="{{route('admin.update.activities',$activity->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Nom')}}</label>
                                <input class="form-control" id="validationServer01" type="text" name="name" value="{{$activity->name}}"  required="" data-original-title="" title="{{trans('Prénom')}}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('Description')}}</label>
                                <textarea class="form-control "  name="description"  title="{{trans('Description')}}">{{$activity->description}}</textarea>
                                @if ($errors->has('lastname'))
                                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                @endif
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
