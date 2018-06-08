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
                                                    <th>{{__('Bee')}}</th>
                                                    <th>{{__('EGPAY')}}</th>
                                                    <th>{{__('SUPERVISORS')}}</th>
                                                    <th>{{__('SALES')}}</th>
                                                    <th>{{__('MERCHANTS')}}</th>
                                                    <th>{{__('INVOICE')}}</th>
                                                    <th>{{__('COMMISSION')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="payment-ajax">
                                                <tr>
                                                    <td colspan="7">{{__('Loading...')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
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
                                            <tbody id="payment-ajax-service">
                                                <tr>
                                                    <td colspan="3">{{__('Loading...')}}</td>
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


@section('footer')
    <script type="text/javascript">
        $(document).ready(function(){
            updateData();

            setInterval(function () {
                updateData(true);
            },30000);

        });

        function updateData($withoutloading){
            if($withoutloading != true){
                $('#payment-ajax').html('<tr><td colspan="7">{{__('Loading...')}}</td></tr>');
                $('#payment-ajax-service').html('<tr><td colspan="3">{{__('Loading...')}}</td></tr>');
            }
            $.getJSON('{{route('payment.payment.index',['ajax'=>'true'])}}',function($data){
                $html = '<tr>\n' +
                    '   <td>'+$data.sdk_wallet+'</td>\n' +
                    '   <td>'+$data.egpay_wallet+'</td>\n' +
                    '   <td>'+$data.supervisor_wallets+'</td>\n' +
                    '   <td>'+$data.sales_wallets+'</td>\n' +
                    '   <td>'+$data.merchant_wallets+'</td>\n' +
                    '   <td><table class="table">\n' +
                    '    <tbody>\n' +
                    '        <tr>\n' +
                    '            <td>{{__('Total Amount')}}</td>\n' +
                    '            <td>'+$data.invoices_amount+'</td>\n' +
                    '        </tr>\n' +
                    '        <tr>\n' +
                    '            <td>{{__('Count')}}</td>\n' +
                    '            <td>'+$data.invoices_count+'</td>\n' +
                    '        </tr>\n' +
                    '    </tbody>\n' +
                    '</table>   </td>\n' +
                    '   <td><table class="table">\n' +
                    '    <tbody>\n' +
                    '        <tr>\n' +
                    '            <td>{{__('System Commission')}}</td>\n' +
                    '            <td>'+$data.system_commission+'</td>\n' +
                    '        </tr>\n' +
                    '        <tr>\n' +
                    '            <td>{{__('Merchant Commission')}}</td>\n' +
                    '            <td>'+$data.merchant_commission+'</td>\n' +
                    '        </tr>\n' +
                    '    </tbody>\n' +
                    '</table>   </td>\n' +
                    '</tr>';

                $('#payment-ajax').html($html);
                $('#payment-ajax-service').html($data.services);


            });

        }
    </script>
@endsection