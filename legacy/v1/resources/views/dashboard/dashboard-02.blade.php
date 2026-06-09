@extends('layouts.simple.master')
@section('title', __('Propriétaire Panel'))
;@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/ramadan.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/owlcarousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
@endsection
@section('style')
@endsection
@section('breadcrumb-title')
    <h3>@lang('dashboard.dashboard')</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">@lang('dashboard.dashboard')</a></li>
@endsection
@section('content')
    {{-- <div class="col-xl-12">
      <div class="card offer-box">
         <div class="card-body p-0">
            <div class="offer-slider">
               <div class="carousel slide pointer-event" id="carouselExampleCaptions" data-bs-ride="carousel">
                  <div class="carousel-inner">
                     <div class="carousel-item active">
                        <div class="selling-slide row">
                           <div class="col-xl-4 col-md-6">
                              <div class="d-flex" style="margin:10% ;">
                                 <div class="left-content" style="margin-top: -15%;">
                                    <p>Ramadan Kareem</p>
                                    <h4 class="f-w-600">Assalamu Alaikum {{auth()->user()->firstname}}</h4>
                                    <p>En ce mois béni de Ramadan,</p>
                                    <p>L'équipe de Nexa vous souhaite un mois rempli de bénédictions, de paix et de spiritualité.</p>
                                    <p>Que ce mois sacré vous apporte la santé, le bonheur et la prospérité.</p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-12">
                              <div class="d-flex"style="width:100%;">
                                 <img style="width:50%;" class="img-fluid" src="{{asset('/assets/images/dashboard-2/ramadan.png')}}" alt="...">
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-6">
                              <div class="d-flex">
                                 <div class="right-content">
                                    <p>marquée par la paix, le bonheur, la santé</p>
                                    <p>et la prospéré; tout en priant le Bon Dieu</p>
                                    <p>de vous en accorder tous les bienfaits</p>
                                    <p>à vous et à tous vos proches.</p>
                                    <h4 class="f-w-600">{{config('app.name')}}</h4>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div> --}}
    {{-- <div class="container-fluid">
      <div class="row size-colum">
         <div class="col-12">
            <div class="dash-chart row">
               <div class="col-xl-12 box-col-12 col-lg-12 col-md-12">
                  <div class="card gradient-primary o-hidden">
                     <div class="card-body">
                        <div class="alert dark" role="alert" id="excuse" style="background-color: #fef0f4;">
                           <p>
                              Cher client, Chère cliente,
                           </p>
                           <p>
                              Nous nous excusons sincèrement pour le désagrément survenu le 29 juin 2022. Nous espérons que cet incident isolé ne viendra pas entacher la confiance dont vous nous témoignez depuis deux années.
                              En vous remerciant pour votre compréhension, nous vous accordons une remise de 10% sur toutes vos factures du mois JUIN 2022.
                              Cordialement;
                           </p>
                           <p>
                              LAHBIRI Othmane.
                           </p>
                           <p>
                              PS: Pour vérifier la remise accordée, aller sur <span style="color:#7366ff">balance</span> puis cliquer sur <span style="color:#7366ff">payer</span> pour afficher le détail de votre facture.
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div> --}}
    {{-- <div class="ramadan">
        <div class="left-content" style="padding: 35px;color:white;">
            <h4 class="text-ramadan f-w-700" style="color:#dede6b;">Ramadan Kareem</h4>
            <h4 class="f-w-400">Assalamu Alaikum {{ auth()->user()->firstname }}</h4>
            <p>Au nom de l'équipe Nexa,</p>
            <p>Nous vous souhaitons un Ramadan moubarak !</p>
            <p>Que ce mois béni vous apporte la paix, la sérénité et la bénédiction divine.</p>
        </div>
        <div class="page">
            <div id="moon" class="fade-in"></div>
            <div id="main-star" class="fade-in">
                <div class="star-five rotating"></div>
            </div>
            <div class="constellations">
                <div class="constellation rotating-slow">
                    <div class="baby-star">
                        <div class="star-five rotating"></div>
                    </div>
                    <div class="baby-star">
                        <div class="star-five rotating"></div>
                    </div>
                    <div class="baby-star">
                        <div class="star-five rotating"></div>
                    </div>
                    <div class="baby-star">
                        <div class="star-five rotating"></div>
                    </div>
                </div>
                <div id="angled">
                    <div class="constellation rotating-slow">
                        <div class="baby-star">
                            <div class="star-five rotating"></div>
                        </div>
                        <div class="baby-star">
                            <div class="star-five rotating"></div>
                        </div>
                        <div class="baby-star">
                            <div class="star-five rotating"></div>
                        </div>
                        <div class="baby-star">
                            <div class="star-five rotating"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text slide-up">رمضان مبارك كريم</div>
        </div>
        <div id="stars"></div>
        <div id="stars2"></div>
        <div id="stars3"></div>
    </div> --}}
    {{-- <div class="col-12" id="example-div">
        @include('test.Aid')
    </div> --}}
    <div class="table-responsive">
        <div class="container-fluid">
            <div class="row size-colum">
                <div class="col-12">
                    <div class="dash-chart row">
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card gradient-primary o-hidden">
                                <div class="card-header">
                                    <h5>@lang('dashboard.quick-send')</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('owner.singleCamp') }}" method="POST">
                                        @if ($errors->any())
                                            @foreach ($errors->all(':message') as $error)
                                                <span style="color:red">{{ $error }} </span><br>
                                            @endforeach
                                        @endif
                                        @csrf
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label for="validationServer02">{{ trans('dashboard.Emetteur') }}</label>

                                                <input class="form-control"
                                                    value="{{ auth()->user()->stores->first()->sender_id }}" name="senderId"
                                                    disabled id="validationServer02" type="text"
                                                    placeholder="{{ !is_null(auth()->user()->stores->first()->sender_id)? auth()->user()->stores->first()->sender_id: __('XBEL STORE') }}"
                                                    required="" data-original-title=""
                                                    title="{{ trans('dashboard.Nom d\'emetteur') }}">
                                                @if ($errors->has('senderId'))
                                                    <div class="invalid-feedback">{{ $errors->first('senderId') }}</div>
                                                @else
                                                    <div class="valid-feedback">Looks good!</div>
                                                @endif
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label for="validationServer02">{{ trans('dashboard.Téléphone') }}</label>
                                                <input class="form-control" id="validationServer02" type="text"
                                                    name="phone" placeholder="212 06-XX-XX-XX-XX" required=""
                                                    data-original-title="" value="212"
                                                    title="{{ trans('dashboard.Téléphone Destinataire') }}">
                                                @if ($errors->has('phone'))
                                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                                @else
                                                    <div class="valid-feedback">Looks good!</div>
                                                @endif
                                            </div>
                                            <div class="mb-3 col-md-12">
                                                <label for="validationServer01">{{ trans('dashboard.Message') }}</label>
                                                <textarea class="form-control" id="validationServer01" type="text" name="message" data-original-title=""
                                                    title="{{ trans('dashboard.Message') }}"></textarea>
                                                @if ($errors->has('message'))
                                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                                @else
                                                    <div class="valid-feedback">Looks good!</div>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit" data-original-title=""
                                            title="">{{ trans('dashboard.Envoyer') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card gradient-primary o-hidden">
                                <div class="card-header">
                                    <h5>{{ trans('dashboard.Balance') }}</h5>
                                </div>
                                <div class="card-body ">
                                    <div class="our-product">
                                        <div class="table-responsive">
                                            <table class="table table-bordernone">
                                                <tbody class="f-w-500">
                                                    <tr>
                                                        <td>
                                                            <div class="media">
                                                                <div class="media-body">
                                                                    <span>{{ trans('dashboard.Elements') }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p>{{ trans('dashboard.Nombre') }}</p>
                                                        </td>
                                                        {{-- <td>
                                          <p>{{trans('dashboard.Prix Unitaire')}} HT</p>
                                          </td> --}}
                                                        <td>
                                                            <p>{{ trans('communs.Total HT') }}</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="media">
                                                                <div class="media-body">
                                                                    <p class="font-roboto">{{ trans('dashboard.SMS') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $count = 0;
                                                                foreach ($countSms as $_) {
                                                                    $count = $count + $_['1'];
                                                                }
                                                            @endphp

                                                            <span>{{ $count }}</span>
                                                        </td>
                                                        {{-- <td>
                                          <span>
                                       
                                             {{Config::get('constant.sms_price')}}
                                          @endif
                                          </span>
                                          </td> --}}
                                                        <td>
                                                            @php
                                                                $sms = Config::get('constant.sms_price');
                                                                $cross = Config::get('constant.sms_cross_selling');
                                                                $first_total = 0;
                                                                foreach ($countSms as $_) {
                                                                    $first_total = $_['0'] === 'cross-selling' ? $first_total + $_['1'] * $cross : $first_total + $_['1'] * $sms;
                                                                }
                                                            @endphp
                                                            <span>{{ $first_total }} Mad</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="media">
                                                                <div class="media-body">
                                                                    <p class="font-roboto">{{ trans('dashboard.mails') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span>{{ $emails }}</span>
                                                        </td>
                                                        {{-- <td>
                                          <span>{{Config::get('constant.email_price')}}</span>
                                          </td> --}}
                                                        <td>
                                                            <span>{{ $seconde_total = number_format((float) ($emails * Config::get('constant.email_price')), 2, '.', '') }}
                                                                Mad</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <p>{{ trans('communs.Total HT') }}</p>
                                                        </td>
                                                        <td>
                                                        </td>
                                                        <td>
                                                            {{ $seconde_total + $first_total }} Mad
                                                        </td>
                                                        {{-- <td>
                                       
                                       </td> --}}
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if ($seconde_total + $first_total > 0)
                                    <div class="card-footer">
                                        <form action="{{ route('owner.checkout-process-detail') }}" method="GET">
                                            @csrf
                                            <button class="btn btn-primary"
                                                type="submit">{{ trans('dashboard.Payer') }}</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row size-colum">
                <div class="col-12">
                    <div class="row dash-chart">
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden">
                                <div class="card-header">
                                    <div class="media">
                                        <div class="media-body">
                                            <p><span class="f-w-500 font-roboto"
                                                    title="T.M.C : Total des produits vendus aujourd'hui tous magasins confondus">{{ trans('dashboard.Total de produit vendue ajourd\'hui  T.M.C') }}</span>
                                            </p>
                                            <h4 class="f-w-500 mb-0 f-26"><span>{{ $total_vente }}</span>
                                                {{ trans('communs.Produits') }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="profit-card">
                                                <!--<div id="spaline-chart"></div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden">
                                <div class="card-header card-no-border">
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="fa fa-spin fa-cog"></i></li>
                                            <li><i class="view-html fa fa-code"></i></li>
                                            <li><i class="icofont icofont-maximize full-card"></i></li>
                                            <li><i class="icofont icofont-minus minimize-card"></i></li>
                                            <li><i class="icofont icofont-refresh reload-card"></i></li>
                                            <li><i class="icofont icofont-error close-card"></i></li>
                                        </ul>
                                    </div>
                                    <div class="media">
                                        <div class="media-body">
                                            <p><span class="f-w-500 font-roboto"
                                                    title="T.M.C : Total des produits vendus aujourd'hui tous magasins confondus">{{ trans('dashboard.Chiffre d\'affire Realiser/mois') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="monthly-visit">
                                        <div id="column-chart"></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @if ($closeAccountReminder === true)
                            <div class="col-xl-12 box-col-12 col-lg-12 col-md-12">
                                <div class="col-xl-12 box-col-12 col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="alert alert-secondary dark" role="alert">
                                                <p> Bonjour, </p>
                                                <p>Pour éviter toute suspension de votre plate-forme nexa.app.
                                                    Veuillez payer votre dette d'un montant de
                                                    {{ $seconde_total + $first_total }} Dhs avant le {{ $dateToPay }} .
                                                </p>
                                                <p>
                                                <ul>
                                                    <li>06 63 42 37 33 </li>
                                                    <li>06 63 34 62 16 </li>
                                                    <li>L’équipe Nexa.</li>
                                                </ul>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden">
                                <div class="card-body">
                                    <div class="ecommerce-widgets media">
                                        <div class="media-body">
                                            <p class="f-w-500 font-roboto">{{ trans('dashboard.C.A D\'AUJOURDHUI') }}<span
                                                    class="badge pill-badge-primary ml-3">{{ trans('dashboard.Nouveau') }}</span>
                                            </p>
                                            <h4 class="f-w-500 mb-0 f-26"><span>{{ $ca_today }}</span>Mad</h4>
                                        </div>
                                        <div class="ecommerce-box light-bg-primary"><i class="fa fa-heart"
                                                aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden">
                                <div class="card-body">
                                    <div class="ecommerce-widgets media">
                                        <div class="media-body">
                                            <p class="f-w-500 font-roboto">{{ trans('dashboard.C.A D\'HIER') }}<span
                                                    class="ml-3 badge pill-badge-primary">{{ trans('dashboard.Nouveau') }}</span>
                                            </p>
                                            <h4 class="mb-0 f-w-500 f-26"><span>{{ $ca_last_day }}</span>Mad</h4>
                                        </div>
                                        <div class="ecommerce-box light-bg-primary"><i class="fa fa-heart"
                                                aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="f-w-500 font-roboto">
                                                {{ trans('dashboard.C.A DE CETTE SEMAINE') }}<span
                                                    class="badge pill-badge-primary ml-3">Hot</span></p>
                                            <div class="progress-box">
                                                <h4 class="f-w-500 mb-0 f-26"><span>{{ $ca_week }}</span>Mad</h4>
                                                <div
                                                    class="progress sm-progress-bar progress-animate app-right d-flex justify-content-end">
                                                    {{-- <div class="progress-gradient-primary" role="progressbar" style="width: 35%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><span class="font-primary">88%</span><span class="animate-circle"></span></div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden">
                                <div class="card-body">
                                    <div class="ecommerce-widgets media">
                                        <div class="media-body">
                                            <p class="f-w-500 font-roboto">{{ trans('dashboard.C.A DE CE MOIS') }}<span
                                                    class="badge pill-badge-primary ml-3">{{ trans('dashboard.Nouveau') }}</span>
                                            </p>
                                            <h4 class="f-w-500 mb-0 f-26"><span>{{ $ca_month }}</span>Mad</h4>
                                        </div>
                                        <div class="ecommerce-box light-bg-primary"><i class="fa-3x fa-money"
                                                aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="f-w-500 font-roboto">{{ trans('dashboard.C.A TOTAL') }}<span
                                                    class="badge pill-badge-primary ml-3">Hot</span></p>
                                            <div class="progress-box">
                                                <h4 class="f-w-500 mb-0 f-26"><span>{{ $ca }}</span>Mad</h4>
                                                <div
                                                    class="progress sm-progress-bar progress-animate app-right d-flex justify-content-end">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card o-hidden dash-chart">
                                <div class="card-header card-no-border">
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="fa fa-spin fa-cog"></i></li>
                                            <li><i class="view-html fa fa-code"></i></li>
                                            <li><i class="icofont icofont-maximize full-card"></i></li>
                                            <li><i class="icofont icofont-minus minimize-card"></i></li>
                                            <li><i class="icofont icofont-refresh reload-card"></i></li>
                                            <li><i class="icofont icofont-error close-card"></i></li>
                                        </ul>
                                    </div>
                                    <div class="media">
                                        <div class="media-body">
                                            <p><span
                                                    class="f-w-500 font-roboto">{{ trans('dashboard.Panier Moyen') }}</span><span
                                                    class="font-primary f-w-700 ml-2"></span></p>
                                            <h4 class="f-w-500 mb-0 f-26"><span
                                                    class="">{{ ceil($orderAverage) }}</span>Mad</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row size-colum">
                <div class="col-12">
                    <div class="row dash-chart">
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card">
                                <div class="card-header card-no-border">
                                    <h5>{{ trans('dashboard.Magasins') }}</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="fa fa-spin fa-cog"></i></li>
                                            <li><i class="view-html fa fa-code"></i></li>
                                            <li><i class="icofont icofont-maximize full-card"></i></li>
                                            <li><i class="icofont icofont-minus minimize-card"></i></li>
                                            <li><i class="icofont icofont-refresh reload-card"></i></li>
                                            <li><i class="icofont icofont-error close-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="our-product">
                                        <div class="table-responsive">
                                            <table class="table table-bordernone">
                                                <tbody class="f-w-500">
                                                    @foreach ($stores->take(5) as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="media">
                                                                    <img class="img-fluid m-r-15 rounded-circle"
                                                                        src="" alt="">
                                                                    <div class="media-body">
                                                                        <span>{{ trans('dashboard.Nom') }}</span>
                                                                        <p class="font-roboto">{{ $item->name }}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p>{{ trans('dashboard.C.A D\'AUJOURDHUI') }}</p>
                                                                <span>{{ calculateTurnOverYesterdayByStore($item->invoices, 0) }}Mad</span>
                                                            </td>
                                                            <td>
                                                                <p>{{ trans('dashboard.C.A D\'HIER') }}</p>
                                                                <span>{{ calculateTurnOverYesterdayByStore($item->invoices, 1) }}Mad</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="fa fa-spin fa-cog"></i></li>
                                            <li><i class="view-html fa fa-code"></i></li>
                                            <li><i class="icofont icofont-maximize full-card"></i></li>
                                            <li><i class="icofont icofont-minus minimize-card"></i></li>
                                            <li><i class="icofont icofont-refresh reload-card"></i></li>
                                            <li><i class="icofont icofont-error close-card"></i></li>
                                        </ul>
                                    </div>
                                    <strong>{{ trans('dashboard.classement Vendeurs') }} MAD</strong>
                                </div>
                                <div class="card-body">
                                    <div class="best-seller-table responsive-tbl">
                                        <div class="item">
                                            <div class="table-responsive product-list">
                                                <table class="table table-bordernone">
                                                    <thead>
                                                        <tr>
                                                            <th class="f-22">
                                                                {{ trans('dashboard.classement Vendeurs') }}
                                                            </th>
                                                            <th>{{ trans('Magasin') }}</th>
                                                            <th>{{ trans('C.A') }}
                                                                {{ Carbon\Carbon::now()->format('Y-m') }}</th>
                                                            <th>{{ trans('C.A') }}
                                                                {{ Carbon\Carbon::now()->subDays(30)->format('Y-m') }}</th>
                                                            <th>{{ trans('C.A') }}
                                                                {{ Carbon\Carbon::now()->subDays(60)->format('Y-m') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($mergedSellers as $seller)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-inline-block align-middle">
                                                                        <div class="d-inline-block">
                                                                            <span>{{ $seller->full_name }}</span>
                                                                            <p class="font-roboto"></p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $seller->store }}</td>
                                                                <td><span
                                                                        class="label">{{ isset($seller->invoices[Carbon\Carbon::now()->format('Y-m')]) 
                                                                            ? $seller->invoices[Carbon\Carbon::now()->format('Y-m')] 
                                                                            : 0 }}
                                                                    </span></td>
                                                                <td><span
                                                                        class="label">{{ isset($seller->invoices[Carbon\Carbon::now()->subDays(30)->format('Y-m')])
                                                                        ? $seller->invoices[Carbon\Carbon::now()->subDays(30)->format('Y-m')]
                                                                        : 0 }}
                                                                    </span></td>
                                                                <td><span
                                                                        class="label">{{ isset($seller->invoices[Carbon\Carbon::now()->subDays(60)->format('Y-m')])
                                                                        ? $seller->invoices[Carbon\Carbon::now()->subDays(60)->format('Y-m')]
                                                                            : 0 }}
                                                                    </span></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/owlcarousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard_2.js') }}"></script>
    
    <!-- <script>
        $(document).ready(function() {
            console.log('hello', localStorage.getItem('mode'))
            if (localStorage.getItem('mode') === 'dark') {
                $('excuse').css("background-color", "");
            } else {
                $('excuse').css("background-color", "#fef0f4");
            }

        });
    </script> -->
@endsection
