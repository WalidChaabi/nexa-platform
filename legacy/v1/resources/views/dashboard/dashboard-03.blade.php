@extends('layouts.employe.master')
@section('title', 'Ecommerce')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/chartist.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/prism.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>{{auth()->user()->store->name}}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{url('/dashboard/employe')}}">{{trans('dashboard.dashboard')}}</a></li>
{{-- <li class="breadcrumb-item active">Ecommerce</li> --}}
@endsection

@section('content')
{{--<div class="col-xl-12">
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
                                 <p>Aid Al Adha Mubarak</p>
                                 <h4 class="f-w-600">SALAM {{auth()->user()->firstname}}</h4>
                                 <p>A l'occasion de la fête sacrée</p>
                                 <p>de « Aïd El Adha »,</p>
                                 <p>L’équipe Sherin vous souhaite une joyeuse fête,</p>
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-4 col-md-12">
                           <div class="center-img"><img class="img-fluid" src="{{asset('/assets/images/dashboard-2/aid.png')}}" alt="..."></div>
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
</div>--}}
<div class="container-fluid">
   <div class="row size-column">
      <div class="col-xl-7 box-col-12 xl-100">
         <div class="row dash-chart">
            <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
               <div class="card o-hidden">
                  <div class="card-body">
                     <div class="ecommerce-widgets media">
                        <div class="media-body">
                           <p class="f-w-500 font-roboto">{{trans('dashboard.C.A Réalisé/Jour')}}</p>
                           <h4 class="f-w-500 mb-0 f-26"><span class="">{{$obj_realised['today']}}</span>Mad</h4>
                        </div>
                        <div class="media-body">
                           <p class="f-w-500 font-roboto">{{trans('dashboard.OBJECTIVE Journalier')}}<span class="badge pill-badge-primary ml-3"></span></p>
                           <h4 class="f-w-500 mb-0 f-26"><span class="">{{$objective->daily}}</span>Mad</h4>
                        </div>
                     </div>
                  </div>
                  <span class="badge pill-badge-primary ml-15"> {{date('Y-m-d')}}</span>
               </div>
            </div>
            <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
               <div class="card o-hidden">
                  <div class="card-body">
                     <div class="ecommerce-widgets media">
                        <div class="media-body">
                           <p class="f-w-500 font-roboto">{{trans('dashboard.C.A Réalisé/Semaine')}}</p>
                           <h4 class="f-w-500 mb-0 f-26"><span class="">{{$obj_realised['this_week']}}</span>Mad</h4>
                        </div>
                        <div class="media-body">
                           <p class="f-w-500 font-roboto">{{trans('dashboard.OBJECTIVE Hebdomadaire')}}<span class="badge pill-badge-primary ml-3"></span></p>
                           <h4 class="f-w-500 mb-0 f-26"><span class="">{{$objective->weekly}}</span>Mad</h4>
                        </div>
                     </div>
                  </div>
                  <span class="badge pill-badge-primary ml-15"> {{date('Y-m-d')}}</span>
               </div>
            </div>
            {{--
             <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                <div class="card o-hidden">
                   <div class="card-body">
                      <div class="ecommerce-widgets media">
                         <div class="media-body">
                            <p class="f-w-500 font-roboto">{{trans('dashboard.C.A Réalisé/Mois')}}</p>
            <h4 class="f-w-500 mb-0 f-26"><span class="">{{$obj_realised['this_month']}}</span>Mad</h4>
         </div>
         <div class="media-body">
            <p class="f-w-500 font-roboto">{{trans('dashboard.OBJECTIVE Mensuel')}}<span class="badge pill-badge-primary ml-3"></span></p>
            <h4 class="f-w-500 mb-0 f-26"><span class="">{{$objective->monthly}}</span>Mad</h4>
         </div>
      </div>
   </div>
   <span class="badge pill-badge-primary ml-15"> {{date('Y-m-d')}}</span>
</div>
</div>
--}}
{{--
             <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
               <div class="card o-hidden">
                  <div class="card-body">
                     <div class="ecommerce-widgets media">
                        <div class="media-body">
                           <p class="f-w-500 font-roboto">{{trans('dashboard.Cumul du C.A')}}</p>
<h4 class="f-w-500 mb-0 f-26"><span class="">{{$ca}}</span>Mad</h4>
</div>
<div class="                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             ====                                                                                                                                                                                    xw                             -body">
   <p class="f-w-500 font-roboto">{{trans('Estimé')}}<span class="badge pill-badge-primary ml-3"></span></p>
   <h4 class="f-w-500 mb-0 f-26"><span class="counter"></span></h4>
</div>
</div>
</div>
<span class="badge pill-badge-primary ml-15"> {{date('Y-m-d')}}</span>
</div>
</div>
--}}

</div>
</div>

<div class="col-md-7  box-col-12">
   <div class="card">
      <div class="card-header card-no-border">
         <h5>{{trans('dashboard.Mes Derniers Factures')}}</h5>
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
         <div class="card-body">
            <div class="best-seller-table responsive-tbl">
               <div class="item">
                  <div class="table-responsive product-list">
                     <table class="table table-bordernone">
                        <thead>
                           <tr>
                              <th class="f-22">
                                 {{trans('dashboard.Facture')}}
                              </th>
                              <th>{{trans('dashboard.Client')}}</th>
                              <th>{{trans('dashboard.Date')}}</th>
                              <th>{{trans('dashboard.Total')}}</th>
                              <th class="text-right">{{trans('communs.Status')}}</th>
                           </tr>
                        </thead>
                        <tbody>
       
                           @foreach ($invoices as $item)
                           <tr>
                              <td>
                                 <div class="d-inline-block align-middle">

                                    <img class="img-40 m-r-15 rounded-circle align-top" src="{{customerAvatar($item->customer->sexe)}}" alt="">

                                    <div class="status-circle bg-primary"></div>
                                    <div class="d-inline-block">
                                       <span>##{{$item->id}}</span>
                                       <p class="font-roboto">{{$item->customer->phone}}</p>
                                    </div>
                                 </div>
                              </td>
                              <td>{{$item->customer->getFullNameAttribute()}}</td>
                              <td>{{$item->invoice_date}}</td>
                              <td> <span class="label">{{$item->total}} MAD</span></td>
                              <td class="text-right"><i class="fa fa-check-circle"></i>{{getInvoiceStatus($item->status)}}</td>
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

<div class="col-md-5  box-col-12">
   <div class="card">
      <div class="card-header card-no-border">
         <h5>{{trans('dashboard.Dérniers Clients')}}</h5>
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
      <div class="card-body new-update pt-0">
         <div class="activity-timeline">
            @foreach ($invoices as $item)
            <div class="media">
               <div class="activity-line" style="position: relative"></div>
               <div class="activity-dot-secondary"></div>
               <div class="media-body">
                  <span>{{$item->customer->firstname}} {{$item->customer->lastname}}</span>
                  <p class="font-roboto">{{$item->customer->email}} - {{$item->customer->phone}} </p>
               </div>
            </div>
            @endforeach


         </div>
      </div>
   </div>
</div>
</div>
</div>

<script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGCQvcXUsXwCdYArPXo72dLZ31WS3WQRw&amp;callback=initMap"></script>
@endsection

@section('script')
<script src="{{asset('assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('assets/js/prism/prism.min.js')}}"></script>
<script src="{{asset('assets/js/clipboard/clipboard.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('assets/js/custom-card/custom-card.js')}}"></script>
<script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
<script src="{{asset('assets/js/dashboard/dashboard_2.js')}}"></script>
@endsection