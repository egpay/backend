@extends('system.layouts')

<div class="modal fade text-xs-left" id="confirm-password"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Confirm Password')}}</label>
            </div>
            {!! Form::open(['onsubmit'=>'confirmPaymentPassword();return false;']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">

                            <div class="col-md-12">
                                <table class="table">
                                    <tbody id="wallet-data-information">
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('password',__('Password')) }}
                                    {!! Form::password('password',['class'=>'form-control','autocomplete'=>'off','id'=>'confirm-password-input']) !!}
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-outline-primary btn-md wallet-loading" value="{{__('Submit')}}">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <style type="text/css">
        .balance-div {
            background-color: cadetblue;
            border-radius: 40px !important;
        }
    </style>
@endsection
@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
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
                            @if($errors->any())
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-danger">
                                            {{__('Some fields are invalid please fix them')}}
                                            <ul>
                                                @foreach($errors->all() as $value)
                                                    <li>{{$value}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @elseif(Session::has('transactionStatus'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert @if(!is_object(session('transactionStatus'))) alert-danger @else alert-success @endif">
                                            @if(!is_object(session('transactionStatus')))
                                                {{__('Unable to transfer money')}}
                                            @else
                                                {!! __('The money was successfully transferred (Transaction ID: :id )',['id'=>'<a href="'.route('system.wallet.transactions.show',['id'=>session('transactionStatus')->id]).'">'.session('transactionStatus')->id.'</a>']) !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if(request()->route()->getName() == 'system.wallet.transferMoneyTwoWallets')
                                {!! Form::open(['id'=>'main-form','route' => 'system.wallet.transferMoneyTwoWalletsPost','method' => 'POST']) !!}
                            @else
                                {!! Form::open(['id'=>'main-form','route' => 'system.wallet.transferMoneyWalletsPost','method' => 'POST']) !!}
                            @endif

                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-block card-dashboard">
                                        @if(request()->route()->getName() == 'system.wallet.transferMoneyWallets')
                                        <div class="col-sm-12">
                                            <div style="text-align: center;">
                                                <div style="font-size: 16px; float: inherit;" class="balance-div tag tag-default mb-1 col-md-6 col-xs-12"> {{__('Your balance is:')}} {{amount(Auth::user()->paymentWallet->balance,true)}}</div>
                                            </div>
                                        </div>
                                        @endif


                                        @if(request()->route()->getName() == 'system.wallet.transferMoneyTwoWallets')

                                                <div class="form-group col-sm-3{!! formError($errors,'send_from',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('send_from', __('From (Wallet ID)').':') !!}
                                                        {!! Form::number('send_from',null,['class'=>'form-control','required']) !!}
                                                    </div>
                                                    {!! formError($errors,'send_from') !!}
                                                </div>

                                                <div class="form-group col-sm-3{!! formError($errors,'send_to',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('send_to', __('To (Wallet ID)').':') !!}
                                                        {!! Form::number('send_to',null,['class'=>'form-control','required']) !!}
                                                    </div>
                                                    {!! formError($errors,'send_to') !!}
                                                </div>

                                        @else
                                            <div class="form-group col-sm-6{!! formError($errors,'send_to',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('send_to', __('Send To (Wallet ID)').':') !!}
                                                    {!! Form::number('send_to',null,['class'=>'form-control','required']) !!}
                                                </div>
                                                {!! formError($errors,'send_to') !!}
                                            </div>
                                        @endif

                                        <div class="form-group col-sm-6{!! formError($errors,'amount',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('amount', __('Amount').':') !!}
                                                {!! Form::number('amount',old('amount'),['class'=>'form-control','id'=>'amount','required']) !!}
                                            </div>
                                            {!! formError($errors,'amount') !!}
                                        </div>

                                        {!! Form::hidden('password',null,['id'=>'main-password']) !!}

                                    </div>
                                </div>
                            </div>



                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Transfer'),['id'=>'checkDataBeforeTransfareID','class'=>'btn btn-success pull-right','onclick'=>'checkDataBeforeTransfare();return false;']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <script type="text/javascript">


        function checkDataBeforeTransfare(){

            $send_from = $('#send_from').val();
            $send_to = $('#send_to').val();

            @if(request()->route()->getName() == 'system.wallet.transferMoneyTwoWallets')
                if(empty($send_from)){
                    return false;
                }

                $jsonURL = 'walletInfo='+$send_to+'&walletInfoFrom='+$send_from;
            @else
                $jsonURL = 'walletInfo='+$send_to;
            @endif

            if(empty($send_to)){
                return false;
            }


            $('#checkDataBeforeTransfareID').val('Loading...').prop('disabled',true);



            $.getJSON('{{route('system.wallet.index')}}?'+$jsonURL,function($data){
                @if(request()->route()->getName() == 'system.wallet.transferMoneyTwoWallets')
                    $html = '<tr>\n' +
                    '\t<td>{{__('From')}}</td>\n' +
                    '        <td><a href="{{url('/system/wallet/')}}/'+$send_from+'">'+$data.from_name+'</a></td>\n' +
                    '</tr>';
                    $html += '<tr>\n' +
                        '\t<td>{{__('From Balance')}}</td>\n' +
                        '        <td>'+$data.from_balance+'</td>\n' +
                        '</tr>';



                $html += '<tr>\n' +
                    '\t<td>{{__('To')}}</td>\n' +
                    '        <td><a href="{{url('/system/wallet/')}}/'+$send_to+'">'+$data.name+'</a></td>\n' +
                    '</tr>';
                    $html += '<tr>\n' +
                        '\t<td>{{__('To Balance')}}</td>\n' +
                        '        <td>'+$data.balance+'</td>\n' +
                        '</tr>';


                @else
                    $html = '<tr>\n' +
                    '\t<td>{{__('Wallet Owner')}}</td>\n' +
                    '        <td><a href="{{url('/system/wallet/')}}/'+$send_to+'">'+$data.name+'</a></td>\n' +
                    '</tr>';
                    if(isset($data.balance)){
                        $html += '<tr>\n' +
                            '\t<td>{{__('Balance')}}</td>\n' +
                            '        <td>'+$data.balance+'</td>\n' +
                            '</tr>';
                    }
                @endif

                $('#wallet-data-information').html($html);

                $('#confirm-password').modal('show');
                $('#checkDataBeforeTransfareID').removeAttr('disabled').val('{{__('Transfer')}}');
            }).error(function() {
                $('#checkDataBeforeTransfareID').removeAttr('disabled').val('{{__('Transfer')}}');
            });
        }

        function confirmTransfer(){
            $to = $("#send_to option:selected").text();
            $amount = $('#amount').val();
            $('#confirm-password').modal('show');

            return false;
            // return confirm("Do you want to transfer "+$amount+" LE to "+$to);
        }

        function confirmPaymentPassword(){
            $('.wallet-loading').val('Loading...').prop('disabled',true);
            $('#main-password').val($('#confirm-password-input').val());
            $('#main-form').submit();
        }

    </script>
@endsection