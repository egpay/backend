@extends('system.layouts')
<!-- Modal -->
<div class="modal fade text-xs-left" id="filter-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Filter')}}</label>
            </div>
            {!! Form::open(['onsubmit'=>'filterFunction($(this));return false;']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('created_at1',__('Created From')) }}
                                    {!! Form::text('created_at1',null,['class'=>'form-control datepicker','id'=>'created_at1']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('created_at2',__('Created To')) }}
                                    {!! Form::text('created_at2',null,['class'=>'form-control datepicker','id'=>'created_at2']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('total_amount1',__('Total Amount From')) }}
                                    {!! Form::number('total_amount1',null,['class'=>'form-control','id'=>'total_amount1']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('total_amount2',__('Total Amount To')) }}
                                    {!! Form::number('total_amount2',null,['class'=>'form-control','id'=>'total_amount2']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('payment_service_id',__('Payment service')) }}
                                    {!! Form::select('payment_service_id',$paymentServices,null,['class'=>'form-control']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::select('status',[__('Select Status'),'pending'=>__('Pending'),'paid'=>__('Paid'),'reverse'=>__('Reverse')],null,['class'=>'form-control']) !!}
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="reset" class="btn btn-outline-secondary btn-md" value="{{__('Reset Form')}}">
                <input type="submit" class="btn btn-outline-primary btn-md" value="{{__('Filter')}}">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                        <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
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
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{$pageTitle}}</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a onclick="updateData();"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>{{__('Service Name')}}</th>
                                                <th>{{__('Total Amount')}}</th>
                                                <th>{{__('Count')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $total = 0;
                                                $count = 0;
                                                @endphp
                                                @foreach($result as $key => $value)
                                                    @php
                                                        $total+= $value->total_amount;
                                                        $count+= $value->count;
                                                    @endphp
                                                    <tr>
                                                        <td>{{$value->payment_service_providers_name}} {{$value->payment_services_name}}</td>
                                                        <td>{{$value->total_amount}}</td>
                                                        <td>{{$value->count}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td><b>{{__('Total')}}</b></td>
                                                    <td>{{amount($total,true)}}</td>
                                                    <td>{{number_format($count)}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
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
    <script src="{{asset('assets/system/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
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
        function filterFunction($this){
            if($this == false) {
                $url = '{{url()->full()}}';
            }else {
                $url = '{{url()->full()}}?'+$this.serialize();
            }

            window.location.href = $url;
        }

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });

    </script>
@endsection
