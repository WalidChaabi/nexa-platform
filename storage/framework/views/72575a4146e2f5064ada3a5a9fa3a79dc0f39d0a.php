<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .categorie-card:hover i {
            visibility: visible !important;
        }

        .card.categorie {
            background-image: url('../assets/categorei/food.png');
            background-size: cover;
            background-position: center;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-center"><?php echo e(trans('Mes Categoreis')); ?></h3>
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
            </div>
        </div>
        <div class="row">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-4 categorie-card">
                    <i class="fas fa-edit" data-toggle="modal" data-target=".bd-example-modal-lg-edit<?php echo e($categorie->id); ?>"
                        style="position: absolute;right: 22px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <i class="fas fa-trash" data-toggle="modal"
                        data-target=".bd-example-modal-lg-delete<?php echo e($categorie->id); ?>"
                        style="position: absolute;right: 41px;z-index: 1;top: 6px;color: white; visibility: hidden;"></i>
                    <a href="<?php echo e(url('employe/products/' . $categorie->id)); ?>">
                        <div class="card categorie">
                            <div class="card-body "style="display:flex;justify-content: center;">
                                <label><?php echo e($categorie->name); ?></label>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="modal fade bd-example-modal-lg-edit<?php echo e($categorie->id); ?>" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Modifier Categorie')); ?></h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo e(route('employe.categorie.update', $categorie->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="_method" value="PUT" />
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <input type="hidden" name="store_id" value="<?php echo e(auth()->user()->store->id); ?>">
                                            <label for="validationServer01"><?php echo e(trans('Nom')); ?></label>
                                            <input class="form-control" id="validationServer01" type="text"
                                                name="name" value="<?php echo e($categorie->name); ?>" required=""
                                                data-original-title="" title="<?php echo e(trans('Nom categorie')); ?>">
                                            <?php if($errors->has('name')): ?>
                                                <div class="invalid-feedback"><?php echo e($errors->first('name')); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationServer02"><?php echo e(trans('description')); ?></label>
                                            <input class="form-control " id="validationServer02" type="text"
                                                name="description" value="<?php echo e($categorie->description); ?>" required=""
                                                data-original-title="" title="<?php echo e(trans('Description')); ?>">
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
                <div class="modal fade bd-example-modal-lg-delete<?php echo e($categorie->id); ?>" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Delete Categorie')); ?></h4>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                                    data-original-title="" title=""><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo e(route('employe.categorie.delete', $categorie->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE" />

                                    <p>Êtes-vous sûr de vouloir supprimer cette categorie??</p>
                                    <div class="modal-footer">
                                        <input type="hidden" name="store_id" value="<?php echo e(auth()->user()->store->id); ?>">
                                        <input type="hidden" name="id" value="<?php echo e($categorie->id); ?>">
                                        <button type="button" class="btn btn-primary" id="close-modal">No</button>
                                        <button class="btn btn-danger" type="submit" data-original-title=""
                                            title="<?php echo e(trans('Oui')); ?>"><?php echo e(trans('Oui')); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"><?php echo e(trans('Nouveau Categorie')); ?></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                        data-original-title="" title=""><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('employe.categorie.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="hidden" name="store_id" value="<?php echo e(auth()->user()->store->id); ?>">
                                <label for="validationServer01"><?php echo e(trans('Nom')); ?></label>
                                <input class="form-control" id="validationServer01" type="text" name="name"
                                    value="<?php echo e(old('name')); ?>" required="" data-original-title=""
                                    title="<?php echo e(trans('Nom categorie')); ?>">
                                <?php if($errors->has('name')): ?>
                                    <div class="invalid-feedback"><?php echo e($errors->first('name')); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02"><?php echo e(trans('Prénom')); ?></label>
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

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>