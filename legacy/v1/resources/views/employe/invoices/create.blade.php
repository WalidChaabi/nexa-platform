@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
    <style>
        .qts {
            width: 52px !important;
        }

        .select2 {
            border: 2px solid #a927f9
        }

        @media (min-width: 768px) {
            .col-md-4 {
                max-width: none;
            }
        }
    </style>
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ auth()->user()->store->name }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('employe.invoices') }}">{{ trans('communs.Factures') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('communs.Nouveau') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                @if (isset($type))
                <input type="hidden" name="praticienType" value="{{$type}}">
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3> {{ trans('communs.Nouvelle Facture') }} : {{ date('Y-m-d H:i:s') }} </h3>
                    </div>
                    <div class="card-body">
                        <div class="customersErrors">

                        </div>
                        @if ($errors->any())
                            <span style="color:red"> {{ implode('', $errors->all(':message')) }}</span>
                        @endif
                        <form action="{{ route('employe.invoices.store') }}" method="POST" id="MyForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{ trans('communs.Reduction en Points') }}</strong> : <span
                                        id="points_red">0</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{ trans('communs.Reduction en MAD') }}</strong> : <span
                                        id="mad_red">0</span>
                                    <input type="hidden" id="mad_red_hidden">
                                </div>
                            </div>
                            <br><br>
                            @if (auth()->user()->store->id == 1)
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>{{ trans('Homme') }}</strong> : <input type="radio" value="1"
                                            name="for" checked>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Femme</strong> : <input type="radio" value="2" name="for">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Mix</strong> : <input type="radio" value="3" name="for">
                                    </div>
                                </div>
                            @endif
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer01">{{ trans('communs.Informations du Client') }}</label>
                                    <select id="customers" class="js-example-basic-single col-sm-12"
                                        onchange="getClientPoints($(this).val())" required name="customer">
                                        <option value="0">--- {{ trans('communs.Choisir un client') }} ---</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->firstname . ' ' . $item->lastname }} : {{ $item->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex" style="justify-content: flex-start">
                                    <div class="d-flex" style="flex-direction: column">
                                        <label for="validationServer02">{{ trans('communs.Nouveau Client') }}</label>
                                        <button class="btn btn-info" type="button" data-toggle="modal"
                                            data-target=".bd-example-modal-lg"
                                            title="{{ trans('communs.Nouveau Client') }}">{{ trans('communs.Nouveau') }}</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            @if (auth()->user()->store->praticien_2 == 1)
                                <div class="row" id="produitPraicien">
                                    <div class="col-md-4 d-flex" style="flex-direction: column">
                                        <label for="validationServer01">{{ trans('communs.Produit') }} :</label>
                                        <select onchange="setPriceProduct(this, this.value,1)"
                                            class="js-example-basic-single col-sm-12" name="products[]">
                                            <option data-tokens="0">--- {{ trans('communs.Choisir Produit/Service') }}
                                                ---
                                            </option>
                                            @foreach ($products as $item)
                                                <option data-tokens="{{ $item->label }}" value="{{ $item->id }}">
                                                    {{ $item->label }} | {{ $item->price }} Mad
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex" style="flex-direction: column">
                                        <label for="validationServer01">{{ trans('communs.Praticien') }} :</label>
                                        <select class="js-example-basic-single col-sm-12" name="userId"
                                            onchange="getPraticien($(this).val())">
                                            <option value="0">---
                                                {{ trans('communs.Choisir le/la Praticien/ne') }}
                                                ---</option>
                                            @foreach ($employes as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->getFullNameAttribute() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex" style="align-items: flex-start;justify-content: space-between;">
                                            <div class="col-md-3 pl-0">
                                                <label for="validationServer02">{{ trans('communs.Prix') }}</label>
                                                <input class="form-control prices" disabled value="0" id="price_1"
                                                    type="text" name="price[]" data-original-title=""
                                                    title="{{ trans('communs.Prix') }}">
                                            </div>
                                            <div class="col-md-4 d-flex">
                                                <div class="d-flex mr-3 mb-3" style="flex-direction: column">
                                                    <label
                                                        for="validationServer02">{{ trans('communs.Quantité') }}</label>
                                                    <input class="form-control qts" value="1" id="qte_1"
                                                        name="quantity[]" min="1" required
                                                        onchange="changeTotalOnce()" data-original-title=""
                                                        title="{{ trans('communs.Quantité') }}">
                                                </div>
                                                <div class="d-flex" style="flex-direction: column ;justify-content :flex-end;">
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;">+</span>
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,-1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;margin-top:10px;">-</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="validationServer02">{{ trans('communs.Ajouter') }}</label>
                                                <button class="form-control btn btn-primary" type="button"
                                                    style="height: 37px!important;display: flex;align-items: center;justify-content: center;"
                                                    onclick="addProductRow('{{ $type }}')"
                                                    title="{{ trans('communs.Ajouter Produit/Service') }}">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-6 d-flex" style="flex-direction: column">
                                        <label for="validationServer01">{{ trans('communs.Produit') }} :</label>
                                        <select onchange="setPriceProduct(this, this.value,1)"
                                            class="js-example-basic-single col-sm-12" name="products[]">
                                            <option data-tokens="0">--- {{ trans('communs.Choisir Produit/Service') }} ---
                                            </option>
                                            @foreach ($products as $item)
                                                <option data-tokens="{{ $item->label }}" value="{{ $item->id }}">
                                                    {{ $item->label }} | {{ $item->price }} Mad
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex"
                                            style="align-items: flex-start;justify-content: space-between;">
                                            <div class="col-md-3 pl-0">
                                                <label for="validationServer02">{{ trans('communs.Prix') }}</label>
                                                <input class="form-control prices" disabled value="0" id="price_1"
                                                    type="text" name="price[]" data-original-title=""
                                                    title="{{ trans('communs.Prix') }}">
                                            </div>
                                            <div class="col-md-4 d-flex">
                                                <div class="d-flex mr-3 mb-3" style="flex-direction: column">
                                                    <label
                                                        for="validationServer02">{{ trans('communs.Quantité') }}</label>
                                                    <input class="form-control qts" value="1" id="qte_1"
                                                        name="quantity[]" min="1" required
                                                        onchange="changeTotalOnce()" data-original-title=""
                                                        title="{{ trans('communs.Quantité') }}">
                                                </div>
                                                <div class="d-flex"
                                                    style="flex-direction: column ;justify-content :flex-end;">
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;">+</span>
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,-1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;margin-top:10px;">-</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="validationServer02">{{ trans('communs.Ajouter') }}</label>
                                                <button class="form-control btn btn-primary" type="button"
                                                    style="height: 37px!important;display: flex;align-items: center;justify-content: center;"
                                                    onclick="addProductRow('{{ $type }}')"
                                                    title="{{ trans('communs.Ajouter Produit/Service') }}">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <br>
                            <div id="products-row">
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.Description') }} :</label>
                                    <textarea class="form-control " id="validationServer02" type="text" name="description"
                                        title="{{ trans('communs.Description') }}"></textarea>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="validationServer02">{{ trans('communs.Mode de Paiement') }}</label>
                                        <div class="custom-radio-ml">
                                            <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="radio1" type="radio"
                                                    name="payment_method" value="1" checked="">
                                                <label class="form-check-label"
                                                    for="radio1">{{ trans('communs.Espèces') }}<span
                                                        class="digits"></span></label>
                                            </div>
                                            <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="radio3" type="radio"
                                                    name="payment_method" value="2">
                                                <label class="form-check-label"
                                                    for="radio3">{{ trans('communs.Chèque') }}<span
                                                        class="digits"></span></label>
                                            </div>
                                            <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="radio4" type="radio"
                                                    name="payment_method" value="3">
                                                <label class="form-check-label"
                                                    for="radio4">{{ trans('communs.Carte Bancaire') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                {{-- @if (auth()->user()->store->praticien == 1)
                                    <div class="col-md-6">
                                        <select class="js-example-basic-single col-sm-12" name="userId"
                                            onchange="getPraticien($(this).val())">
                                            <option value="0">--- {{ trans('communs.Choisir le/la Praticien/ne') }}
                                                ---</option>
                                            @foreach ($employes as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->getFullNameAttribute() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif --}}
                                <div class="col-md-6">
                                    <label class="mt-0" for="inline-sqr-1">{{ trans('communs.Utiliser la reduction') }}
                                        ? <span id="message_using_points"
                                            style="color:red;display:none">{{ trans('Action non Authorisé!') }}</span></label>
                                    <div class="form-check radio radio-primary" id="use_points_true">
                                        <input class="form-check-input" id="radio5"="useRed($(this).val())"
                                            type="radio" name="use_points" value="1">
                                        <label class="form-check-label" for="radio5">{{ trans('communs.Oui') }}<span
                                                class="digits"></span></label>
                                    </div>
                                    <div class="form-check radio radio-primary">
                                        <input class="form-check-input" id="radio6" onclick="useRed($(this).val())"
                                            type="radio" name="use_points" value="0" checked="">
                                        <label class="form-check-label" for="radio6">{{ trans('communs.Non') }}<span
                                                class="digits"></span></label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="form-control " id="code_red" type="text" name="code_red"
                                        value="" placeholder="{{ trans('communs.CODE') }}" data-original-title=""
                                        title="{{ trans('communs.CODE REDUCTION') }}">
                                    <input type="hidden" value="0" class="form-control " id="code_valide"
                                        type="text" name="code_valide" value="" data-original-title=""
                                        title="{{ trans('communs.CODE REDUCTION') }}">
                                    <div>
                                        <span style="color:red" id="code_red_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="button" placeholder="{{ trans('communs.valider') }}"
                                        class="btn btn-primary form-control" style="width:115px;"
                                        onclick="calculateRed($('#code_red').val(),$('#customers').val(),{{ auth()->user()->store->id }})"
                                        value="{{ __('Valider') }}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.Total HT') }}</label>
                                    <input readonly class="form-control " id="total_ht" required type="text"
                                        name="total_ht" value="0" data-original-title=""
                                        title="{{ trans('communs.Total Ht') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.TVA') }}</label>
                                    <input readonly class="form-control" value="{{ auth()->user()->store->tva }}%"
                                        title="{{ trans('communs.TVA') }}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.Total TTC') }}</label>
                                    <input readonly class="form-control" name="total" id="total" type="text"
                                        value="0" title="{{ trans('communs.TOTAL TTC') }}">
                                </div>
                                @if (auth()->user()->store->reste == 0)
                                    <div class="col-md-6">
                                        <label for="validationServer02">{{ trans('communs.Montant Payé') }}</label>
                                        <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                            value="0" data-original-title=""
                                            title="{{ trans('communs.Montant Payé') }}">
                                    </div>
                                @else
                                    <div class="col-md-3">
                                        <label for="validationServer02">{{ trans('communs.Montant Payé') }}</label>
                                        <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                            value="0" data-original-title=""
                                            title="{{ trans('communs.Montant Payé') }}">
                                    </div>
                                    <div class="col-md-3"
                                        style="display: flex; position: relative; flex-direction: column;">
                                        <label for="validationServer02"
                                            style="left: 25px; position: relative;">{{ trans('communs.Reste') }}</label>
                                        <div class="check" style="display: flex; gap:10px;">
                                            <input type="checkbox" id="fluency" name="ResteCheck">
                                            <input readonly class="form-control " id="Reste" type="text"
                                                name="Reste" value="0" title="{{ trans('communs.Reste') }}">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <br>
                            <input id="total_hidden" type="hidden" value="0">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit" data-original-title=""
                                        title="{{ trans('communs.Enregistrer') }}">{{ trans('communs.Enregistrer') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<div id="modal-owner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Nouveau Client') }}</h4>
                <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div id="customersErrors">

                </div>
                {{-- <form> --}}
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationServer01">{{ trans('Nom') }}</label>
                        <input class="form-control" id="firstname" type="text" name="firstname"
                            value="{{ old('firstname') }}" data-original-title="" title="{{ trans('Prénom') }}">
                        @if ($errors->has('firstname'))
                            <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationServer02">{{ trans('Prénom') }}</label>
                        <input class="form-control " id="lastname" type="text" name="lastname"
                            value="{{ old('lastname') }}" data-original-title="" title="{{ trans('Nom') }}">
                        @if ($errors->has('lastname'))
                            <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationServer01">{{ trans('E-mail') }}</label>
                        <input class="form-control" id="email" type="email" name="email"
                            value="{{ old('email') }}" data-original-title="" title="{{ trans('E-mail') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationServer02">{{ trans('Téléphone') }}</label>
                        <input class="form-control" id="phone" type="text" name="phone"
                            value="212{{ old('phone') }}" required="" data-original-title=""
                            title="{{ trans('Téléphone') }}">
                        @if ($errors->has('phone'))
                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationServer01">{{ trans('Date de Naissance') }}</label>
                        <input id="birth" class="form-control" type="date" name="birth"
                            data-language="en">
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('birth') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationServer01">{{ trans('Sexe') }}</label>
                        <select class="form-control digits" name="sexe" id="sexe">
                            <option>--- {{ trans('Choisir Sexe') }} --</option>
                            <option value="0">{{ trans('Homme') }}</option>
                            <option value="1">{{ trans('Femme') }}</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" onclick="createCustomer()"
                    title="{{ trans('Enregistrer') }}">{{ trans('Enregistrer') }}</button>
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>


@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/custom.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
    <script>
        var type = @json($type);
        const form = document.getElementById('MyForm');

        function addProductRow(type) {
            type == "praticien_1" ? url = '/employe/invoices/products/1' : url = '/employe/invoices/products/2'
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#products-row').append(data.html)
                }
            })
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const userData = collectUserData();
            const formData = new FormData(form);
            if (type = "praticien_2") {
                formData.append('userData', JSON.stringify(userData));
                formData.append('type', JSON.stringify(type));
            }

            $.ajax({
                url: "{{ route('employe.invoices.store') }}",
                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(data) {
                    localStorage.setItem('printUrl', JSON.stringify(data));
                    window.location.href = 'http://127.0.0.1:8000/employe/invoices';
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        function collectUserData() {
            const produitPraicien = document.querySelectorAll("#produitPraicien");
            const userData = [];

            produitPraicien.forEach((div) => {
                const productSelect = div.querySelector('select[name="products[]"]');
                const userSelect = div.querySelector('select[name="userId"]');
                const productQuantity = div.querySelector('input[name="quantity[]"]');
                const productId = productSelect.value;
                const userId = userSelect.value;
                const Quantity = productQuantity.value;

                if (productId !== "0" && userId !== "0") {
                    userData.push({
                        userId: userId,
                        product: productId,
                        quantity: Quantity
                    });
                }
            });
            return userData;
        }


        function removeProductRow(id) {
            var div = document.getElementById(id);
            div.remove();
            var prices = [];
            var qts = [];
            var total = 0;

            $(".prices").each(function(index) {
                prices.push($(this).val())
            });
            $('.qts').each(function(qte) {
                qts.push($(this).val());
            });
            Object.entries(prices).forEach(([key, value]) => {
                Object.entries(qts).forEach(([keyQts, valueQts]) => {
                    if (key == keyQts) {
                        console.log('ha qte ' + valueQts)
                        console.log('ha price dialha ' + value)
                        total = total + (value * valueQts);

                    }
                })

            });
            var newValue = (total);
            $('#total').attr('value', newValue);
            $('#total_hidden').attr('value', newValue);
            $('#total_hidden_ttc').attr((total + (total + (total * "{{ auth()->user()->store->tva }}" / 100))))
            $('#total_ht').attr('value', total);
            $('#to_paye').attr('value', (total + (total * "{{ auth()->user()->store->tva }}" / 100)))
        }

        function setPriceProduct(element, id, price_id) {
            console.log(id, element, price_id, 'setPrice to product');
            $.ajax({
                url: "{{ route('employe.productPrice') }}",
                data: {
                    id: id,
                    productId: id,
                },
                type: 'GET',
                success: function(data) {
                    $("#price_" + price_id).val(data.price);
                    changeTotalOnce();
                    changeTotalOnceHT();
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }

        function changeTotalOnce() {
            var prices = [];
            var qts = [];
            var total = 0;

            $(".prices").each(function(index) {
                prices.push($(this).val())
            });
            $('.qts').each(function(qte) {
                qts.push($(this).val());
            });
            Object.entries(prices).forEach(([key, value]) => {
                Object.entries(qts).forEach(([keyQts, valueQts]) => {
                    if (key == keyQts) {
                        total = total + (value * valueQts);
                    }
                })

            });
            changeTotalOnceHT();
            $('#total').attr('value', (total + (total * "{{ auth()->user()->store->tva }}" / 100)))
            $('#total_hidden').attr('value', (total))
            $('#total_hidden_ttc').attr((total + (total * "{{ auth()->user()->store->tva }}" / 100)))
            $('#to_paye').attr('value', (total + (total * "{{ auth()->user()->store->tva }}" / 100)))
        }

        function changeTotalOnceHT() {

            var prices = [];
            var qts = [];
            var total = 0;

            $(".prices").each(function(index) {
                prices.push($(this).val())
            });
            $('.qts').each(function(qte) {
                qts.push($(this).val());
            });
            Object.entries(prices).forEach(([key, value]) => {
                Object.entries(qts).forEach(([keyQts, valueQts]) => {
                    if (key == keyQts) {
                        total = total + (value * valueQts);
                    }
                })

            });
            $('#total_ht').attr('value', total)
        }

        function createCustomer() {
            var firstname = $('#firstname').val();
            var lastname = $('#lastname').val();
            var phone = $('#phone').val();
            var sexe = $('#sexe').val();
            var email = $('#email').val();
            var birth = $('#birth').val();
            var user_id = "{{ auth()->user()->id }}";
            var store_id = "{{ auth()->user()->store->id }}";
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('employe.customers.store') }}",
                type: 'POST',
                data: {
                    type: 1,
                    firstname: firstname,
                    lastname: lastname,
                    phone: phone,
                    sexe: sexe,
                    email: email,
                    birth: birth,
                    user_id: user_id,
                    store_id: store_id,
                    _token: _token
                },
                success: function(data) {
                    $('#customers').append(data);
                    $('#close').click();
                },
                error: function(response) {
                    console.log(messgae)
                    let message = '<span style="color:red">Le N° de Téléphone est invalide</span>';
                    $('#customersErrors').html(message); //
                }
            })
        }

        function getClientPoints(customer_id) {
            $.ajax({
                url: "{{ route('employe.customers.points') }}",
                type: 'GET',
                data: {
                    customer_id: customer_id
                },
                success: function(data) {
                    console.log('reste cr inov', data);
                    $('#mad_red').html(data['mad_red']);
                    $('#points_red').html(data['points_red']);
                    // $('#Reste').attr('value',data['reste']);

                    $('#mad_red_hidden').attr('value', data['mad_red']);
                    if (data['points_red'] <= 0) {
                        $('#use_points_true').css('display', 'none');
                        $('#message_using_points').css('display', 'block')
                    } else {
                        $('#use_points_true').css('display', 'block');
                        $('#message_using_points').css('display', 'none')

                    }
                    // if (data['reste'] > 0) {
                    //     $('#champ_reste').css('color', '#d35959');
                    // }
                }
            })
            getReset(customer_id)
        }

        function getReset(customer_id) {
            $.ajax({
                url: "{{ route('employe.invoices.reste') }}",
                type: 'GET',
                data: {
                    customer_id: customer_id
                },
                success: function(data) {
                    console.log('reste cr inov', data);

                    $('#Reste').attr('value', data['reste']);

                    if (data['reste'] > 0) {
                        $('#champ_reste').css('color', '#d35959');
                    }
                }
            })
        }
        function restePayer() {
            var customer_id = $('#customers').val()
            var reste = $('#Reste').val();
            console.log('customer_id : ', customer_id)
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('employe.invoices.restePayer') }}",
                type: 'POST',
                data: {
                    reste: reste,
                    customer_id: customer_id,
                    _token: _token
                },
                success: function(data) {
                    let message = '<span style="color:red">Reste est payer</span>';
                    $('#validatMsg').html(message); //
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function useRed(value) {
            if ($('#customers').val() != 0) {
                changeTotalOnce();
                changeTotalOnceHT();
                if (value == 1) {
                    var totalHtWithRed = $("#total_ht").val() - $('#mad_red_hidden').val();
                    $('#total_ht').attr('value', $("#total_ht").val() - $('#mad_red_hidden').val());
                    //Recalculat TTC 

                    $('#total').attr('value', (Math.ceil(totalHtWithRed * (1 + ("{{ auth()->user()->store->tva }}" /
                        100)))));
                    $('#to_paye').attr('value', (Math.ceil(totalHtWithRed * (1 + ("{{ auth()->user()->store->tva }}" /
                        100)))));
                } else {
                    var total = $('#total_hidden').val();
                    $('#total_ht').attr('value', total);

                }
            } else {
                alert('vous devez choisir Un client ')
            }

        }

        function changeQuantity(id, op) {
            var currentQte = $('#qte_' + id).val();
            if (typeof currentQte === 'undefined') {
                currentQte = 0;
            }
            if (op == 1) {
                $('#qte_' + id).attr('value', (parseInt(currentQte) + 1));
            } else if (op == -1) {
                if ((parseInt(currentQte) - 1) > 0) {
                    $('#qte_' + id).attr('value', (parseInt(currentQte) - 1));
                }
            }
            changeTotalOnce();
        }

        function calculateRed(code, customer_id, store_id) {
            if (code != '' || customer != undefined) {
                $.ajax({
                    url: "{{ url('employe/customer/code/red') }}",
                    data: {
                        code: code,
                        customer_id: customer_id,
                        store_id: store_id
                    },
                    type: 'GET',
                    success: function(data) {
                        if (data.success == 1) {
                            console.log(data.code);
                            var total = parseFloat($('#total_ht').val());
                            var red_value = data.code;
                            var newTotal = total - ((total * red_value) / 100);
                            console.log('hahwa ' + newTotal);
                            $('#total_ht').attr('value', newTotal)
                            $('#total').attr('value', newTotal + newTotal * (
                                "{{ auth()->user()->store->tva }}"))
                            $('#to_paye').attr('value', newTotal + newTotal * (
                                "{{ auth()->user()->store->tva }}"))
                            $('#code_valide').attr('value', '1');
                        } else {
                            $('#code_red_error').html(data.message)
                        }
                    }

                })
            } else {
                $('#code_red_error').html('Veuillez choisir un client et un code valide !')
            }
        }
        var win = window.open(url, '_blank');
        win.onload = function() {
            win.print();
        };
    </script>
@endsection
