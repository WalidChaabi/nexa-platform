<header class="header custom-header">
    <!--start navbar-->
    <nav class="navbar affix navbar-expand-lg fixed-top custom-nav white-bg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('new/images/logo-nexa.webp') }}"
                    width="120" alt="nexa.app" class="images-fluid"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="ti-menu"></span>
            </button>

            <div class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link page-scroll" data-section="services">Nos services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" data-section="about">Fidélité client</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" data-section="features">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" data-section="pricing">Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" data-section="contact">Contactez-nous</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="{{ route('mention') }}">Mentions légales</a>
                    </li>
                    <li class="nav-item">
                        {{-- <a class="nav-link page-scroll" href="{{ url('127.0.0.1') }}">Espace client</a> --}}
                        <a class="nav-link page-scroll" href="{{ route('authentication.login') }}">Espace client</a>
                    </li>
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link page-scroll" href="?section=home#services">Nos services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="/#about">Fidélité client</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#features">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="/#pricing">Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="/#contact">Contactez-nous</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="{{ route('mention') }}">Mentions légales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="{{ url('login') }}">Espace client</a>
                    </li> --}}

                </ul>
            </div>
        </div>
    </nav>
    <!--end navbar-->
</header>

<a href="#services"></a>
<a href="#about"></a>
<a href="#features"></a>
<a href="#pricing"></a>
<a href="#contact"></a>
