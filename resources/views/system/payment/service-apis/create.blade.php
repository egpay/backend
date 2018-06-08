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
                            {!! Form::open(['route' => isset($result->id) ? ['payment.service-api.update',$result->id]:'payment.service-api.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">


                                        <div class="form-group col-sm-4{!! formError($errors,'name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name', __('Name').':') !!}
                                                {!! Form::text('name',isset($result->id) ? $result->name:old('name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'payment_service_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('payment_service_id', __('Payment Service').':') !!}
                                                {!! Form::select('payment_service_id',[''=>'Select Payment Service']+array_column($paymentServices->toArray(),'name','id') ,isset($result->id) ? $result->payment_service_id:old('payment_service_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'payment_service_id') !!}
                                        </div>


                                        <div class="form-group col-sm-4{!! formError($errors,'service_type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('service_type', __('Service Type').':') !!}
                                                {!! Form::select('service_type',[''=>__('Select Service Type'),'payment'=>__('Payment'),'inquiry'=>__('Inquiry'),'inquire'=>__('Inquire')] ,isset($result->id) ? $result->service_type:old('service_type'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'service_type') !!}
                                        </div>







                                        <div class="form-group col-sm-12{!! formError($errors,'description',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description', __('Description').':') !!}
                                                {!! Form::textarea('description',isset($result->id) ? $result->description:old('description'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'description') !!}
                                        </div>



                                    </div>
                                </div>
                            </div>


                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-6{!! formError($errors,'external_system_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('external_system_id', __('External System ID').':') !!}
                                                    {!! Form::number('external_system_id',isset($result->id) ? $result->external_system_id:old('external_system_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'external_system_id') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'price_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('price_type', __('Price Type').':') !!}
                                                    {!! Form::text('price_type',isset($result->id) ? $result->price_type:old('price_type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'price_type') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'service_value',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('service_value', __('Service Value').':') !!}
                                                    {!! Form::text('service_value',isset($result->id) ? $result->service_value:old('service_value'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'service_value') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'service_value_list',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('service_value_list', __('Service Value List').':') !!}
                                                    {!! Form::text('service_value_list',isset($result->id) ? $result->service_value_list:old('service_value_list'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'service_value_list') !!}
                                            </div>



                                            <div class="form-group col-sm-6{!! formError($errors,'min_value',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('min_value', __('Min Value').':') !!}
                                                    {!! Form::text('min_value',isset($result->id) ? $result->min_value:old('min_value'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'min_value') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'max_value',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('max_value', __('Max Value').':') !!}
                                                    {!! Form::text('max_value',isset($result->id) ? $result->max_value:old('max_value'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'max_value') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'commission_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('commission_type', __('Commission Type').':') !!}
                                                    {!! Form::number('commission_type',isset($result->id) ? $result->commission_type:old('commission_type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'commission_type') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'commission_value_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('commission_value_type', __('Commission Value Type').':') !!}
                                                    {!! Form::number('commission_value_type',isset($result->id) ? $result->commission_value_type:old('commission_value_type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'commission_value_type') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'fixed_commission',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('fixed_commission', __('Fixed Commission').':') !!}
                                                    {!! Form::text('fixed_commission',isset($result->id) ? $result->fixed_commission:old('fixed_commission'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'fixed_commission') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'default_commission',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('default_commission', __('Default Commission').':') !!}
                                                    {!! Form::text('default_commission',isset($result->id) ? $result->default_commission:old('default_commission'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'default_commission') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'from_commission',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('from_commission', __('From Commission').':') !!}
                                                    {!! Form::text('from_commission',isset($result->id) ? $result->from_commission:old('from_commission'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'from_commission') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'to_commission',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('to_commission', __('To Commission').':') !!}
                                                    {!! Form::text('to_commission',isset($result->id) ? $result->to_commission:old('to_commission'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'to_commission') !!}
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

@endsection