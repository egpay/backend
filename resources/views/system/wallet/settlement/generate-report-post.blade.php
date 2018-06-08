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

                                        <div class="card-body">
                                            <div class="col-md-12">
                                                <table style="background-color: antiquewhite;" class="table table-bordered table-striped">
                                                    <tbody>
                                                    <tr>
                                                        <td>{{__('From Date Time')}}</td>
                                                        <td>{{$formData['created_at1']}}</td>
                                                        <td>{{__('To Date Time')}}</td>
                                                        <td>{{$formData['created_at2']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('Owner Type')}}</td>
                                                        <td>{{$formData['model_type']}}</td>

                                                        <td>{{__('Owner ID')}}</td>
                                                        <td>{{$formData['model_id'] ?? '--'}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="nav-vertical">
                                                <ul class="nav nav-tabs nav-left nav-border-left">

                                                    @foreach($result as $key => $value)
                                                        <li class="nav-item">
                                                            <a class="nav-link @if($key == 0)active @endif" id="baseVerticalLeft1-tab{{$key}}"
                                                               data-toggle="tab" aria-controls="tabVerticalLeft1{{$key}}"
                                                               href="#tabVerticalLeft1{{$key}}" onclick="paymentSettlementAjax('{{$key}}','{{$value['from']}}','{{$value['to']}}')" aria-expanded="true">{{$value['from']}} <br> {{$value['to']}}</a>
                                                        </li>
                                                    @endforeach

                                                </ul>
                                                <div class="tab-content px-1">
                                                    @foreach($result as $key => $value)
                                                        <div role="tabpanel" class="tab-pane @if($key == 0)active @endif" id="tabVerticalLeft1{{$key}}"
                                                             aria-expanded="true"
                                                             aria-labelledby="baseVerticalLeft1-tab{{$key}}">
                                                            {{__('Loading')}}...
                                                        </div>
                                                    @endforeach

                                                </div>
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

    <style type="text/css">
        a.panel-heading {
            display: block;
        }

        .panel-primary .panel-heading[aria-expanded="true"], .panel-primary .panel-heading a:hover, .panel-primary .panel-heading a:focus, .panel-primary a.panel-heading:hover, .panel-primary a.panel-heading:focus {
            background-color: #286090;
        }

        .panel-danger .panel-heading[aria-expanded="true"], .panel-danger .panel-heading a:hover, .panel-danger .panel-heading a:focus, .panel-danger a.panel-heading:hover, .panel-danger a.panel-heading:focus {
            background-color: #c9302c;
        }

        .panel-default .panel-heading[aria-expanded="true"], .panel-default .panel-heading a:hover, .panel-default .panel-heading a:focus, .panel-default a.panel-heading:hover, .panel-default a.panel-heading:focus {
            background-color: #dcdcdc;
        }

        .panel-info .panel-heading[aria-expanded="true"], .panel-info .panel-heading a:hover, .panel-info .panel-heading a:focus, .panel-info a.panel-heading:hover, .panel-info a.panel-heading:focus {
            background-color: #31b0d5;
        }

        .panel-success .panel-heading[aria-expanded="true"], .panel-success .panel-heading a:hover, .panel-success .panel-heading a:focus, .panel-success a.panel-heading:hover, .panel-success a.panel-heading:focus {
            background-color: #449d44;
        }

        .panel-warning .panel-heading[aria-expanded="true"], .panel-warning .panel-heading a:hover, .panel-warning .panel-heading a:focus, .panel-warning a.panel-heading:hover, .panel-warning a.panel-heading:focus {
            background-color: #ec971f;
        }

        .panel-group .panel, .panel-group .panel-heading {
            border: none !important;
        }

        .panel-group .panel-body {
            border: 1px solid #ddd !important;
            border-width: 0 1px 1px 1px !important;
        }

        .panel-group .panel-heading a, .panel-group a.panel-heading {
            outline: 0;
        }

        .panel-group .panel-heading a:hover, .panel-group .panel-heading a:focus, .panel-group a.panel-heading:hover, .panel-group a.panel-heading:focus {
            text-decoration: none;
        }

        .panel-group .panel-heading .icon-indicator {
            margin-right: 10px;
        }

        .panel-group .panel-heading .icon-indicator:before {
            content: "\e114";
        }

        .panel-group .panel-heading.collapsed .icon-indicator:before {
            content: "\e080";
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

@endsection;


@section('footer')

    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js"
            type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}"
            type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">

        $(document).ready(function(){
           $('#baseVerticalLeft1-tab0').click();
        });

        loadedKey = [];
        function paymentSettlementAjax(DOMID,$from,$to){
            if(isset(loadedKey[DOMID])) return true;
            $.get('{{route('system.settlement.generate-report-ajax')}}',{
                'from': $from,
                'to': $to,
                'model_type': '{{str_replace('\\','\\\\',$formData['model_type'])}}',
                'model_id': '{{$formData['model_id']}}'
            },function(response){

                console.log(response);

                loadedKey[DOMID] = true;
                $('#tabVerticalLeft1'+DOMID).html(response);
            });
        }

        $(function () {
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });

    </script>
@endsection
