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
    <h3 class="text-center">{{trans('Mes Campaigns')}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/owner')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Campaignes')}}</li>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
            <div class="row">
                <h1 class="text-center">{{trans('Total')}} : {{$total}} </h1>
            </div>
            <div class="row">
              @if($errors->any())
                   <span style="color:red"> {{ implode('', $errors->all(':message')) }}</span>
              @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <form id="importList" action="{{route('owner.campaigns.repport')}}" method="GET" >
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <select  class="form-control digits" name="campaign" >
                                        <option value="-1">-- {{trans('Campaigne')}} --</option>
                                        <option value="0">SMS</option>
                                        <option value="1">Emails</option>
                                    </select>
                                </div>
                                {{-- <div class="col-md-2">
                                    <select  class="form-control digits" name="campaign_type" >
                                        <option value="-1">-- {{trans('Le Type')}} --</option>
                                        <option value="0">Unique</option>
                                        <option value="1">BULK</option>
                                    </select>
                                </div> --}}
                              <div class="col-md-2">
                                <select  class="form-control digits" name="store_id" id="store_id">
                                    <option value="0">-- {{trans('Choisir Magasin')}} --</option>
                                    @foreach ($stores as $key=>$value)
                                        @if(isset($_GET['store_id']))
                                         <option value="{{$key}}" @if($_GET['store_id']==$key) selected @endif>{{$value}}</option>
                                        @else
                                        <option value="{{$key}}" >{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                              </div>
                              <div class="col-md-2">
                                <input type="date" name="start_date" value="{{ (isset($_GET['start_date']) ? $_GET['start_date'] : '' )    }}" class="form-control">
                              </div>
                              <div class="col-md-2">
                                <input type="date" name="end_date" value="{{ (isset($_GET['end_date']) ? $_GET['end_date'] : '' )    }}" class="form-control">
                              </div>
                              <div class="col-md-2">
                                <button type="submit" class="form-control btn btn-primary">{{trans('Rechercher')}}</button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="display datatables" id="render-datatable">
              <thead>
                <tr>
                    @if($type==0)
                    <th>{{ trans('Client') }}</th>
                    @else 
                     <th>{{ trans('Facture') }}</th>
                    @endif
                    <th>{{ trans('Statut') }}</th>
                   
                </tr>
            </thead>
                        <tbody>
                              @foreach($data as $item)
                           
                                <tr>
                                    @if($type==0)
                                    <td>{{ $item->phone}}</td>
                                    @else 
                                     <td>#{{$item->invoice_id}}</td>
                                    @endif
                                    <td>{{ ($item->status>0) ? 'Envoyé':'Echoué'}}</td>
                                    <!--<td>-->
                                    <!--    <a class="btn" data-toggle="modal" data-target="#modalInvoice-{{ $item->id }}" style="background-color: #ea2087"><i-->
                                    <!--        class="fa fa-eye"></i></a>-->
                                    <!--</td>-->
                                </tr>
                               
                            @endforeach
                        </tbody>
			</table>
					</div>
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
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js²') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
@endsection
