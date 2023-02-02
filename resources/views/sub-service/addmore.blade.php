<div class="col-sm-12">
    <table class="table table-bordered table-hover tablesorter">
        <thead>
            <tr>
                <th>Sub Service Images</th>
                <th class="text-center"><a class="btn btn-sm btn-success fieldsaddmore-addbtn"><i
                            class="fa fa-plus"></i></a></th>
            </tr>
        </thead>
        <!-- Main element container -->
        <tbody class="admore-custom-fields">
            @if (old('sub_service_image', false))
                @foreach (old('sub_service_image') as $key => $row)
                    <?php $key + 1; ?>
                    <tr id="{{ $key }}" class="fieldsaddmore-row rowId-{{ $key }}">
                        <td
                            class="text-center width_33 {{ $errors->first('sub_service_image.' . $key . '.image', 'has-error') }}">
                            <div class="input-group col-sm-8">
                                <div class="custom-file">
                                    <input type="file" name="sub_service_image[{{ $key }}][image]"
                                        class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                            {!! $errors->first('sub_service_image.' . $key . '.image', '<span class="text-danger">:message</span>') !!}
                        </td>
                        <td class="text-center width_33"><a href="#" data-rowid="{{ $key }}"
                                class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a>
                        </td>
                    </tr>
                @endforeach
            @elseif (!empty($subService['images']))
                @foreach ($subService['images'] as $key => $row)
                    <tr id="{{ $key }}" class="fieldsaddmore-row rowId-{{ $key }}">
                        {{ Form::hidden("sub_service_image[$key][id]", $row->id) }}
                        <td class="text-center width_33">
                            <div class="input-group col-sm-8">
                                @if (!empty($row->image))
                                    <img src="{{ asset('api/sub-service-image/' . $row->image) }}"
                                        width="100" height="80">
                                @endif
                                <div class=" m-4 custom-file">
                                    <input type="file" name="sub_service_image[{{ $key }}][image]"
                                        class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label text-left" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>

                        </td>
                        <td class="text-center width_33"><a href="#" data-rowid="{{ $key }}"
                                class="btn btn-sm btn-warning fieldsaddmore-removebtn m-4"><i class="fa fa-minus"></i></a>
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
            <div class="input-group col-sm-8">
                <div class="custom-file">
                    <input type="file" name="sub_service_image[key][image]" class="custom-file-input"
                        id="exampleInputFile">
                    <label class="custom-file-label text-left" for="exampleInputFile">Choose file</label>
                </div>
            </div>
        </td>
        <td class="text-center" >
            <a href="#" data-rowid="key" class="btn btn-sm btn-warning fieldsaddmore-removebtn"><i class="fa fa-minus"></i></a>
        </td>
    </tr>
</script>
