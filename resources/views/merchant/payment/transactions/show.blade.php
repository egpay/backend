@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                       
                       @if(!empty($result->icon))
                        <img src="{{$result->icon}}">
                       @endif
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
                                <h4 class="card-title">{{__('Data')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">

                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('Value')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($result->toArray() as $key => $value)
                                            @if(in_array($key,['icon','deleted_at']))
                                                @continue
                                            @endif
                                            <tr>
                                                <td>
                                                    @if($key == 'staff_id')
                                                        {{__('Created By')}}
                                                    @elseif($key == 'area_id')
                                                        {{__('Area')}}
                                                    @elseif($key == 'payment_service_provider_id')
                                                        {{__('Service Provider')}}
                                                    @elseif($key == 'payment_sdk_id')
                                                        {{__('SDK')}}
                                                    @else
                                                        {{humanStr($key)}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($key == 'logo')
                                                        <img src="{{image($value,150,150)}}">
                                                    @elseif($key == 'area_id')
                                                        {{ implode(' -> ',\App\Libs\AreasData::getAreasUp($value,true,$systemLang)) }}
                                                    @elseif($key == 'payment_service_provider_id')
                                                        <a href="{{route('payment.service-providers.show',$value)}}" target="_blank">{{$result->payment_service_provider->{'name_'.$systemLang} }}</a>
                                                    @elseif($key == 'payment_sdk_id')
                                                        <a href="{{route('payment.sdk.show',$value)}}" target="_blank">{{$result->payment_sdk->name}}</a>
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
                        </section>

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('APIs')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>{{__('ID')}}</th>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Description')}}</th>
                                            <th>{{__('Staff')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($result->payment_service_apis as $key => $value)
                                            <tr @if($value->status == 'in-active') class="tr-danger" @endif>
                                                <td>{{$value->id}}</td>
                                                <td>{{$value->name}} ( {{ucfirst($value->service_type)}} )</td>
                                                <td>
                                                    @if($value->description)
                                                        {{$value->description}}
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>{{$value->staff->firstname .' '.$value->staff->lastname}}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="ft-cog icon-left"></i>
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu">
                                                            <li class="dropdown-item"><a href="{{route('payment.service-api.show',$value->id)}}">{{__('View')}}</a></li>
                                                            <li class="dropdown-item"><a href="{{route('payment.service-api.edit',$value->id)}}">{{__('Edit')}}</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                    @if($countPaymentInvoice || $sumTotal || $sumTotalAmount)
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


                                            @if($sumTotal)
                                                <tr style="background: antiquewhite;">
                                                    <td colspan="2" style="text-align: center;">{{__('Total')}}</td>
                                                </tr>

                                                @foreach($sumTotal as $key => $value)
                                                    <tr>
                                                        <td>{{__($key)}}</td>
                                                        <td>{{$value}}</td>
                                                    </tr>
                                                @endforeach

                                            @endif

                                            @if($sumTotalAmount)
                                                <tr style="background: antiquewhite;">
                                                    <td colspan="2" style="text-align: center;">{{__('Total Amount')}}</td>
                                                </tr>

                                                @foreach($sumTotalAmount as $key => $value)
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
                        @endif
                    </div>

                    <div class="col-md-4">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('SDK Data')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <table class="table table-hover">
                                        <tbody>

                                        <tr>
                                            <td>{{__('ID')}}</td>
                                            <td>{{$result->payment_sdk->id}} ( <a href="{{route('payment.sdk.edit',$result->payment_sdk->id)}}">{{__('Edit')}}</a> )</td>
                                        </tr>

                                        <tr>
                                            <td>{{__('Adapter Name')}}</td>
                                            <td>{{$result->payment_sdk->adapter_name}}</td>
                                        </tr>

                                        <tr>
                                            <td>{{__('Name')}}</td>
                                            <td>{{$result->payment_sdk->name}}</td>
                                        </tr>

                                        <tr>
                                            <td>{{__('Address')}}</td>
                                            <td>{{$result->payment_sdk->address}}</td>
                                        </tr>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

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
