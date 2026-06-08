<div class="page-header">
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full" action="#" method="get">
      <div class="form-group w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="{{trans('communs.recherche SHERIN')}} .." name="q" title="" autofocus />
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">{{trans('communs.Loading')}}...</span></div>
            <i class="close-search" data-feather="x"></i>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    {{-- <div class="header-logo-wrapper">
      <div class="logo-wrapper">
        <a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo.png')}}" alt="" /></a>
      </div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="sliders"></i></div>
    </div> --}}
    <div class="left-header col horizontal-wrapper pl-0">
      <a href="{{ url('dashboard/employe') }}"><h3 class="text-left">{{ auth()->user()->store->name }}</h3></a>        
    </div>
    <div class="nav-right col-8 pull-right right-header p-0">
      <ul class="nav-menus">
        <li class="language-nav">
          <div class="translate_wrapper">
            <div class="current_lang">
              <div class="lang"><i class="flag-icon flag-icon-{{ (App::getLocale() == 'en') ? 'us' : App::getLocale() }}"></i><span class="lang-txt">{{ App::getLocale() }} </span></div>
            </div>
            <div class="more_lang">
              <a href="{{ route('lang', 'en' )}}" class="{{ (App::getLocale()  == 'en') ? 'active' : ''}}">
                <div class="lang {{ (App::getLocale()  == 'en') ? 'selected' : ''}}" data-value="en"><i class="flag-icon flag-icon-us"></i> <span class="lang-txt">English</span><span> (US)</span></div>
              </a>
              <a href="{{ route('lang' , 'fr' )}}" class="{{ (App::getLocale()  == 'fr') ? 'active' : ''}}">
                <div class="lang {{ (App::getLocale()  == 'fr') ? 'selected' : ''}}" data-value="fr"><i class="flag-icon flag-icon-fr"></i> <span class="lang-txt">Français</span>(FR)</div>
              </a>
              <a href="{{ route('lang' , 'ma' )}}" class="{{ (App::getLocale()  == 'ma') ? 'active' : ''}}">
                <div class="lang {{ (App::getLocale()  == 'ma') ? 'selected' : ''}}" data-value="ma"><i class="flag-icon flag-icon-ma"></i> <span class="lang-txt">لعربية</span> <span> (AR)</span></div>
              </a>
            </div>
          </div>
        </li>
        <li>
          <span class="header-search"><i data-feather="search"></i></span>
        </li>
        <li>
          {{-- <div class="mode"><i class="fa fa-moon-o"></i></div>fa fa-lightbulb-o --}}
          <div class="mode"><i class="fa fa-lightbulb-o"></i></div>
        </li>
        
        <li class="maximize">
          <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a>
        </li>
        <li class="profile-nav onhover-dropdown p-0 mr-0">
          <div class="media profile-media">
            <img class="b-r-10" src="{{asset('assets/images/dashboard/profile.jpg')}}" alt="" />
            <div class="media-body">
              <span>{{auth()->user()->getFullNameAttribute()}}</span>
              <p class="mb-0 font-roboto">
                  @if(auth()->user()->is_admin==1)
                    {{trans('Admin')}}
                  @elseif(auth()->user()->is_admin==2)
                    {{trans('Propriétaire')}}
                  @endif
                <i class="middle fa fa-angle-down"></i></p>
            </div>
          </div>
          <ul class="chat-dropdown onhover-show-div">
            <li>
              <a href="#"><i data-feather="user"></i><span> {{trans('')}}</span></a>
            </li>
            <li>
             @if(auth()->user()->is_admin==1)
              <a href="{{route('admin.personal')}}"><i data-feather="file-text"></i><span> {{trans('Infos Personnelles')}}</span></a>
            @elseif(auth()->user()->is_admin==2)
              <a href="{{route('owner.personal')}}"><i data-feather="file-text"></i><span> {{trans('Infos Personnelles')}}</span></a>
            @else 
            <a href="{{route('employe.personal')}}"><i data-feather="file-text"></i><span> {{trans('Infos Personnelles')}}</span></a>
            @endif
            </li>
            <li>
              <a href="{{route('admin.access')}}"><i data-feather="file-text"></i><span> {{trans(' Infos d\'Accès')}}</span></a>
            </li>
            {{-- <li>
              <a href="{{route('admin.inbox')}}"><i data-feather="mail"></i><span> {{trans('Boîte de réception')}}</span></a>
            </li> --}}
            @if(auth()->user()->is_admin==2)
            <li>
              <a href="{{route('owner.solde')}}"><i data-feather="dollar-sign"></i><span> {{trans('Solde')}}</span></a>
            </li>
            @endif
            <li>
              <a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="log-in"> </i><span> {{trans('Se Déconnecter')}}</span></a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
          </ul>
        </li>
      </ul>
    </div>
    <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">
      <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
      <div class="ProfileCard-details">
      <div class="ProfileCard-realName">@{{name}}</div>
      </div>
      </div>
    </script>
    <script class="empty-template" type="text/x-handlebars-template">
      <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>
    </script>
  </div>
</div>