<div id="bill" class="col-xs-5 col-center" style="text-align:center;font-family:'Lucida Grande','Neo Sans Arabic';">
    <div style="text-align:center">
        <img src="{{asset('assets/merchant/image/logo.png')}}"></div>
    <div style="text-align:center">
        <style>.dividerB{border-bottom:5px double gray;}</style>
        <div class="dividerB">
            <b>
                {{$data->service_info['provider_name_'.$lang] }}
                <br>
                {{$data->service_info['service_name_'.$lang] }}
            </b>
        </div>
        <table class="table table-condensed">
            <tbody>
            <tr>
                <td>{{__('Merchant ID')}}</td>
                <td>{{$data->service_info['merchant_id']}}</td>
            </tr>
            <tr>
                <td>{{__('Date')}}</td>
                <td>{{explode(' ',$data->dateTime)[0]}}</td>
            </tr>
            <tr>
                <td>{{__('Time')}}</td>
                <td>{{explode(' ',$data->dateTime)[1]}}</td>
            </tr>
            <tr>
                <td>{{__('Service ID')}}</td>
                <td>{{$data->service_info['service_id']}}</td>
            </tr>
            </tbody>
        </table>
        <div class="dividerB">
            <b>{{__('Successful Transaction')}}</b>
        </div>
        @if(isset($data->info))
            <table class="table table-condensed" style="text-align:center">
                <tbody>
                    @foreach($data->info[$lang] as $param)
                        <tr>
                            <td>{{$param['key']}}</td>
                        </tr>
                        <tr>
                            <td>{{$param['value']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if(isset($data->system_amount))
            <table class="table table-condensed">
                <tbody>
                <tr>
                    <td>{{__('Paid Amount')}}</td>
                    <td>{{number_format($data->system_amount['amount'],2)}}</td>
                </tr>
                <tr>
                    <td>{{__('Total Amount')}}</td>
                    <td>{{number_format($data->system_amount['total_amount'],2)}}</td>
                </tr>
                </tbody>
            </table>
        @endif
        <table style="font-size: 10px;width: 100%;font-weight:bold">
            <tbody>
            @if(isset($data->repeated))
                <tr>
                    <td style="text-align: center;font-weight:bold">
                        <span>({{__('Repeated print')}})</span>
                    </td>
                </tr>
            @endif
            <tr>
                <td>EGPAY</td>
            </tr>
            <tr>
                <td>www.EGPAY.com</td>
            </tr>
            <tr>
                <td>Tel: +2 22739229</td>
            </tr>

            <tr style="border-bottom:1px solid #000">
                <td style="text-align: right;font-weight:normal">
                    <sub>{{__('powered by')}} {{$data->payment_by['name']}}</sub>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>