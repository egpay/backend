@if(!$result['status'])
    <h2>{{__('Unknown Error')}}</h2>
@else

    @if(!empty($result['data']->settlement))
        <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <td>{{__('Total System Commission')}}</td>
                    <td>{{amount($totalSystemCommission)}}</td>
                </tr>
                <tr>
                    <td>{{__('Total Merchant Commission')}}</td>
                    <td>{{amount($totalMerchantCommission)}}</td>
                </tr>
                </tbody>
            </table>
        </div>

        @foreach($result['data']->settlement as $key => $value)


                <div class="col-md-12">

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="6" style="    background-color: aliceblue;">
                                <h3 style="text-align: center;">
                                    {!! adminDefineUser($value['model_type'],$value['model_id'],__('#ID').':'.$value['model_id'].' - '.$value['model']['firstname'].' '.$value['model']['lastname']) !!}
                                </h3>
                            </th>
                        </tr>
                        <tr>
                            <th>{{__('Invoice')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('System Commission')}}</th>
                            <th>{{__('Merchant Commission')}}</th>
                            <th title="{{__('Specific System Commission')}}">{{__('SSC')}}</th>
                            <th title="{{__('Specific Merchant Commission')}}">{{__('SMC')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($value['success'] as $keySuccess => $valueSuccess)
                            <tr>
                                <th>
                                    {{--{{route('system.invoice.show',[$valueSuccess['invoice_id']])}}--}}
                                    <a href="">{{$valueSuccess['invoice_id']}}</a>
                                </th>
                                <td>{{amount($valueSuccess['amount'])}}</td>
                                <td>{{amount($valueSuccess['system_commission'])}}</td>
                                <td>{{amount($valueSuccess['merchant_commission'])}}</td>
                                <td>
                                    @if($valueSuccess['DB_charge_type'] == 'fixed')
                                        {{amount($valueSuccess['DB_system_commission'])}}
                                    @else
                                        {{$valueSuccess['DB_system_commission']}} %
                                    @endif
                                </td>
                                <td>
                                    @if($valueSuccess['DB_charge_type'] == 'fixed')
                                        {{amount($valueSuccess['DB_merchant_commission'])}}
                                    @else
                                        {{$valueSuccess['DB_merchant_commission']}} %
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">{{__('System Commission')}}</td>
                            <td colspan="4">{{amount($value['system_commission'])}}</td>
                        </tr>

                        <tr>
                            <td colspan="2">{{__('Merchant Commission')}}</td>
                            <td colspan="4">{{amount($value['merchant_commission'])}}</td>
                        </tr>


                        </tbody>
                    </table>


                    @if(!empty($value['error']))
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th colspan="6" style="color:#FFF;background-color: red;">
                                        <h3 style="text-align: center;">
                                            {{__('Errors')}}
                                        </h3>
                                    </th>
                                </tr>
                                <tr>
                                    <th>{{__('Invoice')}}</th>
                                    <th>{{__('Msg')}}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($value['error'] as $keySuccess => $valueSuccess)
                                    <tr>
                                        <th>
                                            {{--{{route('system.invoice.show',[$valueSuccess['invoice_id']])}}--}}
                                            <a href="">{{$valueSuccess['invoice_id']}}</a>
                                        </th>
                                        <td>{{$valueSuccess['error_msg']}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <hr />
                </div>


        @endforeach
        </div>
    @else
        <h2>{{__('There Are No data To show')}}</h2>
    @endif
@endif