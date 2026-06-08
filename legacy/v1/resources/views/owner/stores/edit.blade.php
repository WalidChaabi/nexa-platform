@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/simple-mde.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-center"></h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/owner')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active"><a href="{{url('owner/stores/')}}">{{trans('Magasin')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Modification')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h2>{{trans('Modification Magasin')}} {{$store->name}}</h2>
				</div>
				<div class="card-body">
                <form action="{{route('owner.stores.update',$store->id)}}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlSelect9">{{trans("Activité")}}</label>
                                <select class="form-control digits" name="activity_id" id="exampleFormControlSelect9">
                                    @foreach ($activities as $item)
                                        @if($item->id==$store->activity_id)
                                        <option value="{{$store->activity_id}}" selected >{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Contact')}}</label>
                            <input   class="form-control " id="validationServer02" type="text" value="{{$store->contact}}" name="contact" data-original-title="" title="{{trans('Contact du Magasin')}}">
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Nom Du Magasin')}}</label>
                            <input class="form-control"   name="store_name" value="{{$store->name}}"  data-original-title="{{trans('Nom Magasin')}}" title="{{trans('Nom Magasin')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Addresse')}}</label>
                            <textarea class="form-control"   name="address"   data-original-title="{{trans('Addresse Magasin')}}" title="{{trans('Addresse Magasin')}}"> {{$store->address}} </textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Téléphone')}}</label>
                            <input   class="form-control " id="validationServer02" type="text" name="phone" value="{{$store->phone}}" data-original-title="" title="{{trans('Téléphone Magasin')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="validationServer01">{{trans('Logo')}}</label>
                            <img src="{{($store->logo)!='' ? asset(getImageByModel($store->id,'stores',$store->logo)):'xxxxx' }}" width="25" height="25">
                            <input class="form-control" name="logo" type="file" data-original-title="" title="{{trans('Logo Magasin')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Base de calcul de la réduction')}}</label>
                            <input   class="form-control " id="validationServer02" type="text" name="base_calcul" value="{{$store->base}}"  required="" data-original-title="{{trans('Base de calcul de la réduction')}}" title="{{trans('Base de calcul de la réduction')}}">
                           
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Réduction suivant la base')}}</label>
                            <input   class="form-control " id="validationServer02" type="text" name="base_profit" value="{{$store->base_profit}}" data-original-title="{{trans('Réduction suivant la base')}}" title="{{trans('Réduction suivant la base')}}">
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Coéfficient (points)')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="coeff"  value="{{$store->coeff}}" data-original-title="{{trans('Coéfficient (points)')}}" title="{{trans('Coéfficient (points)')}}">
                           
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('TVA')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="tva" value="{{$store->tva}}"  data-original-title="" title="{{trans('TVA')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="validationServer01">{{trans('Mentions légales facture')}}</label>
                            <textarea class="form-control"   name="message_invoice"   data-original-title="{{trans('Mentions légales facture')}}" title="{{trans('Mentions légales facture')}}"> {{$store->invoice_message}} </textarea>
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


