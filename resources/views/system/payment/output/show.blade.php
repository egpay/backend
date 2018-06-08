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

                                            @foreach($result->toArray() as $key => $value)
                                                @if($key == 'deleted_at')
                                                    @continue
                                                @endif
                                            <tr>
                                                <td>
                                                    @if($key == 'staff_id')
                                                        {{__('Created By')}}
                                                    @elseif($key == 'area_id')
                                                        {{__('Area')}}
                                                    @else
                                                        {{humanStr($key)}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($key == 'logo')
                                                        <img src="{{image($value,150,150)}}">
                                                    @elseif($key == 'area_id')
                                                        {{ implode(' -> ',\App\Libs\AreasData::getAreasUp($value,true,$systemLang)) }}
                                                    @elseif($key == 'staff_id')
                                                        <a target="_blank" href="{{route('system.staff.show',$value)}}">{{$result->staff->firstname .' '.$result->staff->lasstname}}</a>
                                                    @elseif($key == 'created_at')
                                                        {{$result->created_at->diffForHumans()}}
                                                    @elseif($key == 'updated_at')
                                                        {{$result->updated_at->diffForHumans()}}
                                                    @else
                                                        {{$value}}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach

                                            </tbody>
                                        </table>


                                    </div>
                                </div>

                            </div>
                        </section>
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Counter')}}</h4>
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
                                                <td>{{__('Num. Of Services')}}</td>
                                                <td>{{$result->services()->count()}}</td>
                                            </tr>

                                            @if($countPaymentInvoice)
                                            <tr style="background: antiquewhite;">
                                                <td colspan="2" style="text-align: center;">{{__('Count Payment Invoice')}}</td>
                                            </tr>

                                            @foreach($countPaymentInvoice as $key => $value)
                                            <tr>
                                                <td>{{__($key)}}</td>
                                                <td>{{$value}}</td>
                                            </tr>
                                            @endforeach

                                            @endif


                                            @if($countPaymentInvoice)
                                                <tr style="background: antiquewhite;">
                                                    <td colspan="2" style="text-align: center;">{{__('Count Payment Invoice')}}</td>
                                                </tr>

                                                @foreach($sumTotal as $key => $value)
                                                    <tr>
                                                        <td>{{__($key)}}</td>
                                                        <td>{{$value}}</td>
                                                    </tr>
                                                @endforeach

                                            @endif


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
