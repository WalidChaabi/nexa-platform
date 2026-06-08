<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-left"><?php echo e(auth()->user()->store->name); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard/employe')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><a href="<?php echo e(route('employe.invoices')); ?>"><?php echo e(trans('communs.Factures')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(trans('communs.Modification')); ?></li>
<?php $__env->stopSection(); ?>






<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <input type="hidden" name="praticientType" id="praticientType" value="<?php echo e($type); ?>">
                        <h3> <?php echo e(trans('communs.Modification Facture')); ?> : <?php echo e(date('Y-m-d H:i:s')); ?> </h3>
                    </div>
                    <div class="card-body">
                        <form id="edit-form" action="<?php echo e(route('employe.invoices.update', $invoice->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong><?php echo e(trans('communs.Reduction en Points')); ?></strong> : <span
                                        id="points_red"><?php echo e($customer->points * auth()->user()->store->coeff); ?></span>
                                </div>
                                <div class="col-md-4">
                                    <strong><?php echo e(trans('communs.Reduction en MAD')); ?></strong> : <span
                                        id="mad_red"><?php echo e($customer->points); ?></span>
                                    <input type="hidden" id="mad_red_hidden">
                                </div>
                                <div class="col-md-4">
                                    <strong><?php echo e(trans('communs.Reste')); ?></strong> : <span
                                        id="reste"><?php echo e($invoice->reste); ?></span>
                                    <input type="hidden" id="mad_red_hidden">

                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Home</strong> : <input type="radio" value="1" name="for"
                                        <?php if($invoice->for_sexe == 1): ?> checked <?php endif; ?>>
                                </div>
                                <div class="col-md-4">
                                    <strong>Femme</strong> : <input type="radio" value="2" name="for"
                                        <?php if($invoice->for_sexe == 2): ?> checked <?php endif; ?>>
                                </div>
                                <div class="col-md-4">
                                    <strong>Mix</strong> : <input type="radio" value="3" name="for"
                                        <?php if($invoice->for_sexe == 3): ?> checked <?php endif; ?>>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="validationServer01"><?php echo e(trans('communs.Informations du Client')); ?></label>
                                    <select id="customers" class="js-example-basic-single col-sm-12"
                                        onchange="getClientPoints($(this).val())" required name="customer">
                                        <option value="0">--- <?php echo e(trans('communs.Choisir un client')); ?> ---</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>"
                                                <?php echo e($customer->id == $item->id ? 'selected' : ''); ?>><?php echo e($item->firstname); ?>

                                                <?php echo e($item->lastname); ?> : <?php echo e($item->phone); ?> </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="validationServer02"><?php echo e(trans('communs.Nouveau Client')); ?></label>
                                    <button class="btn btn-info" type="button" data-toggle="modal"
                                        data-target=".bd-example-modal-lg"
                                        title="<?php echo e(trans('communs.Nouveau Client')); ?>"><?php echo e(trans('communs.Nouveau')); ?></button>
                                </div>
                            </div>

                            <br>
                            <div class="row">

                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <label for="validationServer02"><?php echo e(trans('communs.Ajouter P/S')); ?></label>
                                    <button class="form-control btn btn-primary " type="button" onclick="addProductRow()"
                                        title="<?php echo e(trans('communs.Ajouter Produit')); ?>">+</button>
                                </div>
                            </div>
                            --------------------------------------------------------------------------------------------------------------
                            <?php $__currentLoopData = $invoiceProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $id = uniqid(); ?>
                                <?php if(auth()->user()->store->praticien_2 == 1): ?>
                                    <div class="row" id="<?php echo e($id); ?>">
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
                                        <div class="col-md-5">
                                            <div class="d-flex"
                                                style="align-items: flex-start;justify-content: space-between;">
                                                <div class="col-md-3 pl-0">
                                                    <label for="validationServer02"><?php echo e(trans('communs.Prix')); ?></label>
                                                    <input class="form-control prices" value="<?php echo e($item->price); ?>"id="price_<?php echo e($id); ?>" type="text" name="price[]"
                                                         data-original-title="" title="<?php echo e(trans('communs.Prix')); ?>">
                                                </div>
                                                <div class="col-md-4 d-flex">
                                                    <div class="d-flex mr-3 mb-3" style="flex-direction: column">
                                                        <label for="validationServer02"><?php echo e(trans('communs.Quantité')); ?></label>
                                                        <input class="form-control qts" value="<?php echo e($item->qte); ?>" id="qte_<?php echo e($id); ?>" name="quantity[]"
                                                            min="1" required onchange="changeTotalOnce()"data-original-title=""title="<?php echo e(trans('communs.Quantité')); ?>">
                                                    </div>
                                                    <div class="d-flex" style="flex-direction: column ;justify-content :flex-end;">
                                                        <span class="btn btn-primary" onclick="changeQuantity('<?php echo e($id); ?>',1)"
                                                            style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;">+</span>
                                                        <span class="btn btn-primary" onclick="changeQuantity('<?php echo e($id); ?>',-1)"
                                                            style="width: 5px!important;height:25px!important ;display: flex;justify-content:center;align-items: center;margin-top:10px;">-</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="validationServer02"><?php echo e(trans('communs.Supprimer P/S')); ?></label>
                                                    <button class="form-control btn btn-primary " type="button"
                                                        onclick="removeProductRow('<?php echo e($id); ?>','<?php echo e($item->price); ?>',$('#qte_<?php echo e($id); ?>').val(),'<?php echo e(auth()->user()->store->tva); ?>')"
                                                        title="<?php echo e(trans('communs.Supprimer Produit')); ?>">-</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row" id="<?php echo e($id); ?>">
                                        <div class="col-md-6">
                                            <label for="validationServer01"><?php echo e($item->product->label); ?> |<?php echo e($item->product->price); ?> Mad </label>
                                            <input type="hidden" name="products[]" value="<?php echo e($item->product_id); ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServer02"><?php echo e(trans('communs.Prix')); ?></label>
                                            <input class="form-control prices" value="<?php echo e($item->price); ?>"id="price_<?php echo e($id); ?>" type="text" name="price[]"
                                                data-original-title="" title="<?php echo e(trans('communs.Prix')); ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <div class="col-md-8">
                                                <label for="validationServer02"><?php echo e(trans('communs.Quantité')); ?></label>
                                                <input class="form-control qts" value="<?php echo e($item->qte); ?>"id="qte_<?php echo e($id); ?>" name="quantity[]" min="1"
                                                    required onchange="changeTotalOnce()" data-original-title=""title="<?php echo e(trans('communs.Quantité')); ?>">
                                            </div>
                                            <div class="col-md-4 " style="margin-left: 68px!important;margin-top: -47px;">
                                                <span class="btn btn-primary mb-2"onclick="changeQuantity('<?php echo e($id); ?>',1)"
                                                    style="width: 5px!important;height:25px!important">+</span>
                                                <span class="btn btn-primary"onclick="changeQuantity('<?php echo e($id); ?>',-1)"
                                                    style="width: 5px!important;height:25px!important">-</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="validationServer02"><?php echo e(trans('communs.Supprimer P/S')); ?></label>
                                            <button class="form-control btn btn-primary " type="button"
                                                onclick="removeProductRow('<?php echo e($id); ?>','<?php echo e($item->price); ?>',$('#qte_<?php echo e($id); ?>').val(),'<?php echo e(auth()->user()->store->tva); ?>')"
                                                title="<?php echo e(trans('communs.Supprimer Produit')); ?>">-</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            --------------------------------------------------------------------------------------------------------------
                            <div id="products-row">

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.Description')); ?> :</label>
                                    <textarea class="form-control " id="validationServer02" type="text" name="description"
                                        title="<?php echo e(trans('communs.Description')); ?>"><?php echo e($invoice->description); ?></textarea>
                                </div>
                                <div class="col-md-6 ">
                                    <br>
                                    <div class="form-group">
                                        <div class="col">
                                            <label
                                                for="validationServer02"><?php echo e(trans('communs.Mode de Paiement')); ?></label>
                                            <div class="mb-3 m-t-15 custom-radio-ml">
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="radio1" type="radio"
                                                        name="payment_method" value="1"
                                                        <?php if($invoice->payment_method == 1): ?> checked <?php endif; ?>>
                                                    <label class="form-check-label"
                                                        for="radio1"><?php echo e(trans('communs.Espèces')); ?><span
                                                            class="digits"></span></label>
                                                </div>
                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="radio3" type="radio"
                                                        name="payment_method" value="2"
                                                        <?php if($invoice->payment_method == 2): ?> checked <?php endif; ?>>
                                                    <label class="form-check-label"
                                                        for="radio3"><?php echo e(trans('communs.Chèque')); ?><span
                                                            class="digits"></span></label>
                                                </div>

                                                <div class="form-check radio radio-primary">
                                                    <input class="form-check-input" id="radio4" type="radio"
                                                        name="payment_method" value="3"
                                                        <?php if($invoice->payment_method == 3): ?> checked <?php endif; ?>>
                                                    <label class="form-check-label"
                                                        for="radio4"><?php echo e(trans('communs.Carte Bancaire')); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--there is no need to add the provider -->
                                <?php if(in_array(auth()->user()->store_id, [19])): ?>
                                    <div class="col-md-6">
                                        <label for="validationServer02"><?php echo e(trans('communs.Effectué Par')); ?>:</label>
                                        <select id="employers" class="js-example-basic-single col-sm-12" required
                                            name="employ_id">
                                            <option value="0">--- <?php echo e(trans('communs.Choisir le/la Praticien/ne')); ?>

                                                ---</option>
                                            <?php $__currentLoopData = $employes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->id); ?>"
                                                    <?php if($item->id == $invoice->employ_id): ?> selected <?php endif; ?>>
                                                    <?php echo e($item->firstname . ' ' . $item->lastname); ?> : <?php echo e($item->phone); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-1"></div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.Total HT')); ?></label>
                                    <input readonly class="form-control " id="total_ht" required type="text"
                                        name="total_ht" value="<?php echo e($invoice->total_ht); ?>" data-original-title=""
                                        title="<?php echo e(trans('communs.Montant Total Hors Taxes')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.TVA')); ?></label>
                                    <input readonly class="form-control" value="<?php echo e($invoice->tva); ?>%"
                                        title="<?php echo e(trans('communs.TVA')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="validationServer02"><?php echo e(trans('communs.Total TTC')); ?></label>
                                    <input readonly class="form-control" name="total" id="total" type="text"
                                        value="<?php echo e($invoice->total); ?>" title="<?php echo e(trans('communs.TOTAL TTC')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="validationServer02"><?php echo e(trans('communs.Montant Payé')); ?></label>
                                    <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                        value="<?php echo e($invoice->paid_amount); ?>" data-original-title=""
                                        title="<?php echo e(trans('communs.Montant Payé')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="validationServer02"><?php echo e(trans('communs.Reste')); ?></label>
                                    <input class="form-control " id="to_paye" type="text" name="paid_amount"
                                        value="<?php echo e($invoice->reste); ?>" data-original-title=""
                                        title="<?php echo e(trans('communs.Montant Payé')); ?>">
                                </div>

                            </div>
                            <br><br>

                            <input id="total_hidden" type="hidden" value="0">

                        </form>
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" onclick="submit()" data-original-title=""
                                    title="<?php echo e(trans('communs.Modifier')); ?>"><?php echo e(trans('communs.Modifier')); ?></button>
                            </div>
                        </div>
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
                <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('communs.Nouveau Client')); ?></h4>
                <button id="close" class="close" type="button" data-dismiss="modal" aria-label="Close"
                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01"><?php echo e(trans('communs.Nom')); ?></label>
                            <input class="form-control" id="firstname" type="text" name="firstname"
                                value="<?php echo e(old('firstname')); ?>" data-original-title=""
                                title="<?php echo e(trans('communs.Nom')); ?>">
                            <?php if($errors->has('firstname')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('firstname')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02"><?php echo e(trans('communs.Prénom')); ?></label>
                            <input class="form-control " id="lastname" type="text" name="lastname"
                                value="<?php echo e(old('lastname')); ?>" data-original-title=""
                                title="<?php echo e(trans('communs.Prénom')); ?>">
                            <?php if($errors->has('lastname')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('lastname')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01"><?php echo e(trans('communs.E-mail')); ?></label>
                            <input class="form-control" id="email" type="text" name="email"
                                value="<?php echo e(old('email')); ?>" data-original-title=""
                                title="<?php echo e(trans('communs.E-mail')); ?>">
                            <?php if($errors->has('email')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('email')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02"><?php echo e(trans('communs.Téléphone')); ?></label>
                            <input class="form-control" id="phone" type="text" name="phone"
                                value="212<?php echo e(old('phone')); ?>" required="" data-original-title=""
                                title="<?php echo e(trans('communs.Téléphone')); ?>">
                            <?php if($errors->has('phone')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('phone')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01"><?php echo e(trans('communs.Date de Naissance')); ?></label>
                            <input id="birth" class="form-control" type="date" name="birth"
                                data-language="en">
                            <?php if($errors->has('password')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('birth')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01"><?php echo e(trans('communs.Sexe')); ?></label>
                            <select class="form-control digits" name="sexe" id="sexe">
                                <option>-- <?php echo e(trans('communs.Choisir Sexe')); ?> --</option>
                                <option value="1"><?php echo e(trans('communs.Homme')); ?></option>
                                <option value="0"><?php echo e(trans('communs.Femme')); ?></option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="createCustomer()"
                        title="<?php echo e(trans('communs.Enregistrer')); ?>"><?php echo e(trans('communs.Enregistrer')); ?></button>
                </form>
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
        var type = document.getElementById('praticientType').value()

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

            $.ajax({
                url: "<?php echo e(route('employe.productPrice')); ?>",
                data: {
                    id: id
                },
                type: 'GET',
                success: function(data) {
                    $("#price_" + price_id).val(data);
                    changeTotalOnce();
                    changeTotalOnceHT();
                },
                error: function(error) {
                    console.log(error)
                }

            });
        }

        function submit() {
            var prices = [];
            var count_product = 0;


            $(".prices").each(function(index) {
                prices.push($(this).val())
                count_product++;
            });

            if (count_product > 0) {
                $("#edit-form").submit();
            } else {
                $('#exampleModalCenter-message').modal('show')
            }


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
                error: function(error) {
                    console.log(error)
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
                    $('#mad_red').html(data['mad_red']);
                    $('#points_red').html(data['points_red']);
                    $('#reste').html(data['reste']);
                    $('#mad_red_hidden').attr('value', data['mad_red']);
                    console.log('reste', data);
                },
                error: function(error) {
                    console.log(error)
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
    </script>
<?php $__env->stopSection(); ?>
<style>
    .qts {
        width: 52px !important;
    }
</style>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>