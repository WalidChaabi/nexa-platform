@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ trans('communs.Caisse') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/owner') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('communs.Caisse de depart') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ trans('communs.Caisse de depart') }} : {{ $initialFond }} </h2>
                        <h4>{{ trans('communs.Etat de caisse') }} :
                            {{ isset($_GET['date']) ? $_GET['date'] : Date('Y/m/d') }} <h4>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-12 row">
                                <div class="col-md-4">
                                    <input id="date-filter" class="datepicker-here form-control" type="text" data-language="en">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-info" type="button"
                                        onclick="getCaisseByDate('{{ route('employe.caisse.index') }}' ,$('#date-filter').val())"
                                        title="{{ trans('FILTRES') }}">
                                        <i class="fa fa-sliders" title="{{ trans('filtres') }}" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <table class="display" id="render-table-fonds">
                                <thead>
                                    <tr>
                                        <th>{{ trans('communs.Mode de Paiement') }}</th>
                                        <th>{{ trans('communs.Caisse de départ') }}</th>
    
                                        <th>{{ trans('communs.Encaissement') }}</th>
                                        <th>{{ trans('communs.Décaissement') }}</th>
                                        <th>{{ trans('communs.Retour') }}</th>
                                        @if (auth()->user()->store->reste == 1)                                            
                                            <th>{{ trans('communs.Reste_payer') }}</th>
                                        @endif
                                        <th>{{ trans('communs.Total Ligne') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ trans('communs.Espèces') }}</td>
                                        <td>-</td>
                                        <td>{{ $encasementCash }}</td>
                                        <td>{{ $disbursementCash }}</td>
                                        <td>{{ $returnCash }}</td>
                                        @if (auth()->user()->store->reste == 1)
                                            <td>{{ $reste_payer }}</td>
                                        @endif
                                        <td>{{ $encasementCash - $disbursementCash - $returnCash }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('communs.Chèque') }}</td>
                                        <td>-</td>
                                        <td>{{ $encasementCheck }}</td>
                                        <td>{{ $disbursementCheck }}</td>
                                        <td>{{ $returnCheck }}</td>
                                        {{-- <td>{{ $returnCash }}</td> --}}
                                        @if (auth()->user()->store->reste == 1)
                                            <td>{{ $reste_payer }}</td>
                                        @endif
    
                                        <td>{{ $encasementCheck - $disbursementCheck - $returnCheck }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('communs.Carte Bancaire') }}</td>
                                        <td>-</td>
                                        <td>{{ $encasementCard }}</td>
                                        <td>{{ $disbursementCard }}</td>
                                        <td>{{ $returnCard }}</td>
                                        {{-- <td>{{ $returnCash }}</td> --}}
                                        @if (auth()->user()->store->reste == 1)
                                            <td>{{ $reste_payer }}</td>
                                        @endif
    
                                        <td>{{ $encasementCard - $disbursementCard - $returnCard }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('communs.Caisse de départ') }}</td>
                                        <td>{{ $initialFond }}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        @if (auth()->user()->store->reste == 1)
                                        
                                        <td>-</td>
                                        @endif
    
                                        <td>{{ $encasementCard - $disbursementCard - $returnCard }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('communs.Total Caisse') }}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        @if (auth()->user()->store->reste == 1)
    
                                        <td>-</td>
                                        @endif
                                        <td>{{ $initialFond + $encasementCard + $encasementCheck + $encasementCash + $reste_payer - ($disbursementCash + $disbursementCheck + $disbursementCard + $returnCash + $returnCard + $returnCheck) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>


                    </div>
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
@endsection

<script>
    function ShowItems(value, url) {
        window.location = url + "?items=" + value
    }

    function getCaisseByDate(url, date) {
        window.location = url + "?date=" + date
    }
</script>
