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
                            {!! Form::open(['route' => isset($result->id) ? ['system.marketing-message.update',$result->id]:'system.marketing-message.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-12{!! formError($errors,'title',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('title', __('Marketing Messages Title').':') !!}
                                                {!! Form::text('title',isset($result->id) ? $result->title:old('title'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'title') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'message_type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('message_type', __('Message Type').':') !!}
                                                {!! Form::select('message_type',['sms'=>__('SMS'),'email'=>__('E-mail'),'notification'=>__('Notification')],isset($result->id) ? $result->message_type:old('message_type'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_en') !!}
                                        </div>




















                                    </div>
                                </div>
                            </div>













                                <div id="sms-div">

                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>{{__('Arabic Info')}}</h2>
                                            </div>
                                            <div class="card-block card-dashboard">


                                                <div class="form-group col-sm-12{!! formError($errors,'sms_content_ar',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('sms_content_ar', __('SMS (Arabic)').':') !!}
                                                        {!! Form::textarea('sms_content_ar',isset($result->id) ? $result->content_ar:old('sms_content_ar'),['class'=>'form-control sms-area ar','id'=>'sms_content_ar']) !!}
                                                        <b id="smsCount-ar"></b> {{__('SMS')}} (<b id="smsLength-ar"></b>) {{__('Characters left')}}
                                                    </div>
                                                    {!! formError($errors,'sms_content_ar') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>{{__('English Info')}}</h2>
                                            </div>
                                            <div class="card-block card-dashboard">



                                                <div class="form-group col-sm-12{!! formError($errors,'sms_content_en',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('sms_content_en', __('SMS (English)').':') !!}
                                                        {!! Form::textarea('sms_content_en',isset($result->id) ? $result->content_en:old('sms_content_en'),['class'=>'form-control sms-area','id'=>'sms_content_en']) !!}
                                                        <b id="smsCount-en"></b> {{__('SMS')}} (<b id="smsLength-en"></b>) {{__('Characters left')}}
                                                    </div>
                                                    {!! formError($errors,'sms_content_en') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>


                                <div style="display: none;" id="email-div">

                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>{{__('Arabic Info')}}</h2>
                                            </div>
                                            <div class="card-block card-dashboard">



                                                <div class="form-group col-sm-12{!! formError($errors,'email_name_ar',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('email_name_ar', __('Subject (Arabic)').':') !!}
                                                        {!! Form::text('email_name_ar',isset($result->id) ? $result->name_ar:old('email_name_ar'),['class'=>'form-control ar']) !!}
                                                    </div>
                                                    {!! formError($errors,'email_name_ar') !!}
                                                </div>
                                                <div class="form-group col-sm-12{!! formError($errors,'email_content_ar',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('email_content_ar', __('Mail Body (Arabic)').':') !!}
                                                        {!! Form::textarea('email_content_ar',isset($result->id) ? $result->content_ar:old('email_content_ar'),['class'=>'form-control ar','id'=>'email_content_ar']) !!}
                                                    </div>
                                                    {!! formError($errors,'email_content_ar') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>{{__('English Info')}}</h2>
                                            </div>
                                            <div class="card-block card-dashboard">



                                                <div class="form-group col-sm-12{!! formError($errors,'email_name_en',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('email_name_en', __('Subject (English)').':') !!}
                                                        {!! Form::text('email_name_en',isset($result->id) ? $result->name_en:old('email_name_en'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'email_name_en') !!}
                                                </div>
                                                <div class="form-group col-sm-12{!! formError($errors,'email_content_en',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('email_content_en', __('Mail Body (English)').':') !!}
                                                        {!! Form::textarea('email_content_en',isset($result->id) ? $result->content_en:old('email_content_en'),['class'=>'form-control','id'=>'email_content_en']) !!}
                                                    </div>
                                                    {!! formError($errors,'email_content_en') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div style="display: none;" id="notification-div">

                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>{{__('Arabic Info')}}</h2>
                                            </div>
                                            <div class="card-block card-dashboard">



                                                <div class="form-group col-sm-12{!! formError($errors,'notification_name_ar',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('notification_name_ar', __('Title (Arabic)').':') !!}
                                                        {!! Form::text('notification_name_ar',isset($result->id) ? $result->name_ar:old('notification_name_ar'),['class'=>'form-control ar']) !!}
                                                    </div>
                                                    {!! formError($errors,'notification_name_ar') !!}
                                                </div>
                                                <div class="form-group col-sm-12{!! formError($errors,'notification_content_ar',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('notification_content_ar', __('Content (Arabic)').':') !!}
                                                        {!! Form::textarea('notification_content_ar',isset($result->id) ? $result->content_ar:old('notification_content_ar'),['class'=>'form-control ar','id'=>'notification_content_ar']) !!}
                                                    </div>
                                                    {!! formError($errors,'notification_content_ar') !!}
                                                </div>


                                                <div class="form-group col-sm-12{!! formError($errors,'url_ar',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('url_ar', __('URL (Arabic)').':') !!}
                                                        {!! Form::url('url_ar',isset($result->id) ? $result->url_ar:old('url_ar'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'url_ar') !!}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>{{__('English Info')}}</h2>
                                            </div>
                                            <div class="card-block card-dashboard">



                                                <div class="form-group col-sm-12{!! formError($errors,'notification_name_en',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('notification_name_en', __('Title (English)').':') !!}
                                                        {!! Form::text('notification_name_en',isset($result->id) ? $result->name_en:old('notification_name_en'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'notification_name_en') !!}
                                                </div>
                                                <div class="form-group col-sm-12{!! formError($errors,'notification_content_en',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('notification_content_en', __('Content (English)').':') !!}
                                                        {!! Form::textarea('notification_content_en',isset($result->id) ? $result->content_en:old('notification_content_en'),['class'=>'form-control sms-area','id'=>'notification_content_en']) !!}
                                                    </div>
                                                    {!! formError($errors,'notification_content_en') !!}
                                                </div>



                                                <div class="form-group col-sm-12{!! formError($errors,'url_en',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('url_en', __('URL (English)').':') !!}
                                                        {!! Form::url('url_en',isset($result->id) ? $result->url_en:old('url_en'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'url_en') !!}
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h2>{{__('Notification Icon')}}</h2>
                                            </div>
                                            <div class="card-block card-dashboard">



                                                <div class="form-group col-sm-12{!! formError($errors,'image',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('image', __('Icon').':') !!}
                                                        {!! Form::file('image',['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'image') !!}
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>









                            <div class="col-sm-12">
                                <div class="card">

                                    <div class="card-block card-dashboard">


                                        <div class="form-group col-sm-12{!! formError($errors,'send_to',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('send_to', __('Filter').':') !!}
                                                {!! Form::select('send_to',['user'=>__('Users'),'merchant'=>__('Merchants'),'marketing_message_data'=>__('Marketing Data')],isset($result->id) ? $result->send_to:old('send_to'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'send_to') !!}
                                        </div>

                                        <div id="system_to_marketing_message_data" class="form-group col-sm-12{!! formError($errors,'marketing_filter_data',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('marketing_filter_data', __('Send To').':') !!}
                                                {!! Form::select('marketing_filter_data',[],isset($result->id) ? $result->filter_data:old('marketing_filter_data'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'marketing_filter_data') !!}
                                        </div>

                                        <div id="system_to_user">

                                            <div>
                                                <div class="col-md-6">
                                                    <fieldset class="form-group">
                                                        {{ Form::label('created_at1',__('Created From')) }}
                                                        {!! Form::text('user_filter_data[created_at1]',null,['class'=>'form-control datepicker','id'=>'created_at1']) !!}
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-6">
                                                    <fieldset class="form-group">
                                                        {{ Form::label('created_at2',__('Created To')) }}
                                                        {!! Form::text('user_filter_data[created_at2]',null,['class'=>'form-control datepicker','id'=>'created_at2']) !!}
                                                    </fieldset>
                                                </div>


                                                <div class="col-md-6">
                                                    <fieldset class="form-group">
                                                        {{ Form::label('birthdate1',__('Birthdate From')) }}
                                                        {!! Form::text('user_filter_data[birthdate1]',null,['class'=>'form-control datepicker','id'=>'birthdate1']) !!}
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-6">
                                                    <fieldset class="form-group">
                                                        {{ Form::label('birthdate2',__('Birthdate To')) }}
                                                        {!! Form::text('user_filter_data[birthdate2]',null,['class'=>'form-control datepicker','id'=>'birthdate2']) !!}
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset class="form-group">
                                                        {{ Form::label('parent',__('Parent')) }}
                                                        {!! Form::select('user_filter_data[is_parent]',[__('Select Parent'),__('Parent'),__('Children')],null,['class'=>'form-control']) !!}
                                                    </fieldset>
                                                </div>


                                                <div class="col-md-12">
                                                    <fieldset class="form-group">
                                                        {!! Form::label('interest', __('User Interests').':') !!}
                                                        {!! Form::select('user_filter_data[interest]',['0'=>__('Select User Interests'),'1'=>__('Low'),'2'=>__('Normal'),'3'=>__('High')],old('interest'),['class'=>'form-control']) !!}
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset class="form-group">
                                                        {!! Form::label('merchant_category_id', __('Merchant Category').':') !!}
                                                        {!! Form::select('user_filter_data[merchant_category_id]',array_merge(['0'=>__('Select Category')],array_column($merchant_categories->toArray(),'name','id')),old('merchant_category_id'),['class'=>'form-control']) !!}
                                                    </fieldset>
                                                </div>

                                                <hr />

                                                <div class="col-md-12">
                                                    <fieldset class="form-group">
                                                        {{ Form::label('merchant_id',__('Merchant')) }}
                                                        {!! Form::select('user_filter_data[merchant_id]',[''=>__('Select Merchant')],null,['style'=>'width: 100%;' ,'id'=>'merchantSelect2','class'=>'form-control col-md-12']) !!}
                                                    </fieldset>
                                                </div>

                                                <hr />


                                                <div class="form-group col-sm-12{!! formError($errors,'area_id',true) !!}">
                                                    {{ Form::label('area_id',$areaData['type']->name) }}
                                                    @php
                                                        $arrayOfArea = $areaData['areas']->toArray();
                                                        if(!$arrayOfArea){
                                                            $arrayOfArea = [];
                                                        }else{
                                                            $arrayOfArea = array_column($arrayOfArea,'name','id');
                                                        }
                                                    @endphp
                                                    {!! Form::select('area_id[]',array_merge([0=>__('Select Area')],$arrayOfArea),null,['class'=>'form-control','id'=>'area_id','onchange'=>'getNextAreas($(this).val(),"'.$areaData['type']->id.'",\'#nextAreasID\')']) !!}
                                                    {!! formError($errors,'area_id') !!}
                                                </div>


                                                <div id="nextAreasID" class="col-md-12">

                                                </div>

                                            </div>


                                        </div>
                                        <div id="system_to_merchant">



                                        </div>



                                    </div>
                                </div>
                            </div>


                                <div class="col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block card-dashboard">
                                                <div class="form-group col-sm-12{!! formError($errors,'send_at',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('send_at', __('Scheduling').':') !!}
                                                        {!! Form::text('send_at',isset($result->id) ? $result->send_at:old('send_at'),['class'=>'form-control datetimepicker']) !!}
                                                    </div>
                                                    {!! formError($errors,'send_at') !!}
                                                </div>
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


@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
@endsection


@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        CKEDITOR.replace('email_content_ar');
        CKEDITOR.replace('email_content_en');





        function messageType(){
            if($('#message_type').val() == 'sms'){
                $('#sms-div').show();
                $('#email-div,#notification-div').hide();
            }else if($('#message_type').val() == 'email'){
                $('#email-div').show();
                $('#sms-div,#notification-div').hide();
            }else{
                $('#notification-div').show();
                $('#sms-div,#email-div').hide();
            }
        }

        function filterData(){
            if($('#send_to').val() == 'user'){
                $('#system_to_user').show();
                $('#system_to_marketing_message_data,#system_to_merchant').hide();
            }else if($('#send_to').val() == 'merchant'){
                $('#system_to_merchant').show();
                $('#system_to_marketing_message_data,#system_to_user').hide();
            }else{
                $('#system_to_marketing_message_data').show();
                $('#system_to_user,#system_to_merchant').hide();
            }
        }


        $('#message_type').change(function(){
            messageType();
        });
        $('#send_to').change(function(){
            filterData();
        });

        $(document).ready(function(){
            // Date plugin
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });


            $('.datetimepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD HH:mm:SS'
            });

            filterData();
            messageType();

            ajaxSelect2('#merchantSelect2','merchant');


            // Area ON CHANGE OR UPDATE
            // Area ON CHANGE OR UPDATE
            // Area ON CHANGE OR UPDATE
            @php
                $startWorkWithArea = (isset($result->area_id)) ? $result->area_id : getLastNotEmptyItem(old('area_id'));
                if($startWorkWithArea){
                    $areaData = \App\Libs\AreasData::getAreasUp($startWorkWithArea);
                    echo '$runAreaLoop = true;$areaLoopData = [];';
                    if($areaData){
                        foreach ($areaData as $key => $value){
                            echo '$areaLoopData['.$key.'] = '.$value.';';
                        }
                        echo '$(\'#area_id\').val(next($areaLoopData)).change();';
                    }
                }

            @endphp
            // Area ON CHANGE OR UPDATE
            // Area ON CHANGE OR UPDATE
            // Area ON CHANGE OR UPDATE
            // Area ON CHANGE OR UPDATE










            $('#sms_content_ar').smsArea({
                counters: {
                    message: $('#smsCount-ar'),
                    character: $('#smsLength-ar')
                }
            });

            $('#sms_content_en').smsArea({
                counters: {
                    message: $('#smsCount-en'),
                    character: $('#smsLength-en')
                }
            });

        });
    </script>
@endsection