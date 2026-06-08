<div class="page-header">
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full" action="#" method="get">
      <div class="form-group w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="<?php echo e(trans('communs.recherche SHERIN')); ?> .." name="q" title="" autofocus />
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only"><?php echo e(trans('communs.Loading')); ?>...</span></div>
            <i class="close-search" data-feather="x"></i>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    
    <div class="left-header col horizontal-wrapper pl-0">
      <a href="<?php echo e(url('dashboard/employe')); ?>"><h3 class="text-left"><?php echo e(auth()->user()->store->name); ?></h3></a>        
    </div>
    <div class="nav-right col-8 pull-right right-header p-0">
      <ul class="nav-menus">
        <li class="language-nav">
          <div class="translate_wrapper">
            <div class="current_lang">
              <div class="lang"><i class="flag-icon flag-icon-<?php echo e((App::getLocale() == 'en') ? 'us' : App::getLocale()); ?>"></i><span class="lang-txt"><?php echo e(App::getLocale()); ?> </span></div>
            </div>
            <div class="more_lang">
              <a href="<?php echo e(route('lang', 'en' )); ?>" class="<?php echo e((App::getLocale()  == 'en') ? 'active' : ''); ?>">
                <div class="lang <?php echo e((App::getLocale()  == 'en') ? 'selected' : ''); ?>" data-value="en"><i class="flag-icon flag-icon-us"></i> <span class="lang-txt">English</span><span> (US)</span></div>
              </a>
              <a href="<?php echo e(route('lang' , 'fr' )); ?>" class="<?php echo e((App::getLocale()  == 'fr') ? 'active' : ''); ?>">
                <div class="lang <?php echo e((App::getLocale()  == 'fr') ? 'selected' : ''); ?>" data-value="fr"><i class="flag-icon flag-icon-fr"></i> <span class="lang-txt">Français</span>(FR)</div>
              </a>
              <a href="<?php echo e(route('lang' , 'ma' )); ?>" class="<?php echo e((App::getLocale()  == 'ma') ? 'active' : ''); ?>">
                <div class="lang <?php echo e((App::getLocale()  == 'ma') ? 'selected' : ''); ?>" data-value="ma"><i class="flag-icon flag-icon-ma"></i> <span class="lang-txt">لعربية</span> <span> (AR)</span></div>
              </a>
            </div>
          </div>
        </li>
        <li>
          <span class="header-search"><i data-feather="search"></i></span>
        </li>
        <li>
          
          <div class="mode"><i class="fa fa-lightbulb-o"></i></div>
        </li>
        
        <li class="maximize">
          <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a>
        </li>
        <li class="profile-nav onhover-dropdown p-0 mr-0">
          <div class="media profile-media">
            <img class="b-r-10" src="<?php echo e(asset('assets/images/dashboard/profile.jpg')); ?>" alt="" />
            <div class="media-body">
              <span><?php echo e(auth()->user()->getFullNameAttribute()); ?></span>
              <p class="mb-0 font-roboto">
                  <?php if(auth()->user()->is_admin==1): ?>
                    <?php echo e(trans('Admin')); ?>

                  <?php elseif(auth()->user()->is_admin==2): ?>
                    <?php echo e(trans('Propriétaire')); ?>

                  <?php endif; ?>
                <i class="middle fa fa-angle-down"></i></p>
            </div>
          </div>
          <ul class="chat-dropdown onhover-show-div">
            <li>
              <a href="#"><i data-feather="user"></i><span> <?php echo e(trans('')); ?></span></a>
            </li>
            <li>
             <?php if(auth()->user()->is_admin==1): ?>
              <a href="<?php echo e(route('admin.personal')); ?>"><i data-feather="file-text"></i><span> <?php echo e(trans('Infos Personnelles')); ?></span></a>
            <?php elseif(auth()->user()->is_admin==2): ?>
              <a href="<?php echo e(route('owner.personal')); ?>"><i data-feather="file-text"></i><span> <?php echo e(trans('Infos Personnelles')); ?></span></a>
            <?php else: ?> 
            <a href="<?php echo e(route('employe.personal')); ?>"><i data-feather="file-text"></i><span> <?php echo e(trans('Infos Personnelles')); ?></span></a>
            <?php endif; ?>
            </li>
            <li>
              <a href="<?php echo e(route('admin.access')); ?>"><i data-feather="file-text"></i><span> <?php echo e(trans(' Infos d\'Accès')); ?></span></a>
            </li>
            
            <?php if(auth()->user()->is_admin==2): ?>
            <li>
              <a href="<?php echo e(route('owner.solde')); ?>"><i data-feather="dollar-sign"></i><span> <?php echo e(trans('Solde')); ?></span></a>
            </li>
            <?php endif; ?>
            <li>
              <a  href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="log-in"> </i><span> <?php echo e(trans('Se Déconnecter')); ?></span></a>
            </li>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
              <?php echo e(csrf_field()); ?>

          </form>
          </ul>
        </li>
      </ul>
    </div>
    <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">
      <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
      <div class="ProfileCard-details">
      <div class="ProfileCard-realName">{{name}}</div>
      </div>
      </div>
    </script>
    <script class="empty-template" type="text/x-handlebars-template">
      <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>
    </script>
  </div>
</div>