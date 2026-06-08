@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calendar.css') }}">
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
    <div class="container-fluid">
        <div class="">
            <div class="row">
                <div class="col-4">
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
                                        data-target="#modal-owner"
                                        title="{{ trans('communs.Nouveau Client') }}">{{ trans('communs.Nouveau') }}</button>
                                    <button id="button_details" data-toggle="modal" data-target="#modal-event-owner"
                                        style="display: none">
                                    </button>
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
                                        @foreach ($events as $event)
                                            <option value="{{ $event['color'] }}"
                                                style="background-color:{{ $event['color'] }}">
                                                {{ $event['name'] }}
                                            </option>
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
                                <br>
                                <p>
                                <div class="color-box">
                                    @foreach ($events as $event)
                                        <button class="btn"
                                            style="background-color:{{ $event['color'] }}">{{ $event['name'] }}</button>
                                    @endforeach
                                </div>
                                </p>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            Calendrier : {{ auth()->user()->store->name }}
                        </div>
                        <div class="card-body">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-event-owner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel" style="color: #a927f9">{{ trans('Detail du Rendez-vous') }}</h4>
                </div>
                <div class="modal-body">
                    <div id="customersErrors">

                    </div>
                    <form>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3" id="rdv_details">
                                <label for="validationServer01"></label>>
                            </div>
                        </div>
						<div id="divs_buttons"style=" display: flex; justify-content: flex-end; gap: 10px">
						</div>
                    </form>
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

@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script>
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
                    storeId: "{{ auth()->user()->store->id }}"
                };
                storeRdv(rdv);
                reset();
            }

        }

        const employeRdvDeleteUrl = @json(url('/employe/rdv'));

        function escapeHtml(text) {
            if (text === null || text === undefined) {
                return '';
            }
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function deleteRdvById(id, token, onSuccess) {
            if (!confirm('Vous voulez vraiment supprimer ce rendez-vous ?')) {
                return;
            }
            $.ajax({
                type: 'DELETE',
                url: employeRdvDeleteUrl + '/' + id,
                data: { _token: token },
                success: function() {
                    if (onSuccess) {
                        onSuccess();
                    }
                }
            });
        }

        function storeRdv(obj) {
            let _token = $('meta[name="csrf-token"]').attr('content');
            if (obj.customerId == "0" || obj.start == "" || obj.eventId == "") {
                $('#error-rdv').html('Vous devez remplir tout les champs du formulaire : Client, Date, Service')
                return;
            } else {
                $('#error-rdv').html('')
                $.ajax({
                    type: 'POST',
                    url: "{{ route('employe.rdv.store') }}",
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

        document.addEventListener('DOMContentLoaded', function() {
            calendarRender();
        });

        function calendarRender(newRdv = []) {
            let eventsList = [];
            if (newRdv.length > 0) {
                eventsList.push(newRdv);
            }
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'GET',
                url: "{{ route('employe.rdvs') }}",
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
                        timeZone: 'local',
                        locale: 'fr',
                        events: eventsList,
                        editable: true,
                        droppable: true,
                        eventClick: function(event) {
                            $.ajax({
                                type: "GET",
                                url: '{{ route('employe.rdv.detail') }}',
                                data: {
                                    id: event.event.id,
                                    _token: _token
                                },
                                success: function(response) {
                                    var rdvDetailsDiv = document.getElementById('rdv_details');
                                    var divButtons = document.getElementById('divs_buttons');
                                    rdvDetailsDiv.innerHTML ='';
									divButtons.innerHTML ='';

                                    var customerInfo = response.customer[0];
                                    var rdvInfo = response.rdv;

                                    var content = '<p>Client : ' + escapeHtml(customerInfo.firstname) + ' ' + escapeHtml(customerInfo.lastname) + '</p>';
                                    content += '<p>Télephone : ' + escapeHtml(customerInfo.phone) + '</p>';
                                    content += '<p>Date du rendez-vous : ' + escapeHtml(rdvInfo.start) + '</p>';

									var buttons ='<button class="btn btn-primary" type="button" data-dismiss="modal">Fermer</button>'
									buttons +='<button type="button" class="btn btn-danger" id="rdv-delete-btn" data-rdv-id="'+rdvInfo.id+'">Supprimer</button>';

                                    rdvDetailsDiv.innerHTML = content;
                                    divButtons.innerHTML = buttons;
                                    document.getElementById('rdv-delete-btn').addEventListener('click', function() {
                                        deleteRdvById(rdvInfo.id, _token, function() {
                                            calendarRender();
                                        });
                                    });
									toggle_modal_rdv = document.getElementById('button_details')
									toggle_modal_rdv.click()
                                }
                            });
                        },
                        // eventClick: function(event) {
                        // 	var deleteMsg = confirm("Vous voulez vraiment supprimer ce rendez vous ?");
                        // 	if (deleteMsg) {
                        // 		$.ajax({
                        // 			type: "POST",
                        // 			url: '{{ route('employe.rdv.delete') }}',
                        // 			data: {
                        // 				eventId: event.event.id,
                        // 				_token: _token
                        // 			},
                        // 			success: function(response) {
                        // 				console.log(response);
                        // 				calendarRender();
                        // 			}
                        // 		});
                        // 	}
                        // },
                        eventDrop: function(event, delta) {
                            var start = event.event.start;
                            console.log(start);
                            $.ajax({
                                type: "POST",
                                url: '{{ route('employe.rdv.update') }}',
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
                error: function(response) {
                    let message = '<span style="color:red">Le N° de Téléphone est invalide</span>';
                    $('#customersErrors').html(message); //
                }
            })
        }

        function reset() {
            $("#customers").select2("val", "0");
            $('#events').select2('val', "0");
            document.getElementById("example-datetime-local-input start-date").value = "";
        }
    </script>
@endsection
