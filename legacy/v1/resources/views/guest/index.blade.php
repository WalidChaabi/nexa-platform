@extends('templates.index')
@section('content')
    <!--hero section start-->
    <section class="hero-section hero-section-3 ptb-100" id="services">
        <!--animated circle shape start-->
        <div class="circles">
            <div class="point animated-point-1"></div>
            <div class="point animated-point-2"></div>
            <div class="point animated-point-3"></div>
            <div class="point animated-point-4"></div>
            <div class="point animated-point-5"></div>
            <div class="point animated-point-6"></div>
        </div>
        <!--animated circle shape end-->
        <div class="container" id="animation-area-1">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="hero-content section-title text-center text-lg-left my-5 mt-lg-0">
                        <span class="text-uppercase color-secondary font-weight-bold">MARKETING AUTOMATIQUE</span>
                        <h1 class="font-weight-bold">Nous Réalisons les Rêves de Vos Clients</h1>
                        <p class="lead">Faites de votre programme de fidélité un outil marketing puissant et intelligent.
                            NEXA propose automatiquement vos offres à vos clients à des moments clés adapter à leurs
                            envies. </p>
                        <div class="action-btn mt-4">
                            <a href="#" class="btn solid-btn">Voir nos services</a>
                        </div>
                    </div>
                </div>
                <div class="col-9 col-md-7 col-lg-5 offset-xl-1 align-self-sm-end">
                    <div class="hero-img position-relative">
                        <div class="image-bg-positioning">
                            <img src="{{ asset('new/images/app-product.webp') }}" alt="" class="img-fluid">
                        </div>
                        <div class="animation-item d-none d-md-block d-lg-block">
                            <div class="p-4 w-75 rounded-custom d-flex white-bg hero-animated-card-1">
                                <p class="gr-text-11 mb-0 text-mirage-2">“Une meilleure façon de communiquer avec vos
                                    clients.”
                                </p>
                                <div class="small-card-img ml-3">
                                    <img src="{{ asset('new/images/team-2.webp')}}" alt="" width="80px"
                                        class="rounded-circle img-fluid">
                                </div>
                            </div>
                            <div class="p-4 w-75 rounded-custom d-flex secondary-bg hero-animated-card-2">
                                <div class="small-card-img mr-3 text-white">
                                    <img src="{{ asset('new/images/team-5.webp')}}" alt="" width="80px"
                                        class="rounded-circle img-fluid">
                                </div>
                                <p class="gr-text-11 mb-0 text-white">“Nous assurons le meilleur retour sur investissement
                                    pour les entreprises.”
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--hero section end-->

    {{-- <section class="pb-100" id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 mb-4 mb-md-4 mb-lg-0">
                    <div class="single-fact p-3 text-center">
                        <h3 class="title display-3 font-weight-bolder">50K</h3>
                        <p class="mb-0">People are already reading this book and improving!</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mb-4 mb-md-4 mb-lg-0">
                    <div class="single-fact p-3 text-center">
                        <h3 class="title display-3 font-weight-bolder">96% </h3>
                        <p class="mb-0">Satisfaction rate comes from our awesome customers.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 mb-4 mb-md-4 mb-lg-0">
                    <div class="single-fact p-3 text-center">
                        <h3 class="title display-3 font-weight-bolder">4.9</h3>
                        <p class="mb-0">Average customer ratings we have got all over internet.</p>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!--about us section start-->
    <section id="about" class="about-us ptb-100 gray-light-bg">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-4">
                    <div class="about-content-right">
                        <img src="{{ asset('new/images/image-15.webp') }}" alt="about us" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="about-content-left section-heading">
                        <span class="text-uppercase color-secondary sub-title">APPLICATION DE GESTION</span>
                        <h2>Fidélisez plus de clients, Augmentez votre chiffre d'affaires</h2>

                        <div class="single-feature mb-4 mt-5">
                            <div class="icon-box-wrap d-flex align-items-center mb-2">
                                <div class="mr-3 icon-box">
                                    <img src="{{ asset('new/images/image-icon-1.webp')}}" alt="icon image" class="img-fluid">
                                </div>
                                <p class="mb-0">NEXA vous permet de construire le programme de fidélité qui vous
                                    convient le mieux,
                                    en fonction de votre secteur, de votre positionnement et de vos objectifs.</p>
                            </div>
                        </div>
                        <div class="single-feature mb-4">
                            <div class="icon-box-wrap mb-2">
                                <div class="mr-3 icon-box">
                                    <img src="{{ asset('new/images/image-icon-2.webp')}}" alt="icon image" class="img-fluid">
                                </div>
                                <p class="mb-0">NEXA s'adapte à toutes les caisses, les ordinateurs et les télephones
                                    portables, ainsi que tous les réseaux sociaux.</p>
                            </div>
                            <p></p>
                        </div>
                        <div class="single-feature mb-4">
                            <div class="icon-box-wrap mb-2">
                                <div class="mr-3 icon-box">
                                    <img src="{{ asset('new/images/image-icon-3.webp')}}" alt="icon image" class="img-fluid">
                                </div>
                                <p class="mb-0">NEXA diffuse votre offre de parrainage à vos clients par SMS, whatsapp
                                    ou facebook. Les utilisateurs s’inscrivent,
                                    deviennent vos clients, et tout le monde est récompensé !</p>
                            </div>
                            <p></p>
                        </div>
                        <div class="single-feature mb-4">
                            <div class="icon-box-wrap mb-2">
                                <div class="mr-3 icon-box">
                                    <img src="{{ asset('new/images/image-icon-4.webp')}}" alt="icon image" class="img-fluid">
                                </div>
                                <p class="mb-0">Analysez le comportement de vos clients et détecter leurs
                                    habitudes d'achats dans le but de prédire leurs futures désires.</p>
                            </div>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--about us section end-->

    <!--features section start-->
    <div id="features" class="feature-section ptb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="section-heading text-center mb-5">
                        <span class="text-uppercase color-secondary sub-title">MEILLEURES CARACTÉRISTIQUES</span>
                        <h2>Processus Facile avec les Meilleures Fonctionnalités</h2>
                        <p>Offrir une communication diversifiée avec vos clients.
                            Effectuez une transition vers un service client automatisé axé sur les clients.
                        </p>

                    </div>
                </div>
            </div>

            <!--feature new style start-->
            <div class="row row-grid align-items-center">
                <div class="col-lg-4">
                    <div class="d-flex align-items-start mb-5">
                        <div class="pr-4">
                            <div class="icon icon-shape icon-color-1 rounded-circle">
                                <span class="ti-face-smile"></span>
                            </div>
                        </div>
                        <div class="icon-text">
                            <h5>Site Web adaptatif</h5>
                            <p class="mb-0">NEXA s'adapte à toutes les caisses tactiles, les ordinateurs et les
                                télephones portable.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-5">
                        <div class="pr-4">
                            <div class="icon icon-shape icon-color-2 rounded-circle">
                                <span class="ti-vector"></span>
                            </div>
                        </div>
                        <div class="icon-text">
                            <h5>Indicateurs techniques</h5>
                            <p class="mb-0">NEXA s'adapte à toutes les caisses tactiles, les ordinateurs et les
                                télephones portable.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="pr-4">
                            <div class="icon icon-shape icon-color-3 rounded-circle">
                                <span class="ti-headphone-alt"></span>
                            </div>
                        </div>
                        <div class="icon-text">
                            <h5>Assistance en ligne conviviale</h5>
                            <p class="mb-0">L'équipe NEXA propose un accompagnement illimité</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="position-relative test" style="z-index: 10;">
                        <img alt="Image placeholder" src="{{ asset('new/images/image-10.webp')}}" class="img-center img-fluid">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex align-items-start mb-5">
                        <div class="pr-4">
                            <div class="icon icon-shape icon-color-4 rounded-circle">
                                <span class="ti-layout-media-right"></span>
                            </div>
                        </div>
                        <div class="icon-text">
                            <h5>Email et SMS…</h5>
                            <p class="mb-0">NEXA propose aux commerçants et aux entreprises, la communication par SMS et Email.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-5">
                        <div class="pr-4">
                            <div class="icon icon-shape icon-color-5 rounded-circle">
                                <span class="ti-layout-cta-right"></span>
                            </div>
                        </div>
                        <div class="icon-text">
                            <h5>Construit avec Sass</h5>
                            <p class="mb-0">NEXA propose aux commerçants et aux entreprises, la communication par SMS
                                et Email.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="pr-4">
                            <div class="icon icon-shape icon-color-6 rounded-circle">
                                <span class="ti-palette"></span>
                            </div>
                        </div>
                        <div class="icon-text">
                            <h5>Créativité</h5>
                            <p class="mb-0">NEXA propose l’intelligence artificiel pour animer la base clients, et des
                                fonctionnalités pour recruter de nouveaux clients</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--feature new style end-->
        </div>
    </div>
    <!--features section end-->


    <!--our video promo section start-->
    <section class="video-promo ptb-100 background-img"
        style="background: url('img/hero-bg-1.webp')no-repeat center center / cover">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="video-promo-content mt-4 text-center">
                        <a href="https://www.youtube.com/watch?v=9No-FiEInLA"
                            class="popup-youtube video-play-icon d-inline-block"><span class="ti-control-play"></span></a>
                        <h5 class="mt-4 text-white">Watch video overview</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--our video promo section end-->

    <!--our pricing packages section start-->
    <section id="pricing" class="package-section ptb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="section-heading text-center mb-5">
                        <span class="text-uppercase color-secondary sub-title">NOS PRIX EN TTC</span>
                        <h2>Prix ​​et forfaits abordables Choisissez celui qui vous Convient le Mieux.</h2>
                        <p>
                            Si vous avez besoin d’une prestation particulière merci de nous contacter directement.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md">
                    <div class="card text-center single-pricing-pack p-5">
                        <h5 class="mb-2">MARKETING INTELLIGENT</h5>
                        <div class="pricing-img mt-3 mb-4">
                            <img src="{{ asset('new/images/basic.svg')}}" alt="pricing img" class="img-fluid">
                        </div>

                        <div class="card-body p-0">
                            <ul class="list-unstyled text-sm pricing-feature-list">
                                <li>200 SMS</li>
                                <li>Expéditeur personalisé</li>
                                <li>700 Emails</li>
                                <li>Un (1) modéle d'email/mois</li>
                                {{-- <li>24/7 Phone Support</li>
                                <li>Event Analytics</li> --}}
                            </ul>
                            <div class="py-4 border-0 pricing-header">
                                <div class="h1 text-center mb-0 color-secondary"><span
                                        class="price font-weight-bolder">299</span><span
                                        style="font-size: 12px">MAD/Mois</span>
                                </div>
                            </div>
                            <a data-section="contact" class="btn outline-btn page-scroll" target="_blank">Essayez gratuitement</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md">
                    <div class="card text-center popular-price single-pricing-pack p-5">
                        <h5 class="mb-2">NEXA QUARTIER</h5>
                        <div class="pricing-img mt-3 mb-4">
                            <img src="{{ asset('new/images/standard.svg')}}" alt="pricing img" class="img-fluid">
                        </div>

                        <div class="card-body p-0">
                            <ul class="list-unstyled text-sm pricing-feature-list">
                                <li>Programme de fidélité</li>
                                <li>Solution QG</li>
                                <li>Accés en ligne</li>
                                {{-- <li>Daily updated</li> --}}
                                <li>Maintenance</li>
                                {{-- <li>Event Analytics</li> --}}
                            </ul>
                            <div class="py-4 border-0 pricing-header">
                                <div class="h1 text-center mb-0 color-secondary"><span
                                        class="price font-weight-bolder">999</span><span
                                        style="font-size: 12px">MAD/Mois</span>
                                </div>
                            </div>
                            <a data-section="contact" class="btn solid-btn page-scroll" target="_blank">Essayez gratuitement</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md">
                    <div class="card text-center single-pricing-pack p-5">
                        <h5 class="mb-2">MARKETING INTÉGRÉ</h5>
                        <div class="pricing-img mt-3 mb-4">
                            <img src="{{asset('new/images/unlimited.svg')}}" alt="pricing img" class="img-fluid">
                        </div>

                        <div class="card-body p-0">
                            <ul class="list-unstyled text-sm pricing-feature-list">
                                <li>400 SMS</li>
                                <li>Expéditeur personalisé</li>
                                <li>1500 emails</li>
                                <li>2 modéles d'email/mois</li>
                                {{-- <li>24/7 Phone Support</li>
                                <li>Event Analytics</li> --}}
                            </ul>
                            <div class="py-4 border-0 pricing-header">
                                <div class="h1 text-center mb-0 color-secondary">
                                    <div class="h1 text-center mb-0 color-secondary"><span
                                            class="price font-weight-bolder">499</span><span
                                            style="font-size: 12px">MAD/Mois</span>
                                    </div>
                                </div>
                            </div>
                            <a data-section="contact" class="btn outline-btn page-scroll" target="_blank">Essayez gratuitement</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 text-center">
                <p class="mb-2">Si vous avez besoin de services personnalisés ou besoin de plus ? <a href="#contact" class="color-secondary">
                        Nous contacter
                    </a></p>
            </div>
        </div>
    </section>

    <!--contact us section start-->
    <section id="contact" class="contact-us gray-light-bg ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-12 pb-3 message-box d-none">
                    <div class="alert alert-danger"></div>
                </div>
                <div class="col-md-5">
                    <div class="section-heading">
                        <h2>Contactez-Nous</h2>
                        <p>Nous contacter est très facile.
                            Utilisez simplement le formulaire de contact ou rendez-nous visite pour un café au bureau.</p>
                    </div>
                    <div class="footer-address">
                        <h6><strong>Adresse du bureau</strong></h6>
                        <p> N 5, res Firdaous, Bd Moulay Rachid,Gueliz Marrakech 40000.</p>
                        <ul>
                            <li><span>Tél: 06 63 42 37 33</span></li>
                            <li><span>Email : <a href="mailto:contact@nexa.app">contact@nexa.app</a></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-7">
                    <form action="{{ route('home.send') }}" method="POST" novalidate="novalidate" id="form-contact">
                        {{ csrf_field() }}
                        <h5>Contactez-nous rapidement</h5>
                        <div class="row">
                            <div class="col-sm-12 col-12">
                                <div class="form-group name" data-required="إسم">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Prénom"required="required" >
                                    {{-- <span class="prenom" id="spanPrenom">إسم</span> --}}
                                </div>
                                <span class="name text-danger d-none">Le champ name est obligatoire</span>
                            </div>
                            <div class="col-sm-12 col-12">
                                <div class="form-group commerce" data-required="إسم المحل التجاري">
                                    <input type="text" class="form-control" name="commerce" id="email"
                                        placeholder="Nom du commerce" required="required">
                                    {{-- <span class="nom" id="spanNom">إسم المحل التجاري</span> --}}
                                </div>
                                <span class="commerce text-danger d-none">Le champ commerce est obligatoire</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-12">
                                <div class="form-group phone" data-required="الهاتف">
                                    <input type="text" name="phone" value="" class="form-control"
                                        id="phone" placeholder="Num Tél">
                                    {{-- <span class="tel" id="spanTel">الهاتف</span> --}}
                                </div>
                                <span class="phone text-danger d-none">Le champ phone est obligatoire</span>
                            </div>
                            <div class="col-sm-12 col-12">
                                <div class="form-group ville" data-required="المدينة">
                                    <input type="text" name="ville" value=""
                                        class="form-control" id="company" placeholder="Ville">
                                    {{-- <span class="ville" id="spanVille">المدينة</span> --}}
                                </div>
                                <span class="ville text-danger d-none">Le champ ville est obligatoire</span>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea name="message" id="message" class="form-control" rows="7" cols="25" placeholder="Message"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-3">
                                <button type="submit" class="btn solid-btn" id="btnContactUs">
                                    Send Message
                                </button>
                            </div>
                        </div> --}}
                        <div>
                            <button type="submit" class="btn solid-btn" id="btnContactUs">
                                envoyer message
                            </button>
                        </div>
                    </form>
                    <p class="form-message"></p>
                </div>
            </div>
        </div>
    </section>
    <!--contact us section end-->

    <!--client section start-->
    <section class="client-section ptb-100">
        <div class="container">
            <!--clients logo start-->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="section-heading text-center mb-5">
                        <span class="text-uppercase color-secondary sub-title">NOS ADORABLES CLIENTS</span>
                        <h2>Ils Nous Font Confiance</h2>
                        <p>
                            Pour une bonne communication, il faut avoir de bons outils.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme clients-carousel dot-indicator">
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-5-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-1-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-6-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-3-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-4-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-5-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-7-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-2-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-1-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                        <div class="item single-client">
                            <img src="{{asset('new/images/client-3-color.webp')}}" alt="client logo" class="client-img">
                        </div>
                    </div>
                </div>
            </div>
            <!--clients logo end-->
        </div>
    </section>
    <!--client section start-->
    <script>
        $('.form-group input').on('input', function(e) {
            const input = e.target;
            const name = input.getAttribute('name');

            const empty = input.value.length <= 0;

            displayCustomPlaceholder(name, empty);
        });

        function displayCustomPlaceholder(name, empty) {
            console.log(name);
            const formGroup =  $(`.form-group.${name}`);

            if (empty) formGroup.addClass('empty');
            else formGroup.removeClass('empty');
        }

        $('.form-group').addClass('empty');

        // const input1 = document.getElementById("name");
        // const input2 = document.getElementById("email");
        // const input3 = document.getElementById("phone");
        // const input4 = document.getElementById("company");

        // const span1 = document.getElementById("spanPrenom");
        // const span2 = document.getElementById("spanNom");
        // const span3 = document.getElementById("spanTel");
        // const span4 = document.getElementById("spanVille");

        // function DisableSpan1() {
        //     if (input1.value.length >= 1) {
        //         span1.style.color = "white";
        //     } else if (input1.value.length === 0) {
        //         span1.style.color = "#b1b1b1";
        //     }
        // }

        // function DisableSpan2() {
        //     if (input2.value.length >= 1) {
        //         span2.style.color = "white";
        //     } else if (input2.value.length === 0) {
        //         span2.style.color = "#b1b1b1";
        //     }
        // }

        // function DisableSpan3() {
        //     if (input3.value.length >= 1) {
        //         span3.style.color = "white";
        //     } else if (input3.value.length === 0) {
        //         span3.style.color = "#b1b1b1";
        //     }
        // }

        // function DisableSpan4() {
        //     if (input4.value.length >= 1) {
        //         span4.style.color = "white";
        //     } else if (input4.value.length === 0) {
        //         span4.style.color = "#b1b1b1";
        //     }
        // }

        // $('#form-contact input').on('input', function(e) {
        //     const el = e.target.getAttribute('name');
        //     displayError(el, false);
        // })
    </script>
@endsection
