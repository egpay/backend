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
                                {!! Form::open(['route' => isset($result->id) ? ['merchant.staff-group.update',$result->id]:'merchant.staff-group.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Merchant')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12 {!! formError($errors,'merchant_id',true) !!}">
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

                                            <div class="form-group col-sm-12{!! formError($errors,'title',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('title', __('Title').':') !!}
                                                    {!! Form::text('title',isset($result->id) ? $result->title:old('title'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'title') !!}
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
    <script>

        $(function(){
            ajaxSelect2('#merchant_id','merchant');
        });
    </script>

@endsection