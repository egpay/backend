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
                                            <tr>
                                                <td>{{__('Merchant')}}</td>
                                                <td>
                                                    <a href="{{route('merchant.merchant.show',$result->merchant->id)}}" target="_blank">{{$result->merchant->{'name_'.$systemLang} }}</a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>
                                                    {{statusColor($result->status)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('XLS File')}}</td>
                                                <td>
                                                    <a href="{{asset('storage/app/'.$result->xls_path)}}">{{__('Download')}}</a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Cron Jobs')}}</td>
                                                <td>
                                                    {{$result->cron_jobs}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Total Numbers')}}</td>
                                                <td>
                                                    {{$result->numbers->count()}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Total Amount')}}</td>
                                                <td>
                                                    {{amount($result->numbers->sum('amount'),true)}}
                                                </td>
                                            </tr>




                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </section>




                </div>

            </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-head">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Numbers')}}
                                    </h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block card-dashboard">
                                    <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('ID')}}</th>
                                            <th>{{__('Model')}}</th>
                                            <th>{{__('Amount')}}</th>
                                            <th>{{__('Created At')}}</th>
                                            <th>{{__('Type')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('ID')}}</th>
                                            <th>{{__('Model')}}</th>
                                            <th>{{__('Amount')}}</th>
                                            <th>{{__('Created At')}}</th>
                                            <th>{{__('Type')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Action')}}</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Transactions -->
                </div>

        </div>
    </div>
@endsection

@section('header')
@endsection;

@section('footer')
    <script src="{{asset('assets/system/vendors/js/charts/chart.min.js')}}" type="text/javascript"></script>

@endsection
