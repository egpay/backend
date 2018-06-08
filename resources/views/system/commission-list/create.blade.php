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
                                                    <li>{{$value}}</li>
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
                            {!! Form::open(['route' => isset($result->id) ? ['system.commission-list.update',$result->id]:'system.commission-list.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-12{!! formError($errors,'name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name', __('Name').':') !!}
                                                {!! Form::text('name',isset($result->id) ? $result->name:old('name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'description',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description', __('Descriptin').':') !!}
                                                {!! Form::textarea('description',isset($result->id) ? $result->description:old('description'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'description') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'commission_type',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('commission_type', __('Type').':') !!}
                                                {!! Form::select('commission_type',['one'=>__('One'),'multiple'=>__('Multiple')],isset($result->id) ? $result->commission_type:old('commission_type'),['class'=>'form-control','onchange'=>'commission_type_function();']) !!}
                                            </div>
                                            {!! formError($errors,'commission_type') !!}
                                        </div>

                                    </div>
                                </div>
                            </div>



                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>
                                            {{__('Segments')}}
                                        </h2>
                                        <i>{{__('If merchant dosn\'t have agent, agent commission will be add to system commission')}}</i>
                                    </div>
                                    <div class="card-block card-dashboard" id="condition-data-id">


                                        <div id="one">

                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data_charge_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('condition_data_charge_type', __('Charge Type').':') !!}
                                                    {!! Form::select('condition_data_charge_type',['fixed'=>__('Fixed'),'percent'=>__('Percentage')],isset($result->condition_data['charge_type']) && $result->commission_type == 'one' ? $result->condition_data['charge_type']:old('condition_data_charge_type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'condition_data_charge_type') !!}
                                            </div>

                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data_system_commission',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('condition_data_system_commission', __('System Commission').':') !!}
                                                    {!! Form::number('condition_data_system_commission',isset($result->condition_data['system_commission']) && $result->commission_type == 'one' ? $result->condition_data['system_commission']:old('condition_data_system_commission'),['class'=>'form-control','step'=>'0.01']) !!}
                                                </div>
                                                {!! formError($errors,'condition_data_system_commission') !!}
                                            </div>

                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data_agent_commission',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('condition_data_agent_commission', __('Agent Commission').':') !!}
                                                    {!! Form::number('condition_data_agent_commission',isset($result->condition_data['agent_commission']) && $result->commission_type == 'one' ? $result->condition_data['agent_commission']:old('condition_data_agent_commission'),['class'=>'form-control','step'=>'0.01']) !!}
                                                </div>
                                                {!! formError($errors,'condition_data_agent_commission') !!}
                                            </div>

                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data_merchant_commission',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('condition_data_merchant_commission', __('Merchant Commission').':') !!}
                                                    {!! Form::number('condition_data_merchant_commission',isset($result->condition_data['merchant_commission']) && $result->commission_type == 'one' ? $result->condition_data['merchant_commission']:old('condition_data_merchant_commission'),['class'=>'form-control','step'=>'0.01']) !!}
                                                </div>
                                                {!! formError($errors,'condition_data_merchant_commission') !!}
                                            </div>

                                        </div>



                                        <div id="multiple">

                                            <div style="text-align: right; padding-bottom: 10px;" class="col-sm-12">
                                                <button type="button" class="btn btn-primary fa fa-plus addinputfile">
                                                    <span>{{__('Add Segment')}}</span>
                                                </button>
                                            </div>


                                            <div class="uploaddata">

                                                @if(isset($result->id) && $result->commission_type == 'multiple')

                                                    @foreach($result->condition_data as $key => $value)
                                                        <div class="div-with-files">
                                                            <div class="form-group col-sm-5{!! formError($errors,'condition_data[amount_from][]',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('condition_data[amount_from][]', __('Amount From').':') !!}
                                                                    {!! Form::number('condition_data[amount_from][]',$value['amount_from'],['class'=>'form-control','step'=>'0.01']) !!}
                                                                </div>
                                                                {!! formError($errors,'condition_data[amount_from]') !!}
                                                            </div>

                                                            <div class="form-group col-sm-5{!! formError($errors,'condition_data[amount_to][]',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('condition_data[amount_to][]', __('Amount To').':') !!}
                                                                    {!! Form::number('condition_data[amount_to][]',$value['amount_to'],['class'=>'form-control','step'=>'0.01']) !!}
                                                                </div>
                                                                {!! formError($errors,'condition_data[amount_to]') !!}
                                                            </div>

                                                            <div style="padding-top: 40px;" class="col-sm-2 form-group">
                                                                <a style="color: red;" href="javascript:void(0);" class="remove-file"><i class="fa fa-trash"></i></a>
                                                            </div>

                                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data[charge_type][]',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('condition_data[charge_type][]', __('Charge Type').':') !!}
                                                                    {!! Form::select('condition_data[charge_type][]',['fixed'=>__('Fixed'),'percent'=>__('Percentage')],$value['charge_type'],['class'=>'form-control']) !!}
                                                                </div>
                                                                {!! formError($errors,'condition_data[charge_type][]') !!}
                                                            </div>

                                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data[system_commission][]',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('condition_data[system_commission][]', __('System Commission').':') !!}
                                                                    {!! Form::number('condition_data[system_commission][]',$value['system_commission'],['class'=>'form-control','step'=>'0.01']) !!}
                                                                </div>
                                                                {!! formError($errors,'condition_data[system_commission][]') !!}
                                                            </div>

                                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data[agent_commission][]',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('condition_data[agent_commission][]', __('Agent Commission').':') !!}
                                                                    {!! Form::number('condition_data[agent_commission][]',$value['agent_commission'],['class'=>'form-control','step'=>'0.01']) !!}
                                                                </div>
                                                                {!! formError($errors,'condition_data[agent_commission][]') !!}
                                                            </div>

                                                            <div class="form-group col-sm-3{!! formError($errors,'condition_data[merchant_commission][]',true) !!}">
                                                                <div class="controls">
                                                                    {!! Form::label('condition_data[merchant_commission][]', __('Merchant Commission').':') !!}
                                                                    {!! Form::number('condition_data[merchant_commission][]',$value['merchant_commission'],['class'=>'form-control','step'=>'0.01']) !!}
                                                                </div>
                                                                {!! formError($errors,'condition_data[merchant_commission][]') !!}
                                                            </div>

                                                            <div class="col-sm-12">
                                                            <hr />
                                                        </div>
                                                        </div>
                                                    @endforeach

                                                @endif


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
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/CustomInputCommissionList.js"></script>


    <script type="text/javascript">

        // On Page Ready
        $(document).ready(function(){
            commission_type_function();
        });

        function commission_type_function(){
            $value = $('#commission_type').val();
            if($value == 'one'){
                $('#multiple').hide();
                $('#one').show();
            }else{
                $('#one').hide();
                $('#multiple').show();
            }
        }
    </script>

@endsection