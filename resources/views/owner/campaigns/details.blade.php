@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/simple-mde.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/material-dashboard.css') }}">
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
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">{{ $type }}</h6>
                            <input type="hidden" value="{{ $type }}" name="type" id="type">
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <form action="{{ route('owner.campaigns.find', [$type, '#']) }}" method="GET" id="search-form">
                            @csrf
                            <div class="d-flex justify-content-end mr-3">
                                <div class="input-group" style="display: flex;justify-content: center;align-items: center;
                                    width: 40%;margin-bottom: 15px;">
                                    <input id="search-input" type="search" class="form-control rounded"placeholder="Search" 
                                        aria-label="Search" aria-describedby="search-addon" name="search-input" />
                                    <input type="hidden" name="search-input-value" id="search-input-value" value="" />
                                    <button id="search-button" type="submit" class="btn btn-outline-primary"
                                        style="margin:0;" {{-- onclick="getCustomerByName($('#search-input').val(), $('#type').val())" --}}>
                                        <img src="{{ asset('assets/images/SVG/svg1.svg') }}" alt="" style="max-width: 22px;"></button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            EXPÉDITEUR</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            MESSAGE</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Phone</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Name</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            ENVOI</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            PROGRESSION</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            INSÉRÉ LE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages_details as $messages_detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            {{ $messages_detail->store->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width:0px;">
                                                <div>
                                                    <p class="text-xs font-weight-bold mb-0"
                                                        style="text-overflow: ellipsis; overflow: hidden; width: 265px;">
                                                        {{ $messages_detail->message }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            {{ $messages_detail->customer->phone }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            {{ $messages_detail->customer->firstname }}
                                                            {{ $messages_detail->customer->lastname }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">Auto</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div>
                                                        <div class="progress">
                                                            @if ($messages_detail->status == 1)
                                                                <div class="progress-bar bg-gradient-success"
                                                                    role="progressbar" aria-valuenow="100"
                                                                    aria-valuemin="0"aria-valuemax="100"
                                                                    style="width: 100%;"></div>
                                                            @else
                                                                <div class="progress-bar bg-danger" role="progressbar"
                                                                    aria-valuenow="100" aria-valuemin="0"aria-valuemax="100"
                                                                    style="width: 100%;"></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if ($messages_detail->status == 1)
                                                        <span
                                                            class="me-2 text-xs font-weight-bold"style="margin-left: 7px;padding: 5px;
                                                                    border: green 1px solid;border-radius: 38px;color: green;">100%</span>
                                                    @else
                                                        <span class="me-2 text-xs font-weight-bold"
                                                            style="margin-left: 7px;padding: 5px;
                                                                    border: rgb(128, 0, 0) 1px solid;border-radius: 38px;color: rgb(128, 0, 0);">0%</span>
                                                    @endif

                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $messages_detail->created_at }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $messages_details->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/editor/simple-mde/simplemde.min.js') }}"></script>
    <script src="{{ asset('assets/js/editor/simple-mde/simplemde.custom.js') }}"></script>
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
        $('#search-form').submit(function(e) {
            var searchInputValue = $('#search-input').val();
            var formAction = $(this).attr('action');
            formAction = formAction.replace('#', searchInputValue);
            $(this).attr('action', formAction);
        });

        // function getCustomerByName(name, type) {
        //     console.log(name, type);
        //     var url = "{{ route('owner.campaigns.find', ['type' => ':type', 'name' => ':name']) }}";
        //     url = url.replace(':type', type).replace(':name', name);
        //     $.ajax({
        //         url: url,
        //         type: 'GET',
        //         success: function(data) {
        //             console.log(data);
        //         }
        //     });
        // }
    </script>
@endsection
