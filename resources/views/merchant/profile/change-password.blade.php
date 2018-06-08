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
                    {!! Form::open(['route' =>'panel.merchant.user.update-password','files'=>false, 'method' =>'PATCH']) !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('Update Profile')}}</h2>
                                </div>
                                <div class="card-block card-dashboard">

                                    <div class="row">
                                        <div class="form-group col-sm-12{!! formError($errors,'current_password',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('current_password', __('Current password').':') !!}
                                                {!! Form::password('current_password',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'current_password') !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6{!! formError($errors,'password',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('password', __('Password').':') !!}
                                                {!! Form::password('password',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'password') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'password',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('password_confirmation', __('Password Confirmation').':') !!}
                                                {!! Form::password('password_confirmation',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'password') !!}
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
            </div>
                <!--/ Javascript sourced data -->
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('header')
@endsection

@section('footer')
@endsection