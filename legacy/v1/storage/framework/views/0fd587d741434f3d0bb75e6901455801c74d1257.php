<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/calendar.css')); ?>">
<?php $__env->stopSection(); ?>



<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-left"><?php echo e(auth()->user()->store->name); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard/employe')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><a href="<?php echo e(url('employe.customers')); ?>"><?php echo e(trans('communs.Clients')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(trans('communs.Modification')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="">
            <div class="row">
                <div class="col-4">
                    <div class="row card">
                        <div class="card">
                            <div class="card-header">
                                <strong><?php echo app('translator')->getFromJson('Nouveau Rendez Vous'); ?></strong>
                                <br>
                                <strong><span id="error-rdv" style="color: red;"></span></strong>
                            </div>
                            <div class="card-body row">
                                <div class="col-12" style="align-items: left;text-align:right">
                                    <button class="btn btn-primary" type="button" data-toggle="modal"
                                        data-target="#modal-owner"
                                        title="<?php echo e(trans('communs.Nouveau Client')); ?>"><?php echo e(trans('communs.Nouveau')); ?></button>
                                    <button id="button_details" data-toggle="modal" data-target="#modal-event-owner"
                                        style="display: none">
                                    </button>
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01"><?php echo e(trans('Client')); ?></label>
                                    <select id="customers" class="js-example-basic-single col-sm-12" required
                                        name="customer">
                                        <option value="0">--- <?php echo e(trans('communs.Choisir un client')); ?> ---</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>">
                                                <?php echo e($item->firstname . ' ' . $item->lastname); ?> : <?php echo e($item->phone); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01"><?php echo e(trans('Date')); ?></label>
                                    <input id="example-datetime-local-input start-date" class="form-control digits"
                                        type="datetime-local" data-language="fr">
                                    <?php if($errors->has('password')): ?>
                                        <div class="invalid-feedback"><?php echo e($errors->first('date')); ?></div>
                                    <?php endif; ?>
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01"><?php echo e(trans('Service')); ?></label>
                                    <select id="events" class="js-example-basic-single col-sm-12" required
                                        name="event">
                                        <option value="0">--- <?php echo e(trans('communs.Choisir un Service')); ?> ---</option>
                                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($event['color']); ?>"
                                                style="background-color:<?php echo e($event['color']); ?>">
                                                <?php echo e($event['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <br>
                                <div class="col-12">
                                    <label for="validationServer01" style="display:none"><?php echo e(trans('Service')); ?></label>
                                    <br>
                                    <button class="btn btn-info" type="button" onclick="addToCalendar()"
                                        title="<?php echo e(trans('communs.Nouveau Rendez Vous')); ?>"><?php echo e(trans('communs.Ajouter')); ?></button>
                                </div>
                            </div>
                            <div class="card-footer">
                                <br>
                                <p>
                                <div class="color-box">
                                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button class="btn"
                                            style="background-color:<?php echo e($event['color']); ?>"><?php echo e($event['name']); ?></button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                </p>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            Calendrier : <?php echo e(auth()->user()->store->name); ?>

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
                    <h4 class="modal-title" id="myLargeModalLabel" style="color: #a927f9"><?php echo e(trans('Detail du Rendez-vous')); ?></h4>
                </div>
                <div class="modal-body">
                    <div id="customersErrors">

                    </div>
                    <form>
                        <?php echo csrf_field(); ?>
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
                    <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Nouveau Client')); ?></h4>
                    <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div id="customersErrors">

                    </div>
                    <form>
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Nom')); ?></label>
                                <input class="form-control" id="firstname" type="text" name="firstname"
                                    value="<?php echo e(old('firstname')); ?>" data-original-title=""
                                    title="<?php echo e(trans('Prénom')); ?>">
                                <?php if($errors->has('firstname')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('firstname')); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Prénom')); ?></label>
                                <input class="form-control " id="lastname" type="text" name="lastname"
                                    value="<?php echo e(old('lastname')); ?>" data-original-title="" title="<?php echo e(trans('Nom')); ?>">
                                <?php if($errors->has('lastname')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('lastname')); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('E-mail')); ?></label>
                                <input class="form-control" id="email" type="email" name="email"
                                    value="<?php echo e(old('email')); ?>" data-original-title="" title="<?php echo e(trans('E-mail')); ?>">
                                <?php if($errors->has('email')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('email')); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Téléphone')); ?></label>
                                <input class="form-control" id="phone" type="text" name="phone"
                                    value="212<?php echo e(old('phone')); ?>" required="" data-original-title=""
                                    title="<?php echo e(trans('Téléphone')); ?>">
                                <?php if($errors->has('phone')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('phone')); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Date de Naissance')); ?></label>
                                <input id="birth" class="form-control" type="date" name="birth"
                                    data-language="en">
                                <?php if($errors->has('password')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('birth')); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Sexe')); ?></label>
                                <select class="form-control digits" name="sexe" id="sexe">
                                    <option>--- <?php echo e(trans('Choisir Sexe')); ?> --</option>
                                    <option value="0"><?php echo e(trans('Homme')); ?></option>
                                    <option value="1"><?php echo e(trans('Femme')); ?></option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button" onclick="createCustomer()"
                            title="<?php echo e(trans('Enregistrer')); ?>"><?php echo e(trans('Enregistrer')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
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
                    storeId: "<?php echo e(auth()->user()->store->id); ?>"
                };
                storeRdv(rdv);
                reset();
            }

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
                    url: "<?php echo e(route('employe.rdv.store')); ?>",
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
                url: "<?php echo e(route('employe.rdvs')); ?>",
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
                                url: '<?php echo e(route('employe.rdv.detail')); ?>',
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

                                    var content = '<p>Client : ' + customerInfo.firstname + ' ' + customerInfo.lastname +'</p>';
                                    content += '<p>Télephone : ' + customerInfo.phone +'</p>';
                                    content += '<p>Date du rendez-vous : ' + rdvInfo.start +'</p>';

									var buttons ='<button class="btn btn-primary" type="button" data-dismiss="modal">Fermer</button>'
									buttons +='<a href="http://127.0.0.1:8000/employe/rdv/delete-rdv/'+rdvInfo.id+'" class="btn btn-danger" >Supprimer</a>';

                                    rdvDetailsDiv.innerHTML = content;
                                    divButtons.innerHTML = buttons;
									toggle_modal_rdv = document.getElementById('button_details')
									toggle_modal_rdv.click()
									calendarRender();
                                }
                            });
                        },
                        // eventClick: function(event) {
                        // 	var deleteMsg = confirm("Vous voulez vraiment supprimer ce rendez vous ?");
                        // 	if (deleteMsg) {
                        // 		$.ajax({
                        // 			type: "POST",
                        // 			url: '<?php echo e(route('employe.rdv.delete')); ?>',
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
                                url: '<?php echo e(route('employe.rdv.update')); ?>',
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
            var user_id = "<?php echo e(auth()->user()->id); ?>";
            var store_id = "<?php echo e(auth()->user()->store->id); ?>";
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "<?php echo e(route('employe.customers.store')); ?>",
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>