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
                            {!! Form::open(['route' => isset($result->id) ? ['payment.services.update',$result->id]:'payment.services.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}

                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-6{!! formError($errors,'payment_sdk_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('payment_sdk_id', __('Payment SDK').':') !!}
                                                {!! Form::select('payment_sdk_id',['0'=>__('Select Payment SDK')]+array_column($PaymentSDK->toArray(),'name','id'),isset($result->id) ? $result->payment_sdk_id:old('payment_sdk_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'payment_sdk_id') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'payment_service_provider_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('payment_service_provider_id', __('Payment Service Provider').':') !!}
                                                {!! Form::select('payment_service_provider_id',['0'=>__('Payment Service Provider')]+array_column($PaymentServiceProviders->toArray(),'name','id'),isset($result->id) ? $result->payment_service_provider_id:old('payment_service_provider_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'payment_service_provider_id') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'payment_output_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('payment_output_id', __('Payment Output').':') !!}
                                                {!! Form::select('payment_output_id',['0'=>__('Payment Output')]+array_column($PaymentOutput->toArray(),'name','id'),isset($result->id) ? $result->payment_output_id:old('payment_output_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'payment_output_id') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'commission_list_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('commission_list_id', __('Commission List').':') !!}
                                                {!! Form::select('commission_list_id',['0'=>__('Commission List')]+array_column($CommissionList->toArray(),'name','id'),isset($result->id) ? $result->commission_list_id:old('commission_list_id'),['class'=>'form-control select2']) !!}
                                            </div>
                                            {!! formError($errors,'commission_list_id') !!}
                                        </div>

                                    </div>
                                </div>
                            </div>


                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Arabic')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_ar', __('Name (AR)').':') !!}
                                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Description (AR)').':') !!}
                                                    {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'description_ar') !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('English')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_en', __('Name (EN)').':') !!}
                                                    {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_en') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_en', __('Description (EN)').':') !!}
                                                    {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'description_en') !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="card">

                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-4{!! formError($errors,'icon',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('icon', __('Icon').':') !!}
                                                    {!! Form::file('icon',['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'icon') !!}
                                            </div>


                                            <div class="form-group col-sm-4{!! formError($errors,'status',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('status', __('Status').':') !!}
                                                    {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'status') !!}
                                            </div>

                                            <div class="form-group col-sm-4{!! formError($errors,'request_amount_input',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('request_amount_input', __('Request Amount (Input)').':') !!}
                                                    {!! Form::select('request_amount_input',['no'=>__('No'),'yes'=>__('Yes')],isset($result->id) ? $result->request_amount_input:old('request_amount_input'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'request_amount_input') !!}
                                            </div>

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
            $('.select2').select2();
        });
    </script>
@endsection