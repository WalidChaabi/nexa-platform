@extends('layouts.simple.master')
@section('title', 'Default')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3 class="text-left">{{auth()->user()->store->name}}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{url('dashboard/employe')}}">{{trans('dashboard.dashboard')}}</a></li>
    <li class="breadcrumb-item active"><a href="{{route('employe.invoices')}}">{{trans('Factures')}}</a></li>
    <li class="breadcrumb-item active">{{trans('Détails')}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<!-- Ajax Deferred rendering for speed start-->
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h3>{{trans('communs.Détail Facture')}} : {{date("Y-m-d H:i:s")}} </h3>
				</div>
				<div class="card-body">
                
                    --------------------------------------------------------------------------------------------------------------
                    @foreach ($products as $item)
                    <?php $id=uniqid(); ?>
                    <div class="row" id="{{$id}}">
                        <div class="col-md-6">
                            <label for="validationServer01">{{ $item->product->label }} | {{ $item->product->price }} Mad </label>
                                <input type="hidden" name="products[]" value="{{$item->price}}">
                                {{-- {{ $item->product->label }} | {{ $item->product->price }} Mad  --}}
                        </div>
                        <div class="col-md-2">
                                <label for="validationServer02">{{trans('communs.Prix')}}</label>
                                <input class="form-control" value="{{$item->price}}" id="price_{{$id}}" type="text" name="price[]"  data-original-title="" title="{{trans('communs.Prix')}}">
                        </div>
                        <div class="col-md-2">
                            <label for="validationServer02">{{trans('communs.Quantité')}}</label>
                            <input class="form-control " id="qte_{{$id}}" value="{{$item->qte}}" type="number" name="quantity[]" required onchange="changeTotal({{$item->price}},this.value,{{auth()->user()->store->tva}})"  data-original-title="" title="{{trans('communs.Quantité')}}">
                        </div>
                        <div class="col-md-2">
                            <label for="validationServer02">{{trans('communs.Supprimer')}}</label>
                            <button class="form-control btn btn-primary " type="button" onclick="removeProductRow('{{$id}}','{{$item->price}}',$('#qte_{{$id}}').val(),'{{auth()->user()->store->tva}}')" title="{{trans('Supprimer Produit')}}">-</button>
                        </div>
                    </div>
                    
                    @endforeach
                    <div id="products-row">

                    </div>
                    --------------------------------------------------------------------------------------------------------------
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationServer02">{{trans('communs.Description')}}</label>
                            <textarea class="form-control " id="validationServer02" type="text" name="description"  title="{{trans('communs.Description')}}">{{$invoice->description}}</textarea>
                        </div>
                        <div class="col-md-6 ">
                            <br>
                              <div class="form-group">
                                <div class="checkbox checkbox-dark m-squar">
									<input id="inline-sqr-1" name="mode_payment" value="0" type="checkbox" checked>
									<label class="mt-0" for="inline-sqr-1" >{{trans('communs.Espéces')}}</label>
								</div>
                                <div class="checkbox checkbox-dark m-squar">
									<input id="inline-sqr-1" name="mode_payment" value="1" type="checkbox">
									<label class="mt-0" for="inline-sqr-1">{{trans('communs.Carte Bancaire')}}</label>
								</div>
                                <div class="checkbox checkbox-dark m-squar">
									<input id="inline-sqr-1" name="mode_payment" value="2" type="checkbox">
									<label class="mt-0" for="inline-sqr-1">{{trans('communs.Chéque')}}</label>
								</div>
                              </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="form-group">
                            <div class="checkbox checkbox-dark m-squar">
                                <input id="inline-sqr-1" name="use_points" type="checkbox">
                                <label class="mt-0" for="inline-sqr-1">{{trans('communs.Utiliser la reduction ?')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationServer02">{{trans('communs.Total')}}</label>
                            <input class="form-control " id="total" required type="text" readonly name="total" value="{{$invoice->total}}"  data-original-title="" title="{{trans('communs.Montant Total')}}">
                        </div>
                        <div class="col-md-6">
                            <label for="validationServer02">{{trans('Montant Payé')}}</label>
                                <input readonly class="form-control " id="to_paye" type="text" name="paid_amount" value="{{$invoice->paid_amount}}"  data-original-title="" title="{{trans('communs.Montant â payer')}}">
                        </div>
                    </div>
                    <br>
                   
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
@endsection
