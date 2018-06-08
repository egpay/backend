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
                            {!! Form::open(['route' => isset($result->id) ? ['system.tickets.update',$result->id]:'system.tickets.store', 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Merchant')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-12 {!! formError($errors,'merchant_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_id', __('Merchant').' '.__('Optional').':') !!}

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

                                        <div class="form-group col-sm-12{!! formError($errors,'invoiceable_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('invoiceable_id', __('Invoice').' '.__('Optional').':') !!}
                                                @if(isset($result->id))
                                                    {!! Form::select('invoiceable_id',[$result->invoiceable->id => ' #ID: '.$result->invoiceable_id],isset($result->id) ? $result->invoiceable_id:old('invoiceable_id'),['class'=>'select2 form-control']) !!}
                                                @else
                                                    {!! Form::select('invoiceable_id',[__('Select Invoice')],isset($result->id) ? $result->invoiceable_id:old('invoiceable_id'),['class'=>'form-control','id'=>'invoiceable_id']) !!}
                                                @endif
                                            </div>
                                            {!! formError($errors,'invoiceable_id') !!}
                                        </div>

                                        <div class="col-md-12 form-group {!! formError($errors,'subject',true) !!}">
                                            <div class="controls">
                                                {{ Form::label('subject',__('Subject')) }}
                                                {!! Form::text('subject',isset($result->id) ? $result->subject:old('subject'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'subject') !!}
                                        </div>

                                        <div class="col-md-12 form-group {!! formError($errors,'details',true) !!}">
                                            <div class="controls">
                                                {{ Form::label('details',__('Details')) }}
                                                {!! Form::textarea('details',isset($result->id) ? $result->details:old('details'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'details') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'to_id_group',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('to_id_group', __('To department').':') !!}
                                                @if(isset($result->to_id_group))
                                                    {!! Form::select('to_id_group',[$result->to_id_group => ' #ID: '.$result->to_id_group],isset($result->id) ? $result->to_id_group:old('to_id_group'),['class'=>'select2 form-control']) !!}
                                                @else
                                                    {!! Form::select('to_id_group',[__('Select Department')],isset($result->id) ? $result->to_id_group:old('to_id_group'),['class'=>'form-control','id'=>'to_id_group']) !!}
                                                @endif
                                            </div>
                                            {!! formError($errors,'to_id_group') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'to_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('to_id', __('To staff').':') !!}
                                                @if(isset($result->to_id))
                                                    {!! Form::select('to_id',[$result->to_id => ' #ID: '.$result->to_id],isset($result->id) ? $result->to_id:old('to_id'),['class'=>'select2 form-control']) !!}
                                                @else
                                                    {!! Form::select('to_id',[__('Select staff')],isset($result->id) ? $result->to_id:old('to_id'),['class'=>'form-control','id'=>'to_id']) !!}
                                                @endif
                                            </div>
                                            {!! formError($errors,'to_id') !!}
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
        ajaxSelect2('#invoiceable_id','paymentinvoice');
        ajaxSelect2('#to_id_group','forward-to-group');
        ajaxSelect2WithGroupId('#to_id','forward-to-staff',1);

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });

    </script>
@endsection