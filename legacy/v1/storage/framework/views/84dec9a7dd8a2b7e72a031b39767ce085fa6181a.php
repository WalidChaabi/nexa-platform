<?php $__env->startSection('title', 'Default'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/date-picker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
    <h3 class="text-center"><?php echo e(trans('communs.Caisse')); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard/owner')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e(trans('communs.Caisse de depart')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Ajax Deferred rendering for speed start-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo e(trans('communs.Caisse de depart')); ?> : <?php echo e($initialFond); ?> </h2>
                        <h4><?php echo e(trans('communs.Etat de caisse')); ?> :
                            <?php echo e(isset($_GET['date']) ? $_GET['date'] : Date('Y/m/d')); ?> <h4>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-12 row">
                                <div class="col-md-4">
                                    <input id="date-filter" class="datepicker-here form-control" type="text" data-language="en">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-info" type="button"
                                        onclick="getCaisseByDate('<?php echo e(route('employe.caisse.index')); ?>' ,$('#date-filter').val())"
                                        title="<?php echo e(trans('FILTRES')); ?>">
                                        <i class="fa fa-sliders" title="<?php echo e(trans('filtres')); ?>" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <table class="display" id="render-table-fonds">
                                <thead>
                                    <tr>
                                        <th><?php echo e(trans('communs.Mode de Paiement')); ?></th>
                                        <th><?php echo e(trans('communs.Caisse de départ')); ?></th>
    
                                        <th><?php echo e(trans('communs.Encaissement')); ?></th>
                                        <th><?php echo e(trans('communs.Décaissement')); ?></th>
                                        <th><?php echo e(trans('communs.Retour')); ?></th>
                                        <?php if(auth()->user()->store->reste == 1): ?>                                            
                                            <th><?php echo e(trans('communs.Reste_payer')); ?></th>
                                        <?php endif; ?>
                                        <th><?php echo e(trans('communs.Total Ligne')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo e(trans('communs.Espèces')); ?></td>
                                        <td>-</td>
                                        <td><?php echo e($encasementCash); ?></td>
                                        <td><?php echo e($disbursementCash); ?></td>
                                        <td><?php echo e($returnCash); ?></td>
                                        <?php if(auth()->user()->store->reste == 1): ?>
                                            <td><?php echo e($reste_payer); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo e($encasementCash - $disbursementCash - $returnCash); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo e(trans('communs.Chèque')); ?></td>
                                        <td>-</td>
                                        <td><?php echo e($encasementCheck); ?></td>
                                        <td><?php echo e($disbursementCheck); ?></td>
                                        <td><?php echo e($returnCheck); ?></td>
                                        
                                        <?php if(auth()->user()->store->reste == 1): ?>
                                            <td><?php echo e($reste_payer); ?></td>
                                        <?php endif; ?>
    
                                        <td><?php echo e($encasementCheck - $disbursementCheck - $returnCheck); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo e(trans('communs.Carte Bancaire')); ?></td>
                                        <td>-</td>
                                        <td><?php echo e($encasementCard); ?></td>
                                        <td><?php echo e($disbursementCard); ?></td>
                                        <td><?php echo e($returnCard); ?></td>
                                        
                                        <?php if(auth()->user()->store->reste == 1): ?>
                                            <td><?php echo e($reste_payer); ?></td>
                                        <?php endif; ?>
    
                                        <td><?php echo e($encasementCard - $disbursementCard - $returnCard); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo e(trans('communs.Caisse de départ')); ?></td>
                                        <td><?php echo e($initialFond); ?></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <?php if(auth()->user()->store->reste == 1): ?>
                                        
                                        <td>-</td>
                                        <?php endif; ?>
    
                                        <td><?php echo e($encasementCard - $disbursementCard - $returnCard); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo e(trans('communs.Total Caisse')); ?></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <?php if(auth()->user()->store->reste == 1): ?>
    
                                        <td>-</td>
                                        <?php endif; ?>
                                        <td><?php echo e($initialFond + $encasementCard + $encasementCheck + $encasementCash + $reste_payer - ($disbursementCash + $disbursementCheck + $disbursementCard + $returnCash + $returnCard + $returnCheck)); ?>

                                        </td>
                                    </tr>
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


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/chartist/chartist-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.en.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datepicker/date-picker/datepicker.custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<script>
    function ShowItems(value, url) {
        window.location = url + "?items=" + value
    }

    function getCaisseByDate(url, date) {
        window.location = url + "?date=" + date
    }
</script>

<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>