<div class="sidebar-wrapper">
   <div class="logo-wrapper">
      <a href="#"><img class="img-fluid for-light" src="<?php echo e(asset('assets/images/logo/logo.png')); ?>" alt="" /><img class="img-fluid for-dark" src="<?php echo e(asset('assets/images/logo/logo_dark.png')); ?>" alt="" /></a>
      <div class="back-btn"><i class="fa fa-angle-left"></i></div>
      
   </div>
   <div class="logo-icon-wrapper">
      <a href="#"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt="" /></a>
   </div>
   <nav class="sidebar-main">
      <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
      <div id="sidebar-menu">
         <ul class="sidebar-links custom-scrollbar">
            <li class="back-btn">
               <a href="#"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt="" /></a>
               <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-main-title">
               <div>
                  
                  <h6 class="lan-1"><?php echo e(trans('sideBar.Configuration')); ?>  </h6>
                  <p class="lan-2"><?php echo e(trans('sideBar.Magasins,Employés.')); ?></p>
                  
               </div>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="<?php echo e(url('dashboard/owner')); ?>"><i data-feather="home"></i><span class="lan-3"><?php echo e(trans('sideBar.Dashboard')); ?></span>
               </a>
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/owner' ? 'active' : ''); ?>" href="#"><i data-feather="settings"></i><span class="lan-3"><?php echo e(trans('sideBar.Magasins')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/owner' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/owner' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('owner.stores')); ?>"><?php echo e(trans('sideBar.List')); ?></a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="bar-chart-2"></i><span class="lan-3"><?php echo e(trans('sideBar.Statistiques')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
                <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/owner/invoices' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('owner.invoices.repport')); ?>"><?php echo e(trans('communs.Factures')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('owner.customers.repport')); ?>"><?php echo e(trans('sideBar.Clients')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('owner.products.repport')); ?>"><?php echo e(trans('sideBar.Produits')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('owner.invoices.canceled')); ?>"><?php echo e(trans('sideBar.Factures Annulées')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('owner.invoices.returns')); ?>"><?php echo e(trans('Retour')); ?></a></li>
               </ul>  
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" 
                  href="#"><i data-feather="volume"></i><span class="lan-3"><?php echo e(trans('sideBar.Campagnes')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/owner/campaigns' ? 'block;' : 'none;'); ?>">
                  
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('owner.campaigns.list')); ?>"><?php echo e(trans('sideBar.Campaigns list')); ?></a></li>
               </ul> 
            </li>
            
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="users"></i><span class="lan-3"><?php echo e(trans('sideBar.Employées')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('owner.employees')); ?>"><?php echo e(trans('sideBar.List')); ?></a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="users"></i><span class="lan-3"><?php echo e(trans('sideBar.Objectives')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('owner.objectives')); ?>"><?php echo e(trans('sideBar.List')); ?></a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="package"></i><span class="lan-3"><?php echo e(trans('sideBar.Produits et Préstations')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='owner' ? 'active' : ''); ?>" href="<?php echo e(url('owner/products')); ?>"><?php echo e(trans('sideBar.Liste')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='owner' ? 'active' : ''); ?>" href="<?php echo e(route('owner.products.historique')); ?>"><?php echo e(trans('sideBar.Historique produits')); ?></a></li>
               </ul> 
            </li>
            <?php $__currentLoopData = auth()->user()->stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <?php if($store->rendez_vous == 1): ?>
                  <li class="sidebar-list">
                  <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getName() == 'owner.calendar' ? 'active' : ''); ?>" href="<?php echo e(route('owner.calendar')); ?>"><i data-feather="calendar"></i><span class="lan-3"><?php echo e(trans('dashboard.Rendez-vous')); ?> </span>
                  </a>
                  </li>
               <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getName() == 'owner.historique-paiements' ? 'active' : ''); ?>" href="<?php echo e(route('owner.historique-paiements')); ?>"><i data-feather="credit-card"></i><span class="lan-3"><?php echo e(trans('sideBar.Paiements')); ?> </span>
               </a>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="dollar-sign"></i><span class="lan-3"><?php echo e(trans('communs.Caisse')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='owner' ? 'active' : ''); ?>" href="<?php echo e(url('owner/caisse')); ?>"><?php echo e(trans('sideBar.Liste')); ?></a></li>
               </ul> 
            </li>
         </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
   </nav>
</div>
