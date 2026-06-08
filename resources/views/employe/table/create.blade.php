@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
    <style>
        .table-card:hover i {
            visibility: visible !important;
        }

        /* .card.table {
                        background-image: url('../assets/categorei/food.png');
                        background-size: cover;
                        background-position: center;
                    } */
    </style>
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ trans('Mes Tables') }}</h3>
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
                <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-transfer"
                    title="Transfer Table">Transfer</button>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row" style="display: flex;gap:10px;padding:0 12px;">
            @foreach ($tables as $table)
                <div>
                    <i class="fas fa-edit" data-toggle="modal" data-target=".bd-example-modal-lg-edit{{ $table->id }}"
                        style="position: absolute;right: 22px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <i class="fas fa-trash" data-toggle="modal" data-target=".bd-example-modal-lg-delete{{ $table->id }}"
                        style="position: absolute;right: 41px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <a href="{{ url('employe/pos/create/' . $table->id) }}">
                        <div class="card table"
                            style="background: {{ $table->table_status == 1 ? '#54ba4a' : 'grey' }} ;width:100px;">
                            <div
                                class="card-body "style="display:flex;justify-content: center;
                                align-items:center; flex-direction:column;padding:10px !important;">
                                <label>{{ $table->number }}</label>
                                <label
                                    style="overflow: hidden;text-overflow: ellipsis;
                                white-space: nowrap;display: block;width: 92%; text-align: center;">{{ $table->description }}</label>
                            </div>
                        </div>
                    </a>
                </div>
                {{-- <div class="modal fade bd-example-modal-lg-edit{{ $table->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Modifier Categorie') }}</h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('employe.table.update', $table->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT" />
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">
                                            <label for="validationServer01">{{ trans('Nom') }}</label>
                                            <input class="form-control" id="validationServer01" type="text"
                                                name="name" value="{{ $table->name }}" required=""
                                                data-original-title="" title="{{ trans('Nom table') }}">
                                            @if ($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationServer02">{{ trans('description') }}</label>
                                            <input class="form-control " id="validationServer02" type="text"
                                                name="description" value="{{ $table->description }}" required=""
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
                <div class="modal fade bd-example-modal-lg-delete{{ $table->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Delete Categorie') }}</h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('employe.table.delete', $table->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE" />

                                    <p>Êtes-vous sûr de vouloir supprimer cette table??</p>
                                    <div class="modal-footer">
                                        <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">
                                        <input type="hidden" name="id" value="{{ $table->id }}">
                                        <button type="button" class="btn btn-primary" id="close-modal">No</button>
                                        <button class="btn btn-danger" type="submit" data-original-title=""
                                            title="{{ trans('Oui') }}">{{ trans('Oui') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @endforeach
        </div>
    </div>
    <div class="modal fade bd-example-modal-transfer" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Transfer Table') }}</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title=""
                        title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employe.table.tansferer') }}" method="POST">
                        @csrf
                        <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{ trans('Tables') }}</label>
                                <select id="Tables" class="js-example-basic-single col-sm-12"
                                    onchange="getClientPoints($(this).val())" required name="full_table_id">
                                    <option value="0">--- {{ trans('communs.Choisir une table') }} ---</option>
                                    @foreach ($fullTables as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{ trans('empty table') }}</label>
                                <select id="emptyTables" class="js-example-basic-single col-sm-12"
                                    onchange="getClientPoints($(this).val())" required name="empty_table_id">
                                    <option value="0">--- {{ trans('communs.Choisir une empty table') }} ---</option>
                                    @foreach ($tables as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title=""
                            title="{{ trans('trasferer') }}">{{ trans('trasferer') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Nouveau Table') }}</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title=""
                        title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employe.table.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="hidden" name="store_id" value="{{ auth()->user()->store->id }}">

                                <label for="validationServer01">{{ trans('Number') }}</label>
                                <input class="form-control" id="validationServer01" type="number" name="number"
                                    value="{{ old('number') }}" required="" data-original-title=""
                                    title="{{ trans('Number table') }}">
                                @if ($errors->has('number'))
                                    <div class="invalid-feedback">{{ $errors->first('number') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{ trans('Description') }}</label>
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
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
    <script src="{{ asset('assets/js/notify/index.js') }}"></script>
    <script>
        setTimeout(function() {
            const errorAlert = document.querySelector('.alert.alert-danger');
            if (errorAlert) {
                errorAlert.style.display = 'none';
            }
        }, 5000);
    </script>
@endsection
