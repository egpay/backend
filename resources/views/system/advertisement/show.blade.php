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
                    <div class="col-md-12">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('ADV Data')}}
                                    <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.advertisement.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
                                </h4>
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
                                                <td>{{__('Name')}}</td>
                                                <td>{{$result->name}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Image')}}</td>
                                                <td><a href="{{asset('storage/'.imageResize($result->image,70,70))}}" target="_blank">{{__('View')}}</a></td>

                                            </tr>


                                            <tr>
                                                <td>{{__('Dimensions')}}</td>
                                                <td>{{$result->width}} x {{$result->height}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Routes')}}</td>
                                                <td>
                                                    @foreach(explode(',',$result->route) as $key => $value)
                                                        {{$value}}
                                                        <hr />
                                                    @endforeach
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Route ID')}}</td>
                                                <td>{{$result->route_id}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Comment')}}</td>
                                                <td><code>{{$result->comment}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>
                                                    @if($result->status == 'active')
                                                        <b style="color: green;">Active</b>
                                                    @else
                                                        <b style="color: red;">In-Active</b>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Type')}}</td>
                                                <td>
                                                    {{ucfirst($result->type)}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Total Amount')}}</td>
                                                <td>
                                                    {{$result->total_amount}} {{__('LE')}}
                                                </td>
                                            </tr>


                                            @if($result->merchant)
                                            <tr>
                                                <td>{{__('To Merchant')}}</td>
                                                <td>
                                                    <a href="{{route('merchant.merchant.show',$result->merchant->id)}}">{{$result->merchant->{'name_'.$systemLang} }}</a>
                                                </td>
                                            </tr>
                                            @endif




                                            <tr>
                                                <td>{{__('Start At')}}</td>
                                                <td>
                                                    {{$result->from_date}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('End At')}}</td>
                                                <td>
                                                    {{$result->to_date}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    @if($result->created_at == null)
                                                        --
                                                    @else
                                                        {{$result->created_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>
                                                    @if($result->updated_at == null)
                                                        --
                                                    @else
                                                        {{$result->updated_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </section>

                    </div>
                </div>
                <!-- Column Chart -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-body collapse in">
                                <div class="card-block">

                                    <h4 class="card-title">{{__('Filter')}}</h4>

                                    <div class="form-group col-sm-4">
                                        <div class="controls">
                                            <label for="width">{{__('Year')}}</label>
                                            <select class="form-control" id="d_year">
                                                <option value="0">{{__('Select Year')}}</option>
                                                @for($i=2015;$i<=2017;$i++)
                                                    <option @if($i == date('Y')) selected="selected" @endif value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>

                                    </div>


                                    <div class="form-group col-sm-4">
                                        <div class="controls">
                                            <label for="width">{{__('Month')}}</label>
                                            <select class="form-control" id="d_month">
                                                <option value="0">{{__('Select Month')}}</option>
                                                @for($i=1;$i<=12;$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>

                                    </div>


                                    <div class="form-group col-sm-4">
                                        <div class="controls">
                                            <label for="width">{{__('Day')}}</label>
                                            <select class="form-control" id="d_day">
                                                <option value="0">{{__('Select Day')}}</option>
                                                @for($i=1;$i<=31;$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Data Analytics')}}</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block" id="column-chart_div">
                                    <canvas id="column-chart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('GENDER ANALYTICS')}}</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block" id="simple-pie-chart_div">
                                    <canvas id="simple-pie-chart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('View Analytics')}}</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block" id="simple-pie-chart2_div">
                                    <canvas id="simple-pie-chart2" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection

@section('footer')
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>

    <script src="{{asset('assets/system/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>


    <script type="text/javascript">

        $('#d_year,#d_month,#d_day').change(function(){
            getAdvertisementAnalytics({{$result->id}},$('#d_year').val(),$('#d_month').val(),$('#d_day').val());
        });


        function getAdvertisementAnalytics($id,$year,$month,$day){
            $('#column-chart_div').html('<canvas id="column-chart" height="400"></canvas>');
            $('#simple-pie-chart_div').html('<canvas id="simple-pie-chart" height="400"></canvas>');
            $('#simple-pie-chart2_div').html('<canvas id="simple-pie-chart2" height="400"></canvas>');

            $.getJSON(
                '{{route('system.ajax.get')}}',
                {
                    'type': 'getAdvertisementAnalytics',
                    'advertisement_id': $id,
                    'year': $year,
                    'month': $month,
                    'day': $day
                },
                function ($data){

                    if($data.status == false){
                        toastr.error('There Are No Data in this time', 'Error !', {"closeButton": true});
                        return;
                    }

                    $response = $data;
                    $data.monthsClick = Object.keys($data.monthsClick).map(function (key) { return $data.monthsClick[key]; });
                    $data.monthsView  = Object.keys($data.monthsView).map(function (key)  { return $data.monthsView[key];  });

                    // -- Months
                    var ctx = $("#column-chart");
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
                        },
                        title: {
                            display: true,
                            text: '{{__('ADV Analytics ')}} '+$data.year
                        }
                    };
                    var chartData = {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [{
                            label: "Click",
                            data: $data.monthsClick,
                            backgroundColor: "#16D39A",
                            hoverBackgroundColor: "rgba(22,211,154,.9)",
                            borderColor: "transparent"
                        }, {
                            label: "View",
                            data: $data.monthsView,
                            backgroundColor: "#F98E76",
                            hoverBackgroundColor: "rgba(249,142,118,.9)",
                            borderColor: "transparent"
                        }]
                    };

                    var config = {
                        type: 'bar',

                        // Chart Options
                        options : chartOptions,

                        data : chartData
                    };
                    var lineChart = new Chart(ctx, config);
                    // -- Months




























                    var ctx2 = $("#simple-pie-chart");

                    // Chart Options
                    var chartOptions2 = {
                        responsive: true,
                        maintainAspectRatio: false,
                        responsiveAnimationDuration:500,
                    };


                    // Chart Data
                    var chartData2 = {
                        labels: ["{{__('Male')}}", "{{__('Female')}}", "{{__('UN-KNOWN')}}"],
                        datasets: [{
                            label: "Male & Female",
                            data: [$data.maleAnalytics,$data.femaleAnalytics,$data.nullAnalytics],
                            backgroundColor: ['#00A5A8','#FF4558','#404E67'],
                        }]
                    };

                    var config2 = {
                        type: 'pie',
                        // Chart Options
                        options : chartOptions2,
                        data : chartData2
                    };

                    // Create the chart
                    var pieSimpleChart2 = new Chart(ctx2, config2);




























                    var ctx3 = $("#simple-pie-chart2");

                    // Chart Options
                    var chartOptions3 = {
                        responsive: true,
                        maintainAspectRatio: false,
                        responsiveAnimationDuration:500,
                    };


                    // Chart Data
                    var chartData3 = {
                        labels: ["{{__('Click')}}", "{{__('View')}}"],
                        datasets: [{
                            label: "Click & View",
                            data: [$data.clickAnalytics,$data.viewAnalytics],
                            backgroundColor: ['#16d39a','#f98e76'],
                        }]
                    };

                    var config3 = {
                        type: 'pie',
                        // Chart Options
                        options : chartOptions3,
                        data : chartData3
                    };

                    // Create the chart
                    var pieSimpleChart3 = new Chart(ctx3, config3);





                }
            );
        }

        $(document).ready(function(){
            $('#d_year').change();

            $('#product-list').treegrid({
                expanderExpandedClass: 'fa fa-minus',
                expanderCollapsedClass: 'fa fa-plus'
            });
        });
    </script>
@endsection
