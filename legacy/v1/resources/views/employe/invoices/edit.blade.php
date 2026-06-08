@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-left">{{ auth()->user()->store->name }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('employe.invoices') }}">{{ trans('communs.Factures') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('communs.Modification') }}</li>
@endsection

{{-- <div class="modal fade" id="exampleModalCenter-message"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('Erreur') }}</h5>
<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    {{trans('Choisissez au moins un produit pour effectuer une modification')}} !
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="button" data-dismiss="modal">{{ trans('Fermer') }}</button>
</div>
</div>
</div>
</div> --}}




@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <input type="hidden" name="praticientType" id="praticientType" value="{{ $type }}">
                        <h3> {{ trans('communs.Modification Facture') }} : {{ date('Y-m-d H:i:s') }} </h3>
                    </div>
                    <div class="card-body">
                        <form id="edit-form" action="{{ route('employe.invoices.update', $invoice->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>{{ trans('communs.Reduction en Points') }}</strong> : <span
                                        id="points_red">{{ $customer->points * auth()->user()->store->coeff }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>{{ trans('communs.Reduction en MAD') }}</strong> : <span
                                        id="mad_red">{{ $customer->points }}</span>
                                    <input type="hidden" id="mad_red_hidden">
                                </div>
                                <div class="col-md-4">
                                    <strong>{{ trans('communs.Reste') }}</strong> : <span
                                        id="reste">{{ $invoice->reste }}</span>
                                    <input type="hidden" id="mad_red_hidden">

                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Home</strong> : <input type="radio" value="1" name="for"
                                        @if ($invoice->for_sexe == 1) checked @endif>
                                </div>
                                <div class="col-md-4">
                                    <strong>Femme</strong> : <input type="radio" value="2" name="for"
                                        @if ($invoice->for_sexe == 2) checked @endif>
                                </div>
                                <div class="col-md-4">
                                    <strong>Mix</strong> : <input type="radio" value="3" name="for"
                                        @if ($invoice->for_sexe == 3) checked @endif>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="validationServer01">{{ trans('communs.Informations du Client') }}</label>
                                    <select id="customers" class="js-example-basic-single col-sm-12"
                                        onchange="getClientPoints($(this).val())" required name="customer">
                                        <option value="0">--- {{ trans('communs.Choisir un client') }} ---</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $customer->id == $item->id ? 'selected' : '' }}>{{ $item->firstname }}
                                                {{ $item->lastname }} : {{ $item->phone }} </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="validationServer02">{{ trans('communs.Nouveau Client') }}</label>
                                    <button class="btn btn-info" type="button" data-toggle="modal"
                                        data-target=".bd-example-modal-lg"
                                        title="{{ trans('communs.Nouveau Client') }}">{{ trans('communs.Nouveau') }}</button>
                                </div>
                            </div>

                            <br>
                            <div class="row">

                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <label for="validationServer02">{{ trans('communs.Ajouter P/S') }}</label>
                                    <button class="form-control btn btn-primary " type="button" onclick="addProductRow()"
                                        title="{{ trans('communs.Ajouter Produit') }}">+</button>
                                </div>
                            </div>
                            --------------------------------------------------------------------------------------------------------------
                            @foreach ($invoiceProducts as $item)
                                <?php $id = uniqid(); ?>
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
                                        <div class="col-md-5">
                                            <div class="d-flex"
                                                style="align-items: flex-start;justify-content: space-between;">
                                                <div class="col-md-3 pl-0">
                                                    <label for="validationServer02">{{ trans('communs.Prix') }}</label>
                                                    <input class="form-control prices" value="{{ $item->price }}"id="price_{{ $id }}" type="text" name="price[]"
                                                         data-original-title="" title="{{ trans('communs.Prix') }}">
                                                </div>
                                                <div class="col-md-4 d-flex">
                                                    <div class="d-flex mr-3 mb-3" style="flex-direction: column">
                                                        <label for="validationServer02">{{ trans('communs.Quantité') }}</label>
                                                        <input class="form-control qts" value="{{ $item->qte }}" id="qte_{{ $id }}" name="quantity[]"
                                                            min="1" required onchange="changeTotalOnce()"data-original-title=""title="{{ trans('communs.Quantité') }}">
                                                    </div>
                                                    <div class="d-flex" style="flex-direction: column ;justify-content :flex-end;">
                                                        <span class="btn btn-primary" onclick="changeQuantity('{{ $id }}',1)"
                                                            style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;">+</span>
                                                        <span class="btn btn-primary" onclick="changeQuantity('{{ $id }}',-1)"
                                                            style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;margin-top:10px;">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationServer02">{{ trans('communs.Supprimer P/S') }}</label>
                                                    <button class="form-control btn btn-primary " type="button"
                                                        onclick="removeProductRow('{{ $id }}','{{ $item->price }}',$('#qte_{{ $id }}').val(),'{{ auth()->user()->store->tva }}')"
                                                        title="{{ trans('communs.Supprimer Produit') }}">-</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row" id="{{ $id }}">
                                        <div class="col-md-6">
                                            <label for="validationServer01">{{ $item->product->label }} |{{ $item->product->price }} Mad </label>
                                            <input type="hidden" name="products[]" value="{{ $item->product_id }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServer02">{{ trans('communs.Prix') }}</label>
                                            <input class="form-control prices" value="{{ $item->price }}"id="price_{{ $id }}" type="text" name="price[]"
                                                data-original-title="" title="{{ trans('communs.Prix') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <div class="col-md-8">
                                                <label for="validationServer02">{{ trans('communs.Quantité') }}</label>
                                                <input class="form-control qts" value="{{ $item->qte }}"id="qte_{{ $id }}" name="quantity[]" min="1"
                                                    required onchange="changeTotalOnce()" data-original-title=""title="{{ trans('communs.Quantité') }}">
                                            </div>
                                            <div class="col-md-4 " style="margin-left: 68px!important;margin-top: -47px;">
                                                <span class="btn btn-primary mb-2"onclick="changeQuantity('{{ $id }}',1)"
                                                    style="width: 5px!important;height:25px!important">+</span>
                                                <span class="btn btn-primary"onclick="changeQuantity('{{ $id }}',-1)"
                                                    style="width: 5px!important;height:25px!important">-</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServer02">{{ trans('communs.Supprimer P/S') }}</label>
                                            <button class="form-control btn btn-primary " type="button"
                                                onclick="removeProductRow('{{ $id }}','{{ $item->price }}',$('#qte_{{ $id }}').val(),'{{ auth()->user()->store->tva }}')"
                                                title="{{ trans('communs.Supprimer Produit') }}">-</button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            --------------------------------------------------------------------------------------------------------------
                            <div id="products-row">

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.Description') }} :</label>
                                    <textarea class="form-control " id="validationServer02" type="text" name="description"
                                        title="{{ trans('communs.Description') }}">{{ $invoice->description }}</textarea>
                                </div>
                                <div class="col-md-6 ">
                                    <br>
                                    <div class="form-group">
                                        <div class="col">
                                            <label
                                                for="validationServer02">{{ trans('communs.Mode de Paiement') }}</label>
                                            <div class="mb-3 m-t-15 custom-radio-ml">
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="radio1" type="radio"
                                                        name="payment_method" value="1"
                                                        @if ($invoice->payment_method == 1) checked @endif>
                                                    <label class="form-check-label"
                                                        for="radio1">{{ trans('communs.Espèces') }}<span
                                                            class="digits"></span></label>
                                                </div>
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="radio3" type="radio"
                                                        name="payment_method" value="2"
                                                        @if ($invoice->payment_method == 2) checked @endif>
                                                    <label class="form-check-label"
                                                        for="radio3">{{ trans('communs.Chèque') }}<span
                                                            class="digits"></span></label>
                                                </div>

                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="radio4" type="radio"
                                                        name="payment_method" value="3"
                                                        @if ($invoice->payment_method == 3) checked @endif>
                                                    <label class="form-check-label"
                                                        for="radio4">{{ trans('communs.Carte Bancaire') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--there is no need to add the provider -->
                                @if (in_array(auth()->user()->store_id, [19]))
                                    <div class="col-md-6">
                                        <label for="validationServer02">{{ trans('communs.Effectué Par') }}:</label>
                                        <select id="employers" class="js-example-basic-single col-sm-12" required
                                            name="employ_id">
                                            <option value="0">--- {{ trans('communs.Choisir le/la Praticien/ne') }}
                                                ---</option>
                                            @foreach ($employes as $item)
                                                <option value="{{ $item->id }}"
                                                    @if ($item->id == $invoice->employ_id) selected @endif>
                                                    {{ $item->firstname . ' ' . $item->lastname }} : {{ $item->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-1"></div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.Total HT') }}</label>
                                    <input readonly class="form-control " id="total_ht" required type="text"
                                        name="total_ht" value="{{ $invoice->total_ht }}" data-original-title=""
                                        title="{{ trans('communs.Montant Total Hors Taxes') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.TVA') }}</label>
                                    <input readonly class="form-control" value="{{ $invoice->tva }}%"
                                        title="{{ trans('communs.TVA') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="validationServer02">{{ trans('communs.Total TTC') }}</label>
                                    <input readonly class="form-control" name="total" id="total" type="text"
                                        value="{{ $invoice->total }}" title="{{ trans('communs.TOTAL TTC') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="validationServer02">{{ trans('communs.Montant Payé') }}</label>
                                    <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                        value="{{ $invoice->paid_amount }}" data-original-title=""
                                        title="{{ trans('communs.Montant Payé') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="validationServer02">{{ trans('communs.Reste') }}</label>
                                    <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                        value="{{ $invoice->reste }}" data-original-title=""
                                        title="{{ trans('communs.Montant Payé') }}">
                                </div>

                            </div>
                            <br><br>

                            <input id="total_hidden" type="hidden" value="0">

                        </form>
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" onclick="submit()" data-original-title=""
                                    title="{{ trans('communs.Modifier') }}">{{ trans('communs.Modifier') }}</button>
                            </div>
                        </div>
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
                <h4 class="modal-title" id="myLargeModalLabel">{{ trans('communs.Nouveau Client') }}</h4>
                <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{ trans('communs.Nom') }}</label>
                            <input class="form-control" id="firstname" type="text" name="firstname"
                                value="{{ old('firstname') }}" data-original-title=""
                                title="{{ trans('communs.Nom') }}">
                            @if ($errors->has('firstname'))
                                <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{ trans('communs.Prénom') }}</label>
                            <input class="form-control " id="lastname" type="text" name="lastname"
                                value="{{ old('lastname') }}" data-original-title=""
                                title="{{ trans('communs.Prénom') }}">
                            @if ($errors->has('lastname'))
                                <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{ trans('communs.E-mail') }}</label>
                            <input class="form-control" id="email" type="text" name="email"
                                value="{{ old('email') }}" data-original-title=""
                                title="{{ trans('communs.E-mail') }}">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{ trans('communs.Téléphone') }}</label>
                            <input class="form-control" id="phone" type="text" name="phone"
                                value="212{{ old('phone') }}" required="" data-original-title=""
                                title="{{ trans('communs.Téléphone') }}">
                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{ trans('communs.Date de Naissance') }}</label>
                            <input id="birth" class="form-control" type="date" name="birth"
                                data-language="en">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('birth') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{ trans('communs.Sexe') }}</label>
                            <select class="form-control digits" name="sexe" id="sexe">
                                <option>-- {{ trans('communs.Choisir Sexe') }} --</option>
                                <option value="1">{{ trans('communs.Homme') }}</option>
                                <option value="0">{{ trans('communs.Femme') }}</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="createCustomer()"
                        title="{{ trans('communs.Enregistrer') }}">{{ trans('communs.Enregistrer') }}</button>
                </form>
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
        var type = document.getElementById('praticientType').value()

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

            $.ajax({
                url: "{{ route('employe.productPrice') }}",
                data: {
                    id: id
                },
                type: 'GET',
                success: function(data) {
                    $("#price_" + price_id).val(data);
                    changeTotalOnce();
                    changeTotalOnceHT();
                },
                error: function(error) {
                    console.log(error)
                }

            });
        }

        function submit() {
            var prices = [];
            var count_product = 0;


            $(".prices").each(function(index) {
                prices.push($(this).val())
                count_product++;
            });

            if (count_product > 0) {
                $("#edit-form").submit();
            } else {
                $('#exampleModalCenter-message').modal('show')
            }


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
                error: function(error) {
                    console.log(error)
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
                    $('#mad_red').html(data['mad_red']);
                    $('#points_red').html(data['points_red']);
                    $('#reste').html(data['reste']);
                    $('#mad_red_hidden').attr('value', data['mad_red']);
                    console.log('reste', data);
                },
                error: function(error) {
                    console.log(error)
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
    </script>
@endsection
<style>
    .qts {
        width: 52px !important;
    }
</style>
