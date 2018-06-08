@extends('system.layouts')

@section('header')
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
                                @if(isset($result))
                                    {!! Form::model($result,['route' => ['merchant.product-attributes.update',$result->id],'method' => 'PATCH']) !!}
                                @else
                                    {!! Form::open(['route' => 'merchant.product-attributes.store', 'method' => 'POST']) !!}
                                @endif
                                <div class="col-sm-12 card">
                                    <div class="form-group col-sm-12{!! formError($errors,'attribute_category_id',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('attribute_category_id', __('Product attribute category').':') !!}
                                            {!! Form::select('attribute_category_id',$attribute_categories,old('attribute_category_id'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'attribute_category_id') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'type',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('type', __('Attribute Type').':') !!}
                                            {!! Form::select('type',$type,old('type'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'type') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'multi_lang',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('multi_lang', __('Support for Multi-Language').':') !!}
                                            {!! Form::select('multi_lang',$multi,old('multi_lang'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'multi_lang') !!}
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
                                                    {!! Form::label('name_en', __('Product attribute name (English)').':') !!}
                                                    {!! Form::text('name_en',old('name_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_en') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_en', __('Product attribute description (English)').':') !!}
                                                    {!! Form::textarea('description_en',old('description_en'),['class'=>'form-control']) !!}
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
                                                    {!! Form::label('name_ar', __('Product attribute Name (Arabic)').':') !!}
                                                    {!! Form::text('name_ar',old('name_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Product attribute description (Arabic)').':') !!}
                                                    {!! Form::textarea('description_ar',old('description_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'description_ar') !!}
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <table class="table attrvalues">
                                        <thead>
                                            <tr>
                                                <td>{{__('Value (English)')}}</td>
                                                <td class="multi">{{__('Value (Arabic)')}}</td>
                                                <td>{{__('Default')}}</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($result))
                                            @php
                                                $num = 0;
                                            @endphp
                                        @foreach($result->attributeValue as $attrValue)
                                            <tr>
                                                <td>
                                                    {!! Form::text('value_name_en[]',$attrValue->text_en,['class'=>'form-control']) !!}
                                                </td>
                                                <td class="multi">
                                                    {!! Form::text('value_name_ar[]',$attrValue->text_ar,['class'=>'form-control ar']) !!}
                                                </td>
                                                <td class="pt-2">
                                                    {!! Form::radio('value_default[]',$num,$attrValue->is_default) !!}
                                                </td>
                                            </tr>
                                            @php
                                                $num++;
                                            @endphp
                                        @endforeach
                                        @else
                                            @if(old('value_name_en'))

                                                @for($x=0;$x<count(old('value_name_en'));$x++)
                                                    <tr>
                                                        <td>
                                                            {!! Form::text('value_name_en[]',old('value_name_en'.$x),['class'=>'form-control']) !!}
                                                        </td>
                                                        <td class="multi">
                                                            {!! Form::text('value_name_ar[]',old('value_name_ar'.$x),['class'=>'form-control ar']) !!}
                                                        </td>
                                                        <td class="pt-2">
                                                            {!! Form::radio('value_default[]',$x,((old('value_default'.$x))?1:0)) !!}
                                                        </td>
                                                    </tr>
                                                @endfor
                                            @else
                                                <tr>
                                                    <td>
                                                        {!! Form::text('value_name_en[]',null,['class'=>'form-control']) !!}
                                                    </td>
                                                    <td class="multi">
                                                        {!! Form::text('value_name_ar[]',null,['class'=>'form-control ar']) !!}
                                                    </td>
                                                    <td class="pt-2">
                                                        {!! Form::radio('value_default[]',0,0) !!}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td class="multi"></td>
                                                <td><a href="javascript:void(0);" onclick="newAttrRow();"><i class="fa fa-plus-circle fa-lg"></i></a> </td>
                                            </tr>
                                        </tfoot>
                                    </table>
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

    <script>
        $('#multi_lang').on('change',function(){
            if($(this).val() == 'in-active'){
                $('td.multi').hide();
            } else {
                $('td.multi').show();
            }
        });

        function newAttrRow(){
            var radioVal = parseInt($('table.attrvalues tbody tr:last').find('input[type="radio"]').val()) + 1;
            $('table.attrvalues tbody tr:last').clone()
                .find('input').removeAttr('value').end()
                .find('input').val('').end()
                .find('input[type="radio"]').val(radioVal).end()
                .appendTo($('table.attrvalues tbody'));

        }
    </script>

@endsection