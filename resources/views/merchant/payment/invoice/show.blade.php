@extends('merchant.layouts')

<div class="modal fade text-xs-left" id="transaction-model"  role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Payment Transaction')}}</label>
            </div>
            <div class="modal-body">

                <div class="card-body" id="transaction-div">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
            </div>
        </div>
    </div>
</div>


@section('content')
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-6">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Data')}}
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
                                                <td>
                                                    {{$result->id}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Transaction ID')}}</td>
                                                <td>
                                                    <a href="javascript:void(0)" onclick="urlIframe('{{route('panel.merchant.payment.transactions.list',['id'=>$result->payment_transaction_id])}}')">{{$result->payment_transaction_id}}</a>
                                                </td>
                                            </tr>




                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                <td>
                                                    {!! adminDefineUser($result->creatable_type,$result->creatable_id,$result->creatable->firstname.' '.$result->creatable->lastname) !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Total')}}</td>
                                                <td>
                                                    {{amount($result->total)}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Total Amount')}}</td>
                                                <td>
                                                    {{amount($result->total_amount)}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>
                                                    {{statusColor($result->status)}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Settlement')}}</td>
                                                <td>
                                                    @if($result->wallet_settlement_id)
                                                        <table class="table table-condensed">
                                                            <tbody>

                                                            <tr>
                                                                <td>{{__('ID')}}</td>
                                                                <td>
                                                                    <a href="{{route('system.settlement.show',$result->wallet_settlement_id)}}"></a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>{{__('System Commission')}}</td>
                                                                <td>{{amount($result->wallet_settlement_data['system_commission'])}}</td>
                                                            </tr>

                                                            <tr>
                                                                <td>{{__('Merchant Commission')}}</td>
                                                                <td>{{amount($result->wallet_settlement_data['merchant_commission'])}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{__('DB System Commission')}}</td>
                                                                <td>{{$result->wallet_settlement_data['DB_system_commission'].' '.iif($result->wallet_settlement_data['DB_charge_type'] == 'fixed','LE','%')}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{__('DB Merchant Commission')}}</td>
                                                                <td>{{$result->wallet_settlement_data['DB_merchant_commission'].' '.iif($result->wallet_settlement_data['DB_charge_type'] == 'fixed','LE','%')}}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    {{$result->created_at->diffForHumans()}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Updated At')}}</td>
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
                    </div>


                    <div class="col-md-6">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Transaction Data')}}</h4>
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
                                                <td>
                                                    <a href="javascript:void(0);" onclick="urlIframe('{{route('panel.merchant.payment.transactions.list',['id'=>$result->payment_transaction_id])}}')">
                                                        {{$result->payment_transaction->id}}
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>
                                                    @if($result->payment_transaction->response_type == 'done')
                                                        <i style="color: green;" class="fa fa-check"
                                                           aria-hidden="true"></i>
                                                    @elseif($result->payment_transaction->response_type == 'fail')
                                                        <i style="color: red;" class="fa fa-times" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Service Type')}}</td>
                                                <td>
                                                    {{__(ucfirst($result->payment_transaction->service_type))}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Service')}}</td>
                                                <td>
                                                    {{$result->payment_transaction->payment_services->{'name_'.$lang} }}
                                                    ({{$result->payment_transaction->payment_services->payment_service_provider->{'name_'.$lang} }})
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Amount')}}</td>
                                                <td>
                                                    {{amount($result->payment_transaction->amount)}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Total Amount')}}</td>
                                                <td>
                                                    {{amount($result->payment_transaction->total_amount)}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    {{$result->payment_transaction->created_at->diffForHumans()}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Sent Data')}}</td>
                                                <td>
                                                    <table class="table table-condensed">
                                                        <tbody>
                                                        @if(is_array($result->payment_transaction->request_map) && !empty($result->payment_transaction->request_map))
                                                            @foreach($result->payment_transaction->request_map as $key => $value)
                                                                @if(is_array($value))

                                                                    @foreach($value as $key1 => $value1)
                                                                        @if(is_array($value1))
                                                                            @foreach($value as $key2 => $value2)
                                                                                <tr>
                                                                                    <td>{{__($key2)}}</td>
                                                                                    <td>{{$value2}}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td>{{__($key1)}}</td>
                                                                                <td>{{$value1}}</td>
                                                                            </tr>
                                                                        @endif

                                                                    @endforeach

                                                                @else
                                                                    <tr>
                                                                        <td>{{PaymentParamName($key,$lang)}}</td>
                                                                        <td>{{$value}}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach

                                                        @else
                                                            --
                                                        @endif

                                                        </tbody>
                                                    </table>
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





            </div>
@endsection

@section('header')
@endsection

@section('footer')
@endsection
