<div class="sidebar-wrapper">
   <div class="logo-wrapper">
      <a href="{{route('index')}}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo.png')}}" alt=""><img class="img-fluid for-dark" src="../assets/images/logo/logo_dark.png" alt=""></a>
      <div class="back-btn"><i class="fa fa-angle-left"></i></div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
   </div>
   <div class="logo-icon-wrapper"><a href="{{route('index')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div>
   <div id="sidebar-menu">
         <ul class="sidebar-links custom-scrollbar">
            <li class="back-btn">
               <a href="{{route('index')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a>
               <div class="mobile-back text-right"><span>Back</span><i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
            </li>
            <li class="sidebar-list">
               <label class="badge badge-success">2</label>
               <a class="sidebar-link sidebar-title {{ Route::currentRouteName()=='index' ? 'active' : '' }}" style="cursor: pointer;">
                  <i data-feather="home"></i>
                  <span class="lan-3">Dashboard </span>
                  <div class="according-menu"><i class="fa fa-angle-{{ Route::currentRouteName()=='index' ? 'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ Route::currentRouteName()=='index' ? 'block;' : 'none;' }}">
                  <li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('index')}}">Default</a></li>
               </ul>
            </li>

            <li class="sidebar-list">
               <a class="sidebar-link sidebar-title {{   in_array(request()->route()->getPrefix(), ['/color-version', '/page-layout'] ) ? 'active' : '' }}" href="#">
                  <i data-feather="file-text"></i><span>Starter kit</span>
                  <div class="according-menu"><i class="fa fa-angle-{{ in_array(request()->route()->getPrefix(), ['/color-version', '/page-layout'] ) ?  'down' : 'right' }}"></i></div>
               </a>
               <ul class="sidebar-submenu" style="display: {{ array(request()->route()->getPrefix() => ['color-version', 'page-layout'] ) ? 'block' : 'none' }}">
                  <li>
                     <a class="submenu-title {{ request()->route()->getPrefix() == '/color-version' ? 'active' : '' }}" href="#">Color Version<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                     <ul class="nav-sub-childmenu submenu-content" style="display: {{ request()->route()->getPrefix() == '/color-version' ? 'block;' : 'none;' }}">
                        <li><a href="{{ route('layout-light') }}" class="{{ Route::currentRouteName()=='layout-light' ? 'active' : '' }}">Layout Light</a></li>
                        <li><a href="{{ route('layout-dark') }}" class="{{ Route::currentRouteName()=='layout-dark' ? 'active' : '' }}">Layout Dark</a></li>
                     </ul>
                  </li>
                  <li>
                     <a class="submenu-title {{request()->route()->getPrefix() == '/page-layout' ? 'active' : '' }}" href="#">Page layout
                        <div class="according-menu"><i class="fa fa-angle-{{ request()->route()->getPrefix() == '/page-layout' ? 'down' : 'right' }}"></i></div>
                     </a>
                     <ul class="nav-sub-childmenu submenu-content" style="display: {{ request()->route()->getPrefix() == '/page-layout' ? 'block' : 'none' }}">
                        <li><a href="{{ route('layout-box') }}" class="{{ Route::currentRouteName()=='layout-box' ? 'active' : '' }}">Boxed</a></li>
                        <li><a href="{{ route('layout-rtl') }}" class="{{ Route::currentRouteName()=='layout-rtl' ? 'active' : '' }}">RTL</a></li>
                        <li><a href="{{ route('hide-on-scroll') }}" class="{{ Route::currentRouteName() == 'hide-on-scroll' ? 'active' : ''}}">Hide menu on Scroll</a></li>
                     </ul>
                  </li>
                  <li>
                     <a class="submenu-title {{request()->route()->getPrefix() == '/footers' ? 'active' : '' }}" href="#">Footers
                           <div class="according-menu"><i class="fa fa-angle-{{ request()->route()->getPrefix() == '/footers' ? 'down' : 'right' }}"></i></div>
                     </a>
                     <ul class="nav-sub-childmenu submenu-content"  style="display: {{ request()->route()->getPrefix() == '/footers' ? 'block' : 'none' }}">
                        <li><a href="{{ route('footer-light') }}" class="{{ Route::currentRouteName()=='footer-light' ? 'active' : '' }}">Footer Light</a></li>
                        <li><a href="{{ route('footer-dark') }}" class="{{ Route::currentRouteName()=='footer-dark' ? 'active' : '' }}">Footer Dark</a></li>
                        <li><a href="{{ route('footer-fixed') }}" class="{{ Route::currentRouteName()=='footer-fixed' ? 'active' : '' }}">Footer Fixed</a></li>
                     </ul>
                  </li>
               </ul>
            </li>
         </ul>
   </div>
   <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
</div>