@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/simple-mde.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ auth()->user()->getFullNameAttribute() }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ trans('dashboard.dashboard') }}</li>
    <li class="breadcrumb-item active">{{ trans('Campagnes') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg"
                            title="Nouveau Campagnes">Nouveau</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display datatables" id="render-datatable">
                                <thead>
                                    <tr>
                                        <th>{{ trans('Nom') }}</th>
                                        <th>{{ trans('Type') }}</th>
                                        <th>{{ trans('Store') }}</th>
                                        <th>{{ trans('Active') }}</th>
                                        <th>{{ trans('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($campaign_details as $campaign_detail)

                                        <tr>
                                            <th scope="row">{{$campaign_detail->campaign->name}}</th>
                                            <td>{{$campaign_detail->campaign->type}}</td>
                                            <td>{{$campaign_detail->store->name}}</td>
                                            <td>{{$campaign_detail->active}}</td>
                                            <td>
                                                <ul>
                                                    <li><a href="{{ url('/owner/campaigns/details/'. $campaign_detail->campaign->type .'/'. $campaign_detail->id) }}">
                                                        <i class="fa fa-eye" title="{{ trans('Consulter') }}"></i>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <div id="modal-owner" class="modal fade bd-example-modal-lg-store-{{ $campaign_detail->id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
                                            style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myLargeModalLabel">
                                                            {{ trans('Détail Magasin') }}</h4>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close" data-original-title="" title=""><span
                                                                aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="exampleFormControlSelect9">Magasin :</label>
                                                                {{ $campaign_detail->store->name }}
                                                                </div>
                    
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="exampleFormControlSelect9">Campagne :</label>
                                                                    {{ $campaign_detail->campaign->name }}
                                                                    @if ($campaign_detail->product_id > 0)
                                                                        <label for="exampleFormControlSelect9">Produit</label>
                                                                        {{ $campaign_detail->product->name }}
                                                                    @endif
                                                                </div>
                    
                                                                <div class="col-md-6 mb-3"
                                                                    style="border: 1px solid #e6e4e4;border-radius: 10px 0px 0px 10px;padding: 10px;">
                                                                    <label for="validationCustomUsername">@lang('communs.Messages')</label>
                                                                    <div class="d-flex align-items-center mb-3">
                                                                        <span>@lang('communs.Reduction'): </span>
                                                                        <div class="input-group ml-2">
                                                                            <input readonly type="number" class="form-control"value="{{$campaign_detail->reduction}}">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">%</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @foreach ($campaign_detail->message_camp->pluck('messages.message')->flatten() as $msg)
                                                                    <input readonly class="input-message"
                                                                        type="text"value="{{ $msg }}" />
                                                                @endforeach
                                                                </div>
                    
                                                                <div class="col-md-6 mb-3 "
                                                                    style="border: 1px solid #e6e4e4;border-radius: 0px 10px 10px 0px;padding: 10px;">
                                                                    <label for="validationCustomUsername">@lang('communs.Filters')</label>
                                                                    <div class="wrapper">
                                                                        <label for="validationCustomUsername">@lang('communs.Age')</label>
                                                                        <div class="values" style="margin: 25px auto;">
                                                                            <span id="range1">
                                                                                {{$campaign_detail->filter->age_min}}
                                                                            </span>
                                                                            <span> &dash; </span>
                                                                            <span id="range2">
                                                                                {{$campaign_detail->filter->age_max}}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex" style="justify-content: space-between">
                                                                        <div style="width: 40%">
                                                                            <label for="validationCustomUsername">@lang('communs.Points')</label>
                                                                            <input readonly class="form-control" type="text" value="{{$campaign_detail->filter->points}}">
                                                                        </div>
                                                                        <div style="width: 40%">
                                                                            <label for="validationCustomUsername">@lang('communs.Dernière visite')</label>
                                                                            <input readonly class="form-control" type="text" value="{{$campaign_detail->filter->last_visit}}">
                                                                        </div>
                                                                    </div>
                                                                    <label for="validationCustomUsername">@lang('communs.Sexe')</label>
                                                                    <div class="d-flex">
                    
                                                                        <div class="d-flex pl-3">
                                                                            @if ($campaign_detail->filter->sexe == 0)
                                                                                <input readonly class="form-check-input" type="radio" checked>
                                                                                <label class="form-check-label" for="flexRadioDefault1">Homme</label>
                                                                            @endif
                                                                        </div>
                                                                        <div class="d-flex pl-5">
                                                                            @if ($campaign_detail->filter->sexe == 1)
                                                                                <input readonly class="form-check-input" type="radio" checked>
                                                                                <label class="form-check-label" for="flexRadioDefault1">Femme</label>
                                                                            @endif
                                                
                                                                        </div>
                                                                        <div class="d-flex pl-5">
                                                                            @if ($campaign_detail->filter->sexe == 2)
                                                                                <input readonly class="form-check-input" type="radio" checked>
                                                                                <label class="form-check-label" for="flexRadioDefault1">Mixte</label>
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <label for="validationServer01">{{ trans('communs.Horaire') }}</label>
                                                                        <div class="d-flex" style="justify-content: space-between">
                                                                            <div style="width: 40%">
                                                                                <label
                                                                                    for="validationServer01">{{ trans('communs.Heure debut') }}</label>
                                                                                <input readonly class="form-control" type="time" value="{{$campaign_detail->filter->heure_start}}">
                                                                            </div>
                                                                            <div style="width: 40%">
                                                                                <label
                                                                                    for="validationServer01">{{ trans('communs.Heure fin') }}</label>
                                                                                <input readonly class="form-control" type="time" value="{{$campaign_detail->filter->heure_end}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                            
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
@endsection

@section('script')
    <script src="{{ asset('assets/js/editor/simple-mde/simplemde.min.js') }}"></script>
    <script src="{{ asset('assets/js/editor/simple-mde/simplemde.custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
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
