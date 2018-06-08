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
                            {!! Form::open(['route' => isset($result->id) ? ['system.banks.update',$result->id]:'system.banks.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
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
                                    </div>
                                </div>
                            </div>





                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-block card-dashboard">


                                        <div class="form-group col-sm-4{!! formError($errors,'logo',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('logo', __('Logo').':') !!}
                                                {!! Form::file('logo',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'icon') !!}
                                        </div>


                                        <div class="form-group col-sm-4{!! formError($errors,'swift_code',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('swift_code', __('Swift Code').':') !!}
                                                {!! Form::text('swift_code',isset($result->id) ? $result->swift_code:old('swift_code'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'swift_code') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'account_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('account_id', __('Bank Account ID').':') !!}
                                                {!! Form::number('account_id',isset($result->id) ? $result->account_id:old('account_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'account_id') !!}
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
@endsection