@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calendar.css') }}">
    <style>
        #style-7::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        #style-7::-webkit-scrollbar {
            width: 10px;
            background-color: #F5F5F5;
        }

        #style-7::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background-image: -webkit-gradient(linear,
                    left bottom,
                    left top,
					color-stop(0.86, rgb(115, 102, 255)),
					color-stop(0.44, rgb(207, 49, 247))
                    /* color-stop(0.72, rgb(247, 49, 100)), */
			);
        }
    </style>
@endsection



@section('breadcrumb-title')
    <h3 class="text-left">{{ auth()->user()->store->name }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active"><a href="{{ url('employe.customers') }}">{{ trans('communs.Clients') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('communs.Modification') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-4 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Les anniversaires d'aujourd'hui</h4>
                    <p class="f-m-light mt-1">Anniversaires list</p>
                </div>
                <div class="card-body" style="max-height: 430px;overflow:auto;" id="style-7">
                    <div class="list-group">
                        @foreach ($customers_today as $ct)
                            <a class="list-group-item list-group-item-action list-hover-primary"" href="#"
                                aria-current="true">
                                <div class="list-wrapper gap-0">
                                    <div class="list-content">
                                        <h6>{{ $ct->lastname . ' ' . $ct->firstname }}</h6>
                                        <p style="margin: 0">{{ $ct->phone }}</p>
                                        <p>{{ $ct->birth }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Les anniversaires de cette semaine</h4>
                    <p class="f-m-light mt-1">Anniversaires list</p>
                </div>
                <div class="card-body" style="max-height: 430px;overflow:auto;"id="style-7">
                    <div class="list-group">
                        @foreach ($customers_week as $ct)
                            {{-- <a class="list-group-item list-group-item-action active list-hover-primary" href="#" aria-current="true"> --}}
                            <a class="list-group-item list-group-item-action list-hover-primary"" href="#"
                                aria-current="true">
                                <div class="list-wrapper gap-0">
                                    <div class="list-content">
                                        <h6>{{ $ct->lastname . ' ' . $ct->firstname }}</h6>
                                        <p style="margin: 0">{{ $ct->phone }}</p>
                                        <p>{{ $ct->birth }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Les anniversaires de ce mois</h4>
                    <p class="f-m-light mt-1">Anniversaires list</p>
                </div>
                <div class="card-body" style="max-height: 430px;overflow:auto;"id="style-7">
                    <div class="list-group">
                        @foreach ($mergedCollection as $ct)
                            <a class="list-group-item list-group-item-action list-hover-primary"" href="#"
                                aria-current="true">
                                <div class="list-wrapper gap-0">
                                    <div class="list-content">
                                        <h6>{{ $ct->lastname . ' ' . $ct->firstname }}</h6>
                                        <p style="margin: 0">{{ $ct->phone }}</p>
                                        <p>{{ $ct->birth }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

@endsection
