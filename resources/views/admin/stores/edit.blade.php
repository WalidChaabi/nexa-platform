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
     <h3 class="text-center">{{auth()->user()->getFullNameAttribute()}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{trans('dashboard.dashboard')}}</li>
    <li class="breadcrumb-item active">{{trans('Magasin')}}</li>
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
                <form action="{{route('admin.stores.update',$store->id)}}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlSelect9">Activitées</label>
                                <select class="form-control digits" name="activity_id" id="exampleFormControlSelect9">
                                    <option>-- {{trans('Choisir')}} --</option>
                                    @foreach ($activities as $item)
                                        @if($item->id==$store->activity_id)
                                        <option value="{{$store->activity_id}}" selected >{{$item->name}}</option>
                                        @else
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="exampleFormControlSelect9">Propriétaire</label>
                                <select class="form-control digits" name="owner_id" id="exampleFormControlSelect9">
                                <option>-- {{trans('Choisir')}} --</option>
                                @foreach ($owners as $item)
                                    @if($item->id==$store->user_id)
                                    <option value="{{$store->user_id}}" selected >{{$item->getFullNameAttribute()}}</option>
                                    @else
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Nom')}}</label>
                            <input   class="form-control " id="validationServer01" name="store_name" type="text" value="{{$store->name}}"  required="" data-original-title="" title="{{trans('Nom du Magasin')}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Sender Id')}}</label>
                            <input   class="form-control " id="validationServer01" name="sender_id" type="text" value="{{$store->sender_id}}"  required="" data-original-title="" title="{{trans('Nom du Magasin')}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Contact')}}</label>
                            <input   class="form-control " id="validationServer02" type="text" value="{{$store->contact}}" name="contact" data-original-title="" title="{{trans('Contact du Magasin')}}">
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Addresse')}}</label>
                            <textarea class="form-control " id="validationServer01" type="text" name="address"   data-original-title="" title="{{trans('Addresse Magasin')}}">{{$store->address}}</textarea>
                           
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
                            <input   class="form-control " id="validationServer02" type="text" name="base_calcul" value="{{$store->base}}"  required="" data-original-title="" title="Confirmation du mot de passe">
                           
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Réduction suivant la base')}}</label>
                            <input   class="form-control " id="validationServer02" type="text" name="base_profit" value="{{$store->base_profit}}" data-original-title="" title="Confirmation du mot de passe">
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Coéfficient (points)')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="coeff"  value="{{$store->coeff}}" data-original-title="" title="Confirmation du mot de passe">
                           
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('TVA')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="tva" value="{{$store->tva}}"  data-original-title="" title="Confirmation du mot de passe">
                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <label for="validationServer02">{{ trans('Remerciement Facture') }}</label>
                            <div id="editor_container">
                                <textarea id="editable" name="message_invoice">
                                    {{$store->invoice_message}}
                                </textarea>
                            </div>
                            <div id="html_container"></div>
                        </div>
                        
                        <div class="col-md-2"></div>
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
    <script src="{{ asset('assets/js/editor/simple-mde/simplemde.min.js') }}"></script>
    <script src="{{ asset('assets/js/editor/simple-mde/simplemde.custom.js') }}"></script>
@endsection
