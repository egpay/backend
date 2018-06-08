@extends('merchant.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
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
            <div class="row">
                    @if($errors->any())
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="alert alert-danger">
                                    {{__('Some fields are invalid please fix them')}}
                                </div>
                            </div>
                        </div>
                    @endif
                    {!! Form::open(['route' => isset($result->id) ? ['panel.merchant.merchant-knowledge.update',$result->id]:'panel.merchant.merchant-knowledge.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
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
                                        {!! Form::textarea('content_en',isset($result->id) ? $result->content_en:old('content_en'),['class'=>'form-control']) !!}
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
                                        {!! Form::textarea('content_ar',isset($result->id) ? $result->content_ar:old('content_ar'),['class'=>'form-control ar']) !!}
                                    </div>
                                    {!! formError($errors,'content_ar') !!}
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
            {!! Form::close() !!}
        </section>
        <!--/ Javascript sourced data -->
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