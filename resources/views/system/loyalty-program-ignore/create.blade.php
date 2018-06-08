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
                                            <ul>
                                                @foreach($errors->all() as $key => $value)
                                                    <li>{{$key}}: {{$value}}</li>
                                                @endforeach
                                            </ul>
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
                            {!! Form::open(['route' => isset($result->id) ? ['system.loyalty-program-ignore.update',$result->id]:'system.loyalty-program-ignore.store','method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                                @if(!isset($result->id))
                                {!! Form::hidden('loyalty_program_id',$loyaltyProgram->id) !!}
                                @endif
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('English Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_en', __('Description (English)').':') !!}
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
                                            <h2>{{__('Arabic Info')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Description (Arabic)').':') !!}
                                                    {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'description_ar') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Ignore Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-6{!! formError($errors,'ignoremodel_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('ignoremodel_type', __('Model Type').':') !!}
                                                    {!! Form::select('ignoremodel_type',[''=>__('Select Model Type'),'App\Models\MerchantProduct'=>__('Products'),'App\Models\PaymentServices'=>__('Payment Services'),'App\Models\Staff'=>__('Staff'),'App\Models\Merchant'=>__('Merchants'),'App\Models\User'=>__('Users')],isset($result->id) ? $result->ignoremodel_type:old('ignoremodel_type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'ignoremodel_type') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'ignoremodel_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('ignoremodel_id', __('ID').':') !!}
                                                    {!! Form::number('ignoremodel_id',isset($result->id) ? $result->ignoremodel_id:old('ignoremodel_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'ignoremodel_id') !!}
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