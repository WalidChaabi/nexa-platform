<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select2.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .table-card:hover i {
            visibility: visible !important;
        }

        /* .card.table {
                        background-image: url('../assets/categorei/food.png');
                        background-size: cover;
                        background-position: center;
                    } */
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-center"><?php echo e(trans('Mes Tables')); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard/employe')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(trans('Categoreis')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg"
                    title="Nouveau Categorie">Nouveau</button>
                <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-transfer"
                    title="Transfer Table">Transfer</button>
            </div>
        </div>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="row" style="display: flex;gap:10px;padding:0 12px;">
            <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <i class="fas fa-edit" data-toggle="modal" data-target=".bd-example-modal-lg-edit<?php echo e($table->id); ?>"
                        style="position: absolute;right: 22px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <i class="fas fa-trash" data-toggle="modal" data-target=".bd-example-modal-lg-delete<?php echo e($table->id); ?>"
                        style="position: absolute;right: 41px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <a href="<?php echo e(url('employe/pos/create/' . $table->id)); ?>">
                        <div class="card table"
                            style="background: <?php echo e($table->table_status == 1 ? '#54ba4a' : 'grey'); ?> ;width:100px;">
                            <div
                                class="card-body "style="display:flex;justify-content: center;
                                align-items:center; flex-direction:column;padding:10px !important;">
                                <label><?php echo e($table->number); ?></label>
                                <label
                                    style="overflow: hidden;text-overflow: ellipsis;
                                white-space: nowrap;display: block;width: 92%; text-align: center;"><?php echo e($table->description); ?></label>
                            </div>
                        </div>
                    </a>
                </div>
                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="modal fade bd-example-modal-transfer" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Transfer Table')); ?></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title=""
                        title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('employe.table.tansferer')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="store_id" value="<?php echo e(auth()->user()->store->id); ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01"><?php echo e(trans('Tables')); ?></label>
                                <select id="Tables" class="js-example-basic-single col-sm-12"
                                    onchange="getClientPoints($(this).val())" required name="full_table_id">
                                    <option value="0">--- <?php echo e(trans('communs.Choisir une table')); ?> ---</option>
                                    <?php $__currentLoopData = $fullTables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>">
                                            <?php echo e($item->number); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('empty table')); ?></label>
                                <select id="emptyTables" class="js-example-basic-single col-sm-12"
                                    onchange="getClientPoints($(this).val())" required name="empty_table_id">
                                    <option value="0">--- <?php echo e(trans('communs.Choisir une empty table')); ?> ---</option>
                                    <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>">
                                            <?php echo e($item->number); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title=""
                            title="<?php echo e(trans('trasferer')); ?>"><?php echo e(trans('trasferer')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Nouveau Table')); ?></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title=""
                        title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('employe.table.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="hidden" name="store_id" value="<?php echo e(auth()->user()->store->id); ?>">

                                <label for="validationServer01"><?php echo e(trans('Number')); ?></label>
                                <input class="form-control" id="validationServer01" type="number" name="number"
                                    value="<?php echo e(old('number')); ?>" required="" data-original-title=""
                                    title="<?php echo e(trans('Number table')); ?>">
                                <?php if($errors->has('number')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('number')); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Description')); ?></label>
                                <input class="form-control " id="validationServer02" type="text" name="description"
                                    value="<?php echo e(old('description')); ?>" required="" data-original-title=""
                                    title="<?php echo e(trans('Description')); ?>">
                                <?php if($errors->has('description')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('description')); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" data-original-title=""
                            title="<?php echo e(trans('Enregistrer')); ?>"><?php echo e(trans('Enregistrer')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/bootstrap-notify.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dashboard/default.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/notify/index.js')); ?>"></script>
    <script>
        setTimeout(function() {
            const errorAlert = document.querySelector('.alert.alert-danger');
            if (errorAlert) {
                errorAlert.style.display = 'none';
            }
        }, 5000);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>