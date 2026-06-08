@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection

@section('style')
    <style>
        .categorie-card:hover i {
            visibility: visible !important;
        }

        .card.categorie {
            background-image: url('../assets/categorei/food.png');
            background-size: cover;
            background-position: center;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ trans('Mes Categoreis') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('Categoreis') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg"
                    title="Nouveau Categorie">Nouveau</button>
            </div>
        </div>
        <div class="row">
            @foreach ($categories as $categorie)
                <div class="col-sm-4 categorie-card">
                    <i class="fas fa-edit" data-toggle="modal" data-target=".bd-example-modal-lg-edit{{ $categorie->id }}"
                        style="position: absolute;right: 22px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <i class="fas fa-trash" data-toggle="modal"
                        data-target=".bd-example-modal-lg-delete{{ $categorie->id }}"
                        style="position: absolute;right: 41px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <a href="{{ url('employe/products/' . $categorie->id) }}">
                        <div class="card categorie">
                            <div class="card-body "style="display:flex;justify-content: center;">
                                <label>{{ $categorie->name }}</label>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="modal fade bd-example-modal-lg-edit{{ $categorie->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Modifier Categorie') }}</h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('employe.categorie.update', $categorie->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT" />
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">
                                            <label for="validationServer01">{{ trans('Nom') }}</label>
                                            <input class="form-control" id="validationServer01" type="text"
                                                name="name" value="{{ $categorie->name }}" required=""
                                                data-original-title="" title="{{ trans('Nom categorie') }}">
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationServer02">{{ trans('description') }}</label>
                                            <input class="form-control " id="validationServer02" type="text"
                                                name="description" value="{{ $categorie->description }}" required=""
                                                data-original-title="" title="{{ trans('Description') }}">
                                            @if ($errors->has('description'))
                                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit" data-original-title=""
                                        title="{{ trans('Enregistrer') }}">{{ trans('Enregistrer') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-example-modal-lg-delete{{ $categorie->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Delete Categorie') }}</h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('employe.categorie.delete', $categorie->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE" />

                                    <p>Êtes-vous sûr de vouloir supprimer cette categorie??</p>
                                    <div class="modal-footer">
                                        <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">
                                        <input type="hidden" name="id" value="{{ $categorie->id }}">
                                        <button type="button" class="btn btn-primary" id="close-modal">No</button>
                                        <button class="btn btn-danger" type="submit" data-original-title=""
                                            title="{{ trans('Oui') }}">{{ trans('Oui') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Nouveau Categorie') }}</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employe.categorie.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">
                                <label for="validationServer01">{{ trans('Nom') }}</label>
                                <input class="form-control" id="validationServer01" type="text" name="name"
                                    value="{{ old('name') }}" required="" data-original-title=""
                                    title="{{ trans('Nom categorie') }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{ trans('Prénom') }}</label>
                                <input class="form-control " id="validationServer02" type="text" name="description"
                                    value="{{ old('description') }}" required="" data-original-title=""
                                    title="{{ trans('Description') }}">
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title=""
                            title="{{ trans('Enregistrer') }}">{{ trans('Enregistrer') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
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
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js²') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
@endsection
