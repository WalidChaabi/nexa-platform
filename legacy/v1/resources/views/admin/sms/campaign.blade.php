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
    <li class="breadcrumb-item">{{trans('dashboard.dashboard')}}</li>
    <li class="breadcrumb-item active">{{trans('Campagnes')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				
				<div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Propriétaire')}}</label>
                                <select class="form-control digits" name="owner_id" onclick="getStores($(this).val())">
                                    <option value="-1">--{{trans('Choisir Propriétaire')}}--</option>
                                    @foreach($owners as $item)
                                        <option value="{{$item->id}}">{{$item->getFullNameAttribute()}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="stores_id_div">
                                <label for="validationServer01">{{trans('Magasin')}}</label>
                                <select class="form-control digits" id="stores_id" name="stores_id[]" onclick="getCustomers($(this).val())">
                                    
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Campagne')}}</label>
                                <select class="form-control digits" onclick="getMessage($(this).val())" >
                                    <option value="-1">--{{trans('Choisir la Campagne')}}--</option>
                                    @foreach($campaigns as $item)
                                        <option value="{{$item->id}}">{{$item->campaign_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{trans('Message')}}</label>
                                <textarea class="form-control" id="campaign_message" type="text"  required="" data-original-title="" title=""></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('Temps d\'Envoi')}}</label>
                                <select class="form-control digits" onclick="getTimeField($(this).val())" id="exampleFormControlSelect9">
                                    <option value="-1">--{{trans('Choisir le Temps')}}--</option>
                                    @foreach(Config::get('constant.send_time') as $key=>$time)
                                       <option value="{{$key}}"> {{$time}} </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{trans('Envoyer à')}}</label>
                                <select class="form-control digits" onclick="ChooseClients()" id="exampleFormControlSelect9">
                                    <option value="-1">--{{trans('Choisir Clients')}}--</option>
                                    <option>Tous les Clients</option>
                                    <option>Clients ayant factures impayées</option>
                                    <option>Personnaliser</option>
                                </select>
    
                            </div>
                            <div class="col-md-6 mb-3" id="send_date" style="display: none">
                                <label for="validationServer02">{{trans('Choisir la date d\'Envoie')}}</label>
                                <div class="input-group date" id="dt-local" data-target-input="nearest">
                                    <input class="form-control datetimepicker-input digits"  name="send_date" type="text" data-target="#dt-local">
                                    <div class="input-group-append" data-target="#dt-local" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                {{-- <input class="datetimepicker-input digits form-control" value="" type="text" data-language="en"> --}}
                            </div>

                            <div class="col-md-12 " id="send_date" style="display: none">
                                
                               
                            </div>
                            <div class="col-md-12 mb3" id="customers_id_div">

                            </div>

                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('Enregistrer')}}">{{trans('Enregistrer')}}</button>
                    </form>
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
    <script src="{{asset('assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{asset('assets/js/datepicker/date-time-picker/datetimepicker.custom.js')}}"></script>
    <script>

        //call ajax to get stores of the owner
        function getStores(owner_id)
        {
            if(owner_id!=-1){
                $.ajax({
                    url:"{{route('admin.campaign.stores')}}",
                    type:'GET',
                    data:{owner_id:owner_id},
                    success:function(data)
                    {
                        console.log(data.html);
                        $('#stores_id_div').html(data.html);
                    }
                });

            }
        }
        //call ajax to get all the customers of a store
        function getCustomers(store_id)
        {
            if(store_id!=-1){
                $.ajax({
                    url:"{{route('admin.campaign.customers')}}",
                    type:'GET',
                    data:{store_id:store_id},
                    success:function(data)
                    {
                        $('#customers_id_div').html(data.html);
                    }
                });
            }
        }
        //call ajax to get the message once the campaign is selectd
        function getMessage(campaign_id){
            if(campaign_id!=-1){
                $.ajax({
                    url:"{{route('admin.campaign.message')}}",
                    type:'GET',
                    data:{campagn_id:campaign_id},
                    success:function(data)
                    {
                        $('#campaign_message').html(data);
                    }
                });

            }
        }

        function getTimeField(send_time_id){
            if(send_time_id==1)
            {
                document.getElementById('send_date').style.display='block';
            }else{
                document.getElementById('send_date').style.display='none';

            }
        }
    </script>
@endsection
