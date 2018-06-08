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
                            {!! Form::open(['route' => isset($result->id) ? ['system.users.update',$result->id]:'system.users.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">


                                        <div class="form-group col-sm-12{!! formError($errors,'user_name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('user_name', __('User Name').':') !!}
                                                {!! Form::text('user_name',isset($result->id) ? $result->user_name:old('user_name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'user_name') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'firstname',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('firstname', __('First Name').':') !!}
                                                {!! Form::text('firstname',isset($result->id) ? $result->firstname:old('firstname'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'firstname') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'middlename',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('middlename', __('Middle Name').':') !!}
                                                {!! Form::text('middlename',isset($result->id) ? $result->middlename:old('middlename'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'middlename') !!}
                                        </div>

                                        <div class="form-group col-sm-4{!! formError($errors,'lastname',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('lastname', __('Last Name').':') !!}
                                                {!! Form::text('lastname',isset($result->id) ? $result->firstname:old('lastname'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'lastname') !!}
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


                                        <div class="form-group col-sm-6{!! formError($errors,'national_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('national_id', __('National ID').':') !!}
                                                {!! Form::number('national_id',isset($result->id) ? $result->national_id:old('national_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'national_id') !!}
                                        </div>



                                        <div class="form-group col-sm-6{!! formError($errors,'birthdate',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('birthdate', __('Birthdate').':') !!}
                                                {!! Form::date('birthdate',isset($result->id) ? $result->birthdate:old('birthdate'),['class'=>'form-control','rows'=>3]) !!}
                                            </div>
                                            {!! formError($errors,'birthdate') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'address',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('address', __('Address').':') !!}
                                                {!! Form::textarea('address',isset($result->id) ? $result->address:old('address'),['class'=>'form-control','rows'=>3]) !!}
                                            </div>
                                            {!! formError($errors,'address') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'image',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('image', __('Image').':') !!}
                                                {!! Form::file('image',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'image') !!}
                                        </div>




                                        <div class="form-group col-sm-4{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Status').':') !!}
                                                {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>


                                        <div class="form-group col-sm-4{!! formError($errors,'gender',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('gender', __('Gender').':') !!}
                                                {!! Form::select('gender',['male'=>__('Male'),'female'=>__('Female')],isset($result->id) ? $result->gender:old('gender'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'gender') !!}
                                        </div>


                                        <div class="form-group col-sm-4 {!! formError($errors,'parent_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('parent_id', __('Parent Of').':') !!}
                                                @if(isset($parentOf))
                                                    {!! Form::text('merchant_text', $parentOf->firstname.' '.$parentOf->lastname.' #ID:'.$parentOf->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                    {!! Form::hidden('parent_id',null,['id'=>'new_parent_id']) !!}
                                                @else
                                                    @if(isset($result->id) && isset($result->parent->id))
                                                        {!! Form::select('parent_id',[$result->parent->id => $result->parent->firstname.' '.$result->parent->lastname.' #ID:'.$result->parent->id],isset($result->id) ? $result->parent->id:old('parent_id'),['class'=>'select2 form-control']) !!}
                                                    @else
                                                        {!! Form::select('parent_id',[__('Select Parent')],old('parent_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                    @endif
                                                @endif
                                            </div>
                                            {!! formError($errors,'parent_id') !!}
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
        ajaxSelect2('#parent_id','users');
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