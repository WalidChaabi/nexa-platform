@extends('layouts.simple.master')
@section('title', 'Solde')

@section('css')

@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Dashboard</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{url('/dashboard/owner')}}">Dashboard</a></li>
<li class="breadcrumb-item active">{{trans('Solde')}}</li>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row size-column">

      <div class="row">
          
         <div class="col-md-6">
            <div class="card col-md-12" style="align-items: center">
               <div class="card-header">
                  <h5>{{trans('Balance ')}}</h5>
               </div>
               <div class="card-body">
                  <div class="our-product">
                     <div class="table-responsive">
                        <table class="table table-bordernone">
                           <tbody class="">
                              <tr>
                                 <td>
                                    <div class="media">
                                       <div class="media-body">
                                          <span>{{trans('Elements')}}</span>
                                       </div>
                                    </div>
                                 </td>
                                 <td>
                                    <p>{{trans('Nombre')}}</p>
                                 </td>
                                 <td>
                                    <p>{{trans('Prix TTC')}}</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <div class="media">
                                       <div class="media-body">
                                          <p class="font-roboto">SMS</p>
                                       </div>
                                    </div>
                                 </td>
                                 <td>
                                    <span>200</span>
                                 </td>
                                 <td>
                                    <span>230Mad</span>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <div class="media">
                                       <div class="media-body">
                                          <p class="font-roboto">E-mails</p>
                                       </div>
                                    </div>
                                 </td>
                                 <td>
                                    <span>{{$emails=0}}</span>
                                 </td>
                                 <td>
                                    <span>{{  number_format((float)($emails * ( Config::get('constant.email_price') ) ), 2, '.', '')}} Mad</span>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <p>{{trans('Total TTC')}}</p>
                                 </td>
                                 <td>
                                 </td>
                                 <td>
                                    450Mad
                                 </td>
                              </tr>
                              
                           </tbody>
                        </table>
                     </div>

                  </div>
                     <button class="btn btn-primary" type="submit" data-original-title="" title="">{{trans('Détail')}}</button>
                     <a class="btn btn-primary" target="_blank" href="{{url('owner/payement')}}">{{trans('Payer')}}</a>
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="card col-md-12" style="align-items: center">
               <div class="card-header">
                  <h5>{{trans('Historique Paiements')}}</h5>
               </div>
               <div class="card-body">
                  <div class="our-product">
                     <div class="table-responsive">
                        <table class="table table-bordernone">
                           <tbody class="">
                              <tr>
                                <td>
                                    <p>{{trans('Date')}}</p>
                                 </td>
                                 <td>
                                    <p>{{trans('Service')}}</p>
                                 </td>
                                 <td>
                                    <p>{{trans('Total')}}</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td>
                                    <div class="media">
                                       <div class="media-body">
                                          <p class="font-roboto">SMS</p>
                                       </div>
                                    </div>
                                 </td>
                                 <td>
                                    <span>200</span>
                                 </td>
                                 <td>
                                    <span>230Mad</span>
                                 </td>
                              </tr>
                              
                              
                           </tbody>
                        </table>
                     </div>

                  </div>
                     {{-- <button class="btn btn-primary" type="submit" data-original-title="" title="">{{trans('Détail')}}</button>
                     <a class="btn btn-primary" target="_blank" href="{{url('owner/payement')}}">{{trans('Payer')}}</a> --}}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')

@endsection
