@extends('merchant.layouts')

@section('content')


    <div class="card">
        <div class="card-header">
            <h2>{{$pageTitle}}</h2>
        </div>
    </div>

    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            {!! Form::open(['route' => isset($result->id) ? ['panel.merchant.product.update',$result->id]:'panel.merchant.product.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{__('English Data')}}</h2>
                        </div>
                        <div class="card-block card-dashboard">
                            <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                <div class="controls">
                                    {!! Form::label('name_en', __('Product Name').':') !!}
                                    {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                </div>
                                {!! formError($errors,'name_en') !!}
                            </div>

                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                <div class="controls">
                                    {!! Form::label('description_en', __('Product description').':') !!}
                                    {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control']) !!}
                                </div>
                                {!! formError($errors,'description_en') !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{__('Arabic Data')}}</h2>
                        </div>
                        <div class="card-block card-dashboard">
                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                <div class="controls">
                                    {!! Form::label('name_ar', __('Product Name').':') !!}
                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                </div>
                                {!! formError($errors,'name_ar') !!}
                            </div>

                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                <div class="controls">
                                    {!! Form::label('description_ar', __('Product description').':') !!}
                                    {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                </div>
                                {!! formError($errors,'description_ar') !!}
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{__('Product images')}}</h2>
                        </div>
                        <div class="card-block card-dashboard {{(($errors->has('image[]'))?' border-danger': null)}}">
                            <div class="uploaddata">
                                @if(isset($result->id))
                                    @foreach($result->uploadmodel()->get() as $oneupload)
                                        <div class="form-group col-sm-4">
                                            <h3>{{$oneupload->title}}</h3>
                                            <img src="{{asset(str_replace('public/','storage/',$oneupload->path))}}"
                                                 class="img-responsive">
                                            <input type="hidden" name="oldtitle[]" value="{!!$oneupload->title!!}">
                                            <input type="hidden" name="oldimage[]" value="{!!$oneupload->path!!}">
                                            <a class="btn input-xs btn-danger fa fa-trash" onclick="$(this).parents('.form-group').remove();" style="color:#fff;"></a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            {!! formError($errors,'image') !!}

                            <div class="col-sm-12 imagetoupload">
                                <div class="col-sm-5 form-group">
                                    <label for="title[]">{{__('Image title')}}</label>
                                    <input class="form-control" name="title[]" type="text"></div>
                                <div class="col-sm-5 form-group"><label for="image[]">{{__('Product Image')}}</label>
                                    <input class="form-control" name="image[]" type="file">
                                </div>
                                <button class="btn btn-lg btn-danger fa fa-trash delimagerow mt-3" style="color:#fff;"></button>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="pull-right mr-1">
                                    <button type="button" class="btn btn-primary fa fa-plus addinputimg">
                                        <span>{{__('Add Image')}}</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{__('Product info')}}</h2>
                        </div>
                        <div class="card-block card-dashboard">

                            <div class="form-group col-sm-6{!! formError($errors,'merchant_product_category_id',true) !!}">
                                <div class="controls">
                                    {!! Form::label('merchant_product_category_id', __('Product Category').':') !!}
                                    @if(isset($result->id))
                                        {!! Form::select('merchant_product_category_id',$product_category,isset($result->id) ? $result->merchant_product_category_id:old('merchant_product_category_id'),['class'=>'select2 form-control']) !!}
                                    @else
                                        {!! Form::select('merchant_product_category_id',$product_category,old('merchant_product_category_id'),['class'=>'select2 form-control']) !!}
                                    @endif
                                </div>
                                {!! formError($errors,'merchant_product_category_id') !!}
                            </div>


                            <div class="form-group col-sm-6{!! formError($errors,'price',true) !!}">
                                <div class="controls">
                                    {!! Form::label('price', __('Price').':') !!}
                                    <div class="input-group input-group-lg">

                                        {!! Form::number('price',isset($result->id) ? $result->price:old('price'),['class'=>'form-control']) !!}
                                        <span class="input-group-addon" id="sizing-addon1">LE</span>
                                    </div>
                                </div>
                                {!! formError($errors,'price') !!}
                            </div>

                            <div class="form-group col-sm-12{!! formError($errors,'status',true) !!}">
                                <div class="controls">
                                    {!! Form::label('status', __('Product Status').':') !!}
                                    {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                </div>
                                {!! formError($errors,'status') !!}
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{__('Attributes')}}</h2>
                        </div>
                        <div class="card-block card-dashboard">
                            <div class="row nav-vertical">
                                <div class="col-sm-2 nav-vertical">
                                    <ul class="noneli p-0 m-0 nav nav-tabs nav-left">
                                        @if(isset($result->id))
                                            @foreach($oldattribute->groupBy('attribute_id') as $key=>$value)
                                                <li class="nav-item">
                                                    <a class="nav-link" id="baseVerticalLeft-tab{{$value[0]->attribute_id}}" data-toggle="tab" aria-controls="tabVerticalLeft{{$value[0]->attribute_id}}" href="#tabVerticalLeft{{$value[0]->attribute_id}}" aria-expanded="false">
                                                        {{$value[0]->name}}
                                                        <span href="javascript:void(0);" class="text-danger fa fa-trash pull-left" style="margin:5px 5px;" onclick="$(this).parents('li').remove();$('#attributeValues div#tabVerticalLeft{{$value[0]->attribute_id}}').remove();"></span>
                                                    </a>

                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div id="attributeli">
                                        <select name="attribute" id="attribute" class="form-control">
                                            <option value="0">{{__('Select Attribute')}}</option>
                                            @foreach($categories as $category)
                                                <optgroup label="{{$category->name}}">
                                                    @foreach($category->attributes as $attribute)
                                                        <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="col-sm-10 tab-content" id="attributeValues">
                                    @if(isset($result->id))
                                        @foreach($oldattribute->groupBy('attribute_id') as $k=>$v)
                                            <div class="tab-pane" id="tabVerticalLeft{{$v[0]->attribute_id}}"
                                                 aria-labelledby="baseVerticalLeft-tab{{$v[0]->attribute_id}}">

                                                <div class="form-group">
                                                    <label class="">{{__('Required')}}</label>
                                                    <select name="attribute[{{$v[0]->attribute_id}}][required]" class="form-control">
                                                        <option value="0" {{(($v[0]->required=='0')?'selected':null)}}>{{__('No')}}</option>
                                                        <option value="1" {{(($v[0]->required=='1')?'selected':null)}}>{{__('Yes')}}</option>
                                                    </select>
                                                </div>

                                                @if($v[0]->type == 'text')
                                                    <div class="form-group">
                                                        <label class="">{{$v[0]->name}}</label>
                                                        <input type="text" name="attribute[{{$v[0]->attribute_id}}][{{$v[0]->selected_attribute_value}}]" class="form-control">
                                                    </div>
                                                @elseif($v[0]->type == 'textarea')
                                                    <div class="form-group">
                                                        <label class="">{{$v[0]->name}}</label>
                                                        <textarea name="attribute[{{$v[0]->attribute_id}}][{{$v[0]->selected_attribute_value}}]" class="form-control"></textarea>
                                                    </div>
                                                @elseif(in_array($v[0]->type,['select','radio','checkbox']))
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <td>{{__('Option Value')}}</td>
                                                            <td>{{__('Stock Availability')}}</td>
                                                            <td>{{__('Quantity')}}</td>
                                                            <td>{{__('Price')}}</td>
                                                            <td>{{__('Remove')}}</td>
                                                        </tr>
                                                        @foreach($v as $oneattribute)
                                                            <tr>
                                                                <td>
                                                                    <select name="attribute[{{$v[0]->attribute_id}}][val][]" class="form-control">
                                                                        @foreach($oldattributevalues->where('id',$oneattribute->attribute_id)->first()->attributeValue as $values)
                                                                            <option value="{{$values->id}}" {{(($values->id==$oneattribute['selected_attribute_value'])?'selected':null)}}>{{$values->text}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select class="form-control" name="attribute[{{$oneattribute->attribute_id}}][stock][]">
                                                                            <option value="0" {{((!$oneattribute->stock=='0')?'selected':null)}}>{{__('No')}}</option>
                                                                            <option value="1" {{(($oneattribute->stock=='1')?'selected':null)}}>{{__('Yes')}}</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <input class="form-control" name="attribute[{{$oneattribute->attribute_id}}][qty][]" value="{{$oneattribute->quantity}}" type="number">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control" style="width:50%;" name="attribute[{{$oneattribute->attribute_id}}][pricetype][]">
                                                                            <option value="+">+</option>
                                                                            <option value="-" {{(($oneattribute->plus_price<0)?'selected':null)}}>-</option>
                                                                        </select>
                                                                        <input value="{{abs($oneattribute->plus_price)}}" class="form-control" style="width:50%;" name="attribute[{{$oneattribute->attribute_id}}][price][]" type="text">
                                                                        <span class="input-group-addon">{{__('LE')}}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a href="javascript:void(0)" class="btn btn-danger fa fa-trash" onclick="$(this).parents('tr').remove()"></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="4"></td>
                                                            <td>
                                                                <a href="javascript:void(0)" class="btn btn-success fa fa-plus" onclick="NewAttributeRow({'yes':'{{__('Yes')}}','no':'{{__('No')}}','LE':'{{__('LE')}}'},this,'{{$v[0]->attribute_id}}','{{request()->fullUrl()}}');"></a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-body pt-1">
                            <div class="card-block card-dashboard">
                                {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
    </div>
    <!--/ Javascript sourced data -->
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <!--  -->

@endsection

@section('footer')
    <script src="{{asset('assets/system/js/scripts/navs/navs.js')}}"></script>
    <script src="{{asset('assets/merchant/product/create.product.js')}}"></script>
    <script>
        $(function () {
            $('body').on('click', '.delimagerow', function () {
                $(this).closest('div').remove();
            });

            $('.addinputimg').on('click',function(){AddUploadImage();});

        });

        $(function () {
            $('#attribute').on('change', function(){
                var attrId = $(this).val();
                if ($('a#baseVerticalLeft-tab' + attrId).length) {
                    $('a#baseVerticalLeft-tab' + attrId).trigger('click');
                    return;
                }
                VerticalTabContent({'tr':['{{__('Option Value')}}','{{__('Stock Availability')}}','{{__('Quantity')}}','{{__('Price')}}','{{__('Add/Remove')}}'],'required':'{{__('Required')}}','no':'{{__('No')}}','yes':'{{__('Yes')}}','LE':'{{__('LE')}}'},this,'{{request()->fullUrl()}}');
            });
        });
    </script>

    @if(isset($result->id))
        <script>
            $(function () {
                $('.nav-tabs.nav-left li:last a').trigger('click');
            });
        </script>
    @endif
@endsection