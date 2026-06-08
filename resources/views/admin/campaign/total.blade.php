@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{ trans('communs.Caisse') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/owner') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('communs.Caisse de depart') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row size-colum">
            <div class="col-12">
                <div class="dash-chart row">
                    <div class="col-xl-12 box-col-12 col-lg-12 col-md-12">
                        <div class="card gradient-primary o-hidden">
                            <div class="card-body">
                                <button class="btn btn-primary btn-lg " data-toggle="modal" data-target="#payement-form" data-bs-original-title="" title="">@lang('Payer Tout')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body ">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom01">@lang('communs.stores')</label>
                                            <select id="customers" class="js-example-basic-single col-sm-12"
                                                onchange="getCounSms($(this).val())" required name="store">
                                                <option value="0">--- {{ trans('communs.Choisir Le store a débiter') }}
                                                    ---</option>
                                                @foreach ($stores as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <table class="display" id="render-table-fonds">
                                <thead>
                                    <tr>
                                        <th>{{ trans('communs.Nom Compaigne') }}</th>
                                        <th>{{ trans('communs.Count') }}</th>
                                        <th>{{ trans('communs.Debit') }}</th>
                                        <th>{{ trans('communs.Action') }}</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="table-body">
                                    <tr >
                                        <td><label id='name' name='name'>XXX</label></td>
                                        <td><label id='count' name='count'>0</label></td>
                                        <td><input oninput="getData(this.value, this.id)" id="credit"type="number" class="form-control" placeholder="XXX" name="credit" min="0"
                                            oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"></td>
                                        <td><button type="submit" class="form-control btn btn-primary">{{ trans('Payer') }}</button></td>
                                    </tr>
                                    @if(isset($result))
                                        @foreach ($result as $res)
                                        <tr >
                                            <td><label id='name' name='name'>{{$res['0']}}</label></td>
                                            <td><label id='count' name='count'>{{$res['1']}}</label></td>
                                            <td><input type="number" class="form-control" placeholder="XXX" name="credit" min="0"
                                            oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null"></td>
                                            <td><button type="submit" class="form-control btn btn-primary">{{ trans('Payer') }}</button></td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade bd-example-modal-lg" id="payement-form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Payer une facture ') }} </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form action="{{route('admin.campaigns.payAll')}}" method="POST">
                    @csrf
                    <div class="modal-body col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="validationServer02">{{trans('Nom')}}</label>
                                <select id="customers" class="control-form col-sm-12" style="border-radius: 5px !important;
                                border-color: #f6f7fb;
                                height: 40px !important;
                                padding: 5px;" required name="ownerId">
                                    <option value="0">--- {{ trans('communs.Choisir Le store a débiter') }} ---</option>
                                    @foreach ($owners as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->getFullNameAttribute() }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="submit">{{ trans('Payer') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <div class="container-fluid">
            <div class="row size-colum">
                <div class="col-12">
                    <div class="dash-chart row">
                        <div class="col-xl-12 box-col-12 col-lg-12 col-md-12">
                            <div class="card gradient-primary o-hidden">
                                <div class="card-header">
                                    <strong class="text-center">@lang('dashboard.Campaign Credit')</strong>
                                </div>
                                <div class="card-body">
                                    <form action="{{route('admin.campaigns.credit')}}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">@lang('communs.stores')</label>
                                                <select id="customers" class="js-example-basic-single col-sm-12" required name="store">
                                                    <option value="0">--- {{ trans('communs.Choisir Le store a débiter') }} ---</option>
                                                    @foreach ($stores as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustomUsername">@lang('communs.Credit')</label>
                                                <div class="input-group">
                                                    <input class="form-control" id="validationCustomUsername" type="text" name='credit' placeholder="XXX" required="" title="">
                                                    <div class="invalid-feedback">Please choose a username.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit" data-original-title="" title="">{{trans('dashboard.Credit')}}</button>
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
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
@endsection

<script>
    function getCounSms(store_id) {
        $.ajax({
            url: "{{ route('admin.campaigns.store.count') }}",
            type: 'GET',
            data: {
                store_id: store_id,
            },
            success: function(data) {
                console.log(data);
                const tableBody = document.getElementById('table-body');
                tableBody.innerHTML = ''; // remove existing rows

                Object.entries(data).forEach(([key, value]) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <input type="hidden" id="key" name="${key}key" value="${key}">
                        <td><label for="html" name='name'>${value[0]}</label></td>
                        <td><label for="html" name='count'>${value[1]}</label></td>
                        <td><input id="${key}" type="number" class="form-control"placeholder="XXX" name="${key}credit" min="0" max="${value[1]}"
                            oninput="getData(this.value, this.id)" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 
                            ? Math.abs(this.value) 
                            : null"></td>
                        <td>
                            <form action="{{route('admin.campaigns.reset', ['store_id' => '']) }}" method="POST" style="margin:0;">
                                @csrf
                                <label style="display: none" id='name' name='name'>XXX</label>
                                <input type="text" style="display: none" id='${key}1' name='name'></input>
                                <input id="${key}2" style="display: none" type="number" name="credit1">
                                <input type="text" style="display: none" id='${key}3' name='store_id'></input>
                                <button type="submit" class="form-control btn btn-primary">{{ trans('Payer') }}</button>
                            </form>
                        </td>`;

                    tableBody.appendChild(newRow);
                });
            }
        })
    }

    window.onload = function() {        
    const selectedStoreId = "{{ session('store_id') }}";
        if (selectedStoreId) {
            $('#customers').val(selectedStoreId).trigger('change');
            getCounSms(selectedStoreId);
            
            "{{ session()->forget('store_id') }}";
            return;
        }
    }
    function getData(value,id){
        const el = document.getElementById(id+'1');
        const el2 = document.getElementById(id+'2');
        const el3 = document.getElementById(id+'3');
        const store_id = document.getElementById('customers').value;
        el.value = id;
        el2.value = value;
        el3.value = store_id;
        console.log('value',value,'id',id,el3)
    }
</script>
