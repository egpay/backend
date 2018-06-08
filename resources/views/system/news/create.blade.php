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
                            {!! Form::open(['route' => isset($result->id) ? ['system.news.update',$result->id]:'system.news.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
                            <div class="col-sm-12">
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
                                        <div class="form-group col-sm-12{!! formError($errors,'content_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('content_en', __('Content (English)').':') !!}
                                                {!! Form::textarea('content_en',isset($result->id) ? $result->content_en:old('content_en'),['class'=>'form-control','id'=>'content_en']) !!}
                                            </div>
                                            {!! formError($errors,'content_en') !!}
                                        </div>



                                    </div>
                                </div>
                            </div>









                            <div class="col-sm-12">
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
                                        <div class="form-group col-sm-12{!! formError($errors,'content_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('content_ar', __('Content (Arabic)').':') !!}
                                                {!! Form::textarea('content_ar',isset($result->id) ? $result->content_ar:old('content_ar'),['class'=>'form-control ar','id'=>'content_ar']) !!}
                                            </div>
                                            {!! formError($errors,'content_ar') !!}
                                        </div>



                                    </div>
                                </div>
                            </div>





                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-block card-dashboard">


                                        <div class="form-group col-sm-12{!! formError($errors,'image',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('image', __('Image').':') !!}
                                                {!! Form::file('image',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'image') !!}
                                        </div>



                                        <div class="form-group col-sm-6{!! formError($errors,'news_category_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('news_category_id', __('Category Status').':') !!}
                                                {!! Form::select('news_category_id',$categories,isset($result->id) ? $result->news_category_id:old('news_category_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'news_category_id') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Category Status').':') !!}
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
    <script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>

    <script type="text/javascript">
        CKEDITOR.replace('content_ar');
        CKEDITOR.replace('content_en');
    </script>
@endsection