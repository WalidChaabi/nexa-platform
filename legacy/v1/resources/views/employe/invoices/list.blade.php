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
    {{-- <h3 class="text-center">{{ auth()->user()->stores->name }}</h3> --}}
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('communs.Factures') }}</li>
@endsection

@section('content')

    @if (isset($printUrl))
        <script>
            var printUrl = '{{ $printUrl }}';
            var win = window.open(printUrl + '?type=individual', '_blank');
            win.print();
        </script>
    @else
        <script>
            const storedPrintUrl = JSON.parse(localStorage.getItem('printUrl'));
            if (storedPrintUrl) {
                var win = window.open(storedPrintUrl + '?type=individual', '_blank');
                win.print();
                localStorage.setItem('printUrl','');
            }
        </script>
    @endif
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">

                        <form id="" action="{{ route('employe.invoices') }}" method="GET">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    @if (auth()->user()->store->POS == 1)
                                        <a href="{{ route('employe.table.index') }}" class="btn btn-info" type="button"
                                            title="{{ trans('communs.Nouvelle Facture') }}">{{ trans('communs.Nouveau') }}</a>
                                    @else
                                        <a href="{{ route('employe.invoices.create') }}" class="btn btn-info"
                                            type="button"
                                            title="{{ trans('communs.Nouvelle Facture') }}">{{ trans('communs.Nouveau') }}</a>
                                    @endif

                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="start_date"
                                        value="{{ isset($_GET['start_date']) ? $_GET['start_date'] : '' }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="end_date"
                                        value="{{ isset($_GET['end_date']) ? $_GET['end_date'] : '' }}"
                                        class="form-control">
                                </div>
                                @if (auth()->user()->store->id)
                                    <div class="col-md-2">
                                        <select type="date" name="sexe" class="form-control">
                                            <option value="0">{{ trans('Choisir le sexe') }}</option>
                                            @if (isset($_GET['sexe']))
                                                <option value="1" @if ($_GET['sexe'] == 1) selected @endif>
                                                    Homme </option>
                                                <option value="2" @if ($_GET['sexe'] == 2) selected @endif>
                                                    Femme </option>
                                                <option value="3" @if ($_GET['sexe'] == 3) selected @endif>
                                                    Mix </option>
                                            @else
                                                <option value="1">Homme</option>
                                                <option value="2">Femme</option>
                                                <option value="3">Mix</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-2">
                                    <button type="submit"
                                        class="form-control btn btn-primary">{{ trans('Rechercher') }}</button>
                                </div>
                            </div>
                        </form>
                        @if (auth()->user()->store->RA == 1)
                            <form id="" action="{{ route('employe.invoices.recherche') }}" method="GET">
                                @csrf
                                <div class="row" style="margin-top: 30px">
                                    <div class="col-sm-4">
                                        <input style="border: 2 solid blue" class="form-control " id="to_paye"
                                            type="text" name="recherche_avencee" placeholder="+212 6 XX XX XX XX"
                                            title="{{ trans('communs.Recherche avancée') }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <input style="border: 2 solid blue" class="form-control" id="name_rechercher"
                                            type="text" name="name_rechercher" placeholder=""
                                            title="{{ trans('communs.Recherche avancée') }}">
                                    </div>
                                    <div class="col-sm-6 d-flex" style="align-items: flex-end;justify-contetn:center;">
                                        <button type="submit" class="form-control btn btn-primary"
                                            style="width:190px; background: #a927f9 !important;">{{ trans('Recherche avancée') }}</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="card-body row">

                        <div class="table-responsive">
                            <table class="display" id="render-datatable">
                                <thead>
                                    <tr>
                                        <th>{{ trans('dashboard.Client') }}</th>
                                        <th>{{ trans('dashboard.Date') }}</th>
                                        <th>{{ trans('communs.Téléphone') }}</th>
                                        {{-- <th>{{ trans('communs.Total HT') }}</th> --}}
                                        <th>{{ trans('communs.Total TTC') }}</th>
                                        <th>{{ trans('B.R') }}</th>
                                        <th>{{ trans('C.R') }}</th>
                                        @if (auth()->user()->store->reste == 1)
                                            <th>{{ trans('communs.Reste') }}</th>
                                            <th>{{ trans('communs.Reste_payer') }}</th>
                                        @endif
                                        <th class="text-center">{{ trans('communs.Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $item)
                                        <tr
                                            style="{{ $item->status == 4 || $item->status == 2 ? 'background-color: ' . getInvoiceColorByStatus($item->status) : '' }}">
                                            <td>{{ $item->customer->getFullNameAttribute() }}</td>
                                            <td>{{ $item->invoice_date }}</td>
                                            <td>{{ $item->customer->phone }}</td>
                                            {{-- <td>{{ $item->total_ht }}</td> --}}
                                            <td>{{ $item->paid_amount }}</td>
                                            <td>{{ $item->points > 0 ? 'Oui' : 'Non' }}</td>
                                            <td>{{ !empty($item->campaign) ? 'Oui' : 'Non' }}</td>
                                            @if (auth()->user()->store->reste == 1)
                                                <td>{{ $item->reste }}</td>
                                                <td>{{ $item->reste_payer }}</td>
                                            @endif
                                            <td>
                                                @if ($item->status == 0)
                                                    <button class="btn" style="background-color: #ea2087"
                                                        onclick="printInvoice('{{ $item->id }}')">
                                                        <i class="fa fa-print"></i>
                                                    </button>
                                                    @if (auth()->user()->is_admin == 3)
                                                        <a class="btn" style="background-color: #7366ff"
                                                            data-toggle="modal"
                                                            data-target="#exampleModalCenter-{{ $item->id }}"><i
                                                                class="fa fa-trash-o"></i></a>
                                                        <a class="btn" data-toggle="modal"
                                                            data-target="#modalInvoice-{{ $item->id }}"
                                                            style="background-color: #ea2087"><i class="fa fa-eye"></i></a>
                                                        <a class="btn" data-toggle="modal"
                                                            data-target="#modalReturn-{{ $item->id }}"
                                                            style="background-color: #a927f9" href=""><i
                                                                class="fa fa-reply" aria-hidden="true"></i></a>
                                                    @endif
                                                @elseif($item->status == 2 || $item->status == 4)
                                                    <a class="btn" style="background-color: #ea2087"
                                                        href="{{ route('employe.invoices.print', $item->id) }}"><i
                                                            class="fa fa-print"></i></a>
                                                    <a class="btn" style="background-color: #a927f9"
                                                        href="{{ route('employe.invoices.show', $item->id) }}"><i
                                                            class="fa fa-eye"></i></a>
                                                @endif
                                                <div class="modal fade" id="exampleModalCenter-{{ $item->id }}"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ trans('Confirmation') }}</h5>
                                                                <button class="close" type="button"
                                                                    data-dismiss="modal" aria-label="Close"><span
                                                                        aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ trans('Vous voulez vraiment supprimer la facture : #' . $item->id . ' ? ') }}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button"
                                                                    data-dismiss="modal">{{ trans('Non') }}</button>
                                                                <a href="{{ route('employe.invoices.cancel', $item->id) }}"
                                                                    class="btn btn-primary"
                                                                    type="button">{{ trans('Oui') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade bd-example-modal-lg"
                                                    id="modalReturn-{{ $item->id }}" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalCenter" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    {{ trans('Confirmation De Retour') }} </h5>
                                                                <button class="close" type="button"
                                                                    data-dismiss="modal" aria-label="Close"><span
                                                                        aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body col-md-12">

                                                                <div class="col-md-12">
                                                                    <label
                                                                        for="">{{ trans('communs.Produit Factures') }}</label>
                                                                    <select id="select_remove_{{ $item->id }}"
                                                                        class="js-example-basic-single col-sm-12"
                                                                        onchange="removeProductRetour({{ $item->id }},$(this).val())">
                                                                        <option value="0">---
                                                                            {{ trans('communs.Choisir Le produit') }} ---
                                                                        </option>
                                                                        @foreach ($item->items as $product)
                                                                            <option id="option_{{ $product->id }}"
                                                                                value="{{ json_encode($product) }}">
                                                                                {{ isset($product->product->label) ? $product->product->label : '' }}
                                                                                | {{ $product->price }} | MAD </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12 table-responsive">
                                                                    <table class="display">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{ trans('Qte') }}</th>
                                                                                <th>{{ trans('Price') }}</th>
                                                                                <th>{{ trans('Tva') }}</th>
                                                                                <th>{{ trans('Total HT') }}</th>
                                                                                <th class="text-center">
                                                                                    {{ trans('Action') }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="removeProductBody">


                                                                        </tbody>
                                                                        <tr>
                                                                            <td>Total Facture</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td id="total_initial">{{ $item->total_ht }}
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>Total Retour</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td id="totalReturn">00.00 MAD</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button"
                                                                    data-dismiss="modal">{{ trans('Non') }}</button>
                                                                <form method="POST"
                                                                    action="{{ route('employe.invoices.return') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="invoice_id"
                                                                        value="{{ $item->id }}">
                                                                    <div id="totalReturnHidden"></div>
                                                                    <button class="btn btn-primary"
                                                                        type="submit">{{ trans('Oui') }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade bd-example-modal-lg" id="modalInvoice-{{ $item->id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ trans('Detail de facture') }} </h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body col-md-12">
                                                        <div class="row">
                                                            <div class="mb-3 col-md-5">
                                                                <div class="form-group">
                                                                    <label for="full_name">{{ trans('Client') }}</label>
                                                                    <input readonly class="form-control " id="full_name"
                                                                        type="text"
                                                                        value="{{ $item->customer ? $item->customer->getFullNameAttribute() : '' }}">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 col-md-5">
                                                                <label for="date">{{ trans('Date') }}</label>
                                                                <input readonly class="form-control" id="date"
                                                                    type="text" value="{{ $item->invoice_date }}"
                                                                    title="{{ trans('Date de Facture') }}">
                                                            </div>
                                                            <div class="mb-3 col-md-2">
                                                                <div class="form-group">
                                                                    <label for="points">Reduction</label>
                                                                    <input readonly class="form-control " id="points"
                                                                        type="text" value="{{ $item->points }}">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 col-md-12">
                                                                @php
                                                                    $productNumber = 1;
                                                                @endphp
                                                                @foreach ($item->items as $el)
                                                                    <div class="row">
                                                                        <div class="col-md-5 form-group">
                                                                            <label
                                                                                for="produit">{{ trans('Produit' . ' ' . $productNumber) }}</label>
                                                                            <input readonly class="form-control "
                                                                                id="produit" type="text"
                                                                                title="{{ trans('Date de Facture') }}"
                                                                                value="{{ isset($el->product->label) ? $el->product->label : '' }}">
                                                                        </div>
                                                                        <div class="col-md-5 form-group">
                                                                            <label
                                                                                for="price">{{ trans('Prix') }}</label>
                                                                            <input readonly class="form-control "
                                                                                id="price"
                                                                                type="text"value="{{ $el->price }}">
                                                                        </div>
                                                                        <div class="col-md-2 form-group">
                                                                            <label
                                                                                for="qte">{{ trans('Qte') }}</label>
                                                                            <input readonly class="form-control "id="qte"
                                                                                type="text"
                                                                                value="{{ $el->qte }}">
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $productNumber++;
                                                                    @endphp
                                                                @endforeach
                                                            </div>

                                                            <div class="col-md-10 form-group">
                                                                <label
                                                                    for="description">{{ trans('Description') }}</label>
                                                                <textarea readonly class="form-control" name="description" id="description" rows="3">{{ $item->description }}</textarea>
                                                                {{-- <textarea readonly class="form-control "id="qte"></textarea> --}}
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                                <label for="total">{{ trans('Total') }}</label>
                                                                <input readonly class="form-control " id="total"
                                                                    type="text" value="{{ $item->total }}">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <a class="btn btn-primary"
                                                            href="{{ route('employe.invoices.edit', $item->id) }}">Modifier</a>
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">{{ trans('Fermer') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
<div id="modal-owner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Nouveau Client') }}</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employe.customers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="">{{ trans('Nom') }}</label>
                            <input class="form-control" id="" type="text" name="firstname"
                                value="{{ old('firstname') }}" data-original-title=""
                                title="{{ trans('Prénom') }}">
                            @if ($errors->has('firstname'))
                                <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="validationServer02">{{ trans('Prénom') }}</label>
                            <input class="form-control " id="validationServer02" type="text" name="lastname"
                                value="{{ old('lastname') }}" data-original-title="" title="{{ trans('Nom') }}">
                            @if ($errors->has('lastname'))
                                <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="">{{ trans('E-mail') }}</label>
                            <input class="form-control" id="" type="text" name="email"
                                value="{{ old('email') }}" data-original-title="" title="{{ trans('E-mail') }}">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="validationServer02">{{ trans('Téléphone') }}</label>
                            <input class="form-control" id="validationServer02" type="text" name="phone"
                                value="212{{ old('phone') }}" required="" data-original-title=""
                                title="{{ trans('Téléphone') }}">
                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="">{{ trans('Date de Naissance') }}</label>
                            <input class="datepicker-here form-control" type="text" name="birth"
                                data-language="en">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('birth') }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="">{{ trans('Sexe') }}</label>
                            <select class="form-control digits" name="sexe" id="store_id">
                                <option>-- {{ trans('Choisir Sexe') }} --</option>
                                <option value="1">{{ trans('Homme') }}</option>
                                <option value="0">{{ trans('Femme') }}</option>

                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" data-original-title=""
                        title="{{ trans('Enregistrer') }}">{{ trans('Enregistrer') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>



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
<script>
    function ShowItems(value, url) {
        window.location = url + "?items=" + value
    }

    function removeProductRetour(invoice_id, product) {
        var remProduct = jQuery.parseJSON(product);
        console.log(remProduct)
        $("#select_remove_" + invoice_id + " option[value='" + product + "']").remove();
        var html = "<tr>";
        var qte = "<td><input type='number' class='qte_ht' onchange='calculateTotalReturn(" + invoice_id +
            ")' value='" + remProduct.qte + "' min='0' max='" + remProduct.qte + "'></td>";
        var price = "<td> " + remProduct.price + " <input type='hidden' class='price_ht'  value='" + remProduct.price +
            "''></td>";
        var tva = "<td><input diseabled value='" + "{{ auth()->user()->store->tva }}" + "'></td>";
        //   var total = "<td><input readOnly class='total_ht' value='"+( remProduct.qte * remProduct.price )+"'></td>";
        var html = html + qte + price + tva + "</tr>";

        $('#modalReturn-' + invoice_id).find('#removeProductBody').append(html);


        calculateTotalReturn(invoice_id);
    }

    function calculateTotalReturn(invoice_id) {
        var prices = [];
        var qts = [];
        var total = 0;
        var tva = "{{ auth()->user()->store->tva / 100 }}";
        $('#modalReturn-' + invoice_id).find(".qte_ht").each(function(index) {
            qts.push($(this).val())
        });

        $('#modalReturn-' + invoice_id).find(".price_ht").each(function(index) {
            prices.push($(this).val())
        });
        Object.entries(prices).forEach(([key, value]) => {
            Object.entries(qts).forEach(([keyQts, valueQts]) => {
                if (key == keyQts) {
                    total = total + (value * valueQts);
                }
            })

        });
        total = total + (total * "{{ auth()->user()->store->tva }}" / 100);
        console.log('hamza je calcul le retour');
        console.log(total);
        $('#modalReturn-' + invoice_id).find('#totalReturn').html(total + " MAD");
        $('#modalReturn-' + invoice_id).find('#totalReturnHidden').html(
            "<input type='hidden' name='hidden_total' value='" + total + "'>");
    }
</script>
