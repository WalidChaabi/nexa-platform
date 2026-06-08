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
    <h3 class="text-left">{{ trans('Mes Produits') }}</h3>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <form id="importList" action="{{ route('owner.products.repport.table') }}" method="GET">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select class="form-control digits" name="store_id" id="store_id" onchange="updateExcelLink()">
                                                    <option>-- {{ trans('Choisir Magasin') }} --</option>
                                                    @foreach ($stores as $key => $value)
                                                        <option value="{{ $key }}" {{ $store_id == $key ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            <input type="hidden" id="type" name="type">
                                            <div class="col-md-3">
                                                <button type="submit" id="t1" value="t1" class="form-control btn btn-primary"
                                                    onclick="getType($(this).val())">{{ trans('Statistique produit') }}</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" id="t2" value="t2" class="form-control btn btn-primary"
                                                    onclick="getType($(this).val())">{{ trans('Variation stock') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                    <p id="error_message" style="color: red; display: none;">Veuillez choisir un magasin.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="col-md-6 d-flex" style="justify-content: space-between ;margin-bottom: 10px;padding: 0;">
                                <a id="excelButton" onclick="test()" style="width: 100px;" class="btn btn-primary">Excel</a>
                                <button style="width: 100px;" type="submit"
                                    class="form-control btn btn-primary">PDF</button>
                                <button style="width: 100px;" type="submit"
                                    class="form-control btn btn-primary">CSV</button>
                            </div>

                            <table class="display datatables" id="render-datatable">
                                <thead>
                                    <tr>
                                        <th>{{ trans('Produits') }}</th>
                                        @for ($i=1;$i<$length+1;$i++)
                                            <th>{{ trans('Qté ').$i}}</th>
                                            <th>{{ trans('date ').$i}}</th>
                                        @endfor
                                        <th>{{ trans('total')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $index => $item)
                                    <tr>
                                        <th>{{ $item['product'] }}</th>
                                        @for ($i = 1; $i <= $length; $i++)
                                            <td>{{ isset($item['Quantité ' . $i]) ? $item['Quantité ' . $i] : '' }}</td>
                                            <td>{{ isset($item["Date d'ajout " . $i]) ? $item["Date d'ajout " . $i] : '' }}</td>
                                        @endfor
                                        <td>{{ $item['Total'] }}</td>
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
        function getType(type){
            document.getElementById('type').value = type;
            updateExcelLink()
        }
        function updateExcelLink() {
            var storeId = document.getElementById('store_id').value;
            var errorMessage = document.getElementById('error_message');

            if (isNaN(storeId)) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
                var excelButton = document.getElementById('excelButton');
                var href = "{{ url('owner/repport/products/excel2/{storeId}') }}";
                var updatedHref = href.replace('{storeId}', storeId);
                excelButton.setAttribute('href', updatedHref);
            }
        }

        function test() {
            var storeId = document.getElementById('store_id').value;
            var errorMessage = document.getElementById('error_message');

            if (isNaN(storeId)) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        }
        updateExcelLink();
    </script>

@endsection
