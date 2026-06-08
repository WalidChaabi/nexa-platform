<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard/employe')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(trans('communs.Factures')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(isset($printUrl)): ?>
        <script>
            var printUrl = '<?php echo e($printUrl); ?>';
            var win = window.open(printUrl + '?type=individual', '_blank');
            win.print();
        </script>
    <?php else: ?>
        <script>
            const storedPrintUrl = JSON.parse(localStorage.getItem('printUrl'));
            if (storedPrintUrl) {
                var win = window.open(storedPrintUrl + '?type=individual', '_blank');
                win.print();
                localStorage.setItem('printUrl','');
            }
        </script>
    <?php endif; ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">

                        <form id="" action="<?php echo e(route('employe.invoices')); ?>" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <?php if(auth()->user()->store->POS == 1): ?>
                                        <a href="<?php echo e(route('employe.table.index')); ?>" class="btn btn-info" type="button"
                                            title="<?php echo e(trans('communs.Nouvelle Facture')); ?>"><?php echo e(trans('communs.Nouveau')); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('employe.invoices.create')); ?>" class="btn btn-info"
                                            type="button"
                                            title="<?php echo e(trans('communs.Nouvelle Facture')); ?>"><?php echo e(trans('communs.Nouveau')); ?></a>
                                    <?php endif; ?>

                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="start_date"
                                        value="<?php echo e(isset($_GET['start_date']) ? $_GET['start_date'] : ''); ?>"
                                        class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="end_date"
                                        value="<?php echo e(isset($_GET['end_date']) ? $_GET['end_date'] : ''); ?>"
                                        class="form-control">
                                </div>
                                <?php if(auth()->user()->store->id): ?>
                                    <div class="col-md-2">
                                        <select type="date" name="sexe" class="form-control">
                                            <option value="0"><?php echo e(trans('Choisir le sexe')); ?></option>
                                            <?php if(isset($_GET['sexe'])): ?>
                                                <option value="1" <?php if($_GET['sexe'] == 1): ?> selected <?php endif; ?>>
                                                    Homme </option>
                                                <option value="2" <?php if($_GET['sexe'] == 2): ?> selected <?php endif; ?>>
                                                    Femme </option>
                                                <option value="3" <?php if($_GET['sexe'] == 3): ?> selected <?php endif; ?>>
                                                    Mix </option>
                                            <?php else: ?>
                                                <option value="1">Homme</option>
                                                <option value="2">Femme</option>
                                                <option value="3">Mix</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-2">
                                    <button type="submit"
                                        class="form-control btn btn-primary"><?php echo e(trans('Rechercher')); ?></button>
                                </div>
                            </div>
                        </form>
                        <?php if(auth()->user()->store->RA == 1): ?>
                            <form id="" action="<?php echo e(route('employe.invoices.recherche')); ?>" method="GET">
                                <?php echo csrf_field(); ?>
                                <div class="row" style="margin-top: 30px">
                                    <div class="col-sm-4">
                                        <input style="border: 2 solid blue" class="form-control " id="to_paye"
                                            type="text" name="recherche_avencee" placeholder="+212 6 XX XX XX XX"
                                            title="<?php echo e(trans('communs.Recherche avancée')); ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <input style="border: 2 solid blue" class="form-control" id="name_rechercher"
                                            type="text" name="name_rechercher" placeholder=""
                                            title="<?php echo e(trans('communs.Recherche avancée')); ?>">
                                    </div>
                                    <div class="col-sm-6 d-flex" style="align-items: flex-end;justify-contetn:center;">
                                        <button type="submit" class="form-control btn btn-primary"
                                            style="width:190px; background: #a927f9 !important;"><?php echo e(trans('Recherche avancée')); ?></button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="card-body row">

                        <div class="table-responsive">
                            <table class="display" id="render-datatable">
                                <thead>
                                    <tr>
                                        <th><?php echo e(trans('dashboard.Client')); ?></th>
                                        <th><?php echo e(trans('dashboard.Date')); ?></th>
                                        <th><?php echo e(trans('communs.Téléphone')); ?></th>
                                        
                                        <th><?php echo e(trans('communs.Total TTC')); ?></th>
                                        <th><?php echo e(trans('B.R')); ?></th>
                                        <th><?php echo e(trans('C.R')); ?></th>
                                        <?php if(auth()->user()->store->reste == 1): ?>
                                            <th><?php echo e(trans('communs.Reste')); ?></th>
                                            <th><?php echo e(trans('communs.Reste_payer')); ?></th>
                                        <?php endif; ?>
                                        <th class="text-center"><?php echo e(trans('communs.Action')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr
                                            style="<?php echo e($item->status == 4 || $item->status == 2 ? 'background-color: ' . getInvoiceColorByStatus($item->status) : ''); ?>">
                                            <td><?php echo e($item->customer->getFullNameAttribute()); ?></td>
                                            <td><?php echo e($item->invoice_date); ?></td>
                                            <td><?php echo e($item->customer->phone); ?></td>
                                            
                                            <td><?php echo e($item->paid_amount); ?></td>
                                            <td><?php echo e($item->points > 0 ? 'Oui' : 'Non'); ?></td>
                                            <td><?php echo e(!empty($item->campaign) ? 'Oui' : 'Non'); ?></td>
                                            <?php if(auth()->user()->store->reste == 1): ?>
                                                <td><?php echo e($item->reste); ?></td>
                                                <td><?php echo e($item->reste_payer); ?></td>
                                            <?php endif; ?>
                                            <td>
                                                <?php if($item->status == 0): ?>
                                                    <button class="btn" style="background-color: #ea2087"
                                                        onclick="printInvoice('<?php echo e($item->id); ?>')">
                                                        <i class="fa fa-print"></i>
                                                    </button>
                                                    <?php if(auth()->user()->is_admin == 3): ?>
                                                        <a class="btn" style="background-color: #7366ff"
                                                            data-toggle="modal"
                                                            data-target="#exampleModalCenter-<?php echo e($item->id); ?>"><i
                                                                class="fa fa-trash-o"></i></a>
                                                        <a class="btn" data-toggle="modal"
                                                            data-target="#modalInvoice-<?php echo e($item->id); ?>"
                                                            style="background-color: #ea2087"><i class="fa fa-eye"></i></a>
                                                        <a class="btn" data-toggle="modal"
                                                            data-target="#modalReturn-<?php echo e($item->id); ?>"
                                                            style="background-color: #a927f9" href=""><i
                                                                class="fa fa-reply" aria-hidden="true"></i></a>
                                                    <?php endif; ?>
                                                <?php elseif($item->status == 2 || $item->status == 4): ?>
                                                    <a class="btn" style="background-color: #ea2087"
                                                        href="<?php echo e(route('employe.invoices.print', $item->id)); ?>"><i
                                                            class="fa fa-print"></i></a>
                                                    <a class="btn" style="background-color: #a927f9"
                                                        href="<?php echo e(route('employe.invoices.show', $item->id)); ?>"><i
                                                            class="fa fa-eye"></i></a>
                                                <?php endif; ?>
                                                <div class="modal fade" id="exampleModalCenter-<?php echo e($item->id); ?>"
                                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo e(trans('Confirmation')); ?></h5>
                                                                <button class="close" type="button"
                                                                    data-dismiss="modal" aria-label="Close"><span
                                                                        aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php echo e(trans('Vous voulez vraiment supprimer la facture : #' . $item->id . ' ? ')); ?>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button"
                                                                    data-dismiss="modal"><?php echo e(trans('Non')); ?></button>
                                                                <a href="<?php echo e(route('employe.invoices.cancel', $item->id)); ?>"
                                                                    class="btn btn-primary"
                                                                    type="button"><?php echo e(trans('Oui')); ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade bd-example-modal-lg"
                                                    id="modalReturn-<?php echo e($item->id); ?>" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalCenter" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    <?php echo e(trans('Confirmation De Retour')); ?> </h5>
                                                                <button class="close" type="button"
                                                                    data-dismiss="modal" aria-label="Close"><span
                                                                        aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body col-md-12">

                                                                <div class="col-md-12">
                                                                    <label
                                                                        for=""><?php echo e(trans('communs.Produit Factures')); ?></label>
                                                                    <select id="select_remove_<?php echo e($item->id); ?>"
                                                                        class="js-example-basic-single col-sm-12"
                                                                        onchange="removeProductRetour(<?php echo e($item->id); ?>,$(this).val())">
                                                                        <option value="0">---
                                                                            <?php echo e(trans('communs.Choisir Le produit')); ?> ---
                                                                        </option>
                                                                        <?php $__currentLoopData = $item->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option id="option_<?php echo e($product->id); ?>"
                                                                                value="<?php echo e(json_encode($product)); ?>">
                                                                                <?php echo e(isset($product->product->label) ? $product->product->label : ''); ?>

                                                                                | <?php echo e($product->price); ?> | MAD </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12 table-responsive">
                                                                    <table class="display">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><?php echo e(trans('Qte')); ?></th>
                                                                                <th><?php echo e(trans('Price')); ?></th>
                                                                                <th><?php echo e(trans('Tva')); ?></th>
                                                                                <th><?php echo e(trans('Total HT')); ?></th>
                                                                                <th class="text-center">
                                                                                    <?php echo e(trans('Action')); ?></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="removeProductBody">


                                                                        </tbody>
                                                                        <tr>
                                                                            <td>Total Facture</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td id="total_initial"><?php echo e($item->total_ht); ?>

                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td>Total Retour</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td id="totalReturn">00.00 MAD</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button"
                                                                    data-dismiss="modal"><?php echo e(trans('Non')); ?></button>
                                                                <form method="POST"
                                                                    action="<?php echo e(route('employe.invoices.return')); ?>">
                                                                    <?php echo csrf_field(); ?>
                                                                    <input type="hidden" name="invoice_id"
                                                                        value="<?php echo e($item->id); ?>">
                                                                    <div id="totalReturnHidden"></div>
                                                                    <button class="btn btn-primary"
                                                                        type="submit"><?php echo e(trans('Oui')); ?></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade bd-example-modal-lg" id="modalInvoice-<?php echo e($item->id); ?>"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><?php echo e(trans('Detail de facture')); ?> </h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body col-md-12">
                                                        <div class="row">
                                                            <div class="mb-3 col-md-5">
                                                                <div class="form-group">
                                                                    <label for="full_name"><?php echo e(trans('Client')); ?></label>
                                                                    <input readonly class="form-control " id="full_name"
                                                                        type="text"
                                                                        value="<?php echo e($item->customer ? $item->customer->getFullNameAttribute() : ''); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 col-md-5">
                                                                <label for="date"><?php echo e(trans('Date')); ?></label>
                                                                <input readonly class="form-control" id="date"
                                                                    type="text" value="<?php echo e($item->invoice_date); ?>"
                                                                    title="<?php echo e(trans('Date de Facture')); ?>">
                                                            </div>
                                                            <div class="mb-3 col-md-2">
                                                                <div class="form-group">
                                                                    <label for="points">Reduction</label>
                                                                    <input readonly class="form-control " id="points"
                                                                        type="text" value="<?php echo e($item->points); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 col-md-12">
                                                                <?php
                                                                    $productNumber = 1;
                                                                ?>
                                                                <?php $__currentLoopData = $item->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $el): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="row">
                                                                        <div class="col-md-5 form-group">
                                                                            <label
                                                                                for="produit"><?php echo e(trans('Produit' . ' ' . $productNumber)); ?></label>
                                                                            <input readonly class="form-control "
                                                                                id="produit" type="text"
                                                                                title="<?php echo e(trans('Date de Facture')); ?>"
                                                                                value="<?php echo e(isset($el->product->label) ? $el->product->label : ''); ?>">
                                                                        </div>
                                                                        <div class="col-md-5 form-group">
                                                                            <label
                                                                                for="price"><?php echo e(trans('Prix')); ?></label>
                                                                            <input readonly class="form-control "
                                                                                id="price"
                                                                                type="text"value="<?php echo e($el->price); ?>">
                                                                        </div>
                                                                        <div class="col-md-2 form-group">
                                                                            <label
                                                                                for="qte"><?php echo e(trans('Qte')); ?></label>
                                                                            <input readonly class="form-control "id="qte"
                                                                                type="text"
                                                                                value="<?php echo e($el->qte); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                        $productNumber++;
                                                                    ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>

                                                            <div class="col-md-10 form-group">
                                                                <label
                                                                    for="description"><?php echo e(trans('Description')); ?></label>
                                                                <textarea readonly class="form-control" name="description" id="description" rows="3"><?php echo e($item->description); ?></textarea>
                                                                
                                                            </div>
                                                            <div class="col-md-2 form-group">
                                                                <label for="total"><?php echo e(trans('Total')); ?></label>
                                                                <input readonly class="form-control " id="total"
                                                                    type="text" value="<?php echo e($item->total); ?>">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <a class="btn btn-primary"
                                                            href="<?php echo e(route('employe.invoices.edit', $item->id)); ?>">Modifier</a>
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal"><?php echo e(trans('Fermer')); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="row">



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
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('employe.customers.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for=""><?php echo e(trans('Nom')); ?></label>
                            <input class="form-control" id="" type="text" name="firstname"
                                value="<?php echo e(old('firstname')); ?>" data-original-title=""
                                title="<?php echo e(trans('Prénom')); ?>">
                            <?php if($errors->has('firstname')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('firstname')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="validationServer02"><?php echo e(trans('Prénom')); ?></label>
                            <input class="form-control " id="validationServer02" type="text" name="lastname"
                                value="<?php echo e(old('lastname')); ?>" data-original-title="" title="<?php echo e(trans('Nom')); ?>">
                            <?php if($errors->has('lastname')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('lastname')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for=""><?php echo e(trans('E-mail')); ?></label>
                            <input class="form-control" id="" type="text" name="email"
                                value="<?php echo e(old('email')); ?>" data-original-title="" title="<?php echo e(trans('E-mail')); ?>">
                            <?php if($errors->has('email')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('email')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="validationServer02"><?php echo e(trans('Téléphone')); ?></label>
                            <input class="form-control" id="validationServer02" type="text" name="phone"
                                value="212<?php echo e(old('phone')); ?>" required="" data-original-title=""
                                title="<?php echo e(trans('Téléphone')); ?>">
                            <?php if($errors->has('phone')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('phone')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for=""><?php echo e(trans('Date de Naissance')); ?></label>
                            <input class="datepicker-here form-control" type="text" name="birth"
                                data-language="en">
                            <?php if($errors->has('password')): ?>
                                <div class="invalid-feedback"><?php echo e($errors->first('birth')); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for=""><?php echo e(trans('Sexe')); ?></label>
                            <select class="form-control digits" name="sexe" id="store_id">
                                <option>-- <?php echo e(trans('Choisir Sexe')); ?> --</option>
                                <option value="1"><?php echo e(trans('Homme')); ?></option>
                                <option value="0"><?php echo e(trans('Femme')); ?></option>

                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" data-original-title=""
                        title="<?php echo e(trans('Enregistrer')); ?>"><?php echo e(trans('Enregistrer')); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>



<?php $__env->startSection('script'); ?>
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
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js²')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<script>
    function ShowItems(value, url) {
        window.location = url + "?items=" + value
    }

    function removeProductRetour(invoice_id, product) {
        var remProduct = jQuery.parseJSON(product);
        console.log(remProduct)
        $("#select_remove_" + invoice_id + " option[value='" + product + "']").remove();
        var html = "<tr>";
        var qte = "<td><input type='number' class='qte_ht' onchange='calculateTotalReturn(" + invoice_id +
            ")' value='" + remProduct.qte + "' min='0' max='" + remProduct.qte + "'></td>";
        var price = "<td> " + remProduct.price + " <input type='hidden' class='price_ht'  value='" + remProduct.price +
            "''></td>";
        var tva = "<td><input diseabled value='" + "<?php echo e(auth()->user()->store->tva); ?>" + "'></td>";
        //   var total = "<td><input readOnly class='total_ht' value='"+( remProduct.qte * remProduct.price )+"'></td>";
        var html = html + qte + price + tva + "</tr>";

        $('#modalReturn-' + invoice_id).find('#removeProductBody').append(html);


        calculateTotalReturn(invoice_id);
    }

    function calculateTotalReturn(invoice_id) {
        var prices = [];
        var qts = [];
        var total = 0;
        var tva = "<?php echo e(auth()->user()->store->tva / 100); ?>";
        $('#modalReturn-' + invoice_id).find(".qte_ht").each(function(index) {
            qts.push($(this).val())
        });

        $('#modalReturn-' + invoice_id).find(".price_ht").each(function(index) {
            prices.push($(this).val())
        });
        Object.entries(prices).forEach(([key, value]) => {
            Object.entries(qts).forEach(([keyQts, valueQts]) => {
                if (key == keyQts) {
                    total = total + (value * valueQts);
                }
            })

        });
        total = total + (total * "<?php echo e(auth()->user()->store->tva); ?>" / 100);
        console.log('hamza je calcul le retour');
        console.log(total);
        $('#modalReturn-' + invoice_id).find('#totalReturn').html(total + " MAD");
        $('#modalReturn-' + invoice_id).find('#totalReturnHidden').html(
            "<input type='hidden' name='hidden_total' value='" + total + "'>");
    }
</script>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>