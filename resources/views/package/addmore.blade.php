<div class="col-sm-12">
    <table class="table table-bordered table-hover tablesorter">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center"><a class="btn btn-sm btn-success fieldsaddmore-addbtn"><i
                            class="fa fa-plus"></i></a></th>
            </tr>
        </thead>
        <!-- Main element container -->
        <tbody class="admore-custom-fields">
            @if (old('items', false))
                @foreach (old('items') as $key => $row)
                    <?php $key + 1; ?>
                    <tr id="{{ $key }}" class="fieldsaddmore-row rowId-{{ $key }}">
                        <td
                            class="text-center width_33 {{ $errors->first('items.' . $key . '.product_id', 'has-error') }}">
                            <select class="form-control" name="items[{{ $key }}][product_id]" id="product">
                                <option value="">-Select Product-</option>
                                @foreach ($products as $product)
                                    <option {{ $row['product_id'] == $product->product_id ? 'selected' : '' }}
                                        value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                            {{-- {!! Form::select('product_id', $products, null, [
                                'class' => 'form-control',
                                'id' => 'product',
                                'placeholder' => '-Select Product-',
                            ]) !!} --}}
                            {!! $errors->first('items.' . $key . '.product_id', '<span class="text-danger">:message</span>') !!}
                        </td>
                        <td class="text-center width_33"><a href="#" data-rowid="{{ $key }}"
                                class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a>
                        </td>
                    </tr>
                @endforeach
            @elseif (!empty($package->items))
                @foreach ($package->items as $key => $row)
                    <tr id="{{ $key }}" class="fieldsaddmore-row rowId-{{ $key }}">
                        {{ Form::hidden("items[$key]->product_id", $row['id']) }}
                        <td class="text-center width_33">
                            <select class="form-control" name="items[{{ $key }}][product_id]" id="product">
                                <option value="">-Select Product-</option>
                                @foreach ($products as $product)
                                    <option {{ $row->product_id == $product->product_id ? 'selected' : '' }}
                                        value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                @endforeach
                            </select>
                           
                        </td>
                        <td class="text-center width_33"><a href="#" data-rowid="{{ $key }}"
                                class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<!-- Addmore template -->
<script id="fieldsaddmore-template" type="text/template">
    <tr class="fieldsaddmore-row rowId" id='key'>
        <td class="text-center" style="">
            <select class="form-control" name="items[key][product_id]" id="product">
                <option value="">-Select Product-</option>
                @foreach ($products as $product)
                    <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
        </td>
        <td class="text-center" >
            <a href="#" data-rowid="key" class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a>
        </td>
    </tr>
</script>
