@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
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
                            {!! Form::open(['route' => 'payment.recharge-list.store','method' => 'POST','files' => true]) !!}

                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">



                                        <div class="form-group col-sm-12{!! formError($errors,'merchant_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_id', __('Merchant').':') !!}
                                                {!! Form::select('merchant_id',[''=>__('Select Merchant')],null,['style'=>'width: 100%;' ,'id'=>'merchantSelect2','class'=>'form-control col-md-12']) !!}
                                            </div>
                                            {!! formError($errors,'merchant_id') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'start_at',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('start_at', __('Start At').':') !!}
                                                {!! Form::select('start_at',['immediately'=>__('Immediately'),'custom'=>__('Custom')],null,['class'=>'form-control','onchange'=>'change_start_at();']) !!}
                                            </div>
                                            {!! formError($errors,'start_at') !!}
                                        </div>


                                        <div id="cron_jobs_div" style="display: none;" class="form-group col-sm-12{!! formError($errors,'cron_jobs',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('cron_jobs', __('Start Date & Time').':') !!}
                                                {!! Form::text('cron_jobs',null,['class'=>'form-control datepicker']) !!}
                                            </div>
                                            {!! formError($errors,'cron_jobs') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'numbers',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('numbers', __('Numbers ( Excel File )').':') !!} <a href="{{asset('storage/recharge-list-template.xlsx')}}">{{__('View Template')}}</a>
                                                {!! Form::file('numbers',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'numbers') !!}
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

    <script type="text/javascript">
        ajaxSelect2('#merchantSelect2','merchant');


        function change_start_at(){
            $value = $('#start_at').val();
            if($value == 'immediately'){
                $('#cron_jobs_div').hide();
            }else{
                $('#cron_jobs_div').show();
            }
        }

        $(function(){
            change_start_at();
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD HH:mm:SS'
            });
        });
    </script>

@endsection