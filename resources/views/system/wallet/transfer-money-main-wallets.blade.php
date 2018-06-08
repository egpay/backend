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
                                <fieldset class="form-group">
                                    {{ Form::label('password',__('Password')) }}
                                    {!! Form::password('password',['class'=>'form-control','id'=>'confirm-password-input']) !!}
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
                            {!! Form::open(['id'=>'main-form','route' => $postRoute,'method' => 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-6{!! formError($errors,'transfer_type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('transfer_type', __('Transfer Type').':') !!}
                                                {!! Form::select('transfer_type',['transfer_in'=> __('Transfer In'),'transfer_out'=> __('Transfer Out')],null,['class'=>'form-control','onchange'=>'transfer_type_change();']) !!}
                                            </div>
                                            {!! formError($errors,'transfer_type') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'main_wallet_id',true) !!}">
                                            <div class="controls" id="main_wallet_content">
                                            </div>
                                            {!! formError($errors,'main_wallet_id') !!}
                                        </div>


                                        <div id="transfer_in_id">
                                            <div class="form-group col-sm-12{!! formError($errors,'amount_in',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('amount_in', __('Amount').':') !!}
                                                    {!! Form::number('amount_in',null,['class'=>'form-control','step'=>'0.01']) !!}
                                                </div>
                                                {!! formError($errors,'amount_in') !!}
                                            </div>
                                        </div>

                                        <div id="transfer_out_id" style="display: none;">

                                            <div class="form-group col-sm-6{!! formError($errors,'send_to',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('send_to', __('Send To').':') !!}
                                                    {!! Form::select('send_to',[''=>__('Select Supervisor')]+$sendTo,null,['style'=>'width: 100%;' ,'class'=>'form-control col-md-12']) !!}
                                                </div>
                                                {!! formError($errors,'send_to') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'amount_out',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('amount_out', __('Amount').':') !!}
                                                    {!! Form::number('amount_out',null,['class'=>'form-control','step'=>'0.01']) !!}
                                                </div>
                                                {!! formError($errors,'amount_out') !!}
                                            </div>
                                        </div>





                                        {!! Form::hidden('password',null,['id'=>'main-password']) !!}

                                    </div>
                                </div>
                            </div>



                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Transfer'),['class'=>'btn btn-success pull-right','onclick'=>'$(\'#confirm-password\').modal(\'show\');return false;']) !!}
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


        $(document).ready(function(){
            transfer_type_change();
        });

        function transfer_type_change(){
            $value = $('#transfer_type').val();
            if($value == 'transfer_in'){
                var html = "<label for=\"transfer_type\">{{__('Wallet')}}:</label>\n" +
                    "<select class=\"form-control\" id=\"main_wallet_id\" name=\"main_wallet_id\">\n" +
                        @foreach($walletIN as $key => $value)
                            @if(!empty($value->wallet->isNotEmpty()))
                                "<optgroup label=\"{{$value->name}}\">"+
                                    @foreach($value->wallet as $kWallet => $vWallet)
                                        "<option value=\"{{$vWallet->id}}\">{{ucfirst($vWallet->type)}} ({{amount($vWallet->balance,true)}})</option>\n" +
                                    @endforeach
                                "</optgroup>"+
                            @endif
                        @endforeach
                    "</select>";
                $('#main_wallet_content').html(html);

                $('#transfer_out_id').hide();
                $('#transfer_in_id').show();

            }else{
                var html = "<label for=\"transfer_type\">{{__('Wallet')}}:</label>\n" +
                    "<select class=\"form-control\" id=\"main_wallet_id\" name=\"main_wallet_id\">\n" +
                        @foreach($walletOUT as $key => $value)
                            @if(!empty($value->wallet->isNotEmpty()))
                                "<optgroup label=\"{{$value->name}}\">"+
                                    @foreach($value->wallet as $kWallet => $vWallet)
                                        "<option value=\"{{$vWallet->id}}\">{{ucfirst($vWallet->type)}} ({{amount($vWallet->balance,true)}})</option>\n" +
                                    @endforeach
                                "</optgroup>"+
                            @endif
                        @endforeach
                    "</select>";
                $('#main_wallet_content').html(html);

                $('#transfer_in_id').hide();
                $('#transfer_out_id').show();

            }
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