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
                    {!! Form::open(['route' =>'panel.merchant.user.edit-info','files'=>false, 'method' =>'PATCH']) !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('Update Profile')}}</h2>
                                </div>
                                <div class="card-block card-dashboard">

                                    <div class="form-group col-sm-12{!! formError($errors,'firstname',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('firstname', __('Firstname').':') !!}
                                            {!! Form::text('firstname',auth()->user()->firstname,['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'firstname') !!}
                                    </div>

                                    <div class="form-group col-sm-12{!! formError($errors,'lastname',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('lastname', __('Lastname').':') !!}
                                            {!! Form::text('lastname',auth()->user()->lastname,['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'lastname') !!}
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