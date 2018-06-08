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
                            {!! Form::open(['route' => isset($result->id) ? ['merchant.coupon.update',$result->id]:'merchant.coupon.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Merchant & coupon type')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-4{!! formError($errors,'type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('type', __('Coupon type').':') !!}
                                                {!! Form::select('type',['product'=>__('E-Commerce coupon'),'service'=>__('E-Payment coupon')],isset($result->id) ? $result->type:old('type'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'type') !!}
                                        </div>

                                        <div class="form-group col-sm-8 {!! formError($errors,'merchant_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_id', __('Merchant').':') !!}

                                                @if(isset($merchantData))
                                                    {!! Form::text('merchant_text', $merchantData->{'name_'.$systemLang}.' #ID: '.$merchantData->id,['class'=>'form-control','readonly'=>'readonly']) !!}
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Coupon data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="input-group col-sm-12{!! formError($errors,'code',true) !!}">
                                            {!! Form::label('code', __('Coupon code')) !!}
                                            <div class="input-group">
                                                {!! Form::text('code',isset($result->id) ? $result->code:old('code'),['class'=>'form-control','id'=>'code']) !!}
                                                <span class="input-group-btn">
                                                <button onclick="generateCode(6,$('#code'))" class="btn btn-primary" type="button">
                                                    <i class="fa fa-refresh"></i>
                                                </button>
                                            </span>
                                            </div>
                                            {!! formError($errors,'code') !!}
                                        </div>

                                        <div class="mt-1 form-group col-sm-6{!! formError($errors,'description_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_en', __('Description (English)').':') !!}
                                                {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'description_en') !!}
                                        </div>

                                        <div class="mt-1 form-group col-sm-6{!! formError($errors,'description_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_ar', __('Description (Arabic)').':') !!}
                                                {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'description_ar') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'reward_type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('reward_type', __('Reward type').':') !!}
                                                {!! Form::select('reward_type',['fixed'=>__('Fixed'),'percentage'=>__('Percentage')],isset($result->id) ? $result->reward_type:old('reward_type'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'reward_type') !!}
                                        </div>
                                        <div class="form-group col-sm-5{!! formError($errors,'reward',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('reward', __('Reward amount').':') !!}
                                                {!! Form::number('reward',isset($result->id) ? $result->reward:old('reward'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'reward') !!}
                                        </div>
                                        <div class="form-group col-sm-3{!! formError($errors,'quantity',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('quantity', __('Quantity').':') !!}
                                                {!! Form::number('quantity',isset($result->id) ? $result->quantity:(old('quantity')?old('quantity'):0),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'quantity') !!}
                                        </div>

                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                {{ Form::label('start_date',__('Start date')) }}
                                                {!! Form::text('start_date',isset($result->id) ? $result->start_date:old('start_date'),['class'=>'form-control datepicker','id'=>'start_date']) !!}
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6">
                                            <fieldset class="form-group">
                                                {{ Form::label('end_date',__('End date')) }}
                                                {!! Form::text('end_date',isset($result->id) ? $result->end_date:old('end_date'),['class'=>'form-control datepicker','id'=>'end_date']) !!}
                                            </fieldset>
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'user_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('user_id', __('User').':') !!}
                                                {!! Form::select('user_id',[],old('user_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'user_id') !!}
                                        </div>

                                        <table id="users" class="table table-stripped {{((isset($result) && ($result->objUsers))?'':'hidden')}}" data-route="{{route('system.users.show',['id'=>1])}}">
                                            <thead>
                                            <tr>
                                                <td>{{__('#ID')}}</td>
                                                <td>{{__('User')}}</td>
                                                <td>{{__('Remove')}}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($result) && ($result->objUsers))
                                                @foreach($result->objUsers as $user)
                                                <tr id="{{$user->id}}">
                                                    <td>{{$user->id}}</td>
                                                    <td>{{link_to_route('system.users.show',$user->mobile,['id'=>$user->id])}}
                                                        {!! Form::hidden('users[]',$user->id,['class'=>'form-control']) !!}
                                                    </td>
                                                    <td><a href="javascript:void(0);" class="removeUser"><i class="text-danger fa fa-trash"></i></a></td>
                                                </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>


                                        <hr>

                                        <div class="productDiv form-group col-sm-4{!! formError($errors,'product_category_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('product_category_id', __('Product category').':') !!}
                                                {!! Form::select('product_category_id',[],old('product_category_id'),['class'=>'form-control','id'=>'product_category_id']) !!}
                                            </div>
                                            {!! formError($errors,'product_category_id') !!}
                                        </div>

                                        <div class="productDiv form-group col-sm-8{!! formError($errors,'product_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('product_id', __('Product').':') !!}
                                                {!! Form::select('product_id',[],old('product_id'),['class'=>'form-control','id'=>'product_id','data-route'=>route('merchant.product.show',['id'=>1])]) !!}
                                            </div>
                                            {!! formError($errors,'product_id') !!}
                                        </div>


                                        <div class="serviceDiv hidden form-group col-sm-4{!! formError($errors,'payment_service_category_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('payment_service_category_id', __('Category').':') !!}
                                                {!! Form::select('payment_service_category_id',[],old('payment_service_category_id'),['class'=>'form-control','id'=>'payment_service_category_id','style'=>'width: 100%;']) !!}
                                            </div>
                                            {!! formError($errors,'payment_service_category_id') !!}
                                        </div>

                                        <div class="serviceDiv hidden form-group col-sm-4{!! formError($errors,'payment_service_provider_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('payment_service_provider_id', __('Provider').':') !!}
                                                {!! Form::select('payment_service_provider_id',[],old('payment_service_provider_id'),['class'=>'form-control','id'=>'payment_service_provider_id']) !!}
                                            </div>
                                            {!! formError($errors,'payment_service_provider_id') !!}
                                        </div>


                                        <div class="serviceDiv hidden form-group col-sm-4{!! formError($errors,'service_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('service_id', __('Service').':') !!}
                                                {!! Form::select('service_id',[],old('service_id'),['class'=>'form-control','id'=>'service_id','data-route'=>route('payment.services.show',['id'=>1])]) !!}
                                            </div>
                                            {!! formError($errors,'service_id') !!}
                                        </div>



                                        <table id="items" class="table table-stripped {{((isset($result) && ($result->objItems))?'':'hidden')}}">
                                            <thead>
                                            <tr>
                                                <td>{{__('#ID')}}</td>
                                                <td>{{__('Product')}}</td>
                                                <td>{{__('Price')}}</td>
                                                <td>{{__('Remove')}}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($result) && ($result->objItems))
                                                @foreach($result->objItems as $item)
                                                    <tr id="{{$item->id}}">
                                                        <td>{{$item->id}}</td>
                                                        <td>
                                                            @if($result->type == 'product')
                                                                {{link_to_route('merchant.product.show',$item->{'name_'.$lang},['id'=>$item->id])}}
                                                            @else
                                                                {{link_to_route('payment.services.show',$item->{'name_'.$lang},['id'=>$item->id])}}
                                                            @endif
                                                            {!! Form::hidden('items[]',$item->id,['class'=>'form-control']) !!}
                                                        </td>
                                                        <td>{{$item->price}}</td>
                                                        <td><a href="javascript:void(0);" class="removeItem"><i class="text-danger fa fa-trash"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                <!--/ Javascript sourced data -->
            </div>
            </section>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
@endsection

@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>

    <script>
        ajaxSelect2('#merchant_id','merchant');
        ajaxSelect2('#user_id','customer',11);


        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });

    </script>
            <script src="{{asset('assets/system/js/scripts/custom/coupon.js')}}"></script>
@endsection