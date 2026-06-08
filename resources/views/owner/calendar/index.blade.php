@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calendar.css') }}">
    <link href="dist/css/bootstrap-colorpicker.css" rel="stylesheet">

@endsection



@section('breadcrumb-title')
    <h3 class="text-left">{{ auth()->user()->getFullNameAttribute() }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ url('dashboard/employe') }}">{{ trans('dashboard.dashboard') }}</a></li>
    <li class="breadcrumb-item active"><a href="{{ url('employe.customers') }}">{{ trans('communs.Clients') }}</a></li>
    <li class="breadcrumb-item active">{{ trans('communs.Modification') }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="">
            <div class="row">
                <div class="col-12">
                    <div class="row card">
                        <div class="card">
                            <div class="card-header">
                                <strong>@lang('Nouveau Rendez Vous')</strong>
                                <br>
                                <strong><span id="error-rdv" style="color: red;"></span></strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-12" style="align-items: left;text-align:right">
                                    <button class="btn btn-primary" type="button" data-toggle="modal"
                                        data-target=".bd-example-modal-lg"
                                        title="{{ trans('communs.Nouveau Client') }}">{{ trans('communs.Nouveau') }}</button>
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01">{{ trans('Client') }}</label>
                                    <select id="customers" class="js-example-basic-single col-sm-12" required
                                        name="customer">
                                        <option value="0">--- {{ trans('communs.Choisir un client') }} ---</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->firstname . ' ' . $item->lastname }} : {{ $item->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01">{{ trans('Date') }}</label>
                                    <input id="example-datetime-local-input start-date" class="form-control digits"
                                        type="datetime-local" data-language="fr">
                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                                    @endif
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01">{{ trans('Service') }}</label>
                                    <select id="events" class="js-example-basic-single col-sm-12" required
                                        name="event">
                                        <option value="0">--- {{ trans('communs.Choisir un Service') }} ---</option>
                                        @foreach ($stores as $store)
                                            @foreach ($store->epingles as $epingle)
                                                <option value="{{ $epingle['color'] }}"
                                                    style="background-color:{{ $epingle['color'] }}">
                                                    {{ $epingle['name'] }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01" style="display:none">{{ trans('Service') }}</label>
                                    <br>
                                    <button class="btn btn-info" type="button" onclick="addToCalendar()"
                                        title="{{ trans('communs.Nouveau Rendez Vous') }}">{{ trans('communs.Ajouter') }}</button>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target=".bd-example-modal1-lg" title="{{ trans('communs.Nouveau Epingles') }}">{{ trans('communs.Nouveau') }}</button>
                                <button class="btn btn-danger" type="button" onclick="DeleteEpingles()" title="{{ trans('communs.Nouveau Epingles') }}">{{ trans('communs.supprimer') }}</button>
    
                                <br>
                                <p>
                                    <div class="card">
                                        <div class="card-body" >
                                            <div class="color-box">
                                                @foreach ($stores as $store)
                                                    @foreach ($store->epingles as $epingle)
                                                    <button data-toggle="modal" data-target=".bd-example-modal2-lg" id="button{{$epingle['id']}}" 
                                                    data-id="{{$epingle['id']}}" class="btn" style="background-color:{{ $epingle['color'] }}" onclick="ShowEpingles({{$epingle['id']}})">{{ $epingle['name'] }}</button>
                                                    <input type="checkbox" name="check" data-id="{{$epingle['id']}}"  id="check{{$epingle['id']}}">
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </p>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Calendrier : {{ auth()->user()->getFullNameAttribute() }}
                        </div>
                        <div class="card-body" id="#rdvs">
                            <div id='calendar'></div>
                        </div>
                    </div>
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
                    <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div id="customersErrors">

                    </div>
                    <form>
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="validationServer01">{{ trans('Store') }}</label>
                                <select id="stores" class="js-example-basic-single col-sm-12" required
                                    name="store_id">
                                    <option value="0">--- {{ trans('communs.Choisir un Magasin') }} ---</option>
                                    @foreach ($stores as $index => $item)
                                        <option value="{{ $item->id }}" {{ $index == 0 ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

                                    {{--Create epingles--}}



    <div id="modal-owner" class="modal fade bd-example-modal1-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Nouveau Epingles') }}</h4>
                    <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div id="customersErrors">

                    </div>
                    <form>
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="validationServer01">{{ trans('Store') }}</label>
                                <select id="stores-id" class="js-example-basic-single col-sm-12" required
                                    name="store_id">
                                    <option value="0">--- {{ trans('communs.Choisir un Magasin') }} ---</option>
                                    @foreach ($stores as $index => $item)
                                        <option value="{{ $item->id }}" {{ $index == 0 ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{ trans('Nom') }}</label>
                                <input class="form-control" id="name" type="text" name="name" value="{{ old('name') }}" data-original-title="" title="{{ trans('Prénom') }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{ trans('color') }}</label>
                                <div id="cp2" class="input-group" title="Using input value">
                                    <input type="text" class="form-control input-lg" id="text-color" value="#DD0F20FF" onchange="colorchange($('#text-color').val())" />
                                    <span class="input-group-append">
                                        <input type="color" id="favcolor" name="favcolor" style="height:38px;" value="#DD0F20FF" >
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button" onclick="createEpingles()"
                            title="{{ trans('Enregistrer') }}">{{ trans('Enregistrer') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

                                    {{--Update epingles--}}

    <div id="modal-owner" class="modal fade bd-example-modal2-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">{{ trans('Modifier Epingles') }}</h4>
                    <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div id="customersErrors">

                    </div>
                    <form>
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="validationServer01">{{ trans('Store') }}</label>
                                <select id="stores-id" class="js-example-basic-single col-sm-12" required
                                    name="store_id">
                                    <option value="0">--- {{ trans('communs.Choisir un Magasin') }} ---</option>
                                    @foreach ($stores as $index => $item)
                                        <option value="{{ $item->id }}" {{ $index == 0 ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">{{ trans('Nom') }}</label>
                                <input class="form-control" id="name-epingles" type="text" name="name-epingles" value="{{ old('name') }}" data-original-title="" title="{{ trans('Prénom') }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">{{ trans('color') }}</label>
                                <div id="cp2" class="input-group" title="Using input value">
                                    <input type="hidden" name="hidden" id="hidden1" hidden >
                                    <input type="text" class="form-control input-lg" id="text-color1" value="#DD0F20FF" onchange="colorchange1($('#text-color1').val())" />
                                    <span class="input-group-append">
                                        <input type="color" id="favcolor1" name="favcolor" style="height:38px;" value="#DD0F20FF" >
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button" onclick="UpdateEpingles()"
                            title="{{ trans('Modifier') }}">{{ trans('Modifier') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js"
        integrity="sha512-94dgCw8xWrVcgkmOc2fwKjO4dqy/X3q7IjFru6MHJKeaAzCvhkVtOS6S+co+RbcZvvPBngLzuVMApmxkuWZGwQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            calendarRender();
        });

        $('#favcolor').on('input', function() {
            var tfo1 = $('#favcolor').val()
            $('#text-color').val(tfo1);
            console.log(tfo1, tfo1.toUpperCase());
        });
        $('#favcolor1').on('input', function() {
            var tfo1 = $('#favcolor1').val()
            $('#text-color1').val(tfo1);
            console.log(tfo1, tfo1.toUpperCase());
        });

        // $('#text-color').on('input', function() {
        //     var tfo2 = $('#text-color').val()
        //     $('#favcolor').attr('value', tfo2.toUpperCase());
        //     console.log(tfo2, tfo2.toUpperCase());
        // });

    //    function changecolor(tfo1) {
    //     $('#text-color').val(tfo1);
    //         console.log(tfo1, tfo1.toUpperCase());
    //    } 
       function colorchange(tfo2) {
        $('#favcolor').val(tfo2);
            console.log(tfo2, tfo2.toUpperCase());
       } 
       function colorchange1(tfo2) {
        $('#favcolor1').val(tfo2);
            console.log(tfo2, tfo2.toUpperCase());
       } 

        function addToCalendar() {
            var customer = $('#customers').select2('data');
            var customerId = '';
            var start = document.getElementById("example-datetime-local-input start-date").value;
            var event = $('#events').select2('data');
            if (customer.length > 0) {
                customerId = customer[0].id;
                var rdv = {
                    title: customer[0].text,
                    start: start,
                    overlap: false,
                    rendering: 'background',
                    color: event[0].id,
                    customerId: customerId,
                    eventId: event[0].id,
                };
                storeRdv(rdv);
                reset();
            }

        }

        function storeRdv(obj) {
            let _token = $('meta[name="csrf-token"]').attr('content');
            if (obj.customerId == "0" || obj.start == "" || obj.eventId == "") {
                $('#error-rdv').html('Vous devez remplir tous les champs du formulaire : Client, Date, Service')
                return;
            } else {
                $('#error-rdv').html('')
                $.ajax({
                    type: 'POST',
                    url: "{{ route('owner.rdv.store') }}",
                    data: {
                        _token: _token,
                        rdv: obj
                    },
                    success: function(data) {
                        calendarRender(obj);
                    }
                });
            }

        }

        function createCustomer() {
            var firstname = $('#firstname').val();
            var lastname = $('#lastname').val();
            var phone = $('#phone').val();
            var sexe = $('#sexe').val();
            var email = $('#email').val();
            var birth = $('#birth').val();
            var store_id = $('#stores').val();
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('owner.customers.store') }}",
                type: 'POST',
                data: {
                    type: 1,
                    firstname: firstname,
                    lastname: lastname,
                    phone: phone,
                    sexe: sexe,
                    email: email,
                    birth: birth,
                    store_id: store_id,
                    _token: _token
                },
                success: function(data) {
                    $('#customers').append(data);
                    $('#close').click();
                },
                error: function(response) {
                    let message = '<span style="color:red">Le N° de Téléphone est invalide</span>';
                    $('#customersErrors').html(message); //
                }
            })
        }

        function createEpingles() {
            var name = $('#name').val();
            var color = $('#text-color').val();
            var store_id = $('#stores-id').val();
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('owner.epingles.store') }}",
                type: 'POST',
                data: {
                    type: 1,
                    name: name,
                    color: color,
                    store_id: store_id,
                    _token: _token
                },
                success: function(data) {
                    window.location.reload();
                },
                error: function(response) {
                    let message = '<span style="color:red">Le N° de Téléphone est invalide</span>';
                    $('#epinglesErrors').html(message); //
                }
            })
        }

        function DeleteEpingles() {
            const inputs = [];
            document.querySelectorAll('input[type="checkbox"]').forEach(e => {
                if (e.checked) inputs.push($(e).data('id'));
            });

            inputs.forEach(e => {
                $('button#' + 'button' + e).remove();
                $('input[type="checkbox"]#' + 'check' + e).remove();

                $.ajax({
                    type: "DELETE",
                    url: '{{ route('owner.epingles.delete') }}',
                    data: {
                        id: e,
                    },
                    success: function(response) {
                    }
                });
            });
        }

        function ShowEpingles(id) {
            console.log(id)
            $.ajax({
                type: "GET",
                url: '{{ route('owner.epingles.show') }}',
                data: {
                    id: id,
                },
                success: function(data) {
                    console.log('------------------------------------');
                    console.log(data);
                    console.log('------------------------------------');
                    $('#name-epingles').attr('value',data['name']);
                    $('#text-color1').val(data['color']);
                    $('#favcolor1').attr('value',data['color']);
                    $('#hidden1').val(data['id'])
                }
            });
        }

        function UpdateEpingles(id) {
            var store_id = $('#stores').val();
            var id = $('#hidden1').val()
            var color = $('#text-color1').val()
            var name = $('#name-epingles').val()
            $.ajax({
                type: "PUT",
                url: '{{ route('owner.epingles.update') }}',
                data: {
                    id,
                    color,
                    name,
                    store_id
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }

        function calendarRender(newRdv = []) {
            let eventsList = [];
            if (newRdv.length > 0) {
                eventsList.push(newRdv);
            }
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'GET',
                url: "{{ route('owner.rdvs') }}",
                success: function(data) {
                    eventsList = data;
                    var Calendar = FullCalendar.Calendar;
                    var Draggable = FullCalendar.Draggable;
                    var containerEl = document.getElementById('external-events');
                    var calendarEl = document.getElementById('calendar');
                    var checkbox = document.getElementById('drop-remove');
                    var calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay',
                            locale: 'fr'
                        },
                        timeZone: 'UTC',
                        locale: 'fr',
                        events: eventsList,
                        editable: true,
                        droppable: true,
                        eventClick: function(event) {
                            var deleteMsg = confirm(
                                "Vous voulez vraiment supprimer ce rendez vous ?");
                            if (deleteMsg) {
                                $.ajax({
                                    type: "POST",
                                    url: '{{ route('owner.rdv.delete') }}',
                                    data: {
                                        eventId: event.event.id,
                                        _token: _token
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        calendarRender();
                                    }
                                });
                            }
                        },
                        eventDrop: function(event, delta) {
                            var start = event.event.start;
                            console.log(start);
                            // console.log(event.view);

                            $.ajax({
                                type: "POST",
                                url: '{{ route('owner.rdv.update') }}',
                                data: {
                                    start: start.toISOString(),
                                    eventId: event.event.id,
                                    _token: _token
                                },
                                success: function(response) {
                                    calendarRender();
                                }
                            });
                        },
                    });
                    calendar.render();
                }
            });

        }

        function reset() {
            $("#customers").select2("val", "0");
            $('#events').select2('val', "0");
            $('#stores').select2('val', "0");
            document.getElementById("example-datetime-local-input start-date").value = "";
        }
    </script>
@endsection
