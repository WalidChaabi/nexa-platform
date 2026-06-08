<div class="sidebar-wrapper">
   <div class="logo-wrapper">
      <a href="<?php echo e(route('/')); ?>"><img class="img-fluid for-light" src="<?php echo e(asset('assets/images/logo/logo.png')); ?>" alt="" /><img class="img-fluid for-dark" src="<?php echo e(asset('assets/images/logo/logo_dark.png')); ?>" alt="" /></a>
      <div class="back-btn"><i class="fa fa-angle-left"></i></div>
      
   </div>
   <div class="logo-icon-wrapper">
      <a href="<?php echo e(route('/')); ?>"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt="" /></a>
   </div>
   <nav class="sidebar-main">
      <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
      <div id="sidebar-menu">
         <ul class="sidebar-links custom-scrollbar">
            <li class="back-btn">
               <a href="<?php echo e(route('/')); ?>"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt="" /></a>
               <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-main-title">
               <div>
                 
                  <h6 class="lan-1"><?php echo e(trans('sideBar.Configuration')); ?>  </h6>
                  <p class="lan-2"><?php echo e(trans('sideBar.Propriétaires,Magasins,Employés')); ?></p>
             
               </div>
            </li>
            <li class="sidebar-list">
               
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#">
                  <i data-feather="settings"></i><span class="lan-3"><?php echo e(trans('sideBar.Configuration')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='admin.owners' ? 'active' : ''); ?>" href="<?php echo e(route('admin.owners')); ?>"><?php echo e(trans('sideBar.Propriétaires')); ?></a></li>
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='admin.activities' ? 'active' : ''); ?>" href="<?php echo e(route('admin.activities')); ?>"><?php echo e(trans('sideBar.Activitées')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='admin.stores' ? 'active' : ''); ?>" href="<?php echo e(route('admin.stores')); ?>"><?php echo e(trans('sideBar.Magasins')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='admin.employees' ? 'active' : ''); ?>" href="<?php echo e(route('admin.employees')); ?>"><?php echo e(trans('sideBar.Employés')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='admin.cross-products' ? 'active' : ''); ?>" href="<?php echo e(route('admin.cross-products')); ?>"><?php echo e(trans('sideBar.Cross Selling')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='admin.products' ? 'active' : ''); ?>" href="<?php echo e(route('admin.products')); ?>"><?php echo e(trans('sideBar.Produits')); ?> <?php echo e(trans('sideBar.et Prestations')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='admin.menus' ? 'active' : ''); ?>" href="<?php echo e(route('admin.menus')); ?>"><?php echo e(trans('Menus Restaurants')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='admin.menus' ? 'active' : ''); ?>" href="<?php echo e(route('admin.historique-paiments')); ?>"><?php echo e(trans('l\'historique des transactions')); ?></a></li>
               </ul> 
            </li>
            <li class="sidebar-main-title">
               <div>
                  
                  <h6 class="lan-1"><?php echo e(trans('sideBar.Campagnes')); ?>  </h6>
                  <p class="lan-2"><?php echo e(trans('sideBar.Campagnes,Models,SMS,Bulletins')); ?></p>
                  
               </div>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/templates' ? 'active' : ''); ?>" 
                  href="<?php echo e(route('admin.templates')); ?>"><i data-feather="align-justify"></i><span class="lan-6"><?php echo e(trans('sideBar.Models')); ?></span></a>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getName() == '/campaigns' ? 'active' : ''); ?>" href="#">
                  <i data-feather="volume"></i><span class="lan-6"><?php echo e(trans('Campagnes')); ?></span>
                  <div class="according-menu">
                     <i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/campaigns' ? 'down' : 'right'); ?>"></i>
                  </div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='campaigns' ? 'active' : ''); ?>"  href="<?php echo e(route('admin.campaigns')); ?>"><?php echo e(trans('sideBar.List campagnes')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='create' ? 'active' : ''); ?>" href="<?php echo e(route('admin.campaigns.create')); ?>"><?php echo e(trans('sideBar.Ajouter campagnes')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='create' ? 'active' : ''); ?>" href="<?php echo e(route('admin.campaigns.test')); ?>"><?php echo e(trans('sideBar.Payment campagnes')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='create' ? 'active' : ''); ?>" href="<?php echo e(route('admin.campaigns.details')); ?>"><?php echo e(trans('sideBar.Details campagnes')); ?></a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#">
                  <i data-feather="send"></i><span class="lan-3"><?php echo e(trans('sideBar.SMS')); ?> </span>
                  <div class="according-menu">
                     <i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i>
                  </div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('admin.sms.campaign')); ?>"><?php echo e(trans('sideBar.SMS Campagne')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('admin.unique')); ?>"><?php echo e(trans('sideBar.SMS Quick Send')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('admin.sms.campaigns')); ?>"><?php echo e(trans('sideBar.Liste Campagnes')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('admin.sms.uniques')); ?>"><?php echo e(trans('sideBar.List Unique')); ?></a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/admins' ? 'active' : ''); ?>" href="#">
                  <i data-feather="mail"></i><span class="lan-3"><?php echo e(trans('sideBar.Newsletter')); ?> </span>
                  <div class="according-menu">
                     <i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/admin' ? 'down' : 'right'); ?>"></i>
                  </div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('admin.campaign.newsletter.create')); ?>"><?php echo e(trans('sideBar.E-mail Campagne')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('admin.campaign.newsletter')); ?>"><?php echo e(trans('sideBar.Liste e-mail')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='admin.campaign.newsletter' ? 'active' : ''); ?>" href="<?php echo e(route('admin.campaign.newsletter')); ?>"><?php echo e(trans('Mes Templates')); ?></a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/models' ? 'active' : ''); ?>" href="<?php echo e(route('admin.campaigns')); ?>"><i data-feather="users"></i><span class="lan-6"><?php echo e(trans('sideBar.Clients')); ?></span></a>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'active' : ''); ?>" href="#"><i data-feather="bar-chart"></i><span class="lan-3"><?php echo e(trans('sideBar.Rapports')); ?> </span>
                  <div class="according-menu"><i class="fa fa-angle-<?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right'); ?>"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: <?php echo e(request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;'); ?>">
                  <li><a class="lan-4 <?php echo e(Route::currentRouteName()=='index' ? 'active' : ''); ?>" href="<?php echo e(route('admin.report.campaign')); ?>"><?php echo e(trans('sideBar.Campagnes')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('admin.report.sms')); ?>"><?php echo e(trans('sideBar.Envois SMS')); ?></a></li>
                  <li><a class="lan-5 <?php echo e(Route::currentRouteName()=='dashboard-02' ? 'active' : ''); ?>" href="<?php echo e(route('admin.report.charge')); ?>"><?php echo e(trans('sideBar.Unités de Crédit')); ?></a></li>
               </ul> 
            </li>
         </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
   </nav>
</div>

