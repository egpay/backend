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
                            {!! Form::open(['route' => isset($result->id) ? ['system.advertisement.update',$result->id]:'system.advertisement.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-12{!! formError($errors,'name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name', __('Title').':') !!}
                                                {!! Form::text('name',isset($result->id) ? $result->name:old('name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name') !!}
                                        </div>



                                        <div class="form-group col-sm-12{!! formError($errors,'route',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('route', __('Route ( use , to add multiple route name )').' :') !!}
                                                {!! Form::textarea('route',isset($result->id) ? $result->route:old('route'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'route') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('route_id', __('Route ID').':') !!}
                                                {!! Form::number('route_id',isset($result->id) ? $result->route_id:old('route_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'route_id') !!}
                                        </div>



                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-12{!! formError($errors,'image',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('image', __('Banner').':') !!}
                                                    {!! Form::file('image',['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'image') !!}
                                            </div>



                                            <div class="form-group col-sm-6{!! formError($errors,'width',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('width', __('Image Width').':') !!}
                                                    {!! Form::number('width',isset($result->id) ? $result->width:old('width'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'width') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'height',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('height', __('Image Height').':') !!}
                                                    {!! Form::number('height',isset($result->id) ? $result->height:old('height'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'height') !!}
                                            </div>


                                            <div class="form-group col-sm-12{!! formError($errors,'comment',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('comment', __('Comment').':') !!}
                                                    {!! Form::textarea('comment',isset($result->id) ? $result->comment:old('comment'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'comment') !!}
                                            </div>


                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-4{!! formError($errors,'total_amount',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('total_amount', __('Total Amount').':') !!}
                                                    {!! Form::number('total_amount',isset($result->id) ? $result->total_amount:old('total_amount'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'total_amount') !!}
                                            </div>


                                            <div class="form-group col-sm-4{!! formError($errors,'type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('type', __('Type').':') !!}
                                                    {!! Form::select('type',['merchant'=>__('Merchant'),'user'=>__('User')],isset($result->id) ? $result->type:old('type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'type') !!}
                                            </div>

                                            <div class="form-group col-sm-4{!! formError($errors,'status',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('status', __('Status').':') !!}
                                                    {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'status') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'merchant_id',true) !!}">
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


                                            <div class="form-group col-sm-6{!! formError($errors,'from_date',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('from_date', __('Start At').':') !!}
                                                    {!! Form::text('from_date',isset($result->id) ? $result->from_date:old('from_date'),['class'=>'form-control datepicker']) !!}
                                                </div>
                                                {!! formError($errors,'from_date') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'to_date',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('to_date', __('End At').':') !!}
                                                    {!! Form::text('to_date',isset($result->id) ? $result->to_date:old('to_date'),['class'=>'form-control datepicker']) !!}
                                                </div>
                                                {!! formError($errors,'to_date') !!}
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

    <style>
        #map{
            height: 500px !important;
            width: 100% !important;
        }
    </style>

@endsection;

@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>
    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            ajaxSelect2('#merchant_id','merchant');
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });
    </script>
@endsection