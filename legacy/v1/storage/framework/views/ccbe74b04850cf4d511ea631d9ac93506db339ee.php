<?php $__env->startSection('title', 'Ecommerce'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/animate.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/chartist.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/owlcarousel.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/prism.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3><?php echo e(auth()->user()->store->name); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard/employe')); ?>"><?php echo e(trans('dashboard.dashboard')); ?></a></li>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
   <div class="row size-column">
      <div class="col-xl-7 box-col-12 xl-100">
         <div class="row dash-chart">
            <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
               <div class="card o-hidden">
                  <div class="card-body">
                     <div class="ecommerce-widgets media">
                        <div class="media-body">
                           <p class="f-w-500 font-roboto"><?php echo e(trans('dashboard.C.A Réalisé/Jour')); ?></p>
                           <h4 class="f-w-500 mb-0 f-26"><span class=""><?php echo e($obj_realised['today']); ?></span>Mad</h4>
                        </div>
                        <div class="media-body">
                           <p class="f-w-500 font-roboto"><?php echo e(trans('dashboard.OBJECTIVE Journalier')); ?><span class="badge pill-badge-primary ml-3"></span></p>
                           <h4 class="f-w-500 mb-0 f-26"><span class=""><?php echo e($objective->daily); ?></span>Mad</h4>
                        </div>
                     </div>
                  </div>
                  <span class="badge pill-badge-primary ml-15"> <?php echo e(date('Y-m-d')); ?></span>
               </div>
            </div>
            <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
               <div class="card o-hidden">
                  <div class="card-body">
                     <div class="ecommerce-widgets media">
                        <div class="media-body">
                           <p class="f-w-500 font-roboto"><?php echo e(trans('dashboard.C.A Réalisé/Semaine')); ?></p>
                           <h4 class="f-w-500 mb-0 f-26"><span class=""><?php echo e($obj_realised['this_week']); ?></span>Mad</h4>
                        </div>
                        <div class="media-body">
                           <p class="f-w-500 font-roboto"><?php echo e(trans('dashboard.OBJECTIVE Hebdomadaire')); ?><span class="badge pill-badge-primary ml-3"></span></p>
                           <h4 class="f-w-500 mb-0 f-26"><span class=""><?php echo e($objective->weekly); ?></span>Mad</h4>
                        </div>
                     </div>
                  </div>
                  <span class="badge pill-badge-primary ml-15"> <?php echo e(date('Y-m-d')); ?></span>
               </div>
            </div>
            


</div>
</div>

<div class="col-md-7  box-col-12">
   <div class="card">
      <div class="card-header card-no-border">
         <h5><?php echo e(trans('dashboard.Mes Derniers Factures')); ?></h5>
         <div class="card-header-right">
            <ul class="list-unstyled card-option">
               <li><i class="fa fa-spin fa-cog"></i></li>
               <li><i class="view-html fa fa-code"></i></li>
               <li><i class="icofont icofont-maximize full-card"></i></li>
               <li><i class="icofont icofont-minus minimize-card"></i></li>
               <li><i class="icofont icofont-refresh reload-card"></i></li>
               <li><i class="icofont icofont-error close-card"></i></li>
            </ul>
         </div>
      </div>
      <div class="card-body pt-0">
         <div class="card-body">
            <div class="best-seller-table responsive-tbl">
               <div class="item">
                  <div class="table-responsive product-list">
                     <table class="table table-bordernone">
                        <thead>
                           <tr>
                              <th class="f-22">
                                 <?php echo e(trans('dashboard.Facture')); ?>

                              </th>
                              <th><?php echo e(trans('dashboard.Client')); ?></th>
                              <th><?php echo e(trans('dashboard.Date')); ?></th>
                              <th><?php echo e(trans('dashboard.Total')); ?></th>
                              <th class="text-right"><?php echo e(trans('communs.Status')); ?></th>
                           </tr>
                        </thead>
                        <tbody>
       
                           <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <tr>
                              <td>
                                 <div class="d-inline-block align-middle">

                                    <img class="img-40 m-r-15 rounded-circle align-top" src="<?php echo e(customerAvatar($item->customer->sexe)); ?>" alt="">

                                    <div class="status-circle bg-primary"></div>
                                    <div class="d-inline-block">
                                       <span>##<?php echo e($item->id); ?></span>
                                       <p class="font-roboto"><?php echo e($item->customer->phone); ?></p>
                                    </div>
                                 </div>
                              </td>
                              <td><?php echo e($item->customer->getFullNameAttribute()); ?></td>
                              <td><?php echo e($item->invoice_date); ?></td>
                              <td> <span class="label"><?php echo e($item->total); ?> MAD</span></td>
                              <td class="text-right"><i class="fa fa-check-circle"></i><?php echo e(getInvoiceStatus($item->status)); ?></td>
                           </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="col-md-5  box-col-12">
   <div class="card">
      <div class="card-header card-no-border">
         <h5><?php echo e(trans('dashboard.Dérniers Clients')); ?></h5>
         <div class="card-header-right">
            <ul class="list-unstyled card-option">
               <li><i class="fa fa-spin fa-cog"></i></li>
               <li><i class="view-html fa fa-code"></i></li>
               <li><i class="icofont icofont-maximize full-card"></i></li>
               <li><i class="icofont icofont-minus minimize-card"></i></li>
               <li><i class="icofont icofont-refresh reload-card"></i></li>
               <li><i class="icofont icofont-error close-card"></i></li>
            </ul>
         </div>
      </div>
      <div class="card-body new-update pt-0">
         <div class="activity-timeline">
            <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="media">
               <div class="activity-line" style="position: relative"></div>
               <div class="activity-dot-secondary"></div>
               <div class="media-body">
                  <span><?php echo e($item->customer->firstname); ?> <?php echo e($item->customer->lastname); ?></span>
                  <p class="font-roboto"><?php echo e($item->customer->email); ?> - <?php echo e($item->customer->phone); ?> </p>
               </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


         </div>
      </div>
   </div>
</div>
</div>
</div>

<script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGCQvcXUsXwCdYArPXo72dLZ31WS3WQRw&amp;callback=initMap"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/chart/chartist/chartist.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/prism/prism.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/clipboard/clipboard.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/counter/jquery.waypoints.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/counter/jquery.counterup.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/counter/counter-custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/custom-card/custom-card.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/owlcarousel/owl.carousel.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/dashboard/dashboard_2.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.employe.master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>