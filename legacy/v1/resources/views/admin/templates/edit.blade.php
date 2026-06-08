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
     <h3 class="text-center">{{auth()->user()->getFullNameAttribute()}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('/dashboard/admin') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('admin.templates') }}">{{ trans('Templates') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('Modification') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ trans('Modification du Template') }} {{ $template->template_name }}</h2>
                    </div>
                    <div class="card-body">


                        <form action="{{ route('admin.templates.update',$template->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer01">{{ trans('Nom du Template') }}</label>
                                    <input class="form-control is-valid" value="{{$template->template_name}}" id="validationServer01" type="text"
                                        name="template_name" required="" data-original-title="" title="">
                                    <div class="valid-feedback">[NOM] pour inclure le nom du client</div>
                                    <div class="valid-feedback">[CONTRAT] pour inclure le numéro du contrat du client
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationServer02">{{ trans('Contenu') }}</label>
                                    <textarea class="form-control is-valid" id="validationServer02" name="template_content"
                                        required="" data-original-title="" title="">{{$template->template_content}}</textarea>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" data-original-title=""
                                title="{{ trans('Enregistrer') }}">{{ trans('Enregistrer') }}</button>
                        </form>
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
@endsection
