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
<h3 class="text-center">{{auth()->user()->getFullNameAttribute()}}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{url('dashboard/index')}}">{{trans('dashboard.dashboard')}}</a></li>
<li class="breadcrumb-item active">{{trans('Propriétataires')}}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Ajax Deferred rendering for speed start-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-info" type="button" data-toggle="modal" data-target=".bd-example-modal-lg" title="{{trans('Nouveau Propriétaire')}}">{{trans('Nouveau')}}</button>
                </div>
                <div class="card-body">
                    <table class="display" id="export-button">
                        <thead>
                            <tr>
                                <th>{{trans('Nom')}}</th>
                                <th>{{trans('Prénom')}}</th>
                                <th>{{trans('Téléphone')}}</th>
                                <th>{{trans('E-mail')}}</th>
                                <th>{{trans('Active')}}</th>
                                {{-- <th>{{trans('P.Données')}}</th> --}}
                                <th>{{trans('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($owners as $item)
                            <tr>
                                <td>{{$item->firstname}}</td>
                                <td>{{$item->lastname}}</td>
                                <td>{{$item->phone}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{($item->is_active)==0?'Non':'Oui'}}</td>
                                {{-- <td>{{$item->allow_share}}</td> --}}
                                <td>
                                    <a href="#" class="btn btn-primary"><i class="fa fa-eye" data-toggle="modal" data-target=".bd-example-modal-lg-owner-{{$item->id}}" title="{{trans('Consulter')}}"></i></a>
                                    <a class="btn" style="background-color: #a927f9" href="{{url('/admin/owners/'.$item->id.'/edit')}}"><i class="fa fa-pencil" title="{{trans('Modifier')}}"></i></a>
                                    @if($item->is_active==1)
                                    @component('components.delete-button', ['action' => route('admin.destroy.owners', $item->id), 'class' => 'btn btn-success', 'title' => trans('Supprimer')])
                                        <i class="fa fa-check"></i>
                                    @endcomponent
                                    @else
                                    <a href="{{url('/admin/owners/'.$item->id.'/active')}}" class="btn btn-secondary"><i class="fa fa-trash" title="{{trans('Activer')}}"></i></a>
                                    @endif
                                </td>
                            </tr>
                            <div id="modal-owner" class="modal fade bd-example-modal-lg-owner-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">{{trans('Détail Propriétataire')}}</h4>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="validationServer01">{{trans('Nom')}}</label>
                                                    <input class="form-control" readonly value="{{$item->firstname}}" title="{{trans('Nom')}}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="validationServer02">{{trans('Prénom')}}</label>
                                                    <input class="form-control " readonly value="{{$item->lastname}}" title="{{trans('Prénom')}}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="validationServer01">{{trans('E-mail')}}</label>
                                                    <input class="form-control" readonly value="{{$item->email}}" title="{{trans('E-mail')}}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="validationServer02">{{trans('Téléphone')}}</label>
                                                    <input class="form-control" readonly value="{{$item->phone}}" title="{{trans('Téléphone')}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="invalidCheck3">{{trans('Données Partagées')}}: {{$item->allow_share == 1 ? 'OUI' : 'NON'}} </label>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-original-title="" title="">{{trans('Fermer')}}</button>
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
<div id="modal-owner" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">{{trans('Nouveau Propriétataire')}}</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.store.owner')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Nom')}}</label>
                            <input class="form-control" id="validationServer01" type="text" name="lastname" value="{{old('lastname')}}" required="" data-original-title="" title="{{trans('Nom')}}">
                            @if ($errors->has('firstname'))
                            <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Prénom')}}</label>
                            <input class="form-control " id="validationServer02" type="text" name="firstname" value="{{old('firstname')}}" required="" data-original-title="" title="{{trans('Prénom')}}">
                            @if ($errors->has('lastname'))
                            <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('E-mail')}}</label>
                            <input class="form-control" id="validationServer01" type="text" name="email" value="{{old('email')}}" required="" data-original-title="" title="{{trans('E-mail')}}">
                            @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Téléphone')}}</label>
                            <input class="form-control" id="validationServer02" type="text" name="phone" value="{{old('phone')}}" required="" data-original-title="" title="{{trans('Téléphone')}}">
                            @if ($errors->has('phone'))
                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="validationServer01">{{trans('Mot de passe')}}</label>
                            <input class="form-control" id="validationServer01" type="password" name="password" required="" data-original-title="" title="Mot de passe">
                            <div class="show-hide"><span class="show"></span></div>
                            @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationServer02">{{trans('Confirmation')}}</label>
                            <input class="form-control" id="validationServer02" type="password" name="password-confirmation" required="" data-original-title="" title="Confirmation du mot de passe">
                            <div class="show-hide"><span class="show"></span></div>
                            @if ($errors->has('password-confirmation'))
                            <div class="invalid-feedback">{{ $errors->first('password-confirmation') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input is-invalid" name="allow_share" id="invalidCheck3" type="checkbox" value="1" data-original-title="" title="">
                            <label class="form-check-label" for="invalidCheck3">{{trans('Permettre le partage des clients entre mes magasins')}} ?</label>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit" data-original-title="" title="{{trans('Enregistrer')}}">{{trans('Enregistrer')}}</button>
                </form>
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
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
@endsection
@if($errors->any())
<script>
    $('.modal').modal("show");
</script>
@endif