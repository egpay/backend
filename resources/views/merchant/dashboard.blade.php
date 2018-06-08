@extends('merchant.layouts')


@section('content')


    <div class="row">

        <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Merchant Code')}}</span>
                                <h3 class="primary"># {{auth()->user()->merchant()->id}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="ft-info fa-lg primary float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($merchant->paymentWallet))
        <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('E-payment balance')}}</span>
                                <h3 class="primary">{{number_format($merchant->paymentWallet->balance,2)}} {{__('LE')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-usd fa-lg primary float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if((isset($merchant->eCommerceWallet)) &&
        (merchantcan([
            'panel.merchant.product-category.index','panel.merchant.product-category.show','panel.merchant.product-category.destroy','panel.merchant.product-category.create',
            'panel.merchant.product-category.store','panel.merchant.product-category.edit','panel.merchant.product-category.update','panel.merchant.product.index',
            'panel.merchant.product.show','panel.merchant.product.destroy'
            ]))
        )

        <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('E-commerce balance')}}</span>
                                <h3 class="primary">{{number_format($merchant->eCommerceWallet->balance,2)}} {{__('LE')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-usd fa-lg primary float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif


        @if(merchantcan(['panel.merchant.branch.index','panel.merchant.branch.create','panel.merchant.branch.edit']))
        <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Branches')}}</span>
                                <h3 class="warning">{{$merchant->merchant_branch_count}} {{__('Branch')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-th-large fa-lg warning float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif


        @if(merchantcan(['panel.merchant.product.index','panel.merchant.product.create','panel.merchant.product.edit']))
        <div class="col-xl-3 col-lg-6 col-xs-12">
            <div class="card border-success">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Products')}}</span>
                                <h3 class="success">{{$merchant->merchant_products_count}} {{__('Products')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-th-large fa-lg success float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif


    </div>

    <div class="row">
        <div class="col-xl-9 col-lg-9 col-xs-12">
            @if(merchantcan(['panel.merchant.order.index','panel.merchant.order.create']))
            <div class="row">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('Total Orders / Branch')}}</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <canvas id="branches-orders" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <div class="row">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{__('Payment Invoices')}}</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <canvas id="payment-invoice" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-lg-6 col-xs-12">

            <div class="card border-success">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Payment invoices')}}</span>
                                <h3 class="success">{{$merchant->payment_invoice_count}} {{__('Invoices')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-th-large fa-lg success float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-danger">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Wallet transactions')}}</span>
                                <h3 class="danger">{{$merchant->transactions}} {{__('Invoices')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-th-large fa-lg danger float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h2>{{__('Appointment')}}</h2>
                    </div>
                    <div class="card-block">
                        @if($appointment)
                            <table class="table font-size-small">
                                <tr class="bg-{{(($appointment->status=='pending')?'warning':(($appointment->status=='canceled')?'info':(($appointment->status=='fail')?'danger':'primary')))}}">
                                    <td>{{__('Appointment Status')}}</td>
                                    <td>
                                        @if($appointment->status == 'pending')
                                                <label>{{__('Pending')}}</label>
                                        @elseif($appointment->status == 'canceled')
                                                <label>{{__('Canceled')}}</label>
                                        @elseif($appointment->status == 'fail')
                                                <label>{{__('Fail')}}</label>
                                        @elseif($appointment->status == 'done')
                                            <label>{{__('Done')}}</label>
                                        @endif
                                    </td>
                                </tr>
                                @if(count($appointment->appointmentStatus))
                                    <tr>
                                        <td>{{__('Activity')}}</td>
                                    </tr>
                                    @foreach($appointment->appointmentStatus as $appStatus)
                                    <tr  class="bg-{{(($appStatus->status=='pending')?'warning':(($appStatus->status=='canceled')?'info':(($appStatus->status=='fail')?'danger':'primary')))}}">
                                        <td>{{__('Date')}}</td>
                                        <td>{{$appStatus->created_at->diffForHumans()}}</td>
                                    </tr>
                                    <tr class="bg-{{(($appStatus->status=='pending')?'warning':(($appStatus->status=='canceled')?'info':(($appStatus->status=='fail')?'danger':'primary')))}}">
                                        <td>{{__('status')}}</td>
                                        <td>{{$appStatus->status}}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </table>
                        @endif

                        {!! Form::label('appointmenttext', 'Appointment About') !!}
                        {!! Form::textarea('appointmenttext',null,['class'=>'form-control','rows'=>3]) !!}
                        <button class="btn btn-lg btn-primary" onclick="newAppointment();">{{__('Get Appointment')}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection


@include('merchant._modals._iframe')


@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/colors/palette-gradient.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/charts/morris.css')}}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{asset('assets/system/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>

    <script>

        $(window).on("load", function(){

            var ctx = $("#branches-orders");

            // Chart Options
            var chartOptions = {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: 'rgb(0, 255, 0)',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                responsiveAnimationDuration:500,
                legend: {
                    position: 'top',
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            color: "#f3f3f3",
                            drawTicks: false,
                        },
                        scaleLabel: {
                            display: true,
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            color: "#f3f3f3",
                            drawTicks: false,
                        },
                        scaleLabel: {
                            display: true,
                        }
                    }]
                }
            };

            // Branch orders
            var chartData = {
                labels: ["{!!(implode('","',$months))!!}"],
                datasets: [
                    @foreach($merchant_branches as $branch)
                        @if(count($branch->orders))
                            {
                                label: '{{$branch->name}}',
                                data: [
                                    @foreach($months as $oneMonthYear)
                                        @if($orderCount = $branch->orders->where('date',$oneMonthYear)->first())
                                            {{$orderCount->orders_total}},
                                        @else
                                            "",
                                        @endif
                                    @endforeach
                                ],
                                backgroundColor: "#{{rand(100,999)}}",
                                hoverBackgroundColor: "rgba(22,211,154,.9)",
                                borderColor: "transparent"
                            },
                        @endif
                    @endforeach
                ]
            };

            var config = {
                type: 'bar',

                // Chart Options
                options : chartOptions,

                data : chartData
            };

            // Create the chart
            @if(merchantcan(['panel.merchant.order.index','panel.merchant.order.create']))
            var lineChart = new Chart(ctx, config);
            @endif


            /*
                Invoices
             */
            var payment = $("#payment-invoice");
            var paymentinvoice = {
                labels: ["{!!(implode('","',$months))!!}"],
                datasets: [
                    @foreach(['paid','pending','reverse'] as $type)
                        {
                            label: "{{ucfirst($type)}} {{__('Invoices')}}",
                            data: [
                                @foreach($months as $onepMonth)
                                    @if($oneInvMonth = $merchant_invoice->where('status','=',$type)->where('date','=',$onepMonth)->first())
                                        {{$oneInvMonth->total_amount}},
                                    @else
                                        "",
                                    @endif
                                @endforeach
                            ],
                            @if($type=='paid')
                                backgroundColor: "#16D39A",
                                hoverBackgroundColor: "#053124",
                            @elseif ($type=='pending')
                                backgroundColor: "#FFA87D",
                                hoverBackgroundColor: "#ca4300",
                            @else
                                backgroundColor: "#FF7588",
                                hoverBackgroundColor: "#c2001b",
                            @endif

                            borderColor: "transparent"
                        },
                    @endforeach
                ]
            };
            var Paymentconfig = {
                type: 'bar',
                options : chartOptions,
                data : paymentinvoice
            };

            var InvoiceBars = new Chart(payment, Paymentconfig);

        });

        function newAppointment(){
            doPostAction('{{route('panel.merchant.get-appointment')}}',{'desc':$('#appointmenttext').val()});
            $('#appointmenttext').val('');
        }
</script>
@endsection