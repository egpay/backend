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
                                {!! Form::open(['route' => isset($result->id) ? ['merchant.plan.update',$result->id]:'merchant.plan.store', 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Merchant plan')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'title',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('title', __('Plan title').':') !!}
                                                    {!! Form::text('title',isset($result->id) ? $result->title:old('title'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'title') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description', __('Plan description').':') !!}
                                                    {!! Form::textarea('description',isset($result->id) ? $result->description:old('description'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'description') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'months',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('months', __('Months').':') !!}
                                                    {!! Form::number('months',isset($result->id) ? $result->months:old('months'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'months') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'amount',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('amount', __('Amount').':') !!}
                                                    {!! Form::number('amount',isset($result->id) ? $result->amount:old('amount'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'amount') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('type', __('Merchant Type').':') !!}
                                                    <ul style="list-style-type: none;">
                                                        @if(isset($result->id))
                                                            <li>
                                                                <label for="e-payment">
                                                                {!! Form::checkbox('type[]','e-payment',((in_array('e-payment',$result->type))?true:null),['id'=>'e-payment']) !!} <span>E-Payment</span>
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label for="e-commerce">
                                                                {!! Form::checkbox('type[]','e-commerce',((in_array('e-commerce',$result->type))?true:null),['id'=>'e-commerce']) !!} <span>E-Commence</span>
                                                                </label>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <label for="e-payment">
                                                                    {!! Form::checkbox('type[]','e-payment',null,['id'=>'e-payment']) !!} <span>E-Payment</span>
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label for="e-commerce">
                                                                    {!! Form::checkbox('type[]','e-commerce',null,['id'=>'e-commerce']) !!} <span>E-Commerce</span>
                                                                </label>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                {!! formError($errors,'type') !!}
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
            {{--CustomSelect2('#merchant_id','{{route('ajax.findmerchant')}}');--}}

            {{--CustomSelect2('#area_id','{{route('ajax.findarea')}}');--}}
        });
    </script>

@endsection