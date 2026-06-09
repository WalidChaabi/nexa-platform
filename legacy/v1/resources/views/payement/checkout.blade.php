@extends('layouts.simple.master')
@section('title', __('Propriétaire Panel'))

@section('css')
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/icofont.css">
<!-- Themify icon-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/themify.css">
<!-- Flag icon-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/flag-icon.css">
<!-- Feather icon-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/feather-icon.css">
<!-- Plugins css start-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/scrollbar.css">
<!-- Bootstrap css-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/vendors/bootstrap.css">
<!-- App css-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/style.css">
<link id="color" rel="stylesheet" href="https://laravel.pixelstrap.com/cuba/assets/css/color-1.css" media="screen">
<!-- Responsive css-->
<link rel="stylesheet" type="text/css" href="https://laravel.pixelstrap.com/cuba/assets/css/responsive.css">
@endsection

@section('style')
<style>
.invoice,.info_owner{
  display:flex;
  justify-content: space-between;
}
.button_payer{
  display:flex;
  justify-content: flex-end;
}
</style>
@endsection

@section('breadcrumb-title')
<h3>@lang('dashboard.dashboard')</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{url('/dashboard/owner')}}">@lang('dashboard.dashboard')</a></li>
{{-- <li class="breadcrumb-item active">Ecommerce</li> --}}
@endsection

@section('content')
@if(auth()->user()->is_admin==2)
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        @if(!empty($commandeErrors))
        <div class="card-footer">
          <div class="alert alert-danger inverse alert-dismissible fade show" role="alert">
            <i class="icon-thumb-down alert-center"></i>
            <p>
              <b> Votre paiement pour la commande {{$commandeErrors['oid']}}, de {{$commandeErrors['amount']}} MAD a echoué </b>. <br>
            </p>
          </div>
        </div>
        @elseif(!empty($commandeSuccess))
        <div class="card-footer">
          <div class="alert alert-success inverse alert-dismissible fade show" role="alert">
            <i class="icon-thumb-up alert-center"></i>
            <p>
              <b> Votre paiement pour la commande {{$commandeSuccess['oid']}}, de {{$commandeSuccess['amount']}} MAD a été effectué avec succes </b>. <br>
            </p>
          </div>
        </div>
        @endif
      </div>
      <div class="card-body">
        <div class="invoice">
          <div>
            <h4 class="media-heading">{{config('app.name')}}</h4>
            <p>contact@nexa.app<br><span>+212 (0) 6 63 42 37 33</span></p>
          </div>
          @if(empty($commandeSuccess))
          <div>
            <form action="{{route('owner.checkout-process')}}" method="POST">
              @csrf
              <input type="hidden" name="cmd" value="{{$cmd}}">
              <button class="btn btn-secondary btn-lg" type="submit" data-bs-original-title="" title="">Payez maintenant</button>
            </form>
          </div>
          @else
          <div class="col-sm-4" style="text-align: center;justify-content: space-between;">
            <form action="{{route('owner.historique-paiements')}}" method="GET">
              <button class="btn btn-primary btn-lg me-2" type="submit" data-bs-original-title="" title="">Voir l'historique des paiements</button>
            </form>
            <!-- End Info-->
          </div>
          @endif
          <!-- End Title-->
        </div>
            <hr>
        <!-- End InvoiceTop-->
        <div class="info_owner">
          <div>
            <h4 class="media-heading">{{auth()->user()->getFullNameAttribute()}}</h4>
            <p>{{auth()->user()->email}}<br><span>{{auth()->user()->phone}}</span></p>
          </div>
          <div>
            <input type="hidden" name="cmd" value="{{$cmd}}">
            <button class="btn btn-primary btn-lg " data-toggle="modal" data-target="#personal-info" data-bs-original-title="" title="">Modifier Mes Infos</button>
          </div>
        </div>
        <!-- End Invoice Mid-->
        <div>
          <div class="table-responsive invoice-table" id="table">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                  <td class="item">
                    <h6 class="p-2 mb-0">{{ trans('communs.Services') }}</h6>
                  </td>
                  <td class="Hours">
                    <h6 class="p-2 mb-0">{{ trans('communs.Quantité') }}</h6>
                  </td>
                  <td class="Rate">
                    <h6 class="p-2 mb-0">{{ trans('communs.Prix') }}</h6>
                  </td>
                  <td class="subtotal">
                    <h6 class="p-2 mb-0">Sous-Total</h6>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>SMS : Reciblage Normal </label>
                    <p class="m-0">(welcome sms, Birthday sms ...)</p>
                  </td>
                  <td>
                    <p class="itemtext">{{$countSmsBirthdayAndWelcome}}</p>
                  </td>
                  <td>
                    <p class="itemtext">{{$priceSmsBirthday =  config('constant.sms_price') }}</p>
                  </td>
                  <td>
                    <p class="itemtext">{{$firstTotal= $priceSmsBirthday *  $countSmsBirthdayAndWelcome}}</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>SMS : Reciblage Avec Inteligence Artificiel </label>
                    <p class="m-0">(Cross Selling)</p>
                  </td>
                  <td>
                    <p class="itemtext">{{$countSmsCrossSelling}}</p>
                  </td>
                  <td>
                    <p class="itemtext">{{ config('constant.sms_cross_selling') }} </p>
                  </td>
                  <td>
                    <p class="itemtext">{{ $secondeTotal = $countSmsCrossSelling * config('constant.sms_cross_selling')}} </p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Email Service </label>
                    <p class="m-0"></p>
                  </td>
                  <td>
                    <p class="itemtext">{{$emails}}</p>
                  </td>
                  <td>
                    <p class="itemtext">{{config('constant.email_price')}}</p>
                  </td>
                  <td>
                    <p class="itemtext">{{ $tirthTotal =  config('constant.email_price') *  $emails}}</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>TOTAL HT </label>
                    <p class="m-0"></p>
                  </td>
                  <td>
                  </td>
                  <td>
                  </td>
                  <td>
                    {{$total = $firstTotal + $secondeTotal + $tirthTotal}} MAD
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Sous Total HT</label>
                    <p class="m-0"></p>
                  </td>
                  <td>
                  </td>
                  <td>
                  </td>
                  <td>
                    {{$finalTotal = $total}} MAD
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>TVA : {{config('constant.nexa_tva') * 100}} %</label>
                    <p class="m-0"></p>
                  </td>
                  <td>
                  </td>
                  <td>
                  </td>
                  <td>
                    {{ $finalTotal * ( config('constant.nexa_tva'))}} MAD
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>TOTAL TTC</label>
                    <p class="m-0"></p>
                  </td>
                  <td>
                  </td>
                  <td>
                  </td>
                  <td>
                    {{ number_format((float) ( $finalTotal * (1 + config('constant.nexa_tva'))) , 2, '.', '')  }} MAD
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="button_payer">
            <form action="{{route('owner.checkout-process')}}" method="POST">
              @csrf
              <input type="hidden" name="cmd" value="{{$cmd}}">
              <button class="btn btn-secondary btn-lg" type="submit" data-bs-original-title="" title="">Payez maintenant</button>
            </form>
          </div>
          <!-- End Table-->
          <div class="row">
            <div class="col-md-8">
              <div>
                <p class="legal"> <a href="{{route('cgv')}}" target="_blank"><strong>Conditions Générales de Vente </strong></a></p>
              </div>
            </div>
          </div>
        </div>
        <!-- End InvoiceBot-->
      </div>
    </div>
  </div>
