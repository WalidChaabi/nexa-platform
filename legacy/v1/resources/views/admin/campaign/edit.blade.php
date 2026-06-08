@extends('layouts.simple.master')
@section('title', 'Default')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/simple-mde.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ auth()->user()->getFullNameAttribute() }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item">{{ trans('dashboard.dashboard') }}</li>
    <li class="breadcrumb-item active">{{ trans('Campagnes') }}</li>
@endsection

@section('content')

    <div class="table-responsive">
        <div class="container-fluid">
            <div class="row size-colum">
                <div class="col-12">
                    <div class="dash-chart row">
                        <div class="col-xl-12 box-col-12 col-lg-12 col-md-12">
                            <div class="card gradient-primary o-hidden">
                                <div class="card-header">
                                    <strong class="text-center">@lang('dashboard.Modifier Campagnes')</strong>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.campaigns.update',$campaign_details->id) }}" method="PUT" onsubmit="onSubmit(event)">
                                        @csrf
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p>{{ $error }}</p>
                                                @endforeach
                                            </div>
                                        @endif
                                        <input type="hidden" value="0" name="campaignType">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">@lang('communs.Boutique')</label>
                                                <select class="js-example-basic-single col-sm-12" name="store_id" id="store_id" readonly>
                                                    <option value="0">---
                                                        {{ trans('communs.Choisir La boutique a débiter') }} ---</option>
                                                    @foreach ($stores as $item)
                                                        @if ($item->id == $campaign_details->store_id)
                                                            <option value="{{ $campaign_details->store_id }}" selected>
                                                                {{ $item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                {{-- <div class="col-md-12 mb-3" style="display: flex;align-items: baseline;gap: 6px;"> --}}
                                                <label for="validationCustom02">@lang('communs.Campagne')</label>
                                                <select id="campaigne_id" class="js-example-basic-single col-sm-12" readonly name="campaign_id"
                                                    style="width: 618.125px">
                                                    <option value="0">
                                                        ---{{ trans('communs.Choisir La campagne a débiter') }} ---
                                                    </option>
                                                    @foreach ($campaigns as $item)
                                                        @if ($item->id == $campaign_details->campaign->id)
                                                            <option value="{{ $campaign_details->campaign->id }}" selected>
                                                                {{ $item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                {{-- </div> --}}
                                                <div class="col-md-12 mb-3" id="div_product" style="display: none">
                                                    <label for="validationCustom02">@lang('communs.Products')</label>
                                                    <select id="products" class="js-example-basic-single col-sm-12"
                                                        name="product_id" style="width: 618.125px">
                                                        <option value="0">
                                                            ---{{ trans('communs.Choisir La Products a débiter') }} ---
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-3" id="div_name" style="display: none">
                                                    <label for="validationCustom02">@lang('communs.Nom')</label>
                                                    <input type="text" class="form-control" placeholder="name .."
                                                        name="campaigne_name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3"
                                                style="border: 1px solid #e6e4e4;border-radius: 10px 0px 0px 10px;padding: 10px;">
                                                <label for="validationCustomUsername">@lang('communs.Messages')</label>
                                                <div class="d-flex align-items-center mb-3">
                                                    <span>@lang('communs.Reduction'): </span>
                                                    <div class="input-group ml-2">
                                                        <input type="number" class="form-control" placeholder="Reduction"
                                                            name="reduction" min="0" max="100"
                                                            oninput="this.value =
                                                                !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"
                                                            value="{{ $campaign_details->reduction }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="form_messages"></div>
                                                <template id="messagetemp">
                                                    <div style="display:flex; gap:12px;" id="messageform">
                                                        <div style="flex:2 3">
                                                            <label
                                                                style="margin-top:6px;white-space: nowrap;"for="validationCustomUsername">@lang('communs.Message')</label>
                                                            <textarea style="flex:2 3" class="form-control" id="campaign_message" type="text" required=""
                                                                data-original-title="" title=""></textarea>
                                                        </div>
                                                        <div style="flex:1 3">
                                                            <label
                                                                style="margin-top:6px;white-space: nowrap;"for="validationCustomUsername">@lang('communs.Date')</label>
                                                            <input type="number" class="form-control" placeholder="date"
                                                                name="date_start" min="0"
                                                                oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null">
                                                        </div>
                                                        <button type="button" style="height: 32px;margin-top: 35px;"
                                                            onclick="deleteMessage(this.id)"
                                                            class="btn btn-primary">-</button>
                                                    </div>
                                                </template>
                                                <button type="button" style="margin-top: 12px" onclick="newMessage()"
                                                    class="btn btn-primary">{{ trans('Add message') }}
                                                </button>
                                            </div>

                                            <div class="col-md-6 mb-3 "
                                                style="border: 1px solid #e6e4e4;border-radius: 0px 10px 10px 0px;padding: 10px;">
                                                <label for="validationCustomUsername">@lang('communs.Filters')</label>
                                                <div class="wrapper">
                                                    <label for="validationCustomUsername">@lang('communs.Age')</label>
                                                    <div class="values">
                                                        <span id="range1">
                                                            {{ $campaign_details->filter->age_min }}
                                                        </span>
                                                        <span> &dash; </span>
                                                        <span id="range2">
                                                            {{ $campaign_details->filter->age_max }}
                                                        </span>
                                                    </div>
                                                    <div class="container d-flex" style="justify-content: center">
                                                        <div class="slider-track"></div>
                                                        <input type="range" min="0" max="100"
                                                            value="{{ $campaign_details->filter->age_min }}"
                                                            name="age_min" id="slider-1" oninput="slideOne()">
                                                        <input type="range" min="0" max="100"
                                                            value="{{ $campaign_details->filter->age_max }}"
                                                            name="age_max" id="slider-2" oninput="slideTwo()">
                                                    </div>
                                                </div>
                                                <div class="d-flex" style="justify-content: space-between">
                                                    <div style="width: 40%">
                                                        <label for="validationCustomUsername">@lang('communs.Points')</label>
                                                        <input class="form-control" type="text" name="points"
                                                            title=""
                                                            value="{{ $campaign_details->filter->points }}">
                                                    </div>
                                                    <div style="width: 40%">
                                                        <label for="validationCustomUsername">@lang('communs.Dernière visite')</label>
                                                        <input class="form-control" type="text" name="last_visit"
                                                            title=""
                                                            value="{{ $campaign_details->filter->last_visit }}">
                                                    </div>
                                                </div>
                                                <label for="validationCustomUsername">@lang('communs.Sexe')</label>
                                                <div class="d-flex">
                                                    <div class="d-flex pl-3">
                                                        @if ($campaign_details->filter->sexe == 0)
                                                            <input checked class="form-check-input" type="radio" name="sexe"
                                                                id="flexRadioDefault1" value="0">
                                                            <label  class="form-check-label"
                                                                for="flexRadioDefault1">Homme</label>
                                                        @else
                                                            <input class="form-check-input" type="radio" name="sexe"
                                                                id="flexRadioDefault1" value="0">
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault1">Homme</label>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex pl-5">
                                                        @if ($campaign_details->filter->sexe == 1)
                                                            <input checked class="form-check-input" type="radio" name="sexe"
                                                                id="flexRadioDefault1" value="1">
                                                            <label  class="form-check-label"
                                                                for="flexRadioDefault1">Femme</label>
                                                        @else
                                                            <input class="form-check-input" type="radio" name="sexe"
                                                                id="flexRadioDefault1" value="1">
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault1">Femme</label>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex pl-5">
                                                        @if ($campaign_details->filter->sexe == 2)
                                                            <input checked class="form-check-input" type="radio" name="sexe"
                                                                id="flexRadioDefault1" value="2">
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault1">Mixte</label>
                                                        @else
                                                            <input class="form-check-input" type="radio" name="sexe"
                                                                id="flexRadioDefault1" value="2">
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault1">Mixte</label>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label for="validationServer01">{{ trans('communs.Horaire') }}</label>
                                                    <div class="d-flex" style="justify-content: space-between">
                                                        <div style="width: 40%">
                                                            <label for="validationServer01">{{ trans('communs.Heure debut') }}</label>
                                                            <input class="form-control" type="time" name="heure_start" value="{{$campaign_details->filter->heure_start}}">
                                                        </div>
                                                        <div style="width: 40%">
                                                            <label for="validationServer01">{{ trans('communs.Heure fin') }}</label>
                                                            <input class="form-control" type="time" name="heure_end" value="{{$campaign_details->filter->heure_end}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit" data-original-title="" title="">{{ trans('dashboard.Submit') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

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
        const container = document.getElementById('messagetemp');
        const formMessages = document.getElementById('form_messages');

        const messages = @json($campaign_details->message_camp->pluck('messages')->flatten());

        console.log('messwages',messages);

        function smsCross($type) {
            console.log('ok')
            $type = $type.replace(/\s/g, "");
            const labelDate = document.querySelectorAll("#labelDate.labelDate");
            const inputDate = document.querySelectorAll("#deletemsgB.form-control");
            console.log(labelDate)
            const productDiv = document.getElementById("div_product");
            const nameDiv = document.getElementById("div_name");
            const nombreDiv = document.getElementById("div_nombre");
            if ($type === 'birthday') {
                for (var i = 0; i < labelDate.length; i++) {
                    inputDate[i].readOnly = false;
                    labelDate[i].textContent = "Date Avant"
                }
            }
            if ($type === 'cross-selling') {
                for (var i = 0; i < labelDate.length; i++) {
                    inputDate[i].readOnly = false;
                    labelDate[i].textContent = "Date apres conso produit-jour"
                }
                productDiv.style.display = "flex";
                productDiv.style.gap = "29px";
                productDiv.style.alignItems = "baseline";
                productDiv.style.width = "100%";
            } else {
                productDiv.style.display = "none";
            }
            if ($type === 'unique') {
                for (var i = 0; i < labelDate.length; i++) {
                    inputDate[i].readOnly = false;
                    labelDate[i].textContent = "Date d'envoi"
                }
                nameDiv.style.display = "flex";
                nombreDiv.style.display = "flex";
                nameDiv.style.gap = "61px";
                nombreDiv.style.gap = "13px";
                nameDiv.style.alignItems = "baseline";
                nombreDiv.style.alignItems = "baseline";
                nameDiv.style.width = "100%";
                nombreDiv.style.width = "100%";
            } else {
                nameDiv.style.display = "none";
                nombreDiv.style.display = "none";
            }
            if ($type === 'bienvenu') {
                bienvenu()
                bienvenu1()
            } else {
                nameDiv.style.display = "none";
                document.getElementById('newMessageB').disabled = false;
            }
            if ($type === 'rendez-vous') {
                if (messages.length <= 2) {
                    messages.push({});
                    applyMessages();
                }
                RDV($type);
            } else {
                nameDiv.style.display = "none";
            }
        }

        function bienvenu() {
            if (messages.length > 1) {
                messages.splice(1, messages.length - 1);
                console.log('messages', messages)
            }
            formMessages.innerHTML = "";
            messages.forEach((msg, i) => {
                const clonedContainer = container.cloneNode(true);
                const el = clonedContainer.content.getElementById('messageform');
                formMessages.appendChild(el);

                el.querySelector('textarea').value = msg.message ?? '';
                el.querySelector('input').value = msg.date_start ?? '';

                el.querySelector('textarea').addEventListener('change', e => msg.message = e.target.value);
                el.querySelector('input').addEventListener('change', e => msg.date_start = e.target.value);
            });
        }

        function bienvenu1() {
            const labelDate = document.querySelectorAll("#labelDate.labelDate");
            const inputDate = document.querySelectorAll("#deletemsgB.form-control");
            inputDate[0].readOnly = true;
            labelDate[0].textContent = "Envoi instantané"
            document.getElementById('newMessageB').disabled = true;
            console.log('input', inputDate[0])
        }

        function RDV($type) {
            const labelDate = document.querySelectorAll("#labelDate.labelDate");
            const inputDate = document.querySelectorAll("#deletemsgB.form-control");
            if ($type === 'rendez-vous') {
                labelDate[0].textContent = "Envoi instantané";
                inputDate[0].readOnly = true;
                labelDate[1].textContent = "modifie date";
                inputDate[1].readOnly = true;
                for (var i = 2; i < labelDate.length; i++) {
                    labelDate[i].textContent = "heure avant rendez-vous";
                    inputDate[i].type = "time";
                    inputDate[i].min = "00:00";
                    inputDate[i].max = "23:59";
                    inputDate[i].step = "60";
                    inputDate[i].removeAttribute("oninput");
                }
            }
        }

        function getProducts($store_id) {
            $.ajax({
                url: "{{ route('admin.campaigns.product') }}",
                type: 'POST',
                data: {
                    store_id: $store_id,
                },
                success: function(data) {
                    console.log(data);
                    const productDropdown = document.getElementById('products');
                    productDropdown.innerHTML = '';

                    data.forEach(function(product) {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.text = `${product.label} (${product.price})`;
                        productDropdown.add(option);
                    });
                },
                error: function(error) {
                    console.error('Error fetching products:', error);
                }
            })
        }

        function newMessage() {
            formMessages.innerHTML = "";
            messages.push({});
            applyMessages();
            console.log('new:', messages);
            const campaignSelect = $('#campaigne_id option:selected');
            var type = campaignSelect.text();
            type = type.replace(/\s/g, "");
            smsCross(type);
        }


        function onSubmit(e) {
            const i = document.createElement('input');
            i.setAttribute('hidden', true);
            i.name = 'messages';
            i.value = `${JSON.stringify(messages)}`;
            e.target.appendChild(i);
            return true;
        }

        function applyMessages() {
            formMessages.innerHTML = "";
            messages.forEach((msg, i) => {
                const clonedContainer = container.cloneNode(true);
                const el = clonedContainer.content.getElementById('messageform');
                formMessages.appendChild(el);

                el.querySelector('textarea').value = msg.message ?? '';
                el.querySelector('input').value = msg.date_start ?? '';

                el.querySelector('textarea').addEventListener('change', e => msg.message = e.target.value);
                el.querySelector('input').addEventListener('change', e => msg.date_start = e.target.value);

                const deleteBtn = el.querySelector('button');
                deleteBtn.addEventListener('click', (function(index) {
                    return function() {
                        messages.splice(index, 1);
                        applyMessages();
                        console.log('deleted:', messages);
                        const campaignSelect = $('#campaigne_id option:selected');
                        var type = campaignSelect.text();
                        type = type.replace(/\s/g, "");
                        console.log('type delete :', type)
                        smsCross(type);
                    }
                })(i));
            });
        }

        function deleteMessage(index) {
            applyMessages();
        }
        window.onload = function() {
            slideOne();
            slideTwo();
            smsCross($campaign_details->campaign->type);
        }
        let sliderOne = document.getElementById("slider-1");
        console.log(sliderOne);
        let sliderTwo = document.getElementById("slider-2");
        let displayValOne = document.getElementById("range1");
        let displayValTwo = document.getElementById("range2");
        let minGap = 0;
        let sliderTrack = document.querySelector(".slider-track");
        let sliderMaxValue = document.getElementById("slider-1").max;

        function slideOne() {
            if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                sliderOne.value = parseInt(sliderTwo.value) - minGap;
            }
            displayValOne.textContent = sliderOne.value;
            console.log('sliderOne value', sliderOne.value)
            fillColor();
        }

        function slideTwo() {
            if (parseInt(sliderTwo.value) - parseInt(sliderOne.value) <= minGap) {
                sliderTwo.value = parseInt(sliderOne.value) + minGap;
            }
            displayValTwo.textContent = sliderTwo.value;
            console.log('sliderTwo value', sliderTwo.value)

            fillColor();
        }

        function fillColor() {
            percent1 = (sliderOne.value / sliderMaxValue) * 100;
            percent2 = (sliderTwo.value / sliderMaxValue) * 100;
            sliderTrack.style.background =
                `linear-gradient(to right, #dadae5 ${percent1}% , #3264fe ${percent1}% , #3264fe ${percent2}%, #dadae5 ${percent2}%)`;
        }

        applyMessages();
        applyindex();
    </script>
@endsection
