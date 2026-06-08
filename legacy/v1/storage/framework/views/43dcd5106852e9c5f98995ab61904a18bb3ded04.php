<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/simple-mde.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-center"><?php echo e(auth()->user()->getFullNameAttribute()); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><?php echo e(trans('dashboard.dashboard')); ?></li>
    <li class="breadcrumb-item active"><?php echo e(trans('Magasin')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg"
                            title="Nouveau Magasin">Nouveau</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display datatables" id="render-datatable">
                                
                                <colgroup>
                                    <col span="8" style="width: 2%;">
                                    <col span="1" style="width: 1%;">
                                </colgroup>
                                
                                <thead>
                                    <tr>
                                        <th><?php echo e(trans('Nom')); ?></th>
                                        <th><?php echo e(trans('Propriétaire')); ?></th>
                                        <th><?php echo e(trans('Sender Id')); ?></th>
                                        <th><?php echo e(trans('Activitée')); ?></th>
                                        <th><?php echo e(trans('TVA')); ?></th>
                                        <th><?php echo e(trans('Téléphone')); ?></th>
                                        <th><?php echo e(trans('Action')); ?></th>
                                        <th><?php echo e(trans('type pos')); ?></th>
                                        <th><?php echo e(trans('Plugin')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th scope="row"><?php echo e($item->name); ?></th>
                                            <td><?php echo e($item->owner->firstname); ?></td>
                                            <td><?php echo e($item->sender_id); ?></td>
                                            <td><?php echo e($item->activity->name); ?></td>
                                            <td><?php echo e($item->tva); ?></td>
                                            <td><?php echo e($item->phone); ?></td>
                                            <td>
                                                <ul>
                                                    <li><i class="fa fa-eye" data-toggle="modal"
                                                            data-target=".bd-example-modal-lg-store-<?php echo e($item->id); ?>"
                                                            title="<?php echo e(trans('Consulter')); ?>"></i></li>
                                                    <li><a href="<?php echo e(url('/admin/stores/' . $item->id . '/edit')); ?>"><i
                                                                class="fa fa-pencil"
                                                                title="<?php echo e(trans('Modifier')); ?>"></i></a></li>
                                                    <?php if($item->status == 1): ?>
                                                        <li><a href="<?php echo e(url('/admin/stores/' . $item->id . '/delete')); ?>"><i
                                                                    class="fa fa-trash"
                                                                    title="<?php echo e(trans('Supprimer')); ?>"></i></a></li>
                                                    <?php else: ?>
                                                        <li><a href="<?php echo e(url('/admin/stores/' . $item->id . '/active')); ?>"><i
                                                                    class="fa fa-check"
                                                                    title="<?php echo e(trans('Activer')); ?>"></i></a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <div style="display: flex;gap:10px;align-items: center;">
                                                    <div style="display: flex ; flex-direction: column;">
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->QG == 1 ? 'checked' : ''); ?> type="checkbox"
                                                                id="QG_Check<?php echo e($item->id); ?>"
                                                                onchange="check_QG($(this).is(':checked'),<?php echo e($item->id); ?>); uncheck_POS($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;" for="QG">QG</label>
                                                        </div>
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->POS == 1 ? 'checked' : ''); ?> type="checkbox"
                                                                id="POS_Check<?php echo e($item->id); ?>"
                                                                onchange="check_POS($(this).is(':checked'),<?php echo e($item->id); ?>); uncheck_QG($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;" for="POS">POS</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="display: flex;gap:10px;align-items: center;">
                                                    <div style="display: flex ; flex-direction: column;">
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->reste == 1 ? 'checked' : ''); ?> disabled
                                                                type="checkbox" id="reste_Check<?php echo e($item->id); ?>"
                                                                onchange="check_Reste($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;"
                                                                for="Reste">Reste</label>
                                                        </div>
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->rendez_vous == 1 ? 'checked' : ''); ?> disabled
                                                                type="checkbox" id="Rendez_vous_Check<?php echo e($item->id); ?>"
                                                                onchange="check_Rendez_vous($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;"
                                                                for="Rendez_vous">Rendez-vous</label>
                                                        </div>
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->praticien == 1 ? 'checked' : ''); ?> disabled
                                                                type="checkbox" id="Praticien_Check<?php echo e($item->id); ?>"
                                                                onchange="check_Praticien($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;"
                                                                for="Praticien">Praticien</label>
                                                        </div>
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->RA == 1 ? 'checked' : ''); ?> disabled
                                                                type="checkbox" id="RA_Check<?php echo e($item->id); ?>"
                                                                onchange="check_RA($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;"
                                                                for="RA">Recherch</label>
                                                        </div>
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->Anniv == 1 ? 'checked' : ''); ?> disabled
                                                                type="checkbox" id="Anniv_Check<?php echo e($item->id); ?>"
                                                                onchange="check_Anniv($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;"
                                                                for="Anniv">Anniversaire</label>
                                                        </div>
                                                        <div style="display: flex; align-items:center; gap:10px;">
                                                            <input <?php echo e($item->Table == 1 ? 'checked' : ''); ?> disabled
                                                                type="checkbox" id="Table_Check<?php echo e($item->id); ?>"
                                                                onchange="check_Table($(this).is(':checked'),<?php echo e($item->id); ?>)">
                                                            <label style=" padding:0; margin:0;"
                                                                for="Table">Table</label>
                                                        </div>
                                                    </div>
                                                    <button style="width: 122px; height:60%;" class="btn btn-info "
                                                        id="buttonRest<?php echo e($item->id); ?>" type="button"
                                                        onclick="verrouiller('#reste_Check<?php echo e($item->id); ?>','#Rendez_vous_Check<?php echo e($item->id); ?>','#Praticien_Check<?php echo e($item->id); ?>',
                                                        '#RA_Check<?php echo e($item->id); ?>','#Anniv_Check<?php echo e($item->id); ?>','#Table_Check<?php echo e($item->id); ?>',<?php echo e($item->id); ?>)">Deverrouiller</button>
                                                </div>
                                            </td>
                                        </tr>
                                        <div id="modal-owner"
                                            class="modal fade bd-example-modal-lg-store-<?php echo e($item->id); ?>"
                                            tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                                            aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myLargeModalLabel">
                                                            <?php echo e(trans('Détail Magasin')); ?></h4>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close" data-original-title=""
                                                            title=""><span aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleFormControlSelect9">Activitées</label>
                                                                    <?php echo e($item->activity->name); ?>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="exampleFormControlSelect9">Sender Id</label>
                                                                <?php echo e($item->sender_id); ?>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="form-group">
                                                                <label for="exampleFormControlSelect9">Propriétaire</label>
                                                                <?php echo e($item->owner->firstname); ?>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer01"><?php echo e(trans('Nom')); ?></label>
                                                            <input readonly class="form-control " id="validationServer01"
                                                                type="text" value="<?php echo e($item->name); ?>"
                                                                required="" data-original-title=""
                                                                title="<?php echo e(trans('Nom du Magasin')); ?>">

                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02"><?php echo e(trans('Contact')); ?></label>
                                                            <input readonly class="form-control " id="validationServer02"
                                                                type="text" value="<?php echo e($item->contact); ?>"
                                                                name="contact" data-original-title=""
                                                                title="<?php echo e(trans('Contact du Magasin')); ?>">

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label
                                                                for="validationServer01"><?php echo e(trans('Addresse')); ?></label><br>
                                                            <?php echo e($item->address); ?>


                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label
                                                                for="validationServer02"><?php echo e(trans('Téléphone')); ?></label>
                                                            <input readonly class="form-control " id="validationServer02"
                                                                type="text" name="phone"
                                                                value="<?php echo e($item->phone); ?>" data-original-title=""
                                                                title="<?php echo e(trans('Téléphone Magasin')); ?>">

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label for="validationServer01"><?php echo e(trans('Logo')); ?></label>
                                                            <img src="<?php echo e($item->logo != '' ? asset(getImageByModel($item->id, 'stores', $item->logo)) : 'xxxxx'); ?>"
                                                                width="25" height="25">

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label
                                                                for="validationServer02"><?php echo e(trans('Base de calcul de la réduction')); ?></label>
                                                            <input readonly class="form-control " id="validationServer02"
                                                                type="text" name="base_calcul"
                                                                value="<?php echo e($item->base); ?>" required=""
                                                                data-original-title=""
                                                                title="Confirmation du mot de passe">

                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label
                                                                for="validationServer02"><?php echo e(trans('Réduction suivant la base')); ?></label>
                                                            <input readonly class="form-control " id="validationServer02"
                                                                type="text" name="base_profit"
                                                                value="<?php echo e($item->base_profit); ?>" data-original-title=""
                                                                title="Confirmation du mot de passe">

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label
                                                                for="validationServer02"><?php echo e(trans('Coéfficient (points)')); ?></label>
                                                            <input class="form-control " id="validationServer02"
                                                                type="text" name="coeff"
                                                                value="<?php echo e($item->coeff); ?>" data-original-title=""
                                                                title="Confirmation du mot de passe">

                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="validationServer02"><?php echo e(trans('TVA')); ?></label>
                                                            <input class="form-control " id="validationServer02"
                                                                type="text" name="tva"
                                                                value="<?php echo e($item->tva); ?>" data-original-title=""
                                                                title="Confirmation du mot de passe">

                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary" data-dismiss="modal"
                                                        aria-label="Close" data-original-title=""
                                                        title=""><?php echo e(trans('Fermer')); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Nouveau Magasin')); ?></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('admin.stores.store')); ?>" method="POST" enctype='multipart/form-data'>
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect9">Activitées</label>
                                    <select class="form-control digits" name="activity_id"
                                        id="exampleFormControlSelect9">
                                        <option>-- <?php echo e(trans('Choisir')); ?> --</option>
                                        <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect9">Propriétaire</label>
                                    <select class="form-control digits" name="owner_id" id="exampleFormControlSelect9">
                                        <option>-- <?php echo e(trans('Choisir')); ?> --</option>
                                        <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->getFullNameAttribute()); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Nom')); ?></label>
                                <input class="form-control " id="validationServer01" type="text" name="store_name"
                                    required="" data-original-title="" title="<?php echo e(trans('Nom du Magasin')); ?>">
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Sender Id')); ?></label>
                                <input class="form-control " id="validationServer01" type="text" name="sender_id"
                                    required="" data-original-title="" title="<?php echo e(trans('Nom du Magasin')); ?>">
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Contact')); ?></label>
                                <input class="form-control " id="validationServer02" type="text" name="contact"
                                    data-original-title="" title="<?php echo e(trans('Contact du Magasin')); ?>">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Addresse')); ?></label>
                                <textarea class="form-control " id="validationServer01" type="text" name="address" data-original-title=""
                                    title="<?php echo e(trans('Addresse Magasin')); ?>"></textarea>

                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Téléphone')); ?></label>
                                <input class="form-control " id="validationServer02" type="text" name="phone"
                                    required="" data-original-title="" title="<?php echo e(trans('Téléphone Magasin')); ?>">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Logo')); ?></label>
                                <input class="form-control" name="logo" type="file" data-original-title=""
                                    title="<?php echo e(trans('Logo Magasin')); ?>">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Base de calcul de la réduction')); ?></label>
                                <input class="form-control " id="validationServer02" type="text" name="base_calcul"
                                    value="100" required="" data-original-title=""
                                    title="Confirmation du mot de passe">

                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Réduction suivant la base')); ?></label>
                                <input class="form-control " id="validationServer02" type="text" name="base_profit"
                                    required="" data-original-title="" title="Confirmation du mot de passe">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Coéfficient (points)')); ?></label>
                                <input class="form-control " id="validationServer02" type="text" name="coeff"
                                    required="" data-original-title="" title="Confirmation du mot de passe">

                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('TVA')); ?></label>
                                <input class="form-control " id="validationServer02" type="text" name="tva"
                                    required="" data-original-title="" title="Confirmation du mot de passe">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <label for="validationServer02"><?php echo e(trans('Remerciement Facture')); ?></label>
                                <div id="editor_container">
                                    <textarea id="editable" name="message_invoice">
                                        Merci pour votre entreprise! Le paiement est prévu dans les 31 jours; veuillez traiter cette facture dans ce délai. Il y aura une charge de 5 intérêts par mois sur les factures en retard.
                                    </textarea>
                                </div>
                                <div id="html_container"></div>
                            </div>

                            <div class="col-md-2"></div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title=""
                            title=""><?php echo e(trans('Enregistrer')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/editor/simple-mde/simplemde.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/editor/simple-mde/simplemde.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/knob/knob.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/knob/knob-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dashboard/default.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/index.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        function uncheck_POS(isChecked, id) {
            console.log(isChecked, id)
            if (isChecked) {
                $('#POS_Check' + id).prop('checked', false);
                check_POS(false, id)
                check_QG(true, id)
            }
        }

        function uncheck_QG(isChecked, id) {
            if (isChecked) {
                $('#QG_Check' + id).prop('checked', false);
                check_QG(false, id)
                check_POS(true, id)
            }
        }

        function check_QG(QG, store_id) {
            $.ajax({
                url: "<?php echo e(route('admin.stores.QG')); ?>",
                type: 'POST',
                data: {
                    QG: QG ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                    console.log(messgae)
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function check_POS(POS, store_id) {
            $.ajax({
                url: "<?php echo e(route('admin.stores.POS')); ?>",
                type: 'POST',
                data: {
                    POS: POS ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                    console.log(messgae)
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function check_Reste(reste, store_id) {
            console.log('reste : ', reste)
            console.log('store_id : ', store_id)
            $.ajax({
                url: "<?php echo e(route('admin.stores.reste_Payer')); ?>",
                type: 'POST',
                data: {
                    reste: reste ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                    console.log(messgae)
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function check_Rendez_vous(rendez_vous, store_id) {
            console.log('rendez_vous : ', rendez_vous)
            console.log('store_id : ', store_id)
            $.ajax({
                url: "<?php echo e(route('admin.stores.rendez_vous_Payer')); ?>",
                type: 'POST',
                data: {
                    rendez_vous: rendez_vous ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                    console.log(messgae)
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function check_Praticien(praticien, store_id) {
            console.log('praticien : ', praticien)
            console.log('store_id : ', store_id)
            $.ajax({
                url: "<?php echo e(route('admin.stores.praticien_Payer')); ?>",
                type: 'POST',
                data: {
                    praticien: praticien ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                    console.log(messgae)
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function check_RA(RA, store_id) {
            console.log('Ra : ', Ra, 'store_id : ', store_id)
            $.ajax({
                url: "<?php echo e(route('admin.stores.RA')); ?>",
                type: 'POST',
                data: {
                    RA: RA ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                    console.log(messgae)
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function check_Anniv(Anniv, store_id) {
            console.log('Anniv : ', Anniv, 'store_id : ', store_id)
            $.ajax({
                url: "<?php echo e(route('admin.stores.Anniv')); ?>",
                type: 'POST',
                data: {
                    Anniv: Anniv ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                    console.log(messgae)
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function check_Table(Table, store_id) {
            console.log('Table : ', Table, 'store_id : ', store_id)
            $.ajax({
                url: "<?php echo e(route('admin.stores.Table')); ?>",
                type: 'POST',
                data: {
                    Table: Table ? 1 : 0,
                    store_id: store_id,
                },
                success: function(data) {
                },
                error: function(response) {
                    var errorMessage = response.responseJSON.msg;
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: errorMessage,
                    });
                }
                
            })
        }


        function verrouiller(a, b, c, d, e, f, g) {
            if ($(a).prop("disabled") && $(b).prop("disabled") && $(c).prop("disabled") &&
                $(d).prop("disabled") && $(e).prop("disabled") && $(f).prop("disabled")) {
                $(a).prop("disabled", false)
                $(b).prop("disabled", false)
                $(c).prop("disabled", false)
                $(d).prop("disabled", false)
                $(f).prop("disabled", false)
                $("#buttonRest" + g).html('Verrouiller');
            } else {
                $(a).prop("disabled", true)
                $(b).prop("disabled", true)
                $(c).prop("disabled", true)
                $(d).prop("disabled", true)
                $(e).prop("disabled", true)
                $(f).prop("disabled", true)
                $("#buttonRest" + g).html('Deverrouiller');
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>