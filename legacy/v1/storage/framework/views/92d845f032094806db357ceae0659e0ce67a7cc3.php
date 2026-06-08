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
               <div class="mobile-back text-right"><span><?php echo e(trans('communs.Back')); ?></span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-main-title">
               <div>
                  
                  <h6 class="lan-1"><?php echo e(trans('communs.Configuration')); ?>  </h6>
                  <p class="lan-2"><?php echo e(trans('communs.Propriétaires,Magasins,Employés')); ?></p>
                  
               </div>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="<?php echo e(url('dashboard/employe')); ?>"><i data-feather="home"></i><span class="lan-3"><?php echo e(trans('dashboard.dashboard')); ?> </span>
               </a>
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="users"></i><span class="lan-3"><?php echo e(trans('communs.Clients')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.customers')); ?>"><?php echo e(trans('communs.Liste')); ?></a></li>
               </ul> 
            </li>
            <?php if(auth()->user()->store->POS == 1): ?>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="file-text"></i><span class="lan-3"><?php echo e(trans('communs.POS')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <?php if(auth()->user()->store->Table == 1): ?>
                     <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.table.index')); ?>"><?php echo e(trans('communs.Nouveau')); ?></a></li>
                  <?php else: ?>
                     <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.pos.create')); ?>"><?php echo e(trans('communs.Nouveau')); ?></a></li>
                  <?php endif; ?>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.poss')); ?>"><?php echo e(trans('communs.Liste')); ?></a></li>
               </ul>
            </li>
            <?php else: ?>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="file-text"></i><span class="lan-3"><?php echo e(trans('communs.Factures')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">       
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.invoices.create')); ?>"><?php echo e(trans('communs.Nouveau')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.invoices')); ?>"><?php echo e(trans('communs.Liste')); ?></a></li>
               </ul> 
            </li>
            <?php endif; ?>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="package"></i><span class="lan-3"><?php echo e(trans('communs.Produits')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.products')); ?>"><?php echo e(trans('communs.Liste')); ?></a></li>
                  <?php if(auth()->user()->store->POS == 1): ?>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.categorie.create')); ?>"><?php echo e(trans('communs.Categorie')); ?></a></li>
                  <?php endif; ?>
               </ul> 
            </li>
            <?php if(auth()->user()->store->rendez_vous == 1): ?>
               <li class="sidebar-list">
                  <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="<?php echo e(route('employe.calendar')); ?>">
                  <i data-feather="calendar"></i><span class="lan-3"><?php echo e(trans('dashboard.Rendez-vous')); ?> </span></a>
               </li>
            <?php endif; ?>
           
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="dollar-sign"></i><span class="lan-3"><?php echo e(trans('communs.Caisse')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.caisse.index')); ?>"><?php echo e(trans('Etat de Caisse')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('employe.initial-fonds.index')); ?>"><?php echo e(trans('communs.Caisse de départ')); ?></a></li>
               </ul> 
            </li>
            <?php if(auth()->user()->store->Anniv == 1): ?>
               <li class="sidebar-list">
                  <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="<?php echo e(route('employe.anniversaire')); ?>">
                  <i data-feather="gift"></i><span class="lan-3"><?php echo e(trans('dashboard.Anniversaire')); ?> </span></a>
               </li>
            <?php endif; ?>
         </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
   </nav>
</div>
