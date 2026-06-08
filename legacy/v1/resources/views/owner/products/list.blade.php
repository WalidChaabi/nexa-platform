@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('style')
    <style>
        .delete-icon {
            position: absolute;
            top: 10px;
            right: 92px;
        }

        .delete-icon i {
            color: red;
        }

        .file {
            position: relative;
            display: inline-block;
            cursor: pointer;
            height: 2.5rem;
            width: 100%
        }

        .file input {
            min-width: 14rem;
            margin: 0;
            filter: alpha(opacity=0);
            opacity: 0;
        }

        .file-custom {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 5;
            height: 2.5rem;
            padding: 0.5rem 1rem;
            line-height: 1.5;
            color: #555;
            background-color: #fff;
            border: 0.075rem solid #ddd;
            border-radius: 0.25rem;
            box-shadow: inset 0 0.2rem 0.4rem rgba(0, 0, 0, .05);
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .file-custom:before {
            position: absolute;
            top: -0.075rem;
            right: -0.075rem;
            bottom: -0.075rem;
            z-index: 6;
            display: block;
            content: "Parcourir";
            height: 2.5rem;
            padding: 0.5rem 1rem;
            line-height: 1.5;
            color: #555;
            background-color: #eee;
            border: 0.075rem solid #ddd;
            border-radius: 0 0.25rem 0.25rem 0;
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ trans('Produits ou Services') }}</h3>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label></label>
                                    <button class="btn btn-info" type="button" data-toggle="modal"
                                        data-target=".bd-example-modal-lg" title="Nouveau Magasin">Nouveau</button>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <form id="importList" action="{{ route('owner.import.products') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" id="store_id_hidden" name="store">
                                            <div class="col-md-5">
                                                <select class="form-control digits" name="store_id" id="store_id" onchange="checkStoreId()">
                                                    <option>-- {{ trans('Choisir Magasin') }} --</option>
                                                    @foreach ($stores as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="file">
                                                    <input type="file" id="file" aria-label="File browser example">
                                                    <span class="file-custom">Choose file...</span>
                                                    <span class="delete-icon" id="deleteIcon" style="display: none;"><i
                                                            class="fa-solid fa-x"></i></span>
                                                </label>
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <label class="custom-file">
                                                    <input type="file" id="file" name="products"
                                                        class="form-control" required>
                                                    <span class="custom-file-control"></span>
                                                </label>
                                            </div> --}}
                                            <div class="col-md-2">
                                                <button type="button" onclick="importProducts($('#store_id').val())"
                                                    class="btn btn-info">{{ trans('Importer') }}</button>
                                            </div>
                                            <p id="error_message" style="color: red; display: none;">Veuillez choisir un
                                                magasin.
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
                                        <th>{{ trans('Libellé') }}</th>
                                        <th>{{ trans('Magasin') }}</th>
                                        <th>{{ trans('Prix de vente') }}</th>
                                        <th>{{ trans('Prix d\'achat') }}</th>
                                        <th>{{ trans('Quantité du stock') }}</th>
                                        <th>{{ trans('Alimenter QS') }}</th>
                                        <th>{{ trans('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        <tr>
                                            <th scope="row">{{ $item->label }}</th>
                                            <td>{{ $item->store->name }}</td>
                                            <td>{{ $item->price }}</td>
                                            <td>{{ $item->promotion_price }}</td>
                                            <td id="quantite{{ $item->id }}">{{ $item->quantite }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    {{-- <input id="quantityInput{{ $item->id }}" class="mr-2" type="number"
                                                        style="width:100px;border: #a927f9 1px solid;border-radius: 3px;"> --}}
                                                    <input class="form-control mr-2 " id="sup_Qt{{ $item->id }}"
                                                        type="number" name="sup_Qt" data-original-title=""
                                                        title="{{ trans('Ajouter quantité ') }}"min="0"
                                                        oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"
                                                        style="width:100px;border: #a927f9 1px solid;border-radius: 3px;">
                                                    <button class="btn btn-info"
                                                        onclick="addQuantite({{ $item->id }})">{{ trans('valider') }}</button>
                                                    {{-- <button class="btn btn-info" id="change{{ $item->id }}"onclick="changeQuantite({{ $item->id }})" type="button">valider</button> --}}
                                                </div>
                                            </td>
                                            <td>
                                                <ul>
                                                    <li><i class="fa fa-eye" data-toggle="modal"
                                                            data-target=".bd-example-modal-lg-product-{{ $item->id }}"
                                                            title="{{ trans('Consulter') }}"></i></li>
                                                    <li><a href="{{ url('/owner/products/' . $item->id . '/edit') }}"><i
                                                                class="fa fa-pencil"
                                                                title="{{ trans('Modifier') }}"></i></a></li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <div id="modal-owner" class="modal fade bd-example-modal-lg-product-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                                            aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myLargeModalLabel">
                                                            {{ trans('Détail Produit') }}</h4>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close" data-original-title="" title=""><span
                                                                aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleFormControlSelect9">{{ trans('Libellé') }}</label>
                                                                    <input readonly class="form-control "
                                                                        id="validationServer02" type="text"
                                                                        value="{{ $item->label }}"
                                                                        data-original-title=""
                                                                        title="{{ trans('Libellé') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="validationServer02">{{ trans('Magasin') }}</label>
                                                                <input readonly class="form-control "
                                                                    id="validationServer02" type="text"
                                                                    value="{{ $item->store->name }}"
                                                                    data-original-title=""
                                                                    title="{{ trans('Magasin') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleFormControlSelect9">{{ trans('Prix de vente') }}</label>
                                                                    <input readonly class="form-control "
                                                                        id="validationServer01" type="text"
                                                                        value="{{ $item->price }}" required=""
                                                                        data-original-title=""
                                                                        title="{{ trans('Prix de vente') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="validationServer01">{{ trans('Prix d\'achat') }}</label>
                                                                <input readonly class="form-control "
                                                                    id="validationServer01" type="text"
                                                                    value="{{ $item->promotion_price }}" required=""
                                                                    data-original-title=""
                                                                    title="{{ trans('Prix Promotion') }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label
                                                                    for="validationServer01">{{ trans('Quantite du stock') }}</label>
                                                                <input readonly class="form-control "
                                                                    id="validationServer01" type="text"
                                                                    value="{{ $item->quantite }}" required=""
                                                                    data-original-title=""
                                                                    title="{{ trans('Quantité du stock') }}">
                                                            </div>

                                                        </div>
                                                        <button class="btn btn-primary" data-dismiss="modal"
                                                            aria-label="Close" data-original-title=""
                                                            title="">{{ trans('Fermer') }}</button>
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
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Nouveau Produit') }}</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('owner.products.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect9">{{ trans('Magasin') }}</label>
                                    <select class="form-control digits" name="store_id" id="exampleFormControlSelect9">
                                        <option>-- {{ trans('Choisir') }} --</option>
                                        @foreach ($stores as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{ trans('libelle') }}</label>
                                <input class="form-control " id="validationServer01" type="text" name="label"
                                    required="" data-original-title="" title="{{ trans('libelle') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{ trans('Prix de vente') }}</label>
                                <input class="form-control " id="validationServer01" type="text" name="price"
                                    required="" data-original-title="" title="{{ trans('Prix de vente') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{ trans('Prix d\'achat') }}</label>
                                <input class="form-control " id="validationServer02" type="text"
                                    name="promotion_price" data-original-title="" title="{{ trans('Prix d\'achat') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{ trans('Quantité du stock') }}</label>
                                <input class="form-control " id="validationServer02" type="text" name="quantite"
                                    data-original-title="" title="{{ trans('La quantité') }}">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title=""
                            title="">{{ trans('Enregistrer') }}</button>
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
    <script>
        document.getElementById('file').addEventListener('change', function(event) {
            var fileName = event.target.files[0].name;
            var label = document.querySelector('.file-custom');
            var deleteIcon = document.getElementById('deleteIcon');

            var file = event.target.files[0];
            var formData = new FormData();
            formData.append('file', file);

            label.textContent = fileName;
            deleteIcon.style.display = 'inline-block';
            deleteIcon.style.zIndex = '9999';
        });

        document.getElementById('deleteIcon').addEventListener('click', function() {
            var fileInput = document.getElementById('file');
            var label = document.querySelector('.file-custom');
            var deleteIcon = document.getElementById('deleteIcon');

            fileInput.value = '';
            label.textContent = 'Choose file...';
            deleteIcon.style.display = 'none';
        });

        function importProducts(store_id) {
            if (store_id != 0) {
                $('#store_id_hidden').attr('value', store_id);
                $('#importList').submit();
            } else {
                alert('Vous devez selectionnez un magasin!')
            }
        }

        function addQuantite(product_id) {
            var quantite = document.getElementById('quantite' + product_id).innerText;
            console.log(quantite);
            var store_id = document.getElementById('store_id').value;
            var sup_Qt = document.getElementById('sup_Qt' + product_id).value;
            var errorMessage = document.getElementById('error_message');

            if (isNaN(store_id)) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
                $.ajax({
                    url: "{{ route('owner.products.add.quantite') }}",
                    type: 'POST',
                    data: {
                        sup_Qt: sup_Qt,
                        quantite: quantite,
                        product_id: product_id,
                        store_id: store_id
                    },
                    success: function(data) {
                        $('#quantite' + product_id).text(data);
                        $('#sup_Qt' + product_id).val('');
                    },
                    error: function(error) {
                        console.error('Error fetching products:', error);
                    }
                });
            }
        }

        function checkStoreId() {
            var storeId = document.getElementById('store_id').value;
            var errorMessage = document.getElementById('error_message');

            if (isNaN(storeId)) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        }
        
        // function changeQuantite(productId) {
        //     var quantite = document.getElementById('quantityInput' + productId).value;
        //     var store_id = document.getElementById('store_id').value;
        //     var errorMessage = document.getElementById('error_message');

        //     if (isNaN(store_id)) {
        //         errorMessage.style.display = 'block';
        //     } else {
        //         errorMessage.style.display = 'none';
        //         $.ajax({
        //             url: "{{ route('owner.products.change.quantite') }}",
        //             data: {
        //                 productId: productId,
        //                 quantite: quantite,
        //                 store_id: store_id
        //             },
        //             type: 'POST',
        //             success: function(data) {
        //                 console.log(data)
        //                 $('#quantite' + productId).text(data);
        //                 $('#quantityInput' + productId).val('');
        //             },
        //             error: function(error) {
        //                 console.log(error)
        //             }
        //         });
        //     }
        // }
    </script>
@endsection
