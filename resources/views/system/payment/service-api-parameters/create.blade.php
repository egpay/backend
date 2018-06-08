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
                            {!! Form::open(['route' => isset($result->id) ? ['payment.service-api-parameters.update',$result->id]:'payment.service-api-parameters.store','method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-6{!! formError($errors,'payment_services_api_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('payment_services_api_id', __('Payment Service API').':') !!}
                                                {!! Form::select('payment_services_api_id',[''=>'Select Payment Service API']+array_column($paymentServiceAPIs->toArray(),'name','id') ,isset($result->id) ? $result->payment_services_api_id:old('payment_services_api_id'),['class'=>'form-control','id'=>'payment_services_api_id']) !!}
                                            </div>
                                            {!! formError($errors,'payment_services_api_id') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'external_system_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('external_system_id', __('External System ID').':') !!}
                                                {!! Form::number('external_system_id',isset($result->id) ? $result->external_system_id:old('external_system_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'external_system_id') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'name_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_ar', __('Name (AR)').':') !!}
                                                {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_ar') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'name_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_en', __('Name (EN)').':') !!}
                                                {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_en') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'position',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('position', __('Position').':') !!}
                                                {!! Form::number('position',isset($result->id) ? $result->position:old('position'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'position') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('type', __('Type').':') !!}
                                                {!! Form::text('type',isset($result->id) ? $result->type:old('type'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'type') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'visible',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('visible', __('Visible').':') !!}
                                                {!! Form::select('visible',['yes'=>__('Yes'),'no'=>__('No')],isset($result->id) ? $result->visible:old('visible'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'visible') !!}
                                        </div>



                                        <div class="form-group col-sm-6{!! formError($errors,'required',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('required', __('Required').':') !!}
                                                {!! Form::select('required',['yes'=>__('Yes'),'no'=>__('No')],isset($result->id) ? $result->required:old('required'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'required') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'is_client_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('is_client_id', __('Is Client ID').':') !!}
                                                {!! Form::select('is_client_id',['yes'=>__('Yes'),'no'=>__('No')],isset($result->id) ? $result->is_client_id:old('is_client_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'is_client_id') !!}
                                        </div>





                                        <div class="form-group col-sm-6{!! formError($errors,'default_value',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('default_value', __('Default Value').':') !!}
                                                {!! Form::text('default_value',isset($result->id) ? $result->default_value:old('default_value'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'default_value') !!}
                                        </div>



                                        <div class="form-group col-sm-6{!! formError($errors,'min_length',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('min_length', __('Min Length').':') !!}
                                                {!! Form::number('min_length',isset($result->id) ? $result->min_length:old('min_length'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'min_length') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'max_length',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('max_length', __('Max Length').':') !!}
                                                {!! Form::number('max_length',isset($result->id) ? $result->max_length:old('max_length'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'max_length') !!}
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

    {{--<script type="text/javascript">--}}
        {{--function getSDKGroup(){--}}
            {{--$paymentSDKID = $('#payment_sdk_id').val();--}}
            {{--$.getJSON('{{route('system.ajax.get',['type'=>'getSDKGroup'])}}&id='+$paymentSDKID,function($data){--}}
                {{--$return = new Array;--}}
                {{--$return.push('<option value="0">{{__('Select SDK Group')}}</option>');--}}
                {{--$.each($data,function($key,$value){--}}
                    {{--$return.push('<option value="'+$value.id+'">'+$value.name+'</option>');--}}
                {{--});--}}

                {{--$('#payment_sdk_group_id').html($return.join("\n"));--}}
            {{--})--}}
        {{--}--}}
    {{--</script>--}}
@endsection