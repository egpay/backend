@extends('system.layouts')
<div class="modal fade text-xs-left" id="changeInvoice-modal"  role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Change Invoice Status')}}</label>
            </div>
            {!! Form::open(['onsubmit'=>'formSubmitChange(\'#changeInvoice-form\');return false;','id'=>'changeInvoice-form']) !!}
            {{Form::hidden('id',$result->id)}}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            <div id="changeInvoice_error"></div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::select('status',[''=> __('Select Status'),'pending'=> __('Pending'),'paid'=> __('Paid'),'reverse'=> __('Reverse')],null,['class'=>'form-control','id'=>'status']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('comment',__('Comment')) }}
                                    {!! Form::textarea('comment',null,['class'=>'form-control','id'=>'comment']) !!}
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="reset" class="btn btn-outline-secondary btn-md" value="{{__('Reset Form')}}">
                <input type="submit" class="btn btn-outline-primary btn-md" value="{{__('Change')}}">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

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
                    <div class="col-md-4">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Data')}}
                                    <span style="float: right;">
                                        <a data-toggle="modal" data-target="#changeInvoice-modal" class="btn btn-outline-primary"><i class="fa fa-pencil"></i> {{__('Change Status')}}</a>
                                    </span>
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
                                                    {{$result->payment_transaction_id}}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                <td>
                                                    {!! adminDefineUser($result->creatable_type,$result->creatable_id,$result->creatable->firstname.' '.$result->creatable->lastname) !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Service')}}</td>
                                                <td>
                                                    <a href="{{route('payment.services.show',$result->payment_transaction->payment_services->id)}}">{{  $result->payment_transaction->payment_services->{'name_'.$systemLang} }}</a>
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


                    <div class="col-md-4">

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
                                                    <a href="javascript:void(0);" onclick="get_transaction_data('{{route('payment.transactions.ajax-details',['id'=>$result->payment_transaction->id])}}')">
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
                                                        <i style="color: red;" class="fa fa-times"
                                                           aria-hidden="true"></i>
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
                                                    <a href="{{route('payment.services.show',$result->payment_transaction->payment_services_id)}}">{{$result->payment_transaction->payment_services_name}}</a>
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
                                                <td>{{__('Request Map')}}</td>
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
                                                                        <td>{{__($key)}}</td>
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


                                            <tr>
                                                <td>{{__('Response')}}</td>
                                                <td>
                                                    <table class="table table-condensed">
                                                        <tbody>
                                                        @if(is_array($result->payment_transaction->response) && !empty($result->payment_transaction->response))

                                                            @foreach($result->payment_transaction->response['data'] as $key => $value)
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
                                                                        <td>{{__($key)}}</td>
                                                                        <td>{{$value}}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
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


                        <div class="col-md-4">

                            <section class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{__('Wallet Data')}}</h4>
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
                                                    <td>{{__('Wallet Transaction ID')}}</td>
                                                    <td>
                                                        {{$result->wallet_transaction->id}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Amount')}}</td>
                                                    <td>
                                                        {{amount($result->wallet_transaction->amount)}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Type')}}</td>
                                                    <td>
                                                        {{ucfirst(__($result->wallet_transaction->type))}}
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Status')}}</td>
                                                    <td>
                                                        {{statusColor($result->wallet_transaction->status)}}
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Action')}}</td>
                                                    <td>
                                                        <button class="btn btn-primary" type="button" onclick="urlIframe('{{route('system.wallet.transactions.show',['ID'=>$result->wallet_transaction->id])}}')"><i class="ft-eye"></i></button>
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
        </div>
    </div>
@endsection

@section('header')
@endsection

@section('footer')
    <script type="text/javascript">
        function formSubmitChange($formID){
            formSubmit($formID,'{{route('payment.invoice.change-status')}}','post','changeInvoice',function($data){
                console.log($data);
                if($data != false){
                    if($data.status){
                        $('#changeInvoice_error').html('');
                        $('#changeInvoice_error').hide();
                        $('#changeInvoice-modal').modal('hide');
                        $($formID)[0].reset();
                        alertSuccess($data.data.msg);
                        setTimeout(function () {
                            location.reload();
                        },1000);
                    }
                }
            })
        }



        function changeInvoice(){
            $('#changeInvoice-modal').modal('show');
        }
    </script>
@endsection