</div>
@else
<div class="row">
  <div class="col-2"></div>
  <div class="col-8">
    <div class="card">
      <div class="card-header" style="text-align: center;font-weight: bold;font-size: 28px;">
        Paiement Requis
      </div>
      <div class="card-body">
        <p style="font-size: 30px;">
          Bonjour, <br> Votre espace sur la plate-forme Nexa est désactivé.
          <br> Veillez procéder au règlement de la facture du magasin.
        </p>
        <br>
        06 63 42 37 33
        <br>
        06 63 34 62 16
        <br>
        L’équipe Nexa.
      </div>
    </div>

  </div>
</div>
@endif
</div>
<div class="modal fade bd-example-modal-lg" id="personal-info" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ trans('Informations Personnel') }} </h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <form action="{{route('owner.personalInfo.update')}}" method="POST">
        @csrf
        <div class="modal-body col-md-12">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="validationServer01">{{trans(' Prénom')}}</label>
              <input class="form-control" id="validationServer02" type="text" name="firstname" value="{{auth()->user()->firstname}}" required="" data-original-title="" title="{{trans('Nom')}}">

            </div>
            <div class="col-md-12 mb-3">
              <label for="validationServer02">{{trans('Nom')}}</label>
              <input class="form-control" id="validationServer02" type="text" name="lastname" value="{{auth()->user()->lastname}}" required="" data-original-title="" title="{{trans('Prénom')}}">
            </div>
            <div class="col-md-12 mb-3">
              <label for="validationServer02">{{trans('Téléphone')}}</label>
              <input class="form-control" id="validationServer02" type="text" name="phone" value="{{auth()->user()->phone}}" required="" data-original-title="" title="{{trans('Téléphone')}}">
            </div>
            {{--<div class="col-md-12 mb-3">
                        <label for="validationServer02">{{trans('E-mail')}}</label>
            <input class="form-control" id="validationServer02" type="text" name="email" value="{{auth()->user()->email}}" required="" data-original-title="" title="{{trans('E-mail')}}">
          </div>--}}
          <div class="col-md-12 mb-3">
            <label for="validationServer02">{{trans('Address du Propriétaire')}}</label>
            <textarea name="address" id="" class="form-control" cols="30" rows="10">{{auth()->user()->address}}</textarea>
          </div>
        </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" type="submit">{{ trans('Modifier') }}</button>
    </div>
    </form>
  </div>
</div>
@endsection

@section('script')
<script src="https://laravel.pixelstrap.com/cuba/assets/js/jquery-3.5.1.min.js"></script>
<!-- Bootstrap js-->
<script src="https://laravel.pixelstrap.com/cuba/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
<!-- feather icon js-->
<script src="https://laravel.pixelstrap.com/cuba/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="https://laravel.pixelstrap.com/cuba/assets/js/icons/feather-icon/feather-icon.js"></script>
<!-- scrollbar js-->
<script src="https://laravel.pixelstrap.com/cuba/assets/js/scrollbar/simplebar.js"></script>
<script src="https://laravel.pixelstrap.com/cuba/assets/js/scrollbar/custom.js"></script>
<!-- Sidebar jquery-->
<script src="https://laravel.pixelstrap.com/cuba/assets/js/config.js"></script>
<!-- Plugins JS start-->
<script id="menu" src="https://laravel.pixelstrap.com/cuba/assets/js/sidebar-menu.js"></script>
<script src="https://laravel.pixelstrap.com/cuba/assets/js/counter/jquery.waypoints.min.js"></script>
<script src="https://laravel.pixelstrap.com/cuba/assets/js/counter/jquery.counterup.min.js"></script>
<script src="https://laravel.pixelstrap.com/cuba/assets/js/counter/counter-custom.js"></script>
<script src="https://laravel.pixelstrap.com/cuba/assets/js/print.js"></script>
@endsection