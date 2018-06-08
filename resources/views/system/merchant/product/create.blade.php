@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
@endsection
@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            @if($errors->any())
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-danger">
                                            {{__('Some fields are invalid please fix them')}}
                                        </div>
                                    </div>
                                </div>
                            @elseif(Session::has('status'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-{{Session::get('status')}}">
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {!! Form::open(['route' => isset($result->id) ? ['merchant.product.update',$result->id]:'merchant.product.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Merchant')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-6 {!! formError($errors,'merchant_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_id', __('Merchant').':') !!}
                                                @if(isset($merchantData))
                                                    {!! Form::text('merchant_text', $merchantData->{'name_'.$systemLang}.' #ID: '.$merchantData->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                    {!! Form::hidden('merchant_id',null,['id'=>'new_merchant_id']) !!}
                                                @else
                                                    @if(isset($result->id))
                                                        {!! Form::select('merchant_id',[$result->merchant->id => $result->merchant->{'name_'.$systemLang}.' #ID: '.$result->merchant_id],isset($result->id) ? $result->merchant_id:old('merchant_id'),['class'=>'select2 form-control']) !!}
                                                    @else
                                                        {!! Form::select('merchant_id',(isset($current_merchant)) ? [old('merchant_id')=>$current_merchant->{'name_'.$systemLang}.' #ID:'.$current_merchant->id] : [__('Select Merchant')],old('merchant_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                    @endif
                                                @endif
                                            </div>
                                            {!! formError($errors,'merchant_id') !!}
                                        </div>
                                        <div class="form-group col-sm-6{!! formError($errors,'merchant_product_category_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_product_category_id', __('Product Category').':') !!}
                                                @if(isset($result->id))
                                                    {!! Form::select('merchant_product_category_id',$MerchantProductCategory,isset($result->id) ? $result->merchant_product_category_id:old('merchant_product_category_id'),['class'=>'select2 form-control']) !!}
                                                @else
                                                    {!! Form::select('merchant_product_category_id',$MerchantProductCategory,old('merchant_product_category_id'),['class'=>'select2 form-control']) !!}
                                                @endif
                                            </div>
                                            {!! formError($errors,'merchant_product_category_id') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('English Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_en', __('Product Name (English)').':') !!}
                                                {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_en') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_en', __('Product description (English)').':') !!}
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
                                                {!! Form::label('name_ar', __('Product Name (Arabic)').':') !!}
                                                {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'name_ar') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_ar', __('Product description (Arabic)').':') !!}
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
                                        <div class="col-sm-6">
                                            <h2>{{__('Product Images')}}</h2>
                                            @if(formError($errors,'file.*',true))
                                                <p class="text-xs-left"><small class="danger text-muted">{{__('Error File Upload')}}</small></p>
                                            @endif
                                        </div>
                                        <div style="text-align: right;" class="col-sm-6">
                                            <button type="button" class="btn btn-primary fa fa-plus addinputfile">
                                                <span>{{__('Add File')}}</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="uploaddata">
                                            @if(isset($result->id))
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>{{__('File')}}</th>
                                                            <th>{{__('Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($result->upload as $key => $value)
                                                            <tr>
                                                                <th scope="row">{{$key+1}}</th>
                                                                <td><a href="{{asset('storage/'.$value->path)}}">
                                                                        @if(empty($value->title))
                                                                            [FILE]
                                                                        @else
                                                                            {{$value->title}}
                                                                        @endif
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-danger" onclick=""><i class="fa fa-trash"></i></a>
                                                                    {{--{{route('upload.delete',$value->id)}}--}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
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
                                        <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Merchant Status').':') !!}
                                                {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            ajaxSelect2('#merchant_id','merchant');
        });

        $('#merchant_id').change(function(){

            // Category
            $.getJSON('{{route('system.ajax.get')}}',{
                'type': 'getProductCategory',
                'merchant_id': $(this).val()
            },function($data){
                $newData = new Array;
                $newData.push('<option value="">{{__('Select Product Category')}}</option>');
                $.each($data,function(key,value){
                    $newData.push('<option value="'+value.id+'">'+value.name+'</option>');
                });

                $('#merchant_product_category_id').html($newData.join("\n"));
            });

        });

    </script>
@endsection