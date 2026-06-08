<label for="validationServer01">{{trans('Magasin')}}</label>
<select class="form-control digits" id="stores_id" name="stores_id[]" onclick="getCustomers($(this).val())">
    <option value="-1">--{{trans('Choisr Magasin')}}--</option>
    @foreach($stores as $item)
        <option value="{{$item->id}}">{{$item->name}}</option>
    @endforeach                                  
</select>