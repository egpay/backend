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
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-8">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('SDK')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">


                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr>
                                                <td>{{__('ID')}}</td>
                                                <td>{{$result->id}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Service')}}</td>
                                                <td>
                                                    <a href="{{route('payment.services.show',$result->payment_service->id)}}">{{$result->payment_service->{'name_'.$systemLang} }}</a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Service Type')}}</td>
                                                <td>{{humanStr($result->service_type)}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Name')}}</td>
                                                <td>{{$result->name}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Description')}}</td>
                                                <td>{{$result->description}}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" style="text-align: center;background-color: antiquewhite;">{{__('Third Party')}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('External System ID')}}</td>
                                                <td>{{$result->external_system_id}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Price Type')}}</td>
                                                <td>{{$result->price_type}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Service Value')}}</td>
                                                <td>{{$result->service_value}}</td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Service Value List')}}</td>
                                                <td>{{$result->service_value_list}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Min Value')}}</td>
                                                <td>{{$result->min_value}}</td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Max Value')}}</td>
                                                <td>{{$result->max_value}}</td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Commission Type')}}</td>
                                                <td>{{$result->commission_type}}</td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Commission Value Type')}}</td>
                                                <td>{{$result->commission_value_type}}</td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Fixed Commission')}}</td>
                                                <td>{{$result->fixed_commission}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Default Commission')}}</td>
                                                <td>{{$result->default_commission}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('From Commission')}}</td>
                                                <td>{{$result->from_commission}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                <td>
                                                    <a href="{{route('system.staff.show',$result->staff_id)}}" target="_blank">{{$result->staff->firstname}} {{$result->staff->lastname}}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>{{$result->created_at->diffForHumans()}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>{{$result->updated_at->diffForHumans()}}</td>
                                            </tr>


                                            </tbody>
                                        </table>


                                    </div>
                                </div>

                            </div>
                        </section>
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Parameters')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">


                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach($result->payment_service_api_parameters as $key => $value)
                                                    <tr>
                                                        <td>{{$value->id}}</td>
                                                        <td>{{$value->{'name_'.$systemLang} }}</td>
                                                        <td>

                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="ft-cog icon-left"></i>
                                                                <span class="caret"></span></button>
                                                                <ul class="dropdown-menu">
                                                                    <li class="dropdown-item"><a href="{{route('payment.service-api-parameters.show',$value->id)}}">{{__('View')}}</a></li>
                                                                    <li class="dropdown-item"><a href="{{route('payment.service-api-parameters.edit',$value->id)}}">{{__('Edit')}}</a></li>
                                                                    <li class="dropdown-item"><a onclick="deleteRecord({{route('payment.service-api-parameters.destroy',$value->id)}})" href="javascript:void(0)">{{__('Delete')}}</a></li>
                                                                </ul>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>


                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>


                    <div class="col-md-4">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Count Payment Invoice')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <canvas id="countPaymentInvoice" height="250"></canvas>
                                </div>
                            </div>
                        </section>

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Total')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <canvas id="sumTotal" height="250"></canvas>
                                </div>

                            </div>
                        </section>

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Total Amount')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <canvas id="sumTotalAmount" height="250"></canvas>
                                </div>

                            </div>
                        </section>

                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
@endsection;

@section('footer')
    <script src="{{asset('assets/system/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        /*!
 * stack-admin-theme (https://pixinvent.com/bootstrap-admin-template/stack)
 * Copyright 2017 PIXINVENT
 * Licensed under the Themeforest Standard Licenses
 */

        $(window).on("load", function() {
                // ------- countPaymentInvoice
                var ctx = $("#countPaymentInvoice"),
                    chartOptions= {
                        responsive:!0, maintainAspectRatio:!1, responsiveAnimationDuration:500, legend: {
                            position: "top"
                        }
                        , title: {
                            display: !1, text: "{{__('Count Payment Invoice')}}"
                        }
                        , scale: {
                            ticks: {
                                beginAtZero: !0
                            }
                            , reverse:!1
                        }
                        , animation: {
                            animateRotate: !1
                        }
                    }
                    , chartData= {
                        labels:[
                            @foreach($countPaymentInvoice as $key => $value)
                                "{{__($key)}}",
                            @endforeach
                        ], datasets:[ {
                            data: [
                                @foreach($countPaymentInvoice as $key => $value)
                                {{$value}},
                                @endforeach
                            ], backgroundColor: ["#00A5A8", "#626E82", "#FF7D4D"], label: "{{__('Count Payment Invoice')}}"
                        }
                        ]
                    }
                    , config= {
                        type: "polarArea", options: chartOptions, data: chartData
                    }
                ;
                new Chart(ctx, config);
                // ------- countPaymentInvoice


                // ------- sumTotal
                var ctx = $("#sumTotal"),
                    chartOptions= {
                        responsive:!0, maintainAspectRatio:!1, responsiveAnimationDuration:500, legend: {
                            position: "top"
                        }
                        , title: {
                            display: !1, text: "{{__('Total')}}"
                        }
                        , scale: {
                            ticks: {
                                beginAtZero: !0
                            }
                            , reverse:!1
                        }
                        , animation: {
                            animateRotate: !1
                        }
                    }
                    , chartData= {
                        labels:[
                            @foreach($sumTotal as $key => $value)
                                "{{__($key)}}",
                            @endforeach
                        ], datasets:[ {
                            data: [
                                @foreach($sumTotal as $key => $value)
                                {{$value}},
                                @endforeach
                            ], backgroundColor: ["#00A5A8", "#626E82", "#FF7D4D"], label: "{{__('Total')}}"
                        }
                        ]
                    }
                    , config= {
                        type: "polarArea", options: chartOptions, data: chartData
                    }
                ;
                new Chart(ctx, config);
                // ------- sumTotal



                // ------- sumTotal
                var ctx = $("#sumTotalAmount"),
                    chartOptions= {
                        responsive:!0, maintainAspectRatio:!1, responsiveAnimationDuration:500, legend: {
                            position: "top"
                        }
                        , title: {
                            display: !1, text: "{{__('Total Amount')}}"
                        }
                        , scale: {
                            ticks: {
                                beginAtZero: !0
                            }
                            , reverse:!1
                        }
                        , animation: {
                            animateRotate: !1
                        }
                    }
                    , chartData= {
                        labels:[
                            @foreach($sumTotalAmount as $key => $value)
                                "{{__($key)}}",
                            @endforeach
                        ], datasets:[ {
                            data: [
                                @foreach($sumTotalAmount as $key => $value)
                                {{$value}},
                                @endforeach
                            ], backgroundColor: ["#00A5A8", "#626E82", "#FF7D4D"], label: "{{__('Total Amount')}}"
                        }
                        ]
                    }
                    , config= {
                        type: "polarArea", options: chartOptions, data: chartData
                    }
                ;
                new Chart(ctx, config);
                // ------- sumTotal





            }

        );
    </script>
@endsection
