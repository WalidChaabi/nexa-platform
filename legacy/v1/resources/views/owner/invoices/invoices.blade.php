@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
@endsection
@section('style')
@endsection

@section('breadcrumb-title')
    <h3 class="text-center">{{trans('Mes Factures')}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/owner')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Statistiques')}}</li>
@endsection

@section('content')


<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
			    
				<div class="card-body row">
                    <div class="col-md-8">
                            <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="{{trans('FILTRES')}}">
                                <i class="fa fa-sliders"  title="{{trans('filtres')}}" aria-hidden="true"></i>
                            </button>
                            
                    </div>
                    <div class="col-md-4">
                        <select style="width:80px;margin-left:auto" id="items" onchange="ShowItems($(this).val(),'{{url('/owner/invoices')}}')" class="form-control digits">
                            @foreach(Config::get('constant.items') as $item)
                                <option value="{{$item}}" {{ ( isset($_GET['items']) && $_GET['items']==$item  ) ? 'selected':'' }}  >{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="table-responsive">
                              <table class="display" id="render-datatable">
                        <thead>
                            <tr>
                                <th>{{ trans('Client') }}</th>
                                <th>{{ trans('Date') }}</th>
                                <th>{{ trans('Téléphone') }}</th>
                                <th>{{ trans('Total HT') }}</th>
                                <th>{{ trans('Total TTC') }}</th>
                                <th>{{ trans('B.R') }}</th>
                                <th class="text-center">{{ trans('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            @foreach ($invoices as $item)
                                <tr style="{{ $item->status == 4 || $item->status == 2 ? 'background-color: ' . getInvoiceColorByStatus($item->status) : '' }}">
                                    <td>{{ $item->customer->getFullNameAttribute() }}</td>
                                    
                                    <td>{{ $item->invoice_date}}</td>
                                    <td>{{ $item->customer->phone }}</td>
                                    <td>{{ $item->total_ht }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->points > 0 ? 'Oui' : 'Non' }}</td>
                                    <td>
                                        @if ($item->status == 0)

                                            <a class="btn" style="background-color: #ea2087"
                                                href="{{ route('employe.invoices.print', $item->id) }}"><i
                                                    class="fa fa-print"></i></a>
                                            @if (auth()->user()->is_admin == 3)
                                                <a class="btn" style="background-color: #7366ff" data-toggle="modal"
                                                    data-target="#exampleModalCenter-{{ $item->id }}"><i
                                                        class="fa fa-trash-o"></i></a>
                                                <a class="btn" style="background-color: #a927f9"
                                                    href="{{ route('employe.invoices.edit', $item->id) }}"><i
                                                        class="fa fa-edit"></i></a>

                                            @endif
                                        @elseif($item->status==2 || $item->status==4)
                                            <a class="btn" style="background-color: #ea2087"
                                                href="{{ route('employe.invoices.print', $item->id) }}"><i
                                                    class="fa fa-print"></i></a>
                                            <a class="btn" style="background-color: #a927f9"
                                                href="{{ route('employe.invoices.show', $item->id) }}"><i
                                                    class="fa fa-eye"></i></a>
                                        @endif
                                        <div class="modal fade" id="exampleModalCenter-{{ $item->id }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ trans('Confirmation') }}</h5>
                                                        <button class="close" type="button" data-dismiss="modal"
                                                            aria-label="Close"><span
                                                                aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ trans('Vous voulez vraiment supprimer la facture : #' . $item->id . ' ? ') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">{{ trans('Non') }}</button>
                                                        <a href="{{ route('employe.invoices.cancel', $item->id) }}"
                                                            class="btn btn-primary"
                                                            type="button">{{ trans('Oui') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                             @endforeach
                   
                           
                        </tbody>
                    </table>
                    </div>
              
                </div>
                <div class="row">
                    <div class="col-md-12">
                      
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
    <div class="row">
       <div class="col-xl-6 col-md-12 col-sm-12 box-col-12">
          <div class="card">
             <div class="card-header">
                <h5>{{trans('Chiffre d\'affaire de toutes les magasin')}}</h5>
             </div>
             <div class="card-body">
                <div class="ct-6 flot-chart-container"></div>
             </div>
          </div>
       </div>
       <div class="col-xl-6 col-md-12 col-sm-12 box-col-12">
          <div class="card">
             <div class="card-header">
                <h5>{{trans('Evolution de nombre de client')}}</h5>
             </div>
             <div class="card-body chart-block">
                {{-- <div class="ct-7 flot-chart-container"></div> --}}
                    <div class="flot-chart-container">
                       <div class="flot-chart-placeholder" id="graph123"></div>
                    </div>
                 
             </div>
          </div>
       </div>
       <div class="col-xl-6 col-md-12 col-sm-12 box-col-12">
          <div class="card">
             <div class="card-header">
                <h5>{{trans('Taux de Rebon/retention')}}</h5>
             </div>
             <div class="card-body">
                <div class="ct-8 flot-chart-container"></div>
             </div>
          </div>
       </div>
    </div>
 </div>
 <div id="modal-owner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <div class="modal-header">
             <h4 class="modal-title" id="myLargeModalLabel">{{trans('Filtres')}}</h4>
             <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
             <form action="{{route('owner.invoices')}}" method="GET">
               <input type="hidden" name="filtres" value="1">
               <div class="row">
                  <div class="col-md-6 mb-3">
                      <label for="validationServer01">{{trans('Sexe')}}</label>
                      <select class="form-control digits" name="sexe" id="sexe">
                          <option>-- {{trans('Choisir Sexe')}} --</option>
                          <option value="1">{{trans('Homme')}}</option>
                          <option value="0">{{trans('Femme')}}</option>
                      </select>
                  </div>
                <input type="hidden" name="items" value="">
                  <div class="col-md-6 mb-3">
                      <label for="validationServer01">{{ trans('Date de Naissance') }}</label>
                      <input class="datepicker-here form-control" type="text" name="birth" data-language="en">
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <div class="row">
                          <div class="col-md-6">
                              <label for="validationServer01">{{trans('Operation')}}</label>
                              <select class="form-control digits" name="operator" id="sexe">
                                  <option value="-1">-- {{trans('Choisir Operateurs')}} --</option>
                                  @foreach (Config::get('constant.operators') as $key=>$item)
                                  
                                  <option value="{{$key}}"> {{$item}} </option>
                                  @endforeach
                                 
                              
                              </select>
                          </div>
                          <div class="col-md-6">
                                  <label for="validationServer01">{{trans('B.R')}}</label>
                                  <input class="form-control"   name="reduction"  title="{{trans('B.Reduction')}}">
                          </div>
                      </div>
                      
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label for="validationServer01">{{trans('Date Debut')}}</label>
                      <input class="datepicker-here form-control" name="start_date"  data-language="en"  title="{{trans('Date Debut')}}">
                  </div>
                  <div class="col-md-6 mb-3">
                      <label for="validationServer02">{{trans('Date Fin')}}</label>
                      <input class="datepicker-here form-control" name="end_date"  data-language="en"  title="{{trans('Date Fin')}}">
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-2">
                      <button class="btn btn-primary" type="submit" title="{{trans('Appliquer')}}">{{trans('Appliquer')}}</button>
                  </div>
              </div>
            
             </form>
            
            
          </div>
       </div>
      
    </div>
</div>
@endsection

@section('script')
    <script src="{{asset('assets/js/chart/chartist/chartist.js')}}"></script>
    <script src="{{asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
    <script src="{{asset('assets/js/chart/chartist/chartist-custom.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
    <script src="{{asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{asset('assets/js/chart/morris-chart/raphael.js')}}"></script>
    <script src="{{asset('assets/js/chart/morris-chart/morris.js')}}"> </script>
    <script src="{{asset('assets/js/chart/morris-chart/prettify.min.js')}}"></script>
    <script src="{{asset('assets/js/chart/morris-chart/morris-script.js')}}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
   
@endsection