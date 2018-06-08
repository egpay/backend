@extends('system.layouts')

@section('header')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
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
                                                        {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_ar'),['class'=>'form-control']) !!}
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
                                                            <span><a target="_blank" href="{{asset('storage/'.$result->logo)}}">{{__('View Icon')}}</a></span>
                                                        @endif
                                                        {!! Form::file('logo',['class'=>'form-control']) !!}
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
                        </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>
    <script>
        $(function(){

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
    </script>

@endsection