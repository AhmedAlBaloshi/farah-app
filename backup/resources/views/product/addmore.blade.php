<div class="col-sm-12">
    <table class="table table-bordered table-hover tablesorter">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th class="text-center">Active</th>
                <th class="text-center"><a class="btn btn-sm btn-success fieldsaddmore-addbtn"><i class="fa fa-plus"></i></a></th>
            </tr>
        </thead>
        <!-- Main element container -->
        <tbody class="admore-custom-fields">
            @if (old('items',false))
                @foreach (old('items') as $key => $row) 
                    <?php $key + 1; ?>
                    <tr id="{{$key}}" class="fieldsaddmore-row rowId-{{$key}}">
                        <td class="text-center width_33 {{ $errors->first('items.'.$key.'.date', 'has-error') }}">
                            {{ Form::text("items[$key][date]",null,array('class'=>'form-control datepicker'))}}
                            {!! $errors->first('items.'.$key.'.date', '<span class="text-danger">:message</span>') !!}
                        </td>
                        <td class="text-center width_33 {{ $errors->first('items.'.$key.'.time', 'has-error') }}">
                            {{ Form::text("items[$key][time]",null,array('class'=>'form-control timepicker'))}}
                            {!! $errors->first('items.'.$key.'.time', '<span class="text-danger">:message</span>') !!}
                        </td>
                        <td class="text-center width_33 {{ $errors->first('items.'.$key.'is_active', 'has-error') }}">
                            <input type="checkbox" name="items[{{$key}}][is_active]" {{ (isset($row['is_active']) && $row['is_active'] == 1) ? 'checked' : ""}}  data-bootstrap-switch data-off-color="danger" data-on-color="success">
                            {!! $errors->first('items.'.$key.'.is_active', '<span class="text-danger">:message</span>') !!}
                        </td>
                        <td class="text-center width_33"><a href="#" data-rowid="{{$key}}" class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a></td>
                    </tr>
                @endforeach
            @elseif (!empty($product->productAvailability))
                @foreach ($product->productAvailability as $key => $row)
                    <tr id="{{$key}}" class="fieldsaddmore-row rowId-{{$key}}">
                        {{Form::hidden("items[$key][id]",$row['id'])}}
                        <td class="text-center width_33">{{ Form::text("items[$key][date]",date("Y-m-d",strtotime($row['date'])),array('class'=>'form-control datepicker'))}}</td>
                        <td class="text-center width_33">{{ Form::text("items[$key][time]",date("H:i",strtotime($row['time'])),array('class'=>'form-control'))}}</td>
                        <td class="text-center width_33">
                            <input type="checkbox" name="items[{{$key}}][is_active]" {{ (isset($row['is_active']) && $row['is_active'] == 1) ? 'checked' : ""}}  data-bootstrap-switch data-off-color="danger" data-on-color="success">
                        </td>
                        <td class="text-center width_33"><a href="#" data-rowid="{{$key}}" class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<!-- Addmore template -->
<script id="fieldsaddmore-template" type="text/template">
    <tr class="fieldsaddmore-row rowId" id='key'>
        <td class="text-center" style="width:33%">
            {{ Form::text('items[key][date]',null,array('class'=>'form-control datepicker'))}}
        </td>
        <td class="text-center" style="width:32%">
            {{ Form::text('items[key][time]',null,array('class'=>'form-control timepicker'))}}
        </td>
        <td class="text-center" style="width:32%">
            <input type="checkbox" name="items[key][is_active]" checked data-bootstrap-switch data-off-color="danger" data-on-color="success">
        </td>
        <td class="text-center" style="width:2%">
            <a href="#" data-rowid="key" class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a>
        </td>
    </tr>
</script>