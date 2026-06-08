<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<table class="display" id="example-style-1">
    <thead>
        <tr>
            <th class="text-center">{{trans('Magasin')}}</th>
            <th class="text-center">{{trans('Nom Complet')}}</th>
            <th class="text-center">{{trans('Téléphone')}}</th>
            <th class="text-center">{{trans('P.Réd')}}</th>
            <th class="text-center">{{trans('')}}</th>
        </tr>
    </thead>
   <tbody>
       @foreach ($customers as $item)
           <tr>
            <td>{{$item->store->name}}</td>
            <td>{{$item->getFullNameAttribute()}}</td>
            <td>{{$item->phone}}</td>
            <td>{{$item->points ? $item->points: 0 }}</td>
            <td><input type="checkbox" name="customers[]" value="{{$item->id}}"></td>
           </tr>
       @endforeach
   </tbody>
</table>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>