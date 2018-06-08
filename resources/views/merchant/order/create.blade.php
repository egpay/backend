@extends('merchant.layouts')

@section('content')
                <div class="card">
                    <div class="card-header">
                        <h2>{{$pageTitle}}</h2>
                    </div>
                </div>

            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                        {!! Form::open(['route' => isset($order->id) ? ['panel.merchant.order.update',$order->id]:'panel.merchant.order.store','files'=>true, 'method' => isset($order->id) ?  'PATCH' : 'POST']) !!}
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">
                                        <div class="col-sm-6">
                                            <div class="form-group col-sm-12{!! formError($errors,'merchant_branch_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_branch_id', __('Branch').':') !!}
                                                    @if(isset($order->id))
                                                        {!! Form::select('merchant_branch_id',[$order->merchant_branch_id => $order->merchant_branch->{'name_'.$systemLang}.' #ID: '.$order->merchant_branch_id],isset($order->id) ? $order->merchant_branch_id:old('merchant_branch_id'),['class'=>'form-control']) !!}
                                                    @else
                                                        {!! Form::select('merchant_branch_id',$merchantBranches,old('merchant_branch_id'),['style'=>'width: 100%;','class'=>'form-control']) !!}
                                                    @endif
                                                </div>
                                                {!! formError($errors,'merchant_branch_id') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'merchant_product_category_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_product_category_id', __('Category').':') !!}
                                                    {!! Form::select('merchant_product_category_id',[],old('merchant_product_category_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'merchant_product_category_id') !!}
                                            </div>


                                            <div class="form-group col-sm-12{!! formError($errors,'product_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('product_id', __('Product').':') !!}
                                                    @if(isset($order->id))
                                                        {!! Form::select('product_id',[],isset($order->id) ? $order->product_id:old('product_id'),['class'=>'form-control']) !!}
                                                    @else
                                                        {!! Form::select('product_id',[],old('product_id'),['style'=>'width: 100%;','class'=>'form-control']) !!}
                                                    @endif
                                                </div>
                                                {!! formError($errors,'product_id') !!}
                                            </div>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group col-sm-12{!! formError($errors,'user_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('user_id', __('User').':') !!}
                                                    <a class="pull-right" href=""><i class="fa fa-qrcode fa-lg"></i></a>
                                                    {!! Form::select('user_id',[],old('user_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'user_id') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('type', __('Payment type').':') !!}
                                                    {!! Form::select('type',$paymenttype,old('type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'type') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'amount',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('amount', __('Amount').':') !!}
                                                    {!! Form::number('amount',null,['class'=>'form-control']) !!}
                                                    <div class="pull-right">
                                                        <button class="btn btn-success mt-1 addusertoorder">
                                                            <i class="fa fa-plus"></i>
                                                            <span>{{__('Add User')}}</span>
                                                        </button>
                                                    </div>
                                                </div>

                                                {!! formError($errors,'amount') !!}

                                            </div>

                                            <hr>

                                            <table id="users" class="table col-sm-8 col-center hidden">
                                                <thead>
                                                <tr class="font-weight-bold">
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{__('Payment')}}</td>
                                                    <td>{{__('Amount')}}</td>
                                                    <td>{{__('Remove')}}</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @if(isset($order->id))
                                                        @foreach($transactions as $onetransaction)
                                                            <tr id="{{$onetransaction->from_id.$onetransaction->type}}">
                                                                <td>
                                                                    {{\App\Models\Wallet::where('id',$onetransaction->from_id)->first()->walletowner()->first()->mobile}}
                                                                    #ID{{\App\Models\Wallet::where('id',$onetransaction->from_id)->first()->walletowner()->first()->id}}
                                                                    <input type="hidden" name="users[]" value="{{$onetransaction->from_id}}"></td>
                                                                <td>
                                                                    {{(($onetransaction->type=='wallet')?'Via Egpay':'Cash')}}
                                                                    <input type="hidden" name="paytype[]" value="{{$onetransaction->type}}"></td>
                                                                <td>
                                                                    <span class="useramount">{{$onetransaction->amount}}</span>
                                                                    <input type="hidden" name="useramount[]" value="{{$onetransaction->amount}}" class="form-control"></td>
                                                                <td><a href="javascript:void(0);" class="removeuser"><i class="text-danger fa fa-trash"></i></a></td></tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2">{{__('Total')}}</td>
                                                        <td colspan="2" class="userstotal">
                                                            @if(isset($order->id))
                                                                {{$transactions->sum('amount')}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>


                                        </div>

                                        <table id="products" class="table col-sm-8 col-center hidden">
                                            <thead>
                                                <tr class="font-weight-bold">
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{__('Name')}}</td>
                                                    <td>{{__('Price')}}</td>
                                                    <td>{{__('Qty')}}</td>
                                                    <td>{{__('Remove')}}</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($order->id))
                                                    @foreach($items as $oneitem)
                                                        <tr class="row" id="{{$oneitem->id}}">
                                                            <td>
                                                                {{$oneitem->merchant_product_id}}
                                                                <input type="hidden" name="product[]" value="{{$oneitem->merchant_product_id}}">
                                                            </td>
                                                            <td>{{$oneitem->merchant_product->name_en}} - {{$oneitem->merchant_product->name_ar}}</td>
                                                            <td class="proprice">{{$oneitem->price}}</td>
                                                            <td><input type="number" name="qty[]" class="form-control" value="{{$oneitem->qty}}"></td>
                                                            <td>
                                                                <a href="javascript:void(0);" onclick="$(this).parents('tr').remove();">
                                                                    <i class="text-danger fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                @endif

                                            </tbody>
                                            <tfoot>
                                                <tr class="font-weight-bold">
                                                    <td colspan="2">{{__('Total')}}</td>
                                                    <td class="total">
                                                        @if(isset($order->id))
                                                            {{$order->total}}
                                                        @endif
                                                    </td>
                                                    <td colspan="2" class="total_qty">
                                                        @if(isset($order->id))
                                                            {{$items->sum('qty')}}
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tfoot>

                                        </table>


                                    </div>
                                </div>
                            </div>


                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right','id'=>'saveorder']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div id="productAttributes" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Product Options')}}</h4>
                </div>
                <div class="modal-body">
                    <form id="options-form">

                    </form>
                </div>
                <div class="modal-footer">
                    <div id="messageBox"></div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system')}}/vendors/css/forms/selects/select2.min.css">
@endsection

@section('footer')
    <script src="{{asset('assets/system/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/merchant/vendors/validate/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/merchant/order/create.order.js')}}"></script>
    <script>
        ajaxSelect2('#merchant_product_category_id','productcategory',3);
        ajaxSelect2('#user_id','customer',11);

        $('#product_id').select2({
            placeholder: '{{__('Select Product')}}'
        });

        $('#merchant_product_category_id').on('change',function(){
            GetProducts('{{route('panel.merchant.get')}}',$(this).val());
        });

        $('table#products').on('click','.removeproduct',function(){
            $(this).parents('tr').remove();
            calculatetotall();
        });

        $('#product_id').on('change',function(){
            ProductAttributes(this,'{{route('panel.merchant.get')}}');
            calculatetotall();
        });

        $('table#products').on('change keyup',$('input[name^="qty"]'),function(){
            calculatetotall();
        });



        $('.addusertoorder').on('click',function(e){
            e.preventDefault();
            var error = false;
            AddUserToOrder();

            calculateuserstotal();

        });

        $('table#users').on('click','.removeuser',function(){
            $(this).parents('tr').remove();
            calculateuserstotal();
        });

        $('#saveorder').on('click',function(e){
            if(Checktotal()){
                return true;
            } else {
                e.preventDefault();
                $('.total').addClass('text-danger');
                $('.userstotal').addClass('text-danger');
            }
        });



    </script>
@endsection