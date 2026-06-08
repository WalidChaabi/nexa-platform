<div class="sidebar-wrapper">
   <div class="logo-wrapper">
      <a href="{{route('/')}}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo.png')}}" alt="" /><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="" /></a>
      <div class="back-btn"><i class="fa fa-angle-left"></i></div>
      {{-- <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div> --}}
   </div>
   <div class="logo-icon-wrapper">
      <a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt="" /></a>
   </div>
   <nav class="sidebar-main">
      <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
      <div id="sidebar-menu">
         <ul class="sidebar-links custom-scrollbar">
            <li class="back-btn">
               <a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt="" /></a>
               <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-main-title">
               <div>
                 
                  <h6 class="lan-1">{{ trans('sideBar.Configuration') }}  </h6>
                  <p class="lan-2">{{ trans('sideBar.Propriétaires,Magasins,Employés') }}</p>
             
               </div>
            </li>
            <li class="sidebar-list">
               {{-- <label class="badge badge-success">2</label> --}}
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#">
                  <i data-feather="settings"></i><span class="lan-3">{{ trans('sideBar.Configuration') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='admin.owners' ? 'active' : '' }}" href="{{route('admin.owners')}}">{{ trans('sideBar.Propriétaires') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='admin.activities' ? 'active' : '' }}" href="{{route('admin.activities')}}">{{ trans('sideBar.Activitées') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='admin.stores' ? 'active' : '' }}" href="{{route('admin.stores')}}">{{ trans('sideBar.Magasins') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='admin.employees' ? 'active' : '' }}" href="{{route('admin.employees')}}">{{ trans('sideBar.Employés') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='admin.cross-products' ? 'active' : '' }}" href="{{route('admin.cross-products')}}">{{ trans('sideBar.Cross Selling') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='admin.products' ? 'active' : '' }}" href="{{route('admin.products')}}">{{ trans('sideBar.Produits') }} {{trans('sideBar.et Prestations')}}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='admin.menus' ? 'active' : '' }}" href="{{route('admin.menus')}}">{{ trans('Menus Restaurants') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='admin.menus' ? 'active' : '' }}" href="{{route('admin.historique-paiments')}}">{{ trans('l\'historique des transactions') }}</a></li>
               </ul> 
            </li>
            <li class="sidebar-main-title">
               <div>
                  {{-- <h6 class="lan-1">{{ trans('lang.General') }}  </h6> --}}
                  <h6 class="lan-1">{{ trans('sideBar.Campagnes') }}  </h6>
                  <p class="lan-2">{{ trans('sideBar.Campagnes,Models,SMS,Bulletins') }}</p>
                  {{-- <p class="lan-2">{{ trans('lang.Dashboards,widgets & layout.') }}</p> --}}
               </div>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/templates' ? 'active' : '' }}" 
                  href="{{route('admin.templates')}}"><i data-feather="align-justify"></i><span class="lan-6">{{ trans('sideBar.Models') }}</span></a>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{ request()->route()->getName() == '/campaigns' ? 'active' : '' }}" href="#">
                  <i data-feather="volume"></i><span class="lan-6">{{ trans('Campagnes') }}</span>
                  <div class="according-menu">
                     <i class="fa fa-angle-{{request()->route()->getPrefix() == '/campaigns' ? 'down' : 'right' }}"></i>
                  </div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='campaigns' ? 'active' : '' }}"  href="{{route('admin.campaigns')}}">{{ trans('sideBar.List campagnes') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='create' ? 'active' : '' }}" href="{{route('admin.campaigns.create')}}">{{ trans('sideBar.Ajouter campagnes') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='create' ? 'active' : '' }}" href="{{route('admin.campaigns.test')}}">{{ trans('sideBar.Payment campagnes') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='create' ? 'active' : '' }}" href="{{route('admin.campaigns.details')}}">{{ trans('sideBar.Details campagnes') }}</a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#">
                  <i data-feather="send"></i><span class="lan-3">{{ trans('sideBar.SMS') }} </span>
                  <div class="according-menu">
                     <i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i>
                  </div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('admin.sms.campaign')}}">{{ trans('sideBar.SMS Campagne') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('admin.unique')}}">{{ trans('sideBar.SMS Quick Send') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('admin.sms.campaigns')}}">{{ trans('sideBar.Liste Campagnes') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('admin.sms.uniques')}}">{{ trans('sideBar.List Unique') }}</a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/admins' ? 'active' : '' }}" href="#">
                  <i data-feather="mail"></i><span class="lan-3">{{ trans('sideBar.Newsletter') }} </span>
                  <div class="according-menu">
                     <i class="fa fa-angle-{{request()->route()->getPrefix() == '/admin' ? 'down' : 'right' }}"></i>
                  </div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('admin.campaign.newsletter.create')}}">{{ trans('sideBar.E-mail Campagne') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('admin.campaign.newsletter')}}">{{ trans('sideBar.Liste e-mail') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='admin.campaign.newsletter' ? 'active' : '' }}" href="{{route('admin.campaign.newsletter')}}">{{ trans('Mes Templates') }}</a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/models' ? 'active' : '' }}" href="{{route('admin.campaigns')}}"><i data-feather="users"></i><span class="lan-6">{{ trans('sideBar.Clients') }}</span></a>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="bar-chart"></i><span class="lan-3">{{ trans('sideBar.Rapports') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('admin.report.campaign')}}">{{ trans('sideBar.Campagnes') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('admin.report.sms')}}">{{ trans('sideBar.Envois SMS') }}</a></li>
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('admin.report.charge')}}">{{ trans('sideBar.Unités de Crédit') }}</a></li>
               </ul> 
            </li>
         </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
   </nav>
</div>

