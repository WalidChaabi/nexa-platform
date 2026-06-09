@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
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
    <li class="breadcrumb-item active">{{trans('Factures')}}</li>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <form id="importList" action="{{route('owner.invoices.repport')}}" method="GET" >
                            @csrf
                            <div class="row">
                              <div class="col-md-2">
                                <select class="form-control digits" name="store_id" id="store_id">
                                    <option>-- {{trans('Choisir Magasin')}} --</option>
                                    @foreach ($stores as $key=>$value)
                                        @if(isset($_GET['store_id']))
                                         <option value="{{$key}}" @if($_GET['store_id']==$key) selected @endif>{{$value}}</option>
                                        @else
                                        <option value="{{$key}}" >{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                              </div>
                              <div class="col-md-2">
                                <input type="date" name="start_date" value="{{ (isset($_GET['start_date']) ? $_GET['start_date'] : '' )    }}" class="form-control">
                              </div>
                              <div class="col-md-2">
                                <input type="date" name="end_date" value="{{ (isset($_GET['end_date']) ? $_GET['end_date'] : '' )    }}" class="form-control">
                              </div>
                              <div class="col-md-2">
                                <select type="date" name="sexe" class="form-control">
                                    <option value="0">{{trans('Choisir le sexe')}}</option>
                                  @if(isset($_GET['sexe']))
                                  <option value="1" @if($_GET['sexe']==1) selected @endif>Homme</option>
                                  <option value="2" @if($_GET['sexe']==2) selected @endif >Femme</option>
                                  <option value="3" @if($_GET['sexe']==3) selected @endif>Mix</option>
                                  @else  
                                  <option value="1">Homme</option>
                                  <option value="2">Femme</option>
                                  <option value="3">Mix</option>
                                  @endif
                                </select>
                              </div>
                              <div class="col-md-2">
                                <button type="submit" class="form-control btn btn-primary">{{trans('Rechercher')}}</button>
                              </div>
                            </div>
                        </form>
                    </div>
                    <form id="advanced-search-form" action="{{ route('owner.invoices.recherche') }}" method="GET" style="display: none;">
                      @csrf
                      <input type="hidden" id="recherch_store_id" name="recherch_store_id">
                      <div class="row" style="margin-top: 30px">
                          <div class="col-sm-4"> 
                              <label for="validationServer02">{{ trans('communs.Recherche avancée') }}</label>
                              <input style="border: 2 solid blue" class="form-control " id="to_paye" type="text" name="recherche_avencee" placeholder="+212 6 XX XX XX XX"
                                  title="{{ trans('communs.Recherche avancée') }}">
                          </div>
                          <div class="col-sm-6 d-flex" style="align-items: flex-end;justify-contetn:center;">
                              <button type="submit" class="form-control btn btn-primary" style="width:190px; background: #a927f9 !important;">{{ trans('Recherche avancée') }}</button>
                          </div>
                      </div>
                  </form>
                </div>
                
            </div>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="display datatables" id="export-button" aria-describedby="export-button_info">
              <thead>
                <tr>
                    <th>{{ trans('Client') }}</th>
                    <th>{{ trans('Date') }}</th>
                    <th>{{ trans('Téléphone') }}</th>
                    <th>{{ trans('Total HT') }}</th>
                    <th>{{ trans('Total TTC') }}</th>
                    <th>{{ trans('B.R') }}</th>
                    <th>{{ trans('C.R') }}</th>
                    <th>{{ trans('R. MAD') }}</th>
                    <th>{{ trans('Action') }}</th>
                </tr>
              </thead>
              <tbody>
                  @foreach($invoices as $item)
                      <tr>
                          {{-- <td>{{ $item->customer->getFullNameAttribute() }}</td> --}}
                          <td>{{ $item->customer ? $item->customer->getFullNameAttribute() : '' }}</td>
                          <td>{{ $item->invoice_date}}</td>
                          {{-- <td>{{ $item->customer->phone }}</td> --}}
                          <td>{{ $item->customer ? $item->customer->phone : '' }} </td>
                          <td>{{ $item->total_ht }}</td>
                          <td>{{ $item->total }}</td>
                          <td>{{ $item->points > 0 ? 'Oui' : 'Non' }}</td>
                          <td>{{ !empty($item->campaign) ? 'Oui' : 'Non' }}</td>
                          <td>{{ $item->points > 0 ? $item->points : 0 }}</td>
                          <td>
                            <a class="btn" data-toggle="modal" data-target="#modalInvoice-{{ $item->id }}" style="background-color: #ea2087"><i
                                class="fa fa-eye"></i></a>
                          </td>
                      </tr>
                      <div class="modal fade bd-example-modal-lg" id="modalInvoice-{{ $item->id }}"
                        tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ trans('Detail de facture') }}   </h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body col-md-12">
                                   <div class="row">
                                      <div class="mb-3 col-md-5">
                                          <div class="form-group">
                                              <label for="full_name">{{ trans('Client') }}</label>
                                              <input readonly class="form-control " id="full_name" type="text" 
                                                value="{{ $item->customer ? $item->customer->getFullNameAttribute() : '' }}">
                                          </div>
                                      </div>
                                      <div class="mb-3 col-md-5">
                                          <label for="date">{{ trans('Date') }}</label>
                                          <input readonly class="form-control" id="date" type="text"
                                              value="{{$item->invoice_date}}" title="{{ trans('Date de Facture') }}">
                                      </div>
                                      <div class="mb-3 col-md-2">
                                        <div class="form-group">
                                            <label for="points">Reduction</label>
                                            <input readonly class="form-control " id="points" type="text" value="{{$item->points }}">
                                        </div>
                                      </div>
                                      <div class="mb-3 col-md-12">
                                        @php
                                          $productNumber = 1;
                                        @endphp
                                        @foreach($item->items as $el)
                                          <div class="row">
                                              <div class="col-md-5 form-group">
                                                <label for="produit">{{ trans('Produit' . ' ' . $productNumber ) }}</label>
                                                <input readonly class="form-control " id="produit" type="text" title="{{ trans('Date de Facture') }}"
                                                  value="{{ isset($el->product->label) ? $el->product->label : '' }}" >
                                              </div>
                                              <div class="col-md-5 form-group">
                                                    <label for="price">{{ trans('Prix') }}</label>    
                                                    <input readonly class="form-control " id="price" type="text"value="{{$el->price}}">    
                                              </div>
                                              <div class="col-md-2 form-group">
                                                <label for="qte">{{ trans('Qte') }}</label>    
                                                <input readonly class="form-control "id="qte" type="text" value="{{$el->qte}}">    
                                              </div>
                                          </div>
                                          @php
                                            $productNumber++;
                                          @endphp
                                        @endforeach
                                      </div>

                                      <div class="col-md-10 form-group">
                                        <label for="description">{{ trans('Description') }}</label> 
                                        <textarea readonly class="form-control" name="description" id="description" rows="3">{{$item->description}}</textarea>   
                                        {{-- <textarea readonly class="form-control "id="qte"></textarea> --}}
                                      </div>
                                      <div class="col-md-2 form-group">
                                        <label for="total">{{ trans('Total') }}</label>    
                                        <input readonly class="form-control " id="total" type="text" value="{{$item->total}}">    
                                      </div>                                      
                                  </div>

                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ trans('Fermer') }}</button>
                                </div>
                            </div>
                        </div>
                      </div>
                  @endforeach
              </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
       <script src="https://app.nexa.app/assets/js/jquery-3.5.1.min.js"></script>
