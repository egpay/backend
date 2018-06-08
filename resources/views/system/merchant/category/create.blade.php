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
                            {!! Form::open(['route' => isset($result->id) ? ['merchant.category.update',$result->id]:'merchant.category.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('English Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">



                                        <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_en', __('Name (English)').':') !!}
                                                {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_en') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_en', __('Category Description (English)').':') !!}
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
                                        <h2>{{__('Arabic Info')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">



                                        <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_ar', __('Name (Arabic)').':') !!}
                                                {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'name_ar') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_ar', __('Category Description (Arabic)').':') !!}
                                                {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'description_ar') !!}
                                        </div>



                                    </div>
                                </div>
                            </div>



                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-4{!! formError($errors,'icon',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('icon', __('Icon').':') !!}

                                                @if(isset($result->id) && !empty($result->icon))
                                                    <a href="{{asset('storage/app/'.$result->icon)}}" target="_blank">{{__('View')}}</a>
                                                @endif

                                                {!! Form::file('icon',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'icon') !!}
                                        </div>


                                        <div class="form-group col-sm-4{!! formError($errors,'main_category_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('main_category_id', __('Main Category').':') !!}
                                                {!! Form::select('main_category_id',[''=>__('Main Category')]+$mainCategory,isset($result->id) ? $result->main_category_id:old('main_category_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'main_category_id') !!}
                                        </div>


                                        <div class="form-group col-sm-4{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Category Status').':') !!}
                                                {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>



                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <h2 class="mb-2">{{__('Product attribute categories')}}</h2>
                                @foreach($AttrCategories as $AttrCategory)
                                    <label class="col-sm-4">
                                        {!! Form::checkbox("attribute_categories[]", "$AttrCategory->id", ((isset($result) && (is_array($result->attribute_categories)))?((in_array($AttrCategory->id,$result->attribute_categories))?true:false):false)) !!}
                                        {!! $AttrCategory->name !!}
                                        <p>
                                            {!! $AttrCategory->description !!}
                                        </p>
                                    </label>
                                @endforeach
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
                    </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
@endsection