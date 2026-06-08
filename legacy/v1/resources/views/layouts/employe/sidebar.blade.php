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
               <div class="mobile-back text-right"><span>{{trans('communs.Back')}}</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-main-title">
               <div>
                  {{-- <h6 class="lan-1">{{ trans('lang.General') }}  </h6> --}}
                  <h6 class="lan-1">{{ trans('communs.Configuration') }}  </h6>
                  <p class="lan-2">{{ trans('communs.Propriétaires,Magasins,Employés') }}</p>
                  {{-- <p class="lan-2">{{ trans('lang.Dashboards,widgets & layout.') }}</p> --}}
               </div>
            </li>
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="{{url('dashboard/employe')}}"><i data-feather="home"></i><span class="lan-3">{{ trans('dashboard.dashboard') }} </span>
               </a>
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="users"></i><span class="lan-3">{{ trans('communs.Clients') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.customers')}}">{{ trans('communs.Liste') }}</a></li>
               </ul> 
            </li>
            @if (auth()->user()->store->POS == 1)
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="file-text"></i><span class="lan-3">{{ trans('communs.POS') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  @if (auth()->user()->store->Table == 1)
                     <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.table.index')}}">{{ trans('communs.Nouveau') }}</a></li>
                  @else
                     <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.pos.create')}}">{{ trans('communs.Nouveau') }}</a></li>
                  @endif
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.poss')}}">{{ trans('communs.Liste') }}</a></li>
               </ul>
            </li>
            @else
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="file-text"></i><span class="lan-3">{{ trans('communs.Factures') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">       
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.invoices.create')}}">{{ trans('communs.Nouveau') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.invoices')}}">{{ trans('communs.Liste') }}</a></li>
               </ul> 
            </li>
            @endif
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="package"></i><span class="lan-3">{{ trans('communs.Produits') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.products')}}">{{ trans('communs.Liste') }}</a></li>
                  @if(auth()->user()->store->POS == 1)
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.categorie.create')}}">{{ trans('communs.Categorie') }}</a></li>
                  @endif
               </ul> 
            </li>
            @if(auth()->user()->store->rendez_vous == 1)
               <li class="sidebar-list">
                  <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="{{route('employe.calendar')}}">
                  <i data-feather="calendar"></i><span class="lan-3">{{ trans('dashboard.Rendez-vous') }} </span></a>
               </li>
            @endif
           
            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="dollar-sign"></i><span class="lan-3">{{ trans('communs.Caisse') }} </span>
                  <div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.caisse.index')}}">{{ trans('Etat de Caisse') }}</a></li>
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('employe.initial-fonds.index')}}">{{ trans('communs.Caisse de départ') }}</a></li>
               </ul> 
            </li>
            @if(auth()->user()->store->Anniv == 1)
               <li class="sidebar-list">
                  <a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="{{route('employe.anniversaire')}}">
                  <i data-feather="gift"></i><span class="lan-3">{{ trans('dashboard.Anniversaire') }} </span></a>
               </li>
            @endif
         </ul>
      </div>
      <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
   </nav>
</div>