<!-- Bootstrap js-->
<script src="https://app.nexa.app/assets/js/bootstrap/popper.min.js"></script>
<script src="https://app.nexa.app/assets/js/bootstrap/bootstrap.js"></script>
<!-- feather icon js-->
<script src="https://app.nexa.app/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="https://app.nexa.app/assets/js/icons/feather-icon/feather-icon.js"></script>
<!-- Sidebar jquery-->
<script src="https://app.nexa.app/assets/js/config.js"></script>
<!-- Plugins JS start-->
<script src="https://app.nexa.app/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/jszip.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/buttons.colVis.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/pdfmake.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/vfs_fonts.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.select.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/buttons.html5.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/buttons.print.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.keyTable.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.colReorder.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/dataTables.scroller.min.js"></script>
<script src="https://app.nexa.app/assets/js/datatable/datatable-extension/custom.js"></script>
<script src="https://app.nexa.app/assets/js/sidebar-menu.js"></script>
<script src="https://app.nexa.app/assets/js/tooltip-init.js"></script>
<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="https://app.nexa.app/assets/js/script.js"></script>
<script src="https://app.nexa.app/assets/js/theme-customizer/customizer.js"></script>
  
  </body>

</html>
<script>
  if(localStorage.getItem('mode')=='dark'){
    $('body').toggleClass('dark-only')
  }
</script>

<script>
  $(document).ready(function() {
      $('#store_id').on('change', function() {
          var StoreId = $(this).val();
          $("#recherch_store_id").val(StoreId);
          var checkRAUrl = '/owner/repport/invoices/check-store-property/' + StoreId;

          $.ajax({
              url: checkRAUrl,
              type: 'GET',
              success: function(data) {
                  if (data.hasRA) {
                      $('#advanced-search-form').show();
                  } else {
                      $('#advanced-search-form').hide();
                  }
              },
              error: function() {
                  // Handle error if necessary
              }
          });
      });
  });
</script>
   
@endsection
