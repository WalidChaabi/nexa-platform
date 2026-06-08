<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .qts {
            width: 52px !important;
        }

        .select2 {
            border: 2px solid #a927f9
        }

        @media (min-width: 768px) {
            .col-md-4 {
                max-width: none;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-center"><?php echo e(auth()->user()->store->name); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard/employe')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><a href="<?php echo e(route('employe.invoices')); ?>"><?php echo e(trans('communs.Factures')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(trans('communs.Nouveau')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <?php if(isset($type)): ?>
                <input type="hidden" name="praticienType" value="<?php echo e($type); ?>">
                <?php endif; ?>
                <div class="card">
                    <div class="card-header">
                        <h3> <?php echo e(trans('communs.Nouvelle Facture')); ?> : <?php echo e(date('Y-m-d H:i:s')); ?> </h3>
                    </div>
                    <div class="card-body">
                        <div class="customersErrors">

                        </div>
                        <?php if($errors->any()): ?>
                            <span style="color:red"> <?php echo e(implode('', $errors->all(':message'))); ?></span>
                        <?php endif; ?>
                        <form action="<?php echo e(route('employe.invoices.store')); ?>" method="POST" id="MyForm">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><?php echo e(trans('communs.Reduction en Points')); ?></strong> : <span
                                        id="points_red">0</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><?php echo e(trans('communs.Reduction en MAD')); ?></strong> : <span
                                        id="mad_red">0</span>
                                    <input type="hidden" id="mad_red_hidden">
                                </div>
                            </div>
                            <br><br>
                            <?php if(auth()->user()->store->id == 1): ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong><?php echo e(trans('Homme')); ?></strong> : <input type="radio" value="1"
                                            name="for" checked>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Femme</strong> : <input type="radio" value="2" name="for">
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Mix</strong> : <input type="radio" value="3" name="for">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer01"><?php echo e(trans('communs.Informations du Client')); ?></label>
                                    <select id="customers" class="js-example-basic-single col-sm-12"
                                        onchange="getClientPoints($(this).val())" required name="customer">
                                        <option value="0">--- <?php echo e(trans('communs.Choisir un client')); ?> ---</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>">
                                                <?php echo e($item->firstname . ' ' . $item->lastname); ?> : <?php echo e($item->phone); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex" style="justify-content: flex-start">
                                    <div class="d-flex" style="flex-direction: column">
                                        <label for="validationServer02"><?php echo e(trans('communs.Nouveau Client')); ?></label>
                                        <button class="btn btn-info" type="button" data-toggle="modal"
                                            data-target=".bd-example-modal-lg"
                                            title="<?php echo e(trans('communs.Nouveau Client')); ?>"><?php echo e(trans('communs.Nouveau')); ?></button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <?php if(auth()->user()->store->praticien_2 == 1): ?>
                                <div class="row" id="produitPraicien">
                                    <div class="col-md-4 d-flex" style="flex-direction: column">
                                        <label for="validationServer01"><?php echo e(trans('communs.Produit')); ?> :</label>
                                        <select onchange="setPriceProduct(this, this.value,1)"
                                            class="js-example-basic-single col-sm-12" name="products[]">
                                            <option data-tokens="0">--- <?php echo e(trans('communs.Choisir Produit/Service')); ?>

                                                ---
                                            </option>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option data-tokens="<?php echo e($item->label); ?>" value="<?php echo e($item->id); ?>">
                                                    <?php echo e($item->label); ?> | <?php echo e($item->price); ?> Mad
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex" style="flex-direction: column">
                                        <label for="validationServer01"><?php echo e(trans('communs.Praticien')); ?> :</label>
                                        <select class="js-example-basic-single col-sm-12" name="userId"
                                            onchange="getPraticien($(this).val())">
                                            <option value="0">---
                                                <?php echo e(trans('communs.Choisir le/la Praticien/ne')); ?>

                                                ---</option>
                                            <?php $__currentLoopData = $employes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>">
                                                    <?php echo e($item->getFullNameAttribute()); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex" style="align-items: flex-start;justify-content: space-between;">
                                            <div class="col-md-3 pl-0">
                                                <label for="validationServer02"><?php echo e(trans('communs.Prix')); ?></label>
                                                <input class="form-control prices" disabled value="0" id="price_1"
                                                    type="text" name="price[]" data-original-title=""
                                                    title="<?php echo e(trans('communs.Prix')); ?>">
                                            </div>
                                            <div class="col-md-4 d-flex">
                                                <div class="d-flex mr-3 mb-3" style="flex-direction: column">
                                                    <label
                                                        for="validationServer02"><?php echo e(trans('communs.Quantité')); ?></label>
                                                    <input class="form-control qts" value="1" id="qte_1"
                                                        name="quantity[]" min="1" required
                                                        onchange="changeTotalOnce()" data-original-title=""
                                                        title="<?php echo e(trans('communs.Quantité')); ?>">
                                                </div>
                                                <div class="d-flex" style="flex-direction: column ;justify-content :flex-end;">
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;">+</span>
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,-1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;margin-top:10px;">-</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="validationServer02"><?php echo e(trans('communs.Ajouter')); ?></label>
                                                <button class="form-control btn btn-primary" type="button"
                                                    style="height: 37px!important;display: flex;align-items: center;justify-content: center;"
                                                    onclick="addProductRow('<?php echo e($type); ?>')"
                                                    title="<?php echo e(trans('communs.Ajouter Produit/Service')); ?>">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <div class="col-md-6 d-flex" style="flex-direction: column">
                                        <label for="validationServer01"><?php echo e(trans('communs.Produit')); ?> :</label>
                                        <select onchange="setPriceProduct(this, this.value,1)"
                                            class="js-example-basic-single col-sm-12" name="products[]">
                                            <option data-tokens="0">--- <?php echo e(trans('communs.Choisir Produit/Service')); ?> ---
                                            </option>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option data-tokens="<?php echo e($item->label); ?>" value="<?php echo e($item->id); ?>">
                                                    <?php echo e($item->label); ?> | <?php echo e($item->price); ?> Mad
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex"
                                            style="align-items: flex-start;justify-content: space-between;">
                                            <div class="col-md-3 pl-0">
                                                <label for="validationServer02"><?php echo e(trans('communs.Prix')); ?></label>
                                                <input class="form-control prices" disabled value="0" id="price_1"
                                                    type="text" name="price[]" data-original-title=""
                                                    title="<?php echo e(trans('communs.Prix')); ?>">
                                            </div>
                                            <div class="col-md-4 d-flex">
                                                <div class="d-flex mr-3 mb-3" style="flex-direction: column">
                                                    <label
                                                        for="validationServer02"><?php echo e(trans('communs.Quantité')); ?></label>
                                                    <input class="form-control qts" value="1" id="qte_1"
                                                        name="quantity[]" min="1" required
                                                        onchange="changeTotalOnce()" data-original-title=""
                                                        title="<?php echo e(trans('communs.Quantité')); ?>">
                                                </div>
                                                <div class="d-flex"
                                                    style="flex-direction: column ;justify-content :flex-end;">
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;">+</span>
                                                    <span class="btn btn-primary" onclick="changeQuantity(1,-1)"
                                                        style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;margin-top:10px;">-</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="validationServer02"><?php echo e(trans('communs.Ajouter')); ?></label>
                                                <button class="form-control btn btn-primary" type="button"
                                                    style="height: 37px!important;display: flex;align-items: center;justify-content: center;"
                                                    onclick="addProductRow('<?php echo e($type); ?>')"
                                                    title="<?php echo e(trans('communs.Ajouter Produit/Service')); ?>">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <br>
                            <div id="products-row">
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.Description')); ?> :</label>
                                    <textarea class="form-control " id="validationServer02" type="text" name="description"
                                        title="<?php echo e(trans('communs.Description')); ?>"></textarea>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="validationServer02"><?php echo e(trans('communs.Mode de Paiement')); ?></label>
                                        <div class="custom-radio-ml">
                                            <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="radio1" type="radio"
                                                    name="payment_method" value="1" checked="">
                                                <label class="form-check-label"
                                                    for="radio1"><?php echo e(trans('communs.Espèces')); ?><span
                                                        class="digits"></span></label>
                                            </div>
                                            <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="radio3" type="radio"
                                                    name="payment_method" value="2">
                                                <label class="form-check-label"
                                                    for="radio3"><?php echo e(trans('communs.Chèque')); ?><span
                                                        class="digits"></span></label>
                                            </div>
                                            <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="radio4" type="radio"
                                                    name="payment_method" value="3">
                                                <label class="form-check-label"
                                                    for="radio4"><?php echo e(trans('communs.Carte Bancaire')); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <label class="mt-0" for="inline-sqr-1"><?php echo e(trans('communs.Utiliser la reduction')); ?>

                                        ? <span id="message_using_points"
                                            style="color:red;display:none"><?php echo e(trans('Action non Authorisé!')); ?></span></label>
                                    <div class="form-check radio radio-primary" id="use_points_true">
                                        <input class="form-check-input" id="radio5"="useRed($(this).val())"
                                            type="radio" name="use_points" value="1">
                                        <label class="form-check-label" for="radio5"><?php echo e(trans('communs.Oui')); ?><span
                                                class="digits"></span></label>
                                    </div>
                                    <div class="form-check radio radio-primary">
                                        <input class="form-check-input" id="radio6" onclick="useRed($(this).val())"
                                            type="radio" name="use_points" value="0" checked="">
                                        <label class="form-check-label" for="radio6"><?php echo e(trans('communs.Non')); ?><span
                                                class="digits"></span></label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="form-control " id="code_red" type="text" name="code_red"
                                        value="" placeholder="<?php echo e(trans('communs.CODE')); ?>" data-original-title=""
                                        title="<?php echo e(trans('communs.CODE REDUCTION')); ?>">
                                    <input type="hidden" value="0" class="form-control " id="code_valide"
                                        type="text" name="code_valide" value="" data-original-title=""
                                        title="<?php echo e(trans('communs.CODE REDUCTION')); ?>">
                                    <div>
                                        <span style="color:red" id="code_red_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="button" placeholder="<?php echo e(trans('communs.valider')); ?>"
                                        class="btn btn-primary form-control" style="width:115px;"
                                        onclick="calculateRed($('#code_red').val(),$('#customers').val(),<?php echo e(auth()->user()->store->id); ?>)"
                                        value="<?php echo e(__('Valider')); ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.Total HT')); ?></label>
                                    <input readonly class="form-control " id="total_ht" required type="text"
                                        name="total_ht" value="0" data-original-title=""
                                        title="<?php echo e(trans('communs.Total Ht')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.TVA')); ?></label>
                                    <input readonly class="form-control" value="<?php echo e(auth()->user()->store->tva); ?>%"
                                        title="<?php echo e(trans('communs.TVA')); ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.Total TTC')); ?></label>
                                    <input readonly class="form-control" name="total" id="total" type="text"
                                        value="0" title="<?php echo e(trans('communs.TOTAL TTC')); ?>">
                                </div>
                                <?php if(auth()->user()->store->reste == 0): ?>
                                    <div class="col-md-6">
                                        <label for="validationServer02"><?php echo e(trans('communs.Montant Payé')); ?></label>
                                        <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                            value="0" data-original-title=""
                                            title="<?php echo e(trans('communs.Montant Payé')); ?>">
                                    </div>
                                <?php else: ?>
                                    <div class="col-md-3">
                                        <label for="validationServer02"><?php echo e(trans('communs.Montant Payé')); ?></label>
                                        <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                            value="0" data-original-title=""
                                            title="<?php echo e(trans('communs.Montant Payé')); ?>">
                                    </div>
                                    <div class="col-md-3"
                                        style="display: flex; position: relative; flex-direction: column;">
                                        <label for="validationServer02"
                                            style="left: 25px; position: relative;"><?php echo e(trans('communs.Reste')); ?></label>
                                        <div class="check" style="display: flex; gap:10px;">
                                            <input type="checkbox" id="fluency" name="ResteCheck">
                                            <input readonly class="form-control " id="Reste" type="text"
                                                name="Reste" value="0" title="<?php echo e(trans('communs.Reste')); ?>">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <br>
                            <input id="total_hidden" type="hidden" value="0">
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit" data-original-title=""
                                        title="<?php echo e(trans('communs.Enregistrer')); ?>"><?php echo e(trans('communs.Enregistrer')); ?>

                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

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
                
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="validationServer01"><?php echo e(trans('Nom')); ?></label>
                        <input class="form-control" id="firstname" type="text" name="firstname"
                            value="<?php echo e(old('firstname')); ?>" data-original-title="" title="<?php echo e(trans('Prénom')); ?>">
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
                
            </div>
        </div>
    </div>
</div>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select2/select2-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
    <script>
        var type = <?php echo json_encode($type, 15, 512) ?>;
        const form = document.getElementById('MyForm');

        function addProductRow(type) {
            type == "praticien_1" ? url = '/employe/invoices/products/1' : url = '/employe/invoices/products/2'
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#products-row').append(data.html)
                }
            })
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const userData = collectUserData();
            const formData = new FormData(form);
            if (type = "praticien_2") {
                formData.append('userData', JSON.stringify(userData));
                formData.append('type', JSON.stringify(type));
            }

            $.ajax({
                url: "<?php echo e(route('employe.invoices.store')); ?>",
                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(data) {
                    localStorage.setItem('printUrl', JSON.stringify(data));
                    window.location.href = 'http://127.0.0.1:8000/employe/invoices';
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        function collectUserData() {
            const produitPraicien = document.querySelectorAll("#produitPraicien");
            const userData = [];

            produitPraicien.forEach((div) => {
                const productSelect = div.querySelector('select[name="products[]"]');
                const userSelect = div.querySelector('select[name="userId"]');
                const productQuantity = div.querySelector('input[name="quantity[]"]');
                const productId = productSelect.value;
                const userId = userSelect.value;
                const Quantity = productQuantity.value;

                if (productId !== "0" && userId !== "0") {
                    userData.push({
                        userId: userId,
                        product: productId,
                        quantity: Quantity
                    });
                }
            });
            return userData;
        }


        function removeProductRow(id) {
            var div = document.getElementById(id);
            div.remove();
            var prices = [];
            var qts = [];
            var total = 0;

            $(".prices").each(function(index) {
                prices.push($(this).val())
            });
            $('.qts').each(function(qte) {
                qts.push($(this).val());
            });
            Object.entries(prices).forEach(([key, value]) => {
                Object.entries(qts).forEach(([keyQts, valueQts]) => {
                    if (key == keyQts) {
                        console.log('ha qte ' + valueQts)
                        console.log('ha price dialha ' + value)
                        total = total + (value * valueQts);

                    }
                })

            });
            var newValue = (total);
            $('#total').attr('value', newValue);
            $('#total_hidden').attr('value', newValue);
            $('#total_hidden_ttc').attr((total + (total + (total * "<?php echo e(auth()->user()->store->tva); ?>" / 100))))
            $('#total_ht').attr('value', total);
            $('#to_paye').attr('value', (total + (total * "<?php echo e(auth()->user()->store->tva); ?>" / 100)))
        }

        function setPriceProduct(element, id, price_id) {
            console.log(id, element, price_id, 'setPrice to product');
            $.ajax({
                url: "<?php echo e(route('employe.productPrice')); ?>",
                data: {
                    id: id,
                    productId: id,
                },
                type: 'GET',
                success: function(data) {
                    $("#price_" + price_id).val(data.price);
                    changeTotalOnce();
                    changeTotalOnceHT();
                },
                error: function(error) {
                    console.log(error)
                }
            });
        }

        function changeTotalOnce() {
            var prices = [];
            var qts = [];
            var total = 0;

            $(".prices").each(function(index) {
                prices.push($(this).val())
            });
            $('.qts').each(function(qte) {
                qts.push($(this).val());
            });
            Object.entries(prices).forEach(([key, value]) => {
                Object.entries(qts).forEach(([keyQts, valueQts]) => {
                    if (key == keyQts) {
                        total = total + (value * valueQts);
                    }
                })

            });
            changeTotalOnceHT();
            $('#total').attr('value', (total + (total * "<?php echo e(auth()->user()->store->tva); ?>" / 100)))
            $('#total_hidden').attr('value', (total))
            $('#total_hidden_ttc').attr((total + (total * "<?php echo e(auth()->user()->store->tva); ?>" / 100)))
            $('#to_paye').attr('value', (total + (total * "<?php echo e(auth()->user()->store->tva); ?>" / 100)))
        }

        function changeTotalOnceHT() {

            var prices = [];
            var qts = [];
            var total = 0;

            $(".prices").each(function(index) {
                prices.push($(this).val())
            });
            $('.qts').each(function(qte) {
                qts.push($(this).val());
            });
            Object.entries(prices).forEach(([key, value]) => {
                Object.entries(qts).forEach(([keyQts, valueQts]) => {
                    if (key == keyQts) {
                        total = total + (value * valueQts);
                    }
                })

            });
            $('#total_ht').attr('value', total)
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
                    console.log(messgae)
                    let message = '<span style="color:red">Le N° de Téléphone est invalide</span>';
                    $('#customersErrors').html(message); //
                }
            })
        }

        function getClientPoints(customer_id) {
            $.ajax({
                url: "<?php echo e(route('employe.customers.points')); ?>",
                type: 'GET',
                data: {
                    customer_id: customer_id
                },
                success: function(data) {
                    console.log('reste cr inov', data);
                    $('#mad_red').html(data['mad_red']);
                    $('#points_red').html(data['points_red']);
                    // $('#Reste').attr('value',data['reste']);

                    $('#mad_red_hidden').attr('value', data['mad_red']);
                    if (data['points_red'] <= 0) {
                        $('#use_points_true').css('display', 'none');
                        $('#message_using_points').css('display', 'block')
                    } else {
                        $('#use_points_true').css('display', 'block');
                        $('#message_using_points').css('display', 'none')

                    }
                    // if (data['reste'] > 0) {
                    //     $('#champ_reste').css('color', '#d35959');
                    // }
                }
            })
            getReset(customer_id)
        }

        function getReset(customer_id) {
            $.ajax({
                url: "<?php echo e(route('employe.invoices.reste')); ?>",
                type: 'GET',
                data: {
                    customer_id: customer_id
                },
                success: function(data) {
                    console.log('reste cr inov', data);

                    $('#Reste').attr('value', data['reste']);

                    if (data['reste'] > 0) {
                        $('#champ_reste').css('color', '#d35959');
                    }
                }
            })
        }
        function restePayer() {
            var customer_id = $('#customers').val()
            var reste = $('#Reste').val();
            console.log('customer_id : ', customer_id)
            let _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "<?php echo e(route('employe.invoices.restePayer')); ?>",
                type: 'POST',
                data: {
                    reste: reste,
                    customer_id: customer_id,
                    _token: _token
                },
                success: function(data) {
                    let message = '<span style="color:red">Reste est payer</span>';
                    $('#validatMsg').html(message); //
                },
                error: function(response) {
                    console.log(messgae)
                }
            })
        }

        function useRed(value) {
            if ($('#customers').val() != 0) {
                changeTotalOnce();
                changeTotalOnceHT();
                if (value == 1) {
                    var totalHtWithRed = $("#total_ht").val() - $('#mad_red_hidden').val();
                    $('#total_ht').attr('value', $("#total_ht").val() - $('#mad_red_hidden').val());
                    //Recalculat TTC 

                    $('#total').attr('value', (Math.ceil(totalHtWithRed * (1 + ("<?php echo e(auth()->user()->store->tva); ?>" /
                        100)))));
                    $('#to_paye').attr('value', (Math.ceil(totalHtWithRed * (1 + ("<?php echo e(auth()->user()->store->tva); ?>" /
                        100)))));
                } else {
                    var total = $('#total_hidden').val();
                    $('#total_ht').attr('value', total);

                }
            } else {
                alert('vous devez choisir Un client ')
            }

        }

        function changeQuantity(id, op) {
            var currentQte = $('#qte_' + id).val();
            if (typeof currentQte === 'undefined') {
                currentQte = 0;
            }
            if (op == 1) {
                $('#qte_' + id).attr('value', (parseInt(currentQte) + 1));
            } else if (op == -1) {
                if ((parseInt(currentQte) - 1) > 0) {
                    $('#qte_' + id).attr('value', (parseInt(currentQte) - 1));
                }
            }
            changeTotalOnce();
        }

        function calculateRed(code, customer_id, store_id) {
            if (code != '' || customer != undefined) {
                $.ajax({
                    url: "<?php echo e(url('employe/customer/code/red')); ?>",
                    data: {
                        code: code,
                        customer_id: customer_id,
                        store_id: store_id
                    },
                    type: 'GET',
                    success: function(data) {
                        if (data.success == 1) {
                            console.log(data.code);
                            var total = parseFloat($('#total_ht').val());
                            var red_value = data.code;
                            var newTotal = total - ((total * red_value) / 100);
                            console.log('hahwa ' + newTotal);
                            $('#total_ht').attr('value', newTotal)
                            $('#total').attr('value', newTotal + newTotal * (
                                "<?php echo e(auth()->user()->store->tva); ?>"))
                            $('#to_paye').attr('value', newTotal + newTotal * (
                                "<?php echo e(auth()->user()->store->tva); ?>"))
                            $('#code_valide').attr('value', '1');
                        } else {
                            $('#code_red_error').html(data.message)
                        }
                    }

                })
            } else {
                $('#code_red_error').html('Veuillez choisir un client et un code valide !')
            }
        }
        var win = window.open(url, '_blank');
        win.onload = function() {
            win.print();
        };
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>