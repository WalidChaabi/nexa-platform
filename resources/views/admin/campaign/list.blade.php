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
    <div class="container-fluid">
        <div class="row size-colum">
            <div class="col-12">
                <div class="dash-chart row">
                    <div class="col-xl-12 box-col-12 col-lg-12 col-md-12">
                        <div class="card gradient-primary o-hidden">
                            <div class="card-body">
                                <button class="btn btn-primary btn-lg " data-toggle="modal" data-target="#payement-form"
                                    data-bs-original-title="" title="">@lang('Payer Tout')</button>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <strong class="text-center">@lang('dashboard.Campaign Birthday')</strong>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.campaigns.reset') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="0" name="campaignType">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom01">@lang('communs.stores')</label>
                                                <select id="customers" class="js-example-basic-single col-sm-12"
                                                    onchange="getBalanceOfStore($(this).val(),0,'birthdayCamp')" required
                                                    name="store">
                                                    <option value="0">---
                                                        {{ trans('communs.Choisir Le store a débiter') }} ---</option>
                                                    @foreach ($stores as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom02">@lang('communs.Balance Actuel')</label>
                                                <input class="form-control" id="birthdayCamp" type="text"
                                                    placeholder="0.00" title="">
                                                <div class="valid-feedback">Looks good!</div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustomUsername">@lang('communs.Debit')</label>
                                                <div class="input-group">
                                                    <input class="form-control" id="validationCustomUsername" type="text"
                                                        name='debit' placeholder="XXX" required="" title="">
                                                    <div class="invalid-feedback">Please choose a username.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit" data-original-title=""
                                            title="">{{ trans('dashboard.Debiter') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
                                    <strong class="text-center">@lang('dashboard.Campaign Cross Selling')</strong>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.campaigns.reset') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="1" name="campaignType">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom01">@lang('communs.stores')</label>
                                                <select id="customers" class="js-example-basic-single col-sm-12"
                                                    onchange="getBalanceOfStore($(this).val(),1,'CrossCamp')" required
                                                    name="store">
                                                    <option value="0">---
                                                        {{ trans('communs.Choisir Le store a débiter') }} ---</option>
                                                    @foreach ($stores as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustom02">@lang('communs.Balance Actuel')</label>
                                                <input class="form-control" id="CrossCamp" type="text"
                                                    placeholder="0.00" title="">
                                                <div class="valid-feedback">Looks good!</div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="validationCustomUsername">@lang('communs.Debit')</label>
                                                <div class="input-group">
                                                    <input class="form-control" id="validationCustomUsername"
                                                        type="text" name='debit' placeholder="XXX" required=""
                                                        title="">
                                                    <div class="invalid-feedback">Please choose a username.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit" data-original-title=""
                                            title="">{{ trans('dashboard.Debiter') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
                                    <form action="{{ route('admin.campaigns.credit') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="validationCustom01">@lang('communs.stores')</label>
                                                <select id="customers" class="js-example-basic-single col-sm-12" required
                                                    name="store">
                                                    <option value="0">---
                                                        {{ trans('communs.Choisir Le store a débiter') }} ---</option>
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
                                                    <input class="form-control" id="validationCustomUsername"
                                                        type="text" name='credit' placeholder="XXX" required=""
                                                        title="">
                                                    <div class="invalid-feedback">Please choose a username.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit" data-original-title=""
                                            title="">{{ trans('dashboard.Credit') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="payement-form" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenter" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Payer une facture ') }} </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form action="{{ route('admin.campaigns.payAll') }}" method="POST">
                    @csrf
                    <div class="modal-body col-md-12">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="validationServer02">{{ trans('Nom') }}</label>
                                <select id="customers" class="control-form col-sm-12" required name="ownerId">
                                    <option value="0">--- {{ trans('communs.Choisir Le store a débiter') }} ---
                                    </option>
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
        function getBalanceOfStore(store, campaignType, elementId) {
            $.ajax({
                url: "{{ route('admin.campaigns.store.type') }}",
                type: 'GET',
                data: {
                    store: store,
                    campaignType
                },
                success: function(data) {
                    let element = $('#' + elementId);
                    element.attr('value', data);
                    element.attr('disabled', true);
                }
            })
        }
    </script>
@endsection
