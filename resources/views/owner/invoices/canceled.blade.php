@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/chartist.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ auth()->user()->getFullNameAttribute()}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('Factures Annulées') }}</li>
@endsection

@section('content')


    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card-header">
                    <h3>  {{ trans('Total Factures Annulées') }} : {{$total}} Mad </h3>
                </div>
                <div class="card">
                    
                    <div class="card-body row">
                        <div class="col-md-8">
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="{{trans('FILTRES')}}">
                                <i class="fa fa-sliders"  title="{{trans('filtres')}}" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <select style="width:80px;margin-left:auto" onchange="ShowItems($(this).val(),'{{url('owner/invoices/canceled')}}')" class="form-control digits">
                                @foreach(Config::get('constant.items') as $item)
                                    <option value="{{$item}}" {{ ( isset($_GET['items']) && $_GET['items']==$item  ) ? 'selected':'' }}  >{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <table class="display" id="render-datatable">
                            <thead>
                                <tr>
                                    <th>{{ trans('Client') }}</th>
                                    <th>{{ trans('Date') }}</th>
                                    <th>{{ trans('Téléphone') }}</th>
                                    <th>{{ trans('Total HT') }}</th>
                                    <th>{{ trans('Total TTC') }}</th>
                                    <th>{{ trans('B.R') }}</th>
                                    <th class="text-center">{{ trans('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $item)
                                    <tr>
                                        <td>{{ $item->customer->getFullNameAttribute() }}</td>
                                        <td>{{ $item->invoice_date}}</td>
                                        <td>{{ $item->customer->phone }}</td>
                                        <td>{{ $item->total_ht }}</td>
                                        <td>{{ $item->total }}</td>
                                        <td>{{ $item->points > 0 ? 'Oui' : 'Non' }}</td>
                                      
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                    
                        <div class="col-md-12">
                            {{$invoices->links()}}
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
             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Filtres')}}</h4>
             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="validationServer01">{{ trans('Date de Debut') }}</label>
                    <input class="datepicker-here form-control" type="text" id="start_date" name="start_date" data-language="en">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="validationServer01">{{ trans('Date de Fin') }}</label>
                    <input class="datepicker-here form-control" type="text" id="end_date" name="end_date" data-language="en">
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                    <a class="btn btn-primary" onclick="filtres($('#start_date').val(),$('#end_date').val(),'/owner/invoices/canceled')" title="{{trans('Appliquer')}}">{{trans('Appliquer')}}</a>
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
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
@endsection
<script>
        function ShowItems(value,url)
        {
           window.location=url+"?items="+value
        } 
        function filtres(start_date,end_date,url){
            window.location = url + "?start_date="+ start_date + "&end_date="+end_date; 
            
        }
 </script>
