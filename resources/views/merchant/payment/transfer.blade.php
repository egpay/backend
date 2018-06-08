@extends('merchant.payment.layout')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="pull-left">{{$pageTitle}}</h2>
                    <h2 class="pull-right">
                        <label>{{__('Balance')}}</label>
                        <span id="balance" class="font-weight-bold">{{number_format($balance,2)}} {{__('LE')}}</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="card" id="transfer">
                <div class="card-header">
                    <h2>{{__('Transfer')}}</h2>
                </div>

                <div class="card-block">
                    {!! Form::open(['route' => 'panel.merchant.payment.transfer.do', 'method' => 'POST']) !!}

                    <div class="col-sm-7 col-center">
                        {!! Form::hidden('url',request()->fullUrl(),['class'=>'form-control']) !!}

                        <div class="form-group col-sm-12{!! formError($errors,'wallet_id',true) !!}">
                            <div class="controls">
                                {!! Form::label('wallet_id', __('Wallet ID').':') !!}
                                {!! Form::number('wallet_id',old('wallet_id'),['class'=>'form-control']) !!}
                            </div>
                            {!! formError($errors,'wallet_id') !!}
                        </div>

                        <div class="form-group col-sm-12{!! formError($errors,'wallet_id_confirmation',true) !!}">
                            <div class="controls">
                                {!! Form::label('wallet_id_confirmation', __('Wallet ID confirmation').':') !!}
                                {!! Form::number('wallet_id_confirmation',old('wallet_id'),['class'=>'form-control']) !!}
                            </div>
                            {!! formError($errors,'wallet_id_confirmation') !!}
                        </div>

                        <div class="form-group col-sm-12{!! formError($errors,'amount',true) !!}">
                            <div class="controls">
                                {!! Form::label('amount', __('Amount').':') !!}
                                {!! Form::number('amount',old('amount'),['class'=>'form-control']) !!}
                            </div>
                            {!! formError($errors,'amount') !!}
                        </div>

                        <div class="col-xs-12">
                            {!! Form::submit(__('Transfer'),['class'=>'btn btn-success pull-right']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
    <div class="row clearfix"></div>

@endsection


@section('header')
    <link rel="stylesheet" href="{{asset('assets/merchant/payment/payment.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/fonts/receipt.css')}}">
    <link rel="stylesheet" href="{{asset('assets/system/vendors/css/extensions/sweetalert.css')}}">
@endsection

@section('footer')
    <script src="{{asset('assets/merchant/form/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/system/vendors/js/extensions/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/merchant/payment/payment.js')}}"></script>
@endsection