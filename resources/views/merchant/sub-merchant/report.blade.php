@extends('merchant.layouts')

@section('content')

    <div class="card">
        <div class="card-header">
            <h2>{{$pageTitle}}</h2>
        </div>
    </div>

    <div class="content-body">
        <!-- Server-side processing -->
        <div id="server-processing">
            <div class="row">
                {!! Form::open(['route' => 'panel.merchant.sub-merchant.report', 'method' => 'POST']) !!}
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-block card-dashboard">

                            <div class="form-group col-sm-12{!! formError($errors,'merchant_id',true) !!}">
                                <div class="controls">
                                    {!! Form::label('merchant_id', __('Merchant').':') !!}
                                    {!! Form::select('merchant_id',$submerchants,old('merchant_id'),['style'=>'width: 100%;','class'=>'form-control','id'=>'merchant_id']) !!}
                                </div>
                                {!! formError($errors,'merchant_id') !!}
                            </div>


                            <div class="form-group col-sm-6{!! formError($errors,'from_date',true) !!}">
                                <div class="controls">
                                    {!! Form::label('from_date', __('From Date').':') !!}
                                    {!! Form::text('from_date',isset($result->id) ? $result->fromdate:old('from_date'),['class'=>'form-control datepicker']) !!}
                                </div>
                                {!! formError($errors,'from_date') !!}
                            </div>

                            <div class="form-group col-sm-6{!! formError($errors,'to_date',true) !!}">
                                <div class="controls">
                                    {!! Form::label('to_date', __('To Date').':') !!}
                                    {!! Form::text('to_date',isset($result->id) ? $result->todate:old('to_date'),['class'=>'form-control datepicker']) !!}
                                </div>
                                {!! formError($errors,'to_date') !!}
                            </div>


                            <div class="row">
                                {!! Form::submit(__('Generate report'),['class'=>'btn btn-success pull-right mr-2']) !!}
                            </div>

                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
            <!--/ Javascript sourced data -->
        </div>
        @if(isset($result))
            <div class="content-detached">
                <div class="content-body">
                <section class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-head">
                                <div class="card-header">
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="px-1">
                                    <ul class="list-inline list-inline-pipe text-xs-center p-1" style="margin-bottom: 0px !important;padding-bottom: 0px !important;">
                                        <li>

                                            @if($result->type == 'payment')
                                                <lable class="label label-warning">{{ucfirst($result->type)}}</lable>
                                            @else
                                                <lable class="label label-danger">{{ucfirst($result->type)}}</lable>
                                            @endif

                                        </li>
                                    </ul>
                                    <ul class="list-inline list-inline-pipe text-xs-center p-1 border-bottom-grey border-bottom-lighten-3">
                                        <li>{{__('Created From')}}: <span class="text-muted">{{$result->created_at->diffForHumans()}}</span></li>
                                        <li>{{__('Last Update')}}: <span class="text-muted">{{$result->updated_at->diffForHumans()}}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- project-info -->
                            <div id="project-info" class="card-block row">
                                <div class="project-info-count col-lg-6 col-md-12">
                                    <div class="project-info-icon">
                                        <h2>{{number_format($result->balance)}} {{__('LE')}}</h2>
                                        <div class="project-info-sub-icon">
                                            <span class="fa fa-money"></span>
                                        </div>
                                    </div>
                                    <div class="project-info-text pt-1">
                                        <h5>{{__('Balance')}}</h5>
                                    </div>
                                </div>
                                <div class="project-info-count col-lg-6 col-md-12">
                                    <div class="project-info-icon">
                                        <h2>{{count($transactions)}}</h2>
                                        <div class="project-info-sub-icon">
                                            <span class="fa fa-info"></span>
                                        </div>
                                    </div>
                                    <div class="project-info-text pt-1">
                                        <h5>{{__('Number of transactions')}}</h5>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </section>
            </div>
        </div>
        @endif
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">

@endsection


@section('footer')
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>

    <script src="{{asset('assets/system/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script>
        $('#merchant_id').select2({
            placeholder: '{{__('Select Product')}}'
        });

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });
    </script>
@endsection