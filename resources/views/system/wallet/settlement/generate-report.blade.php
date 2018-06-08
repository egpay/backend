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
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class="content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
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
                                            {!! Form::open(['route' => 'system.settlement.generate-report-port', 'method' => 'POST']) !!}
                                            <div class="col-sm-12">
                                                <div class="card" style="margin-bottom: 0px;">
                                                    <div class="card-block card-dashboard">

                                                        <div class="form-group col-sm-6{!! formError($errors,'created_at1',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('created_at1', __('From Date Time').':') !!}
                                                                {!! Form::text('created_at1',null,['class'=>'form-control datepicker']) !!}
                                                            </div>
                                                            {!! formError($errors,'created_at1') !!}
                                                        </div>

                                                        <div class="form-group col-sm-6{!! formError($errors,'created_at2',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('created_at2', __('To Date Time').':') !!}
                                                                {!! Form::text('created_at2',null,['class'=>'form-control datepicker']) !!}
                                                            </div>
                                                            {!! formError($errors,'created_at2') !!}
                                                        </div>

                                                        @php
                                                            $walletOwnerTypeArray = [''=>__('Select Owner Type')];
                                                            foreach ($walletUserType as $key => $value){
                                                                $walletOwnerTypeArray[$value] = __(ucfirst($key));
                                                            }
                                                        @endphp

                                                        <div class="form-group col-sm-6{!! formError($errors,'model_type',true) !!}">
                                                            <div class="controls">
                                                                {{ Form::label('model_type',__('Owner Type')) }}
                                                                {!! Form::select('model_type',$walletOwnerTypeArray,null,['class'=>'form-control']) !!}
                                                            </div>
                                                            {!! formError($errors,'model_type') !!}
                                                        </div>

                                                        <div class="form-group col-sm-6{!! formError($errors,'model_id',true) !!}">
                                                            <div class="controls">
                                                                {{ Form::label('model_id',__('Owner ID')) }}
                                                                {!! Form::number('model_id',null,['class'=>'form-control']) !!}
                                                            </div>
                                                            {!! formError($errors,'model_id') !!}
                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="card-block card-dashboard">
                                                            {!! Form::submit(__('Generate Report'),['class'=>'btn btn-success pull-right col-md-12']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection




@section('header')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

@endsection;


@section('footer')

    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });

    </script>
@endsection
