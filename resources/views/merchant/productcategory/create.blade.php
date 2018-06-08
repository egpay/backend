@extends('merchant.layouts')

@section('header')

@endsection

@section('content')
            <div class="card">
                <div class="card-header">
                    <h2>{{$pageTitle}}</h2>
                </div>
            </div>

            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    {!! Form::open(['route' => isset($result->id) ? ['panel.merchant.product-category.update',$result->id]:'panel.merchant.product-category.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('English Data')}}</h2>
                                </div>
                                <div class="card-block card-dashboard">

                                    <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('name_en', __('Category name').':') !!}
                                            {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'name_en') !!}
                                    </div>

                                    <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('description_en', __('Category description').':') !!}
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
                                            {!! Form::label('name_ar', __('Category Name').':') !!}
                                            {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                        </div>
                                        {!! formError($errors,'name_ar') !!}
                                    </div>

                                    <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('description_ar', __('Category description4').':') !!}
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
                                    <h2>{{__('Category icon')}}</h2>
                                </div>
                                <div class="card-block card-dashboard">
                                    <div class="form-group col-sm-6{!! formError($errors,'icon',true) !!}">
                                        @if(isset($productcategory))
                                            <img src="{{asset('')}}{{$result->icon}}" class="img-responsive">
                                        @endif
                                        <div class="controls">
                                            {!! Form::label('icon', __('Category icon').':') !!}
                                            @if(isset($result->icon))
                                                <img src="{{asset('storage/'.imageResize($result->icon,70,70))}}">
                                            @endif
                                            <label class="custom-file center-block block">
                                                {!! Form::file('icon',['class'=>'custom-file-input']) !!}
                                                <span class="custom-file-control"></span>
                                            </label>
                                        </div>
                                        {!! formError($errors,'icon') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('status', __('Category Status').':') !!}                                            {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
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
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('footer')
@endsection