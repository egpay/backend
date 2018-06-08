@extends('system.layouts')

@section('header')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/plugins/forms/wizard.css')}}">

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




                            @if(!isset($result->id))
                                <div>
                                {!! Form::open(['route' => isset($result->id) ? ['merchant.merchant.update',$result->id]:'merchant.merchant.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST','class'=>'number-tab-steps wizard-circle']) !!}


                                <!-- Step 1 -->
                                    <h6>{{__('Information')}}</h6>
                                    <fieldset style="padding: 0px;">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('English Data')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('name_en', __('Merchant name (English)').':') !!}
                                                            {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'name_en') !!}
                                                    </div>

                                                    <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('description_en', __('Merchant description (English)').':') !!}
                                                            {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'description_en') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('Arabic Data')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('name_ar', __('Merchant name (Arabic)').':') !!}
                                                            {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                                        </div>
                                                        {!! formError($errors,'name_ar') !!}
                                                    </div>

                                                    <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('description_ar', __('Merchant description (Arabic)').':') !!}
                                                            {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                                        </div>
                                                        {!! formError($errors,'description_ar') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-block card-dashboard">
                                                    <div class="form-group col-sm-12{!! formError($errors,'merchant_category_id',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('merchant_category_id', __('Merchant Category').':') !!}


                                                            {!! Form::select('merchant_category_id',$merchant_categories,isset($result->id) ? $result->merchant_category_id:old('merchant_category_id'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'merchant_category_id') !!}
                                                    </div>

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
                                                        {!! Form::select('area_id[]',[0=>__('Select Area')]+$arrayOfArea,null,['class'=>'form-control','id'=>'area_id','onchange'=>'getNextAreas($(this).val(),"'.$areaData['type']->id.'",\'#nextAreasID\')']) !!}
                                                        {!! formError($errors,'area_id') !!}
                                                    </div>


                                                    <div id="nextAreasID" class="col-md-12">

                                                    </div>


                                                    <div class="form-group col-sm-12{!! formError($errors,'address',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('address', __('Address').':') !!}
                                                            {!! Form::textarea('address',isset($result->id) ? $result->address:old('address'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'address') !!}
                                                    </div>

                                                    <div class="form-group col-sm-4{!! formError($errors,'is_reseller',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('is_reseller', __('is Reseller').':') !!}
                                                            {!! Form::select('is_reseller',['in-active'=>__('No'),'active'=>__('Yes')],isset($result->id) ? $result->is_reseller:old('is_reseller'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'is_reseller') !!}
                                                    </div>

                                                    <div class="form-group col-sm-4{!! formError($errors,'logo',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('logo', __('Merchant Logo').':') !!}
                                                            @if(isset($result->logo))
                                                                <span><a target="_blank" href="{{asset('storage/'.$result->logo)}}">{{__('View logo')}}</a></span>
                                                            @elseif(request('logo'))
                                                                <span><a target="_blank" href="{{asset('storage/'.request('logo'))}}">{{__('View Logo')}}</a></span>
                                                            @endif
                                                            <label class="custom-file center-block block">
                                                                {!! Form::file('logo',['class'=>'form-control']) !!}

                                                            </label>
                                                        </div>
                                                        {!! formError($errors,'logo') !!}
                                                    </div>

                                                    <div class="form-group col-sm-4{!! formError($errors,'status',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('status', __('Merchant Status').':') !!}
                                                            {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'status') !!}
                                                    </div>

                                                    <div class="clearfix"></div>


                                                    <div class="form-group col-sm-12{!! formError($errors,'parent_id',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('parent_id', __('Parent From Merchant').':') !!}
                                                            @if(isset($result->parent_id))
                                                                {!! Form::select('parent_id',[$result->parent->id => $result->parent->{'name_'.$systemLang}.' #ID: '.$result->parent_id],isset($result->id) ? $result->parent_id:old('parent_id'),['class'=>'select2 form-control']) !!}
                                                            @else
                                                                {!! Form::select('parent_id',[__('Select Merchant')],old('parent_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                            @endif
                                                        </div>
                                                        {!! formError($errors,'parent_id') !!}
                                                    </div>


                                                    @if($sales_data)
                                                        <div class="clearfix"></div>


                                                        <div class="form-group col-sm-12{!! formError($errors,'staff_id',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('staff_id', __('Sales Man').':') !!}
                                                                {!! Form::select('staff_id',$sales_data,old('staff_id'),['class'=>'form-control','readeonly'=>true]) !!}
                                                            </div>
                                                            {!! formError($errors,'staff_id') !!}
                                                        </div>
                                                    @else
                                                        {!! Form::hidden('staff_id',Auth::id()) !!}
                                                    @endif


                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-block card-dashboard" style="padding-bottom: 160px !important;">
                                                    <div class="contactdata">
                                                        @if(isset($result->id))
                                                            @foreach($result->contact()->get() as $onecontact)
                                                                <div class="form-group col-sm-12">
                                                                    <div class="controls">
                                                                        <label for="{!! $onecontact->type !!}">{!! ucfirst($onecontact->type) !!}:</label>
                                                                        <div class="input-group">
                                                                            <input class="form-control" name="contact[{!! $onecontact->type !!}][]" class="{{$onecontact->type}}" type="{{(($onecontact->type=='email')?'email':'text')}}" value="{{$onecontact->value}}">
                                                                            <a class="input-group-addon btn btn-danger delcontactinfo"><i class="fa fa-trash"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if(request('contact'))
                                                            @foreach(request('contact') as $key=>$vals)
                                                                @foreach($vals as $contact)
                                                                    <div class="form-group col-sm-12">
                                                                        <div class="controls">
                                                                            <label for="{!! $key !!}">{!! ucfirst($key) !!}:</label>
                                                                            <div class="input-group">
                                                                                <input class="form-control" name="contact[{!! $key !!}][]" class="{{$key}}" type="{{(($key=='email')?'email':'text')}}" value="{{$contact}}">
                                                                                <a class="input-group-addon btn btn-danger delcontactinfo"><i class="fa fa-trash"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </div>

                                                    {!! formError($errors,'contact.name') !!}
                                                    {!! formError($errors,'contact.email') !!}
                                                    {!! formError($errors,'contact.mobile') !!}

                                                    {!! formError($errors,'contact.name.*') !!}
                                                    {!! formError($errors,'contact.email.*') !!}
                                                    {!! formError($errors,'contact.mobile.*') !!}

                                                    <br>
                                                    <div class="pull-right">
                                                        <div class="input-group">
                                                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <span>{{__('Add info')}}</span>
                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item addinput" href="javascript:void(0)" data-type="name">Name</a>
                                                                    <a class="dropdown-item addinput" href="javascript:void(0)" data-type="mobile">Mobile</a>
                                                                    <a class="dropdown-item addinput" href="javascript:void(0)" data-type="phone">Phone</a>
                                                                    <a class="dropdown-item addinput" href="javascript:void(0)" data-type="email">Email</a>
                                                                    <a class="dropdown-item addinput" href="javascript:void(0)" data-type="fax">Fax</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>

                                    <!-- Step 2 -->
                                    <h6>{{__('Media')}}</h6>
                                    <fieldset style="padding: 0px;">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col-sm-6">
                                                        <h2>{{__('Images')}}</h2>
                                                        @if(formError($errors,'imagefiles.*',true))
                                                            <p class="text-xs-left"><small class="danger text-muted">{{__('Error File Upload')}}</small></p>
                                                        @endif
                                                    </div>
                                                    <div style="text-align: right;" class="col-sm-6">
                                                        <button type="button" class="btn btn-primary fa fa-plus addnewimage">
                                                            <span>{{__('Add Image')}}</span>
                                                        </button>
                                                    </div>

                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <div class="uploadimage">
                                                        <div class="col-sm-12 div-with-files">
                                                            <div class="col-sm-5 form-group">
                                                                <label for="imagetitles[]">{{__('Title')}}</label>
                                                                {!! Form::text('imagetitles[]',null,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="col-sm-5 form-group">
                                                                <label for="imagefiles[]">{{__('File')}}</label>
                                                                <input class="form-control" name="imagefiles[]" type="file">
                                                            </div>
                                                            <div class="col-sm-2 form-group">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <!-- Step 3 -->
                                    <h6>{{__('Branch')}}</h6>
                                    <fieldset style="padding: 0px;">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('English Data')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <div class="form-group col-sm-12{!! formError($errors,'branch_name_en',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_name_en', __('English Name').':') !!}
                                                            {!! Form::text('branch_name_en',old('branch_name_en'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_name_en') !!}
                                                    </div>
                                                    <div class="form-group col-sm-12{!! formError($errors,'branch_address_en',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_address_en', __('Address (English)').':') !!}
                                                            {!! Form::text('branch_address_en',old('branch_address_en'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_address_en') !!}
                                                    </div>
                                                    <div class="form-group col-sm-12{!! formError($errors,'branch_description_en',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_description_en', __('Description (English)').':') !!}
                                                            {!! Form::textarea('branch_description_en',old('branch_description_en'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_description_en') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('Arabic Data')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <div class="form-group col-sm-12{!! formError($errors,'branch_name_ar',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_name_ar', __('Name (Arabic)').':') !!}
                                                            {!! Form::text('branch_name_ar',old('branch_name_ar'),['class'=>'form-control ar']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_name_ar') !!}
                                                    </div>
                                                    <div class="form-group col-sm-12{!! formError($errors,'branch_address_ar',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_address_ar', __('Address (Arabic)').':') !!}
                                                            {!! Form::text('branch_address_ar',old('branch_address_ar'),['class'=>'form-control ar']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_address_ar') !!}
                                                    </div>
                                                    <div class="form-group col-sm-12{!! formError($errors,'branch_description_ar',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_description_ar', __('Description (Arabic)').':') !!}
                                                            {!! Form::textarea('branch_description_ar',old('branch_description_ar'),['class'=>'form-control ar']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_description_ar') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('Determine Location')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <input id="pac-input" class="controls form-control" type="text" placeholder="{{__('Search Box')}}">
                                                    <div id="map-events" class="height-400" style="margin-bottom: 15px;"></div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'branch_latitude',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_latitude', __('Latitude').':') !!}
                                                            {!! Form::text('branch_latitude',old('branch_latitude'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_latitude') !!}
                                                    </div>
                                                    <div class="form-group col-sm-6{!! formError($errors,'branch_longitude',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('branch_longitude', __('Longitude').':') !!}
                                                            {!! Form::text('branch_longitude',old('branch_longitude'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'branch_longitude') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>



                                    <!-- Step 4 -->
                                    <h6>{{__('Contract')}}</h6>
                                    <fieldset style="padding: 0px;">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('Data')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">

                                                    <div class="form-group col-sm-12{!! formError($errors,'contract_plan_id',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('contract_plan_id', __('Plan').':') !!}
                                                            {!! Form::select('contract_plan_id',$merchantPlans,old('contract_plan_id'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'contract_plan_id') !!}
                                                    </div>

                                                    <div class="form-group col-sm-12{!! formError($errors,'contract_description',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('contract_description', __('Description').':') !!}
                                                            {!! Form::textarea('contract_description',old('contract_description'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'contract_description') !!}
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>{{__('Contract Info')}}</h2>
                                                </div>
                                                <div class="card-block card-dashboard">

                                                    <div class="form-group col-sm-12{!! formError($errors,'contract_price',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('contract_price', __('Contract fees').':') !!}
                                                            <div class="input-group input-group-lg">
                                                                <span class="input-group-addon" id="sizing-addon1">{{__('EGP')}}</span>
                                                                {!! Form::number('contract_price',old('contract_price'),['class'=>'form-control']) !!}
                                                            </div>
                                                        </div>
                                                        {!! formError($errors,'contract_price') !!}
                                                    </div>


                                                    <div class="form-group col-sm-6{!! formError($errors,'contract_admin_name',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('contract_admin_name', __('Manager name').':') !!}
                                                            {!! Form::text('contract_admin_name',old('contract_admin_name'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'contract_admin_name') !!}
                                                    </div>

                                                    <div class="form-group col-sm-6{!! formError($errors,'contract_admin_job_title',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('contract_admin_job_title', __('Manager job title').':') !!}
                                                            {!! Form::text('contract_admin_job_title',old('contract_admin_job_title'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'contract_admin_job_title') !!}
                                                    </div>


                                                    <div style="display: none;" class="form-group start-end-date col-sm-6">
                                                        <div class="controls">
                                                            {!! Form::label('contract_start_date', __('Start Date').':') !!}
                                                            {!! Form::text('contract_start_date',null,['class'=>'form-control','disabled'=>'disabled']) !!}
                                                        </div>
                                                    </div>

                                                    <div style="display: none;" class="form-group start-end-date col-sm-6">
                                                        <div class="controls">
                                                            {!! Form::label('contract_end_date', __('End Date').':') !!}
                                                            {!! Form::text('contract_end_date',null,['class'=>'form-control','disabled'=>'disabled']) !!}
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="col-sm-6">
                                                        <h2>{{__('Contract files')}}</h2>
                                                        @if(formError($errors,'contract_file.*',true))
                                                            <p class="text-xs-left"><small class="danger text-muted">{{__('Error File Upload')}}</small></p>
                                                        @endif
                                                    </div>
                                                    <div style="text-align: right;" class="col-sm-6">
                                                        <button type="button" class="btn btn-primary fa fa-plus addinputfile">
                                                            <span>{{__('Add File')}}</span>
                                                        </button>
                                                    </div>

                                                </div>
                                                <div class="card-block card-dashboard">
                                                    <div class="uploaddata">
                                                        @if(isset($tempData))
                                                            @if(count($tempData['uploads']))
                                                                @foreach($tempData['uploads'] as $oneupload)
                                                                    <div class="onfile col-sm-12 div-with-files">
                                                                        <div class="col-sm-5 form-group">
                                                                            <label for="title[]">{{__('Title')}}</label>
                                                                            <input type="text" class="form-control" value="{{$oneupload->title}}" name="contractTitle[]">
                                                                        </div>
                                                                        <div class="col-sm-5 form-group">
                                                                            <label for="file[]">{{__('File')}}</label>
                                                                            {{link_to_asset('storage/'.$oneupload->path,__('View File'),['target'=>'_blank'])}}
                                                                            {!! Form::hidden('old_contract_title[]',$oneupload->title,['class'=>'form-control']) !!}
                                                                            {!! Form::hidden('old_contract_file[]','storage/'.$oneupload->path,['class'=>'form-control']) !!}
                                                                        </div>
                                                                        <div class="col-sm-2 form-group">
                                                                            <a href="javascript:void(0);" onclick="$(this).closest('.onfile.col-sm-12.div-with-files').remove();" class="text-danger">
                                                                                <i class="fa fa-lg fa-trash mt-3"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <h2>{{__('No Uploads')}}</h2>
                                                            @endif
                                                        @endif

                                                        <div class="col-sm-12 div-with-files">
                                                            <div class="col-sm-5 form-group">
                                                                <label for="title[]">{{__('Title')}}</label>
                                                                <input type="text" class="form-control" name="contractTitle[]">
                                                            </div>
                                                            <div class="col-sm-5 form-group">
                                                                <label for="file[]">{{__('File')}}</label>
                                                                <input class="form-control" name="contractFile[]" type="file">
                                                            </div>
                                                            <div class="col-sm-2 form-group">

                                                            </div>
                                                        </div>

                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <!-- Step 5 -->
                                    <h6>{{__('Staff')}}</h6>
                                    <fieldset style="padding: 0px;">

                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-block card-dashboard">
                                                    <div class="form-group col-sm-6{!! formError($errors,'staff_firstname',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_firstname', __('First Name').':') !!}
                                                            {!! Form::text('staff_firstname',old('staff_firstname'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'staff_firstname') !!}
                                                    </div>

                                                    <div class="form-group col-sm-6{!! formError($errors,'staff_lastname',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_lastname', __('Last Name').':') !!}
                                                            {!! Form::text('staff_lastname',old('staff_lastname'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'staff_lastname') !!}
                                                    </div>


                                                    <div class="form-group col-sm-6{!! formError($errors,'staff_national_id',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_national_id', __('National ID').':') !!}
                                                            {!! Form::number('staff_national_id',old('staff_national_id'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'staff_national_id') !!}
                                                    </div>

                                                    <div class="form-group col-sm-6{!! formError($errors,'staff_email',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_email', __('Email').':') !!}
                                                            {!! Form::email('staff_email',old('staff_email'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'staff_email') !!}
                                                    </div>


                                                    <div class="form-group col-sm-6{!! formError($errors,'staff_mobile',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_mobile', __('Mobile').':') !!}
                                                            {!! Form::text('staff_mobile',old('staff_mobile'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'staff_mobile') !!}
                                                    </div>

                                                    <div class="form-group col-sm-12{!! formError($errors,'staff_address',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_address', __('Address').':') !!}
                                                            {!! Form::textarea('staff_address',old('staff_address'),['class'=>'form-control','rows'=>3]) !!}
                                                        </div>
                                                        {!! formError($errors,'staff_address') !!}
                                                    </div>

                                                    <div class="form-group col-sm-4{!! formError($errors,'staff_birthdate',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_birthdate', __('Birthdate').':') !!}
                                                            {!! Form::date('staff_birthdate',old('staff_birthdate'),['class'=>'form-control','rows'=>3]) !!}
                                                        </div>
                                                        {!! formError($errors,'birthdate') !!}
                                                    </div>

                                                    @if(isset($tempData))
                                                        {!! Form::hidden('temp_data',$tempData->id,['class' => 'form-control']) !!}
                                                    @else
                                                        <div class="form-group col-sm-4{!! formError($errors,'staff_password',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('staff_password', __('Password').':') !!}
                                                                {!! Form::password('staff_password', ['class' => 'form-control','id'=>'staff_password']) !!}
                                                            </div>
                                                            {!! formError($errors,'staff_password') !!}
                                                        </div>

                                                        <div class="form-group col-sm-4{!! formError($errors,'staff_password_confirmation',true) !!}">
                                                            <div class="controls">
                                                                {!! Form::label('staff_password_confirmation', __('Retype Password').':') !!}
                                                                {!! Form::password('staff_password_confirmation', ['class' => 'form-control','id'=>'staff_password_confirmation']) !!}
                                                            </div>
                                                            {!! formError($errors,'staff_password_confirmation') !!}
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>






                                    {!! Form::close() !!}
                                </div>
                            @else
                                {!! Form::open(['route' => isset($result->id) ? ['merchant.merchant.update',$result->id]:'merchant.merchant.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('English Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_en', __('Merchant name (English)').':') !!}
                                                    {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_en') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_en', __('Merchant description (English)').':') !!}
                                                    {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'description_en') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Arabic Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_ar', __('Merchant name (Arabic)').':') !!}
                                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Merchant name (Arabic)').':') !!}
                                                    {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'description_ar') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'merchant_category_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_category_id', __('Merchant Category').':') !!}


                                                    {!! Form::select('merchant_category_id',$merchant_categories,isset($result->id) ? $result->merchant_category_id:old('merchant_category_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'merchant_category_id') !!}
                                            </div>

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


                                            <div class="form-group col-sm-12{!! formError($errors,'address',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('address', __('Address').':') !!}
                                                    {!! Form::textarea('address',isset($result->id) ? $result->address:old('address'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'address') !!}
                                            </div>

                                            <div class="form-group col-sm-4{!! formError($errors,'is_reseller',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('is_reseller', __('is Reseller').':') !!}
                                                    {!! Form::select('is_reseller',['in-active'=>__('No'),'active'=>__('Yes')],isset($result->id) ? $result->is_reseller:old('is_reseller'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'is_reseller') !!}
                                            </div>

                                            <div class="form-group col-sm-4{!! formError($errors,'logo',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('logo', __('Merchant Logo').':') !!}
                                                    @if(isset($result->logo))
                                                        <span><a target="_blank" href="{{asset('storage/'.$result->logo)}}">{{__('View logo')}}</a></span>
                                                    @endif
                                                    <label class="custom-file center-block block">
                                                        {!! Form::file('logo',['class'=>'custom-file-input']) !!}
                                                        <span class="custom-file-control"></span>
                                                    </label>
                                                </div>
                                                {!! formError($errors,'logo') !!}
                                            </div>

                                            <div class="form-group col-sm-4{!! formError($errors,'status',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('status', __('Merchant Status').':') !!}
                                                    {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'status') !!}
                                            </div>

                                            <div class="clearfix"></div>


                                            <div class="form-group col-sm-12{!! formError($errors,'parent_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('parent_id', __('Parent From Merchant').':') !!}
                                                    @if(isset($result->parent_id))
                                                        {!! Form::select('parent_id',[$result->parent->id => $result->parent->{'name_'.$systemLang}.' #ID: '.$result->parent_id],isset($result->id) ? $result->parent_id:old('parent_id'),['class'=>'select2 form-control']) !!}
                                                    @else
                                                        {!! Form::select('parent_id',[__('Select Merchant')],old('parent_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                    @endif
                                                </div>
                                                {!! formError($errors,'parent_id') !!}
                                            </div>



                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard">


                                            <div class="contactdata">
                                                @if(isset($result->id))
                                                    @foreach($result->contact()->get() as $onecontact)
                                                        <div class="form-group col-sm-12">
                                                            <div class="controls">
                                                                <label for="{!! $onecontact->type !!}">{!! ucfirst($onecontact->type) !!}:</label>
                                                                <div class="input-group">
                                                                    <input class="form-control" name="contact[{!! $onecontact->type !!}][]" class="{{$onecontact->type}}" type="{{(($onecontact->type=='email')?'email':'text')}}" value="{{$onecontact->value}}">
                                                                    <a class="input-group-addon btn btn-danger delcontactinfo"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>

                                            {!! formError($errors,'contact.name') !!}
                                            {!! formError($errors,'contact.email') !!}
                                            {!! formError($errors,'contact.mobile') !!}

                                            {!! formError($errors,'contact.name.*') !!}
                                            {!! formError($errors,'contact.email.*') !!}
                                            {!! formError($errors,'contact.mobile.*') !!}

                                            <br>
                                            <div class="pull-right">
                                                <div class="input-group">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span>{{__('Add info')}}</span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="name">{{__('Name')}}</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="mobile">{{__('Mobile')}}</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="phone">{{__('Phone')}}</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="email">{{__('Email')}}</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="fax">{{__('Fax')}}</a>
                                                        </div>
                                                    </div>
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
                                {!! Form::close() !!}
                            @endif


                        </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <div id="popups" class="hidden">
        <div id="contractPapers">
            <div class="onfile col-sm-12 div-with-files">
                <div class="col-sm-5 form-group">
                    <label for="title[]">{{__('Title')}}</label>
                    {!! Form::select('contractTitle[]',$contract_papers,null,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-5 form-group">
                    <label for="contractFile[]">{{__('File')}}</label>
                    <input class="form-control" name="contractFile[]" type="file">
                </div>
                <div class="col-sm-2 form-group">
                    <a href="javascript:void(0);" onclick="$(this).closest('.onfile.col-sm-12.div-with-files').remove();" class="text-danger">
                        <i class="fa fa-lg fa-trash mt-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <div id="OneImageFile">
            <div class="imagefile col-sm-12 div-with-files">
                <div class="col-sm-5 form-group">
                    <label for="imagetitles[]">{{__('Image title')}}</label>
                    {!! Form::text('imagetitles[]',null,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-5 form-group">
                    <label for="imagefiles[]">{{__('Image')}}</label>
                    <input class="form-control" name="imagefiles[]" type="file">
                </div>
                <div class="col-sm-2 form-group">
                    <a href="javascript:void(0);" onclick="$(this).closest('.imagefile.col-sm-12.div-with-files').remove();" class="text-danger">
                        <i class="fa fa-lg fa-trash mt-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <script src="{{asset('assets/system')}}/vendors/js/extensions/jquery.steps.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/forms/wizard-steps.js" type="text/javascript"></script>

    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>

    <script>

        @php
            $startWorkWithArea = (isset($result->area_id)) ? $result->area_id : ((isset($tempData))?\Illuminate\Support\Facades\Input::get('area_id'):getLastNotEmptyItem(old('area_id')));
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
    </script>

    <script>
        $('#contract_plan_id').change(function(){
            $planID     = $('#contract_plan_id').val();

            if($planID){
                $.getJSON(
                    '{{route('system.ajax.get')}}',
                    {
                        'type': 'merchantNewContractsDates',
                        'plan_id': $planID
                    },
                    function(response){
                        if(response.status == false){
                            $('.start-end-date').hide('fast');
                        }else{
                            $('#contract_start_date').val(response.start_date);
                            $('#contract_end_date').val(response.end_date);
                            $('.start-end-date').show('fast');
                        }
                    }
                );
            }else{
                $('.start-end-date').hide('fast');
            }

        });

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD'
            });

            ajaxSelect2('#parent_id','merchant');
            if($('#is_reseller').val()=='active'){
                $('label[for="parent_id"]').parents('.form-group').addClass('hidden');
            } else {
                $('label[for="parent_id"]').parents('.form-group').removeClass('hidden');
            }
            $('#is_reseller').on('change',function(){
                if($(this).val()=='active'){
                    $('label[for="parent_id"]').parents('.form-group').addClass('hidden');
                } else {
                    $('label[for="parent_id"]').parents('.form-group').removeClass('hidden');
                }
            });


        });
        // Branch
        markers = [];
        var map = '';
        var mapInitilized = false;
        function initAutocomplete() {
            map = new google.maps.Map(document.getElementById('map-events'), {
                @if(old('branch_latitude') && old('branch_longitude'))
                center: { lat: '{{old('branch_latitude')}}', lng: '{{old('branch_longitude')}}' },
                zoom: 16,
                @else
                center: {lat: 27.02194154036109, lng: 31.148436963558197},
                zoom: 6,
                @endif

                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });

            map.addListener('click', function(e) {
                placeMarker(e.latLng,map);
            });

                    @if(old('branch_latitude') && old('branch_longitude'))
            var marker = new google.maps.Marker({
                    position: {lat: {{old('branch_latitude')}}, lng: {{old('branch_longitude')}} },
                    map: map
                });
            markers.push(marker);
            @endif

            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach(function(marker) {
                    marker.setMap(null);
                });


                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function placeMarker(location,map) {
            clearOverlays();
            var marker = new google.maps.Marker({
                position: location,
                map: map,
            });
            var lng = location.lng();
            $('#branch_latitude').val(location.lat());
            $('#branch_longitude').val(location.lng());
            //console.log(lat+' And Long is: '+lng);
            markers.push(marker);
            //map.setCenter(location);
        }

        function clearOverlays() {
            for (var i = 0; i < markers.length; i++ ) {
                markers[i].setMap(null);
            }
            markers.length = 0;
        }

        $(function() {
            var observer = new MutationObserver(function(mutations) {
                if($('#map-events').is(':visible')) {
                    if (!mapInitilized) {
                        google.maps.event.trigger(map, 'resize');
                        mapInitilized = true;
                    }
                }
            });
            observer.observe(document.querySelector('#steps-uid-0-p-1'), {
                attributes: true
            });
        });


        $('.addinputfile').on('click',function(){
            var html = $('#contractPapers').clone()
                .html();
            $($('<hr>'),html).appendTo('.uploaddata');
        });

        $('.addnewimage').on('click',function(){
            $($('#OneImageFile').clone().html()).appendTo('.uploadimage');
        });
    </script>

@endsection