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
                            <div class="col-12">
                                <h1 class="text-left">{{ trans('Total des ventes') }} : {{ $totalSell }} (MAD) </h1> <br>
                                <h1 class="text-left">{{ trans('Total des marges brutes') }} : {{ $totalMargeBrute }} (MAD)
                                </h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <form id="importList" action="{{ route('owner.products.repport.table') }}" method="GET" onsubmit="return validateForm()">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select class="form-control digits" name="store_id" id="store_id"
                                                    onchange="updateExcelLink()">
                                                    <option>-- {{ trans('Choisir Magasin') }} --</option>
                                                    @foreach ($stores as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $store_id == $key ? 'selected' : '' }}>
                                                            {{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" id="type" name="type">
                                            <div class="col-md-3">
                                                <button type="submit" id="t1" value="t1"
                                                    class="form-control btn btn-primary"
                                                    onclick="getType($(this).val())">{{ trans('Statistique produit') }}</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" id="t2" value="t2"
                                                    class="form-control btn btn-primary"
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
                            <div class="col-md-6 d-flex"
                                style="justify-content: space-between ;margin-bottom: 10px;padding: 0;">
                                @if ($products != [])
                                    <a id='excelButton' style="width: 100px;"class="btn btn-primary">Excel</a>
                                    <button style="width: 100px;" type="submit"
                                        class="form-control btn btn-primary">PDF</button>
                                    <button style="width: 100px;" type="submit"
                                        class="form-control btn btn-primary">CSV</button>
                                @endif
                            </div>

                            <table class="display datatables" id="render-datatable">
                                <thead>
                                    <tr>
                                        <th>{{ trans('Produits') }}</th>
                                        <th>{{ trans('Quantité du stock') }}</th>
                                        <th>{{ trans('Quantité vendue') }}</th>
                                        <th>{{ trans('Prix de vente') }}</th>
                                        <th>{{ trans('Prix d\'achat') }}</th>
                                        <th>{{ trans('Total de vente') }}</th>
                                        <th>{{ trans('Stock theorique') }}</th>
                                        <th>{{ trans('Marge brute') }}</th>
                                        <th>{{ trans('Total brute') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        <tr>
                                            <td>{{ $item->label }}</td>
                                            <td>{{ $item->quantite }}</td>
                                            <td>{{ $item->qte_total }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->promotion_price }}</td>
                                            <td>{{ $item->price * $item->qte_total }}</td>
                                            <td>{{ $margeBrute = $item->price - $item->promotion_price }}</td>
                                            <td><span style="color:green">{{ $margeBrute * $item->qte_total }}
                                                    {{ trans('Mad') }}</span></td>
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
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>

    <script>
        function getType(type) {
            document.getElementById('type').value = type;
        }
    
        function validateForm() {
            var selectValue = document.getElementById("store_id").value;
            console.log(selectValue);
    
            if (isNaN(selectValue)) {
                document.getElementById("error_message").style.display = "block";
                return false; // Prevents the form submission
            } else {
                document.getElementById("error_message").style.display = "none";
                return true; // Allows the form submission
            }
        }
    
        function updateExcelLink() {
            var storeId = document.getElementById('store_id').value;
            var errorMessage = document.getElementById('error_message');
            var excelButton = document.getElementById('excelButton');
            if (!excelButton) return;
            var form = document.getElementById('importList');
    
            if (isNaN(storeId) || storeId === '') {
                errorMessage.style.display = 'block';
                form.removeAttribute('action');
                form.removeAttribute('method');
            } else {
                errorMessage.style.display = 'none';
            var href = "{{ url('owner/repport/products/excel/{storeId}') }}";
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
    
        document.addEventListener("DOMContentLoaded", function() {
            updateExcelLink();    
        });
    </script>
    

@endsection
