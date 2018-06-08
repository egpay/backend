@extends('merchant.layouts')

@section('content')

            <div class="card">
                <div class="card-header">
                    <h2>{{$pageTitle}}</h2>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    {!! Form::open(['route' => isset($result->id) ? ['panel.merchant.employee.update',$result->id]:'panel.merchant.employee.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('Employee info')}}</h2>
                                </div>
                                <div class="card-block card-dashboard">

                                    <div class="form-group col-sm-6{!! formError($errors,'firstname',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('firstname', __('Firstname').':') !!}
                                            {!! Form::text('firstname',isset($result->id) ? $result->firstname:old('firstname'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'firstname') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'lastname',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('lastname', __('Lastname').':') !!}
                                            {!! Form::text('lastname',isset($result->id) ? $result->lastname:old('lastname'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'lastname') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'national_id',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('national_id', __('National ID').':') !!}
                                            {!! Form::text('national_id',isset($result->id) ? $result->national_id:old('national_id'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'national_id') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'email',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('email', __('Email').':') !!}
                                            {!! Form::text('email',isset($result->id) ? $result->email:old('email'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'email') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'merchant_staff_group_id',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('merchant_staff_group_id', __('Staff group').':') !!}
                                            {!! Form::select('merchant_staff_group_id',$staff_group,isset($result->id) ? $result->merchant_staff_group_id:old('merchant_staff_group_id'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'merchant_staff_group_id') !!}
                                    </div>

                                    <div class="form-group col-sm-6{!! formError($errors,'status',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('status', __('Employee Account Status').':') !!}
                                            {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'status') !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('Branches')}}</h2>
                                </div>
                                <div class="card-block card-dashboard">
                                    @if((isset($result)) && $merchant->merchant_staff_group()->first()->id == $result->id)
                                        <div>
                                            <h3 class="text-success">{{__('By default users of this group have permissions on all branches')}}</h3>
                                        </div>
                                    @else
                                        <ul style="list-style-type: none;">
                                            @foreach($branches as $key=>$val)
                                                <label class="col-sm-4 mb-1">
                                                    {!! Form::checkbox("branches[]", "$key", isset($result->id) ? in_array($key,$result->branches) : false) !!}
                                                    {!! ucfirst($val) !!}
                                                </label>
                                            @endforeach
                                        </ul>
                                        <div class="col-sm-12">{!! formError($errors,'branches') !!}</div>
                                    @endif
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
            </div>
                <!--/ Javascript sourced data -->
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('header')
@endsection

@section('footer')
@endsection