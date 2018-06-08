@extends('system.layouts')

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
                            {!! Form::open(['route' => isset($result->id) ? ['system.call-tracking.update',$result->id]:'system.call-tracking.store', 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Call data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-8{!! formError($errors,'phone_number',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('phone_number', __('Phone number').':') !!}
                                                {!! Form::text('phone_number',isset($result->id) ? $result->phone_number:old('phone_number'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'phone_number') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('type', __('Call type').':') !!}
                                                {!! Form::select('type',['in'=>__('Call-In'),'out'=>__('Call-Out')],isset($result->id) ? $result->type:old('type'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'type') !!}
                                        </div>

                                        <div class="mt-1 form-group col-sm-8{!! formError($errors,'caller_name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('caller_name', __('Caller name').':') !!}
                                                {!! Form::text('caller_name',isset($result->id) ? $result->caller_name:old('caller_name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'caller_name') !!}
                                        </div>

                                        <div class="mt-1 form-group col-sm-4{!! formError($errors,'calltime',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('calltime', __('Call time').':') !!}
                                                {!! Form::text('calltime',isset($result->id) ? $result->calltime:old('calltime'),['class'=>'form-control datepicker','id'=>'calltime']) !!}
                                            </div>
                                            {!! formError($errors,'calltime') !!}
                                        </div>

                                        <div class="mt-1 form-group col-sm-12{!! formError($errors,'details',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('details', __('Call details').':') !!}
                                                {!! Form::textarea('details',isset($result->id) ? $result->details:old('details'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'details') !!}
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
                <!--/ Javascript sourced data -->
            </div>
            </section>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
@endsection

@section('footer')

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>

    <script>
        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'days',
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });

    </script>
@endsection