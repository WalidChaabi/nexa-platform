<div class="sidebar-wrapper">
   <div class="logo-wrapper">
      <a href="#"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo.png')}}" alt="" /><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="" /></a>
      <div class="back-btn"><i class="fa fa-angle-left"></i></div>
      {{-- <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div> --}}
   </div>
   <div class="logo-icon-wrapper">
      <a href="#"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt="" /></a>
   </div>
   <nav class="sidebar-main">
      <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
      <div id="sidebar-menu">
         <ul class="sidebar-links custom-scrollbar">
            <li class="back-btn">
               <a href="#"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt="" /></a>
               <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-main-title">
               <div>
                  {{-- <h6 class="lan-1">{{ trans('sideBar.lang.General') }}  </h6> --}}
                  <h6 class="lan-1">{{ trans('sideBar.Configuration') }}  </h6>
                  <p class="lan-2">{{ trans('sideBar.Magasins,Employés.') }}</p>
                  {{-- <p class="lan-2">{{ trans('sideBar.lang.Dashboards,widgets & layout.') }}</p> --}}
               </div>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="{{url('dashboard/owner')}}"><i data-feather="home"></i><span class="lan-3">{{trans('sideBar.Dashboard')}}</span>
               </a>
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/owner' ? 'active' : '' }}" href="#"><i data-feather="settings"></i><span class="lan-3">{{ trans('sideBar.Magasins') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/owner' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/owner' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.stores')}}">{{ trans('sideBar.List') }}</a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="bar-chart-2"></i><span class="lan-3">{{ trans('sideBar.Statistiques') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
                <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/owner/invoices' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.invoices.repport')}}">{{ trans('communs.Factures') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.customers.repport')}}">{{ trans('sideBar.Clients') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.products.repport')}}">{{ trans('sideBar.Produits') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.invoices.canceled')}}">{{ trans('sideBar.Factures Annulées') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.invoices.returns')}}">{{ trans('Retour') }}</a></li>
               </ul>  
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" 
                  href="#"><i data-feather="volume"></i><span class="lan-3">{{ trans('sideBar.Campagnes') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/owner/campaigns' ? 'block;' : 'none;' }}">
                  {{-- <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.campaigns.rapport')}}">{{ trans('sideBar.Rapport') }}</a></li> --}}
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('owner.campaigns.list')}}">{{ trans('sideBar.Campaigns list') }}</a></li>
               </ul> 
            </li>
            
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="users"></i><span class="lan-3">{{ trans('sideBar.Employées') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('owner.employees')}}">{{ trans('sideBar.List') }}</a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="users"></i><span class="lan-3">{{ trans('sideBar.Objectives') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('owner.objectives')}}">{{ trans('sideBar.List') }}</a></li>
               </ul> 
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="package"></i><span class="lan-3">{{ trans('sideBar.Produits et Préstations') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='owner' ? 'active' : '' }}" href="{{url('owner/products')}}">{{ trans('sideBar.Liste') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='owner' ? 'active' : '' }}" href="{{route('owner.products.historique')}}">{{ trans('sideBar.Historique produits') }}</a></li>
               </ul> 
            </li>
            @foreach (auth()->user()->stores as $store)
               @if($store->rendez_vous == 1)
                  <li class="sidebar-list">
                  <a class="sidebar-link sidebar-title {{request()->route()->getName() == 'owner.calendar' ? 'active' : '' }}" href="{{route('owner.calendar')}}"><i data-feather="calendar"></i><span class="lan-3">{{ trans('dashboard.Rendez-vous') }} </span>
                  </a>
                  </li>
               @endif
            @endforeach
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getName() == 'owner.historique-paiements' ? 'active' : '' }}" href="{{route('owner.historique-paiements')}}"><i data-feather="credit-card"></i><span class="lan-3">{{ trans('sideBar.Paiements') }} </span>
               </a>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="dollar-sign"></i><span class="lan-3">{{ trans('communs.Caisse') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='owner' ? 'active' : '' }}" href="{{url('owner/caisse')}}">{{ trans('sideBar.Liste') }}</a></li>
               </ul> 
            </li>
         </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
   </nav>
</div>
