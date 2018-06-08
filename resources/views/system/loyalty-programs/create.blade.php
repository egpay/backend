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
                            {!! Form::open(['route' => isset($result->id) ? ['system.loyalty-programs.update',$result->id]:'system.loyalty-programs.store','method' => isset($result->id) ?  'PATCH' : 'POST']) !!}

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('English Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_en', __('Name (English)').':') !!}
                                                    {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'name_en') !!}
                                            </div>
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



                                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_ar', __('Name (Arabic)').':') !!}
                                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>
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
                                            <h2>{{__('Options')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-3{!! formError($errors,'type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('type', __('Type').':') !!}
                                                    {!! Form::select('type',[''=>__('Select Type'),'invoice'=>__('Payment'),'order'=>__('E-commerce')],isset($result->id) ? $result->type:old('type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'type') !!}
                                            </div>

                                            <div class="form-group col-sm-3{!! formError($errors,'transaction_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('transaction_type', __('Type').':') !!}
                                                    {!! Form::select('transaction_type',[''=>__('Select Transaction Type'),'wallet'=>__('Wallet'),'cash'=>__('Cash')],isset($result->id) ? $result->transaction_type:old('transaction_type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'transaction_type') !!}
                                            </div>



                                            <div class="form-group col-sm-3{!! formError($errors,'pay_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('pay_type', __('Pay Type').':') !!}
                                                    {!! Form::select('pay_type',[''=>__('Select Pay Type'),'income'=>__('Income'),'expenses'=>__('Expenses')],isset($result->id) ? $result->pay_type:old('pay_type'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'pay_type') !!}
                                            </div>

                                            <div class="form-group col-sm-3{!! formError($errors,'owner',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('owner', __('Owner').':') !!}
                                                    {!! Form::select('owner',[''=>__('Select Owner'),'user'=>__('User'),'merchant'=>__('Merchant'),'staff'=>__('Staff')],isset($result->id) ? $result->owner:old('owner'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'owner') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'list_type',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('list_type', __('Point Type').':') !!}
                                                    {!! Form::select('list_type',[''=>__('Select Point Type'),'static'=>__('Static'),'dynamic'=>__('Dynamic')],isset($result->id) ? $result->list['type']:old('list_type'),['class'=>'form-control','onchange'=>'list_type_function();']) !!}
                                                </div>
                                                {!! formError($errors,'list_type') !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-12" id="static-point-div">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Static Point')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-6{!! formError($errors,'list_amount',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('list_amount', __('Amount').':') !!}
                                                    {!! Form::number('list_amount',isset($result->id) && $result->list['type'] == 'static' ? $result->list['list']['amount']:old('list_amount'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'list_amount') !!}
                                            </div>

                                            <div class="form-group col-sm-6{!! formError($errors,'list_point',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('list_point', __('Points').':') !!}
                                                    {!! Form::number('list_point',isset($result->id) && $result->list['type'] == 'static' ? $result->list['list']['point']:old('list_point'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'list_point') !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12" id="dynamic-point-div">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col-sm-6">
                                                <h2>{{__('Dynamic Point')}}</h2>
                                                @if(formError($errors,'list.*',true))
                                                    <p class="text-xs-left"><small class="danger text-muted">{{__('Unknown Error')}}</small></p>
                                                @endif
                                            </div>
                                            <div style="text-align: right;" class="col-sm-6">
                                                <button type="button" class="btn btn-primary fa fa-plus addinputfile">
                                                    <span>{{__('Add Parameter')}}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="uploaddata">
                                                @if(isset($result->id) && $result->list['type'] == 'dynamic')

                                                    @foreach($result->list['list'] as $key => $value)
                                                        <div class="div-with-files">

                                                            <div class="form-group col-sm-3">
                                                                <div class="controls">
                                                                    <label>{{__('From Amount')}}:</label>
                                                                    <input type="number" value="{{$value['from_amount']}}" class="form-control" name="list[from_amount][]">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-3">
                                                                <div class="controls">
                                                                    <label>{{__('To Amount')}}:</label>
                                                                    <input type="number" value="{{$value['to_amount']}}" class="form-control" name="list[to_amount][]">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-4">
                                                                <div class="controls">
                                                                    <label>{{__('Point')}}:</label>
                                                                    <input type="number" value="{{$value['point']}}" class="form-control" name="list[point][]">
                                                                </div>
                                                            </div>

                                                            <div style="padding-top: 40px;" class="col-sm-2 form-group">
                                                                <a style="color: red;" href="javascript:void(0);" class="remove-file"><i class="fa fa-trash"></i></a>
                                                            </div>

                                                        </div>
                                                    @endforeach

                                                @endif
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
    <script src="{{asset('assets/system/js/scripts')}}/custom/CustomInputLoyaltyPrograms.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            list_type_function();
        });

        function list_type_function(){
            $value = $('#list_type').val();
            if($value == 'static'){
                $('#dynamic-point-div').hide();
                $('#static-point-div').show();
            }else{
                $('#static-point-div').hide();
                $('#dynamic-point-div').show();
            }
        }

    </script>
@endsection