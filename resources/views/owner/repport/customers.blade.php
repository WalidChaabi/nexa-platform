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
    <h3 class="text-center">{{ trans('Mes Clients') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/owner') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('Produits') }}</li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <h1 class="text-center">{{ trans('Total') }} : {{ $total }} </h1>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <form id="importList" action="{{ route('owner.customers.repport') }}" method="GET">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select class="form-control digits" name="store_id" id="store_id">
                                                    <option>-- {{ trans('Choisir Magasin') }} --</option>
                                                    @foreach ($stores as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" name="start_date" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" name="end_date" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit"
                                                    class="form-control btn btn-primary">{{ trans('Rechercher') }}</button>
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
                                        <th>{{ trans('Client') }}</th>
                                        <th>{{ trans('Date De creation') }}</th>
                                        <th>{{ trans('Téléphone') }}</th>
                                        <th>{{ trans('Sexe') }}</th>
                                        <th>{{ trans('R.M') }}</th>
                                        <th>{{ trans('R.P') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $item)
                                        <tr>
                                            <td>{{ $item->getFullNameAttribute() }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ getSexeMembre($item->sexe) }}</td>
                                            <td>{{ $item->points }}</td>
                                            <td>{{ $item->points * $item->store->coeff }}</td>
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
    <script>
        function importProducts(store_id) {
            if (store_id != 0) {
                $('#store_id_hidden').attr('value', store_id);
                $('#importList').submit();
            } else {
                alert('Vous devez selectionnez un magasin!')
            }

        }
    </script>
@endsection
