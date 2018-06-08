@extends('system.layouts')

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
                                        </div>
                                    </div>
                                </div>
                            @elseif(Session::has('status'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-{{Session::get('status')}}">
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                                {!! Form::open(['route' => isset($order->id) ? ['merchant.order.update',$order->id]:'merchant.order.store','files'=>true, 'method' => isset($order->id) ?  'PATCH' : 'POST']) !!}
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Merchant')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12 {!! formError($errors,'merchant_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_id', __('Merchant').':') !!}

                                                    @if(isset($order->merchant))
                                                        {!! Form::text('merchant_text', $order->merchant->{'name_'.$systemLang}.' #ID: '.$order->merchant->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                        {!! Form::hidden('merchant_id',null,['id'=>'new_merchant_id']) !!}
                                                    @else

                                                        @if(isset($result->id))
                                                            {!! Form::select('merchant_id',[$result->merchant->id => $result->merchant->{'name_'.$systemLang}.' #ID: '.$result->merchant_id],isset($result->id) ? $result->merchant_id:old('merchant_id'),['class'=>'select2 form-control']) !!}
                                                        @else
                                                            {!! Form::select('merchant_id',[__('Select Merchant')],old('merchant_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                        @endif
                                                    @endif


                                                </div>
                                                {!! formError($errors,'merchant_id') !!}
                                            </div>



                                            <div class="col-sm-6">
                                                <div class="form-group col-sm-12{!! formError($errors,'merchant_branch_id',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('merchant_branch_id', __('Branch').':') !!}
                                                        @if(isset($order->id))
                                                            {!! Form::select('merchant_branch_id',[$order->merchant_branch_id => $order->merchant_branch->{'name_'.$systemLang}.' #ID: '.$order->merchant_branch_id],isset($order->id) ? $order->merchant_branch_id:old('merchant_branch_id'),['class'=>'form-control']) !!}
                                                        @else
                                                            {!! Form::select('merchant_branch_id',[],old('merchant_branch_id'),['style'=>'width: 100%;','class'=>'form-control']) !!}
                                                        @endif
                                                    </div>
                                                    {!! formError($errors,'merchant_branch_id') !!}
                                                </div>

                                                <div class="form-group col-sm-12{!! formError($errors,'merchant_product_category_id',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('merchant_product_category_id', __('Category').':') !!}
                                                        {!! Form::select('merchant_product_category_id',((isset($order))?$order->productCategories:[]),old('merchant_product_category_id'),['class'=>'form-control']) !!}
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
                                                            <button class="btn btn-primary mt-1 addusertoorder">
                                                                <i class="fa fa-plus"></i>
                                                                <span>{{__('Add User')}}</span>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {!! formError($errors,'amount') !!}

                                                </div>

                                                <hr>
                                            @if(isset($order))
                                                <table id="users" class="table col-sm-8 col-center">
                                            @else
                                                <table id="users" class="table col-sm-8 col-center hidden">
                                            @endif
                                                    <thead>
                                                    <tr class="font-weight-bold">
                                                        <td>{{__('ID')}}</td>
                                                        <td>{{__('Payment')}}</td>
                                                        <td>{{__('Amount')}}</td>
                                                        <td>{{__('Status')}}</td>
                                                        <td>{{__('Remove')}}</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(isset($order->id))
                                                        @foreach($order->trans->whereIn('status',$order->transToShow) as $onetransaction)
                                                            <tr id="{{\App\Models\Wallet::where('id',$onetransaction->from_id)->first()->walletowner()->first()->id.$onetransaction->type}}">
                                                                <td>
                                                                    {{\App\Models\Wallet::where('id',$onetransaction->from_id)->first()->walletowner()->first()->mobile}}
                                                                    #ID{{\App\Models\Wallet::where('id',$onetransaction->from_id)->first()->walletowner()->first()->id}}
                                                                    <input type="hidden" name="users[]" value="{{\App\Models\Wallet::where('id',$onetransaction->from_id)->first()->walletowner()->first()->id}}">
                                                                </td>
                                                                <td>
                                                                    {{(($onetransaction->type=='wallet')?__('Wallet'):__('Cash'))}}
                                                                    <input type="hidden" name="paytype[]" value="{{$onetransaction->type}}"></td>
                                                                <td>
                                                                    <span class="useramount">{{$onetransaction->amount}}</span>
                                                                    <input type="hidden" name="useramount[]" value="{{$onetransaction->amount}}" class="form-control">
                                                                </td>
                                                                <td>
                                                                    <span>{{$onetransaction->status}}</span>
                                                                </td>
                                                                <td><a href="javascript:void(0);" class="removeuser"><i class="text-danger fa fa-trash"></i></a>
                                                                    <input type="hidden" name="transaction[]" value="{{$onetransaction->id}}"></td></tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <td colspan="2">{{__('Total')}}</td>
                                                        <td colspan="2" class="userstotal">
                                                            @if(isset($order->id))
                                                                {{$order->transactions->whereIn('status',$order->transToShow)->sum('amount')}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    </tfoot>
                                                </table>


                                            </div>

                                            @if(isset($order))
                                                <table id="products" class="table col-sm-8 col-center">
                                            @else
                                                <table id="products" class="table col-sm-8 col-center hidden">
                                            @endif
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
                                                    @foreach($order->orderitems as $oneitem)
                                                        <tr class="row" id="{{$oneitem->id}}" data-attribute="{{(($oneitem->orderItemAttribute()->count())?$oneitem->orderItemAttribute()->count():'')}}">
                                                            <td>
                                                                {{$oneitem->merchant_product_id}}
                                                                <input type="hidden" name="product[]" value="{{$oneitem->merchant_product_id}}">
                                                            </td>
                                                            <td>{{$oneitem->merchant_product->$colName}} #ID:{{$oneitem->merchant_product->id}}</td>
                                                            <td class="proprice">{{$oneitem->price/$oneitem->qty}}</td>
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
                                                            {{$order->orderitems->sum('qty')}}
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
    <script src="{{asset('assets/system/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/merchant/vendors/validate/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/merchant/order/create.order.js')}}"></script>
    <script>
        ajaxSelect2('#merchant_id','merchant');

        ajaxSelect2('#user_id','customer',11);

        $('#merchant_id').on('select2:select', function (e) {
            var param = e.params.data;
            $.getJSON('{{route('system.ajax.get')}}',{type:'getMerchantBranches',merchant_id:param.id},function(data){
                $.each(data,function(i,val){
                    $('#merchant_branch_id').append($('<option>',{value:val.id,text:val.name}));
                    $('#merchant_branch_id').select2({}).trigger('change');
                });
            });

            $.getJSON('{{route('system.ajax.get')}}',{type:'getProductCategory',merchant_id:param.id},function(data){
                $.each(data,function(i,val){
                    $('#merchant_product_category_id').append($('<option>',{value:val.id,text:val.name}));
                    $('#merchant_product_category_id').select2();
                });
            });


        });

        $('#product_id').select2({
            placeholder: '{{__('Select Product')}}'
        });

        $('#merchant_product_category_id').on('select2:select change',function(){
            GetProducts('{{route('system.ajax.get')}}',$(this).val());
        });

        $('table#products').on('click','.removeproduct',function(){
            $(this).parents('tr').remove();
            calculatetotall();
        });

        $('#product_id').on('change',function(){
            ProductAttributes(this,'{{route('system.ajax.get')}}');
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

        @if(isset($order))
            $(function(){
                $.each($('#products tbody tr'),function(i,tr){
                    var hash = hashFnv32a($(tr).find('td:nth(1)').text() + $(tr).attr('data-attribute'));
                    $(tr).attr('id',hash);
                    $(tr).find('td:nth(0) input').attr('name','product['+hash+'][id]');
                    $(tr).find('td:nth(3) input').attr('name','product['+hash+'][qty]');
                });
                $.each($('#users tbody tr'),function(x,utr){
                    var hash = hashFnv32a($(utr).attr('id'));
                    $(utr).attr('id',hash);
                    $(utr).find('td:nth(0) input').attr('name','users['+hash+'][id]');
                    $(utr).find('td:nth(1) input').attr('name','users['+hash+'][paytype]');
                    $(utr).find('td:nth(2) input').attr('name','users['+hash+'][amount]');
                    $(utr).find('td:nth(4) input').attr('name','users['+hash+'][transaction]');
                });
            });
        @endif


    </script>
@endsection