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
                <div class="card-body">
                    
                        
                        @if($checkFond)
                                <h1 class="text-center">{{trans('communs.La caisse d\'aujourd\'hui est déja initialisée!')}}</h1>
                        @else 
                        <form action="{{route('employe.initial-fonds.store')}}" method="POST">
                            @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('communs.Date')}}</label>
                                <input class="form-control" id="validationServer01" type="text" readonly value="{{Date('Y-m-d')}}"  data-original-title="" title="{{trans('communs.Date')}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('communs.Encaissement')}} : MAD</label>
                                <input class="form-control " id="validationServer02" type="text" name="encaissement" value="{{old('lastname')}}"  data-original-title="" title="{{trans('communs.Nom')}}">
                            </div>
                        </div>
                          <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('communs.Enregistrer')}}">{{trans('communs.Enregistrer')}}</button>
                        </form>
                        @endif
                   
                </div>
			</div>

            <div class="card">
                <div class="card-body">
                    <table class="display" id="render-datatable">
                        <thead>
                            <tr>
                                <th class="text-center">{{trans('communs.Date')}}</th>
                                <th class="text-center">{{trans('communs.Encaissement')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($initialFonds as $item)
                                <tr>
                                    <td class="text-center">{{$item->date}}</td>
                                    <td  class="text-center">{{$item->value}}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>    
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
<script>
   
</script>
