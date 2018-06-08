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
                        {!! Form::open(['route' => isset($result->id) ? ['panel.merchant.bank.update',$result->id]:'panel.merchant.bank.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Bank Details')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name', __('Name').':') !!}
                                                {!! Form::text('name',isset($result->id) ? $result->name:old('name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'account_number',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('account_number', __('Account Number').':') !!}
                                                {!! Form::text('account_number',isset($result->id) ? $result->account_number:old('account_number'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'account_number') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'bank_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('bank_id', __('Bank').':') !!}
                                                {!! Form::select('bank_id',$banks,isset($result->id) ? $result->bank_id:old('bank_id'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'bank_id') !!}
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
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection

@section('header')
@endsection

@section('footer')
@endsection