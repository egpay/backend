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
                            {!! Form::open(['route' => isset($result->id) ? ['merchant.staff.update',$result->id]:'merchant.staff.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-6 {!! formError($errors,'merchant_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_id', __('Merchant').':') !!}
                                                @if(isset($merchantData))
                                                    {!! Form::text('merchant_text', $merchantData->{'name_'.$systemLang}.' #ID: '.$merchantData->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                    {!! Form::hidden('merchant_id',null,['id'=>'new_merchant_id']) !!}
                                                @else
                                                    @if(isset($result->id))
                                                        {!! Form::select('merchant_id',[$result->staff_group->merchant->id => $result->staff_group->merchant->{'name_'.$systemLang}.' #ID: '.$result->staff_group->merchant_id],isset($result->id) ? $result->staff_group->merchant_id:old('merchant_id'),['class'=>'select2 form-control','readonly']) !!}
                                                    @else
                                                        {!! Form::select('merchant_id',[__('Select Merchant')],old('merchant_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                    @endif
                                                @endif
                                            </div>
                                            {!! formError($errors,'merchant_id') !!}
                                        </div>


                                        <div class="form-group col-sm-6 {!! formError($errors,'merchant_staff_group_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_staff_group_id', __('Staff Group').':') !!}
                                                {!! Form::select('merchant_staff_group_id',$merchantStaffGroup,isset($result->id) ? $result->merchant_staff_group_id:old('merchant_staff_group_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'merchant_staff_group_id') !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-6{!! formError($errors,'firstname',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('firstname', __('First Name').':') !!}
                                                {!! Form::text('firstname',isset($result->id) ? $result->firstname:old('firstname'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'firstname') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'lastname',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('lastname', __('Last Name').':') !!}
                                                {!! Form::text('lastname',isset($result->id) ? $result->lastname:old('lastname'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'lastname') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'username',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('username', __('User Name').':') !!}
                                                {!! Form::text('username',isset($result->id) ? $result->username:old('username'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'username') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'national_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('national_id', __('National ID').':') !!}
                                                {!! Form::number('national_id',isset($result->id) ? $result->national_id:old('national_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'national_id') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'email',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('email', __('Email').':') !!}
                                                {!! Form::email('email',isset($result->id) ? $result->email:old('email'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'email') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'mobile',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('mobile', __('Mobile').':') !!}
                                                {!! Form::number('mobile',isset($result->id) ? $result->mobile:old('mobile'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'mobile') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'address',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('address', __('Address').':') !!}
                                                {!! Form::textarea('address',isset($result->id) ? $result->address:old('address'),['class'=>'form-control','rows'=>3]) !!}
                                            </div>
                                            {!! formError($errors,'address') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'birthdate',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('birthdate', __('Birthdate').':') !!}
                                                {!! Form::date('birthdate',isset($result->id) ? $result->birthdate:old('birthdate'),['class'=>'form-control','rows'=>3]) !!}
                                            </div>
                                            {!! formError($errors,'birthdate') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Status').':') !!}
                                                {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'branches',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('branches', __('Branches').':') !!}
                                                <select id="branches" name="branches[]" class="form-control" multiple>
                                                    @foreach($merchantBranchs as $key => $value)
                                                        @if( (isset($result->id) && in_array($key,$result->branches)) || (is_array(old('branches')) && in_array($key,old('branches'))) )
                                                            <option selected="selected" value="{{$key}}">{{$value}}</option>
                                                        @else
                                                            <option value="{{$key}}">{{$value}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            {!! formError($errors,'branches') !!}
                                        </div>


                                        <div class="form-group col-sm-6{!! formError($errors,'password',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('password', __('Password').':') !!}
                                                {!! Form::password('password', ['class' => 'form-control','id'=>'password']) !!}
                                            </div>
                                            {!! formError($errors,'password') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'password_confirmation',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('password_confirmation', __('Retype Password').':') !!}
                                                {!! Form::password('password_confirmation', ['class' => 'form-control','id'=>'password_confirmation']) !!}
                                            </div>
                                            {!! formError($errors,'password_confirmation') !!}
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
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>
    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>
    <script>
        @if(!isset($result->id))
        ajaxSelect2('#merchant_id','merchant');
        @endif
        $('#merchant_id').change(function(){

            // Get Staff Groups
           $.getJSON('{{route('system.ajax.get')}}',{
               'type': 'getMerchantStaffGroup',
               'merchant_id': $(this).val()
           },function($data){

               $return = new Array;
               $return.push('<option value="0">{{__('Select Staff Group')}}</option>');

               $.each($data,function(key,value){
                   $return.push('<option value="'+value.id+'">'+value.title+'</option>');
               });

               $('#merchant_staff_group_id').html($return.join("\n"));
           });




           // Get Merchant Branchs

            $.getJSON('{{route('system.ajax.get')}}',{
                'type': 'getMerchantBranchs',
                'merchant_id': $(this).val()
            },function($data){
                $return = new Array;
                $return.push('<option value="0">{{__('Select Branchs')}}</option>');

                $.each($data,function(key,value){
                    $return.push('<option value="'+value.id+'">'+value.name+'</option>');
                });

                $('#branches').html($return.join("\n"));
            });




        });
    </script>
@endsection