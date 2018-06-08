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
                                <h4 class="card-title">{{__('Service Provider')}}</h4>
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
                                                <td>
                                                    {{__('ID')}}
                                                </td>
                                                <td>
                                                    {{$result->id}}
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>
                                                    {{__('Name')}}
                                                </td>
                                                <td>
                                                    {{$result->{'name_'.$systemLang} }}
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>
                                                    {{__('Description')}}
                                                </td>
                                                <td>
                                                    {{str_limit($result->{'description_'.$systemLang},10)}}
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>
                                                    {{__('Icon')}}
                                                </td>
                                                <td>
                                                    @if($result->icon)
                                                    <img src="{{asset('storage/'.imageResize($result->icon,70,70))}}" />
                                                    @else
                                                    --
                                                    @endif
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>
                                                    {{__('Status')}}
                                                </td>
                                                <td>
                                                    {{$result->status}}
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>
                                                    {{__('Created By')}}
                                                </td>
                                                <td>
                                                    <a href="{{route('system.staff.show',$result->staff->id)}}">{{$result->staff->firstname}} {{$result->staff->lastname}}</a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    {{__('Created At')}}
                                                </td>
                                                <td>
                                                    {{$result->created_at->diffForHumans()}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{__('Updated At')}}
                                                </td>
                                                <td>
                                                    {{$result->updated_at->diffForHumans()}}
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>


                                    </div>
                                </div>

                            </div>
                        </section>

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Service Providers')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">


                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Logo')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Description')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($result->payment_service_providers as $key => $value)
                                                <tr>
                                                    <td>{{$value->id}}</td>
                                                    <td>
                                                        @if(!$value->logo)
                                                            --
                                                        @else
                                                            <img src="{{asset('storage/'.imageResize($value->logo,70,70))}}" />
                                                        @endif
                                                    </td>
                                                    <td>{{$value->{'name_'.$systemLang} }}</td>
                                                    <td>{{str_limit($value->{'description_'.$systemLang},10)}}</td>

                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="ft-cog icon-left"></i>
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu">
                                                                <li class="dropdown-item"><a href="{{route('payment.service-providers.show',$value->id)}}">{{__('View')}}</a></li>
                                                                <li class="dropdown-item"><a href="{{route('payment.service-providers.edit',$value->id)}}">{{__('Edit')}}</a></li>
                                                                <li class="dropdown-item"><a onclick="deleteRecord('{{route('payment.service-providers.destroy',$value->id)}}')" href="javascript:void(0)">{{__('Delete')}}</a></li>
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

                        {{--<section class="card">--}}
                            {{--<div class="card-header">--}}
                                {{--<h4 class="card-title">{{__('Counter')}}</h4>--}}
                            {{--</div>--}}
                            {{--<div class="card-body collapse in">--}}
                                {{--<div class="card-block">--}}
                                    {{--<div class="table-responsive">--}}


                                        {{--<table class="table table-hover">--}}
                                            {{--<thead>--}}
                                            {{--<tr>--}}
                                                {{--<th>#</th>--}}
                                                {{--<th>{{__('Value')}}</th>--}}
                                            {{--</tr>--}}
                                            {{--</thead>--}}
                                            {{--<tbody>--}}

                                            {{--<tr>--}}
                                                {{--<td>{{__('Num. Of Services')}}</td>--}}
                                                {{--<td>{{$result->services()->count()}}</td>--}}
                                            {{--</tr>--}}

                                            {{--@if($countPaymentInvoice)--}}
                                            {{--<tr style="background: antiquewhite;">--}}
                                                {{--<td colspan="2" style="text-align: center;">{{__('Count Payment Invoice')}}</td>--}}
                                            {{--</tr>--}}

                                            {{--@foreach($countPaymentInvoice as $key => $value)--}}
                                            {{--<tr>--}}
                                                {{--<td>{{__($key)}}</td>--}}
                                                {{--<td>{{$value}}</td>--}}
                                            {{--</tr>--}}
                                            {{--@endforeach--}}

                                            {{--@endif--}}


                                            {{--@if($countPaymentInvoice)--}}
                                                {{--<tr style="background: antiquewhite;">--}}
                                                    {{--<td colspan="2" style="text-align: center;">{{__('Count Payment Invoice')}}</td>--}}
                                                {{--</tr>--}}

                                                {{--@foreach($sumTotal as $key => $value)--}}
                                                    {{--<tr>--}}
                                                        {{--<td>{{__($key)}}</td>--}}
                                                        {{--<td>{{$value}}</td>--}}
                                                    {{--</tr>--}}
                                                {{--@endforeach--}}

                                            {{--@endif--}}


                                            {{--</tbody>--}}
                                        {{--</table>--}}


                                    {{--</div>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</section>--}}
                   {{----}}

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


                    {{--<div class="col-md-4">--}}

                        {{--<section class="card">--}}
                            {{--<div class="card-header">--}}
                                {{--<h4 class="card-title">{{__('Count Payment Invoice')}}</h4>--}}
                            {{--</div>--}}
                            {{--<div class="card-body collapse in">--}}
                                {{--<div class="card-block">--}}
                                    {{--<canvas id="countPaymentInvoice" height="250"></canvas>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</section>--}}

                        {{--<section class="card">--}}
                            {{--<div class="card-header">--}}
                                {{--<h4 class="card-title">{{__('Total')}}</h4>--}}
                            {{--</div>--}}
                            {{--<div class="card-body collapse in">--}}
                                {{--<div class="card-block">--}}
                                    {{--<canvas id="sumTotal" height="250"></canvas>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</section>--}}

                        {{--<section class="card">--}}
                            {{--<div class="card-header">--}}
                                {{--<h4 class="card-title">{{__('Total Amount')}}</h4>--}}
                            {{--</div>--}}
                            {{--<div class="card-body collapse in">--}}
                                {{--<div class="card-block">--}}
                                    {{--<canvas id="sumTotalAmount" height="250"></canvas>--}}
                                {{--</div>--}}

                            {{--</div>--}}
                        {{--</section>--}}

                    {{--</div>--}}



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
