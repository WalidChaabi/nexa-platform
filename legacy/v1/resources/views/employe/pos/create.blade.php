@extends('layouts.simple.restau')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
    <style>
        #btn-terminer:hover {
            background: #d533cd !important;
        }

        @media print {

            /* unset headers and footers */
            @page {
                margin: 0;
            }

            body {
                margin: 0;
            }
        }

        .categorei-card {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .text-left {
            color: #7a67fd;
            font-size: 33px;
            margin: 0;
        }

        .page-wrapper.null.compact-wrapper {
            background: #f6f6f6;
        }

        .page-wrapper.compact-wrapper .page-body-wrapper.sidebar-icon .page-body {
            min-height: calc(100vh - 85px);
        }

        .page-wrapper.compact-wrapper .page-header {
            margin-bottom: 15px !important;
            margin-left: 0 !important;
            position: relative;
            width: 100% !important;
        }

        .page-wrapper .page-header .header-wrapper {
            padding: 15px 30px;
        }

        .select2 {
            width: 100% !important;
        }

        @media (max-width: 1190px) {
            .reduction {
                flex-direction: column;
                justify-content: space-around;
            }
        }

        @media (max-width: 770px) {
            .container-fluid.row {
                height: 100% !important;
            }
        }

        .category-list {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .category-item {
            width: 14.7%;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #f6f7fb;
            padding: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .categorei-card {
            width: 14.7%;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #f6f7fb;
            padding: 4px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .category-name {
            margin: 0;
            text-align: center;
        }
        .swal2-confirm.swal2-styled{
            background: #7366ff !important;
        }
    </style>
@endsection

{{-- @cannot('update', Model::class) 
    @section('breadcrumb-title')
        <h3 class="text-left">{{ auth()->user()->store->name }}</h3>
    @endsection
    
    @section('breadcrumb-items')
        <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ trans('communs.POS') }}</li>
    @endsection
@endcannot --}}

@section('content')
    <div class="container-fluid row" style="height: calc(100vh - 95px);display: flex;">
        @if ($errors->any())
            {{ implode('', $errors->all(':message')) }}
        @endif
        @if (isset($table))
            <input type="hidden" name="table_id" id="table_id" value="{{ $table->id }}">
        @endif

        <div class="col-xl-6 col-md-6 box-col-6 d-flex" style="height:100%;">
            <div class="card" style="width:100%;margin-bottom:10px;">
                <div class="card-header" style="padding: 23px 10px !important;">
                    <div class="d-flex" style="justify-content: space-between">
                        <div class="select-customer" style="width: 66%">
                            <select id="customers" class="js-example-basic-single col-sm-12"
                                onchange="getCustomerPoints($(this).val())" required name="customer">
                                <option value="0">--- {{ trans('communs.Choisir un client') }} ---</option>
                                @foreach ($customers as $item)
                                    <option value="{{ $item['id'] }}" @if (config('constant.neant_client') == $item['phone']) selected @endif>
                                        {{ $item['firstname'] . ' ' . $item['lastname'] }} : {{ $item['phone'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button id='btn_check_password'data-toggle="modal" data-target=".bd-example-modal-checkPassword"
                        class="btn btn-primary btn-sm" type="button" style="display: none"></button>
                        <div class="button-customer" style="width: 30%">
                            <button data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary btn-sm"
                                type="button" style="height: 40px; width: 100%; padding: 0 !important;">Nouveau
                                Client</button>
                        </div>
                    </div>
                </div>
                <div class="card-body file-manager" style="padding:25px;">
                    <div class="row" style="flex-direction: column; height: 100%; flex-wrap: nowrap;">
                        <div class="d-flex" style="height: 97px">
                            <div class="col-4 reduction" style="display: flex;align-items:baseline;">
                                <label style="margin-right: 5px">Points de Reduction</label>
                                <span class="badge rounded-pill badge-success" id="redPts"
                                    style="height: 35px;width: 35px;text-align: center;align-items: center;justify-content: 
                                        center;display: flex;font-weight: 31px;font-size: 14px;">0</span>
                                <input type="hidden" id="redPtsHidden">
                            </div>
                            <div class="col-4 reduction" style="display: flex;align-items:baseline;">
                                <label style="margin-right: 5px">Reduction en Mad</label>
                                <span class="badge rounded-pill badge-primary" id="redMad"
                                    style="height: 35px;width: 35px;text-align: center;align-items: center;justify-content: 
                                        center;display: flex;font-weight: 31px;font-size: 14px;margin-bottom: 5px;">0.00</span>
                                <input type="hidden" id="redMadHidden" value='0'>
                            </div>
                            <div class="col-4 reduction" style="display: flex;">
                                <label class="col-form-label m-r-10">Utiliser la réduction? </label>
                                <label class="switch" style="min-width: 60px">
                                    <input type="checkbox" onchange="reduction()" id='switchReduction'><span
                                        class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                        <div class="table-responsive wishlist"
                            style="height: 270px;position: relative;overflow: auto;border: solid;">
                            <table class="col-12">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;">Prdouct Name</th>
                                        <th style="text-align: left;">Price</th>
                                        <th style="text-align: left;">Action</th>
                                        <th style="text-align: left;">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="order">

                                </tbody>

                            </table>
                        </div>
                        <div class="d-flex" style="flex-direction: column ;width:100%;">
                            <div class="d-flex" style="justify-content: space-between;margin: 20px 0;">
                                <div class="d-flex" style="flex-direction: column">
                                    <label>Total HT : <span id="totalHt">00.00</span></label>
                                    <input type="hidden" id="totalHtHidden">
                                </div>
                                <div class="d-flex" style="flex-direction: column">
                                    <label> <strong> Total : <span id="total">00.00 </span> MAD </strong> </label>
                                    <input type="hidden" id="totalHidden">
                                </div>
                            </div>
                            <div class="d-flex" style="justify-content: space-between;margin-bottom: 20px;">
                                <div class="d-flex" style="flex-direction: column;margin-right: 10px;">
                                    <label>CODE DE REDUCTION </label>
                                    <input type="text" id="redCode" class="form-control">
                                </div>
                                <div class="d-flex" style="flex-direction: column">
                                    <label style="flex: 1">TVA : <span>{{ auth()->user()->store->tva }}</span></label>
                                    <button class="btn btn-primary col-12" type="button" onclick="reductionCode()"
                                        style="flex: 1;min-width: 135px;">
                                        Appliquer</button>
                                </div>
                            </div>
                            <div class="d-flex" style="justify-content: space-between ;width:100%;">
                                <div class="d-flex" style="flex-direction: column">
                                    <button class="btn btn-primary col-12" type="button"
                                        onclick='calculator()'>Calculatrice</button>
                                </div>
                                @if (isset($table))
                                    <div class="d-flex" style="flex-direction: row-reverse ;gap:6px; ">
                                        <button class="btn btn-primary col-12" type="button"
                                            onclick='facture()'style="max-width: 135px ;">Facture</button>
                                        <button class="btn btn-primary col-12" type="button" id="btn-terminer"
                                            onclick='stepOne()'style="max-width: 135px ;">Terminer</button>
                                    </div>
                                @else
                                    <div class="d-flex"">
                                        <button class="btn btn-primary col-12" type="button"
                                            onclick='facture()'style="min-width: 135px">Facture</button>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{-- </div> --}}
        </div>
        <div class="col-xl-6 col-md-6 box-col-6 d-flex" style="height:100%;">
            <div class="file-content w-100">
                <div class="card" style="height:98.5%;">
                    <div class="card-header" style="padding: 23px 13px !important;">
                        <div class="col-12">
                            <select id="customers" class="js-example-basic-single col-sm-12"
                                onchange="addFromSearchProducts($(this).val())" required name="customer">
                                <option value="0">--- {{ trans('communs.Choisir un Produit') }} ---</option>
                                @if (!empty($products))
                                    @foreach ($products as $items)
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->label }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="card-body file-manager" style="position: relative;overflow: auto;">
                        <div class="d-flex" style="justify-content: flex-start; flex-wrap: wrap;">
                            @if (!empty($categories))
                                @foreach ($categories as $categorie)
                                    <div class="categorei-card"
                                        onclick="getProductsBycategorieid('{{ $categorie->id }}')">
                                        {{-- <div class="file-box" style="width: 100%;white-space:nowrap ;list-style-type: none;"
                                            onclick="getProductsBycategorieid('{{ $categorie->id }}')"> --}}
                                        {{-- <div class="file-bottom"> --}}
                                        {{-- <h6 style="text-overflow: ellipsis;">{{ substr($categorie->name, 0, 7) }} </h6> --}}
                                        <h6 style="text-overflow: ellipsis;  word-wrap: break-word;width:100%;">
                                            {{ $categorie->name }} </h6>
                                        {{-- <p class="mb-1">total : {{ $categorie->products->count() }}</p> --}}
                                        {{-- </div> --}}
                                        {{-- </div> --}}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        </ul>
                        <div class="tabcontent">
                            <div class="active" id="0">
                                <ul class="files" id="listProducts">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal-calculator" class="modal fade bd-example-modal-calculator" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Calculator') }}</h4>
                        <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                            data-original-title="" title=""><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <table border="1" class="col-md-12 table">
                            <tr>
                                <td colspan="3"><input type="text" id="result" class="form-control" /></td>
                                <!-- clr() function will call clr to clear all value -->
                                <td><input type="button" value="c" onclick="clr()" class="form-control" /> </td>
                            </tr>
                            <tr>
                                <!-- create button and assign value to each button -->
                                <!-- dis("1") will call function dis to display value -->
                                <td><input type="button" value="1" onclick="dis('1')" class="form-control" />
                                </td>
                                <td><input type="button" value="2" onclick="dis('2')" class="form-control" />
                                </td>
                                <td><input type="button" value="3" onclick="dis('3')" class="form-control" />
                                </td>
                                <td><input type="button" value="/" onclick="dis('/')" class="form-control" />
                                </td>
                            </tr>
                            <tr>
                                <td><input type="button" value="4" onclick="dis('4')" class="form-control" />
                                </td>
                                <td><input type="button" value="5" onclick="dis('5')" class="form-control" />
                                </td>
                                <td><input type="button" value="6" onclick="dis('6')" class="form-control" />
                                </td>
                                <td><input type="button" value="-" onclick="dis('-')"class="form-control" /> </td>
                            </tr>
                            <tr>
                                <td><input type="button" value="7" onclick="dis('7')" class="form-control" />
                                </td>
                                <td><input type="button" value="8" onclick="dis('8')" class="form-control" />
                                </td>
                                <td><input type="button" value="9" onclick="dis('9')" class="form-control" />
                                </td>
                                <td><input type="button" value="+" onclick="dis('+')" class="form-control" />
                                </td>
                            </tr>
                            <tr>
                                <td><input type="button" value="." onclick="dis('.')" class="form-control" />
                                </td>
                                <td><input type="button" value="0" onclick="dis('0')" class="form-control" />
                                </td>
                                <!-- solve function call function solve to evaluate value -->
                                <td><input type="button" value="=" onclick="solve()" class="form-control" /> </td>
                                <td><input type="button" value="*" onclick="dis('*')" class="form-control" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal-owner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Nouveau Client') }}</h4>
                        <button id="close-customers" class="close" type="button" data-dismiss="modal"
                            aria-label="Close" data-original-title="" title=""><span
                                aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="customersErrors">

                        </div>
                        <form>
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer01">{{ trans('Nom') }}</label>
                                    <input class="form-control" id="firstname" type="text" name="firstname"
                                        value="{{ old('firstname') }}" data-original-title=""
                                        title="{{ trans('Prénom') }}">
                                    @if ($errors->has('firstname'))
                                        <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer02">{{ trans('Prénom') }}</label>
                                    <input class="form-control " id="lastname" type="text" name="lastname"
                                        value="{{ old('lastname') }}" data-original-title=""
                                        title="{{ trans('Nom') }}">
                                    @if ($errors->has('lastname'))
                                        <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer01">{{ trans('E-mail') }}</label>
                                    <input class="form-control" id="email" type="email" name="email"
                                        value="{{ old('email') }}" data-original-title=""
                                        title="{{ trans('E-mail') }}">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal-owner" class="modal fade bd-example-modal-checkPassword" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Validater la suppression') }}</h4>
                        <button id="close-checker" class="close" type="button" data-dismiss="modal"
                            aria-label="Close" data-original-title="" title=""><span
                                aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="customersErrors">

                        </div>
                        <form onsubmit="return false">
                            @csrf
                            <label for="validationServer01">{{ trans('Password') }}</label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input class="form-control" id="token" type="password" name="token">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <button class="btn btn-primary" type="button" onclick="checkPassword()"
                                        title="{{ trans('Valider') }}">{{ trans('Valider') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        let isadmin = false 
        let idProduct = null
        function calculator() {
            var total = document.getElementById('totalHidden').value;
            if (total != '') {
                document.getElementById('result').value = total + '-';
                $("#modal-calculator").modal()
            }
        }

        function addFromSearchProducts(productId) {
            $.ajax({
                url: "{{ route('employe.productPrice') }}",
                type: 'GET',
                data: {
                    productId: productId
                },
                success: function(data) {
                    let tbody = document.getElementById("order");
                    let tr = '<tr id="tr_' + data.id +
                        '"><td><div class="product-name" style="text-align: left;">' + data.label +
                        '</div></td><td>' + data.price + '</td><td><button onclick="remove(' + data.id +
                        ')" data-feather="x-circle" style="color:red">Supprimer</button></td><td>' + data
                        .price + ' <input type="hidden" value="' + data.price + '"></td></tr>';
                    tbody.innerHTML += tr;
                    setTotal();
                }
            })
        }

        function add(id, label, price) {
            let tbody = document.getElementById("order");
            let tr = '<tr id="tr_' + id + '"><td><div class="product-name" style="text-align: left;">' + label +
                '</div></td><td>' + price + '</td><td><button onclick="remove(' + id +
                ')" data-feather="x-circle" class="btn btn-outline-secondary btn-sm">Supprimer</button></td><td>' + price +
                ' <input type="hidden" value="' + price + '"></td></tr>';
            tbody.innerHTML += tr;
            setTotal();
        }

        function checkPassword() {
            var store_id = "{{ auth()->user()->store->id }}"
            var token = document.getElementById('token').value
            $.ajax({
                url: "{{ route('employe.pos.check.password') }}",
                type: 'POST',
                data: {
                    store_id: store_id,
                    token: token
                },
                success: function(data) {
                    isadmin = true;
                    remove(idProduct);
                    document.getElementById('close-checker').click()
                },
                error(e){
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Mot de passe incorrect',
                    });
                }
            })
        }

        function remove(id) {
            if(!isadmin){
                idProduct = id;
                document.getElementById('btn_check_password').click()
            }else{
                if(document.getElementById('tr_' + id +'_old')){
                    document.getElementById('tr_' + id +'_old').remove();
                }else{
                    document.getElementById('tr_' + id ).remove();
                }
                setTotal();
            }
        }

        function setTotal() {
            var totalHt = 0;
            let elements = document.getElementById('order');
            for (let i = 0; i < elements.children.length; i++) {
                totalHt = totalHt + parseFloat(elements.children[i].children[3].children[0].value);
            }
            total = totalHt + (totalHt * "{{ auth()->user()->store->tva }}" / 100);
            total = total.toFixed(2);
            document.getElementById('total').innerHTML = total;
            document.getElementById('totalHidden').value = total;
            document.getElementById('totalHt').innerHTML = totalHt;
            document.getElementById('totalHtHidden').value = totalHt;
        }

        function getCustomerPoints(customerId) {
            $.ajax({
                url: "{{ route('employe.customers.points') }}",
                type: 'GET',
                data: {
                    customer_id: customerId
                },
                success: function(data) {
                    console.log('reste create pos', data);
                    document.getElementById('redMad').innerHTML = data.mad_red;
                    document.getElementById('redMadHidden').value = data.mad_red;
                    document.getElementById('redPts').innerHTML = data.points_red;
                    document.getElementById('redPtsHidden').value = data.points_red;
                }
            })
        }

        function reduction() {
            if (document.getElementById('switchReduction').checked == true) {
                if (document.getElementById('customers').value == 0) {
                    alert('Please choose the customer')
                    document.getElementById('switchReduction').checked = false;
                } else {
                    var totalHt = document.getElementById('totalHtHidden').value;
                    var redMadElement = document.getElementById('redMadHidden');
                    var redMad = redMadElement.value;
                    var diff = totalHt - redMad;
                    var newtotal = (Math.ceil(diff * (1 + ("{{ auth()->user()->store->tva }}" / 100))))
                    document.getElementById('total').innerHTML = newtotal;
                    document.getElementById('totalHidden').innerHTML = newtotal;
                }
            } else {
                if (document.getElementById('customers').value == 0) {
                    alert('Please choose the customer')
                    document.getElementById('switchReduction').checked = false;
                } else {
                    var totalHt = document.getElementById('totalHidden').value;
                    document.getElementById('total').innerHTML = totalHt;
                    document.getElementById('totalHidden').innerHTML = totalHt;
                }
            }
        }

        function getProductsBycategorieid(categorieId) {
            console.log('categorieId', categorieId)
            $.ajax({
                url: "{{ route('employe.products.categorie.list') }}",
                type: 'GET',
                data: {
                    categorie_id: categorieId
                },
                success: function(data) {

                    console.log('product by categorie id is  :', data);
                    var html = '';
                    Object.values(data).forEach(element => {
                        let label = element.label.substr(0, 15);
                        path = element.image != '' ? "{{ asset('uploads/product/images/') }}" + "/" +
                            element
                            .store_id + "/" + element.image : "https://via.placeholder.com/100x100";
                        html = html +
                            "<li class='file-box' style='margin-right: 10px;margin-bottom: 10px ;width: 18%;padding:4px;' onclick='add(" +
                            element.id + "," + "`" + label + "`" + "," + element.price +
                            ")'><div class='' title='" + element.label +
                            "' ><img  style='width:100%' height='100' src='" + path + "' title='" +
                            element.label +
                            "' ></div> <p style='text-align:center;white-space: nowrap;'>" + label +
                            "<br>  " + element.price + " MAD</p></li>"
                    });
                    document.getElementById('listProducts').innerHTML = html;
                }
            })
        }

        function reductionCode() {
            var code = document.getElementById('redCode').value;
            var customerId = document.getElementById('customers').value;
            var totalHtElement = document.getElementById('totalHtHidden');
            var errorElement = document.getElementById('code_red_error');
            var error = 'Veuillez choisir un client et un code valide !';

            if (totalHtElement.value == '') {
                error = error + '<br> Veuillez choisir des produits';
            }
            if (customerId != 0 && code != '' && totalHtElement.value != '') {
                errorElement.innerHTML = '';
                $.ajax({
                    url: "{{ url('employe/customer/code/red') }}",
                    data: {
                        code: code,
                        customer_id: customerId
                    },
                    type: 'GET',
                    success: function(data) {
                        if (data.success == 1) {
                            var totalHt = parseFloat(totalHtElement.value);
                            var red_value = data.code;
                            var newTotal = totalHt - totalHt * red_value;
                            total = newTotal + (newTotal * "{{ auth()->user()->store->tva }}" / 100);
                            total = total.toFixed(2);
                            document.getElementById('total').innerHTML = total;
                            document.getElementById('totalHidden').value = total;
                            document.getElementById('totalHt').innerHTML = totalHt;
                            document.getElementById('totalHtHidden').value = totalHt;
                        } else {
                            $('#code_red_error').html(data.message);
                            document.getElementById('redCode').value = '';
                        }
                    }

                })
            } else {
                errorElement.innerHTML = error;
                document.getElementById('redCode').value = '';
            }
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
                    document.getElementById('close-customers').click();
                },
                error: function(error) {
                    console.log(error.responseText.errors, 10000)
                    $('#customersErrors').html(
                        '<span style="color:red">Error:vous devez remplire au moins le numero de telephone</span>'
                    ); //
                }
            })
        }

        function dis(val) {
            document.getElementById("result").value += val
        }

        //function that evaluates the digit and return result
        function solve() {
            let x = document.getElementById("result").value
            let y = eval(x)
            document.getElementById("result").value = y
        }

        //function that clear the display
        function clr() {
            document.getElementById("result").value = ""
        }

        function getProducts() {
            let productsId = [];
            var elements = document.getElementById('order').childNodes;
            var trIds = Array.from(elements);
            delete trIds[0]
            trIds.forEach((element) => {
                trId = element.id;
                if (trId.endsWith("_old")) {
                    id = trId.replace('tr_', '').replace('_old', '');
                    productsId.push([id, 'old']);
                } else {
                    id = trId.replace('tr_', '');
                    productsId.push([id]);
                }
            });
            return productsId;
        }

        function removeAll() {
            let elements = document.getElementById('order');
            $("#order tr").remove();
            setTotal();
        }

        function stepOne() {
            let productsId = getProducts();
            let tableproduct = document.querySelectorAll('.table_product_id')
            let tableId = document.getElementById('table_id').value;
            var store_id = "{{ auth()->user()->store->id }}";
            let _token = $('meta[name="csrf-token"]').attr('content');
            console.log('table_product_id', tableproduct)
            $.ajax({
                url: "{{ route('employe.pos.create.table.product') }}",
                data: {
                    table_id: tableId,
                    products: productsId,
                    store_id: store_id,
                    _token: _token,
                },
                type: 'POST',
                success: function(data) {
                    if (data.success == true) {
                        return window.location.href('https://app.nexa.app/employe/table');
                    }
                }
            })
        }

        function updateTable() {
            let tableId = document.getElementById('table_id').value;
            let _token = $('meta[name="csrf-token"]').attr('content');
            console.log(tableId, _token)
            $.ajax({
                url: "{{ route('employe.table.update.status') }}",
                data: {
                    table_id: tableId,
                    _token: _token,
                },
                type: 'GET',
                success: function(data) {
                    console.log(data)
                }
            })
        }

        function facture() {
            let productsId = getProducts();
            let customerId = document.getElementById('customers').value;
            let reductionCode = document.getElementById('redCode').value;
            let isUseReduction = document.getElementById('switchReduction').checked == true ? 1 : 0;
            let Reduction = document.getElementById('redMadHidden').value
            let total = isUseReduction ? document.getElementById('totalHidden').value - Reduction : document.getElementById('totalHidden').value;
            let totalHt = document.getElementById('totalHtHidden').value;
            let _token = $('meta[name="csrf-token"]').attr('content');
            let data = [productsId, customerId, reductionCode, isUseReduction, total, totalHt, _token]
            let tableId = document.getElementById('table_id');
            if (tableId !== null) {
                updateTable()
            }
            $.ajax({
                url: "{{ route('employe.invoices.store') }}",
                data: {
                    code_valide: reductionCode,
                    customer: customerId,
                    products: productsId,
                    use_points: isUseReduction,
                    paid_amount: total,
                    total: total,
                    total_ht: totalHt,
                    _token: _token,
                },
                type: 'POST',
                success: function(url) {
                    console.log(url)
                    var win = window.open(url + '?type=individual');
                    win.print();
                    var customerId = $('#customers').val();
                    getCustomerPoints(customerId);
                    removeAll();
                },
                error: function(errors) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Veuillez sélectionner un client',
                    });
                }
            })
        }

        function getproductTable() {
            var store_id = "{{ auth()->user()->store->id }}";
            let _token = $('meta[name="csrf-token"]').attr('content');
            let tableId = document.getElementById('table_id').value;
            $.ajax({
                url: "{{ route('employe.table.get.product') }}",
                data: {
                    table_id: tableId,
                    store_id: store_id,
                    _token: _token,
                },
                type: 'GET',
                success: function(data) {
                    let tbody = document.getElementById("order");
                    data.forEach((d) => {

                        let tr = '<tr id="tr_' + d.product_table.id +
                            '_old"><td><div class="product-name" style="text-align: left;">' +
                            d.product_table.label +
                            '</div></td><td>' + d.product_table.price +
                            '</td><td><button onclick="remove(' + d.product_table.id +
                            ')" data-feather="x-circle" style="color:red">Supprimer</button></td><td>' +
                            d.product_table.price +
                            ' <input type="hidden" value="' + d.product_table.price + '"></td></tr>';
                        tbody.innerHTML += tr;
                    })
                    setTotal();
                }
            })
        }

        $(document).ready(function() {
            let tableId = document.getElementById('table_id');

            var categorie_id = '{{ $products->count() > 0 ? $products->first()[0]->categorie_id : 0 }}';
            var customerId = $('#customers').val();
            // getCustomerPoints(customerId);
            getProductsBycategorieid(categorie_id);
            if (tableId !== null) {
                getproductTable();
            }
        });
    </script>
@endsection
