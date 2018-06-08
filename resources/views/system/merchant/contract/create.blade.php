@extends('system.layouts')

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
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
                                {!! Form::open(['route' => isset($result->id) ? ['merchant.contract.update',$result->id]:'merchant.contract.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Base Info')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-6{!! formError($errors,'merchant_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_id', __('Merchants').':') !!}

                                                    @if(isset($merchantData))
                                                        {!! Form::text('merchant_text', $merchantData->{'name_'.$systemLang}.' #ID: '.$merchantData->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                        {!! Form::hidden('merchant_id',null,['id'=>'new_merchant_id']) !!}
                                                    @else

                                                        @if(isset($result->id))
                                                            {!! Form::text('merchant_text', $result->merchant->{'name_'.$systemLang}.' #ID: '.$result->merchant->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                        @else
                                                            {!! Form::select('merchant_id',[__('Select Merchant')],old('merchant_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                        @endif
                                                    @endif


                                                </div>
                                                {!! formError($errors,'merchant_id') !!}
                                            </div>


                                            <div class="form-group col-sm-6{!! formError($errors,'plan_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('plan_id', __('Plan').':') !!}
                                                    @php
                                                    $attrs = ['class'=>'form-control'];
                                                    if(isset($result->id)){
                                                        $attrs['disabled'] = 'disabled';
                                                    }
                                                    @endphp
                                                    {!! Form::select('plan_id',array_merge([0=>__('Select Plan')],$merchantPlans),isset($result->id) ? $result->plan_id:old('plan_id'),$attrs) !!}
                                                </div>
                                                {!! formError($errors,'plan_id') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description', __('Description').':') !!}
                                                    {!! Form::textarea('description',isset($result->id) ? $result->description:old('description'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'description') !!}
                                            </div>


                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Contract Info')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">

                                            <div class="form-group col-sm-12{!! formError($errors,'price',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('price', __('Contract fees').':') !!}
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-addon" id="sizing-addon1">EGP</span>
                                                        @php
                                                            $attrs = ['class'=>'form-control'];
                                                            if(isset($result->id)){
                                                                $attrs['disabled'] = 'disabled';
                                                            }
                                                        @endphp
                                                        {!! Form::number('price',isset($result->id) ? $result->price:old('price'),$attrs) !!}
                                                    </div>
                                                </div>
                                                {!! formError($errors,'price') !!}
                                            </div>


                                                <div class="form-group col-sm-6{!! formError($errors,'admin_name',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('admin_name', __('Manager name').':') !!}
                                                        {!! Form::text('admin_name',isset($result->id) ? $result->admin_name:old('admin_name'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'admin_name') !!}
                                                </div>

                                                <div class="form-group col-sm-6{!! formError($errors,'admin_job_title',true) !!}">
                                                    <div class="controls">
                                                        {!! Form::label('admin_job_title', __('Manager job title').':') !!}
                                                        {!! Form::text('admin_job_title',isset($result->id) ? $result->admin_job_title:old('admin_job_title'),['class'=>'form-control']) !!}
                                                    </div>
                                                    {!! formError($errors,'admin_job_title') !!}
                                                </div>


                                            <div style="display: none;" class="form-group start-end-date col-sm-6">
                                                <div class="controls">
                                                    {!! Form::label('start_date', __('Start Date').':') !!}
                                                    {!! Form::text('start_date',null,['class'=>'form-control','disabled'=>'disabled']) !!}
                                                </div>
                                            </div>

                                            <div style="display: none;" class="form-group start-end-date col-sm-6">
                                                <div class="controls">
                                                    {!! Form::label('end_date', __('End Date').':') !!}
                                                    {!! Form::text('end_date',null,['class'=>'form-control','disabled'=>'disabled']) !!}
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col-sm-6">
                                                <h2>{{__('Contract files')}}</h2>
                                                @if(formError($errors,'file.*',true))
                                                    <p class="text-xs-left"><small class="danger text-muted">{{__('Error File Upload')}}</small></p>
                                                @endif
                                            </div>
                                            <div style="text-align: right;" class="col-sm-6">
                                                <button type="button" class="btn btn-primary fa fa-plus addinputfile">
                                                    <span>{{__('Add File')}}</span>
                                                </button>
                                            </div>

                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="uploaddata">

                                                @if(isset($result->id))
                                                    <div class="table-responsive">


                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{__('File')}}</th>
                                                                <th>{{__('Action')}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($result->upload as $key => $value)
                                                            <tr>
                                                                <th scope="row">{{$key+1}}</th>
                                                                <td><a href="{{asset('storage/'.$value->path)}}">
                                                                        @if(empty($value->title))
                                                                            [FILE]
                                                                        @else
                                                                            {{$value->title}}
                                                                        @endif
                                                                    </a></td>
                                                                <td>
                                                                    <a class="btn btn-danger" onclick=""><i class="fa fa-trash"></i></a>
                                                                    {{--{{route('upload.delete',$value->id)}}--}}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>

                                                    </div>
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
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script>

        $(function(){
            ajaxSelect2('#merchant_id','merchant');

            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD'
            });

            {{--$('select#plan_id').on('change',function(){--}}
                {{--$.get('{{route('ajax.getplan')}}',{id:$(this).val()},function(data){--}}
                    {{--$('#start_date').val(data.start_date);--}}
                    {{--$('#end_date').val(data.end_date);--}}

                {{--},'json');--}}
            {{--});--}}
        });


        $('#merchant_id,#plan_id').change(function(){
            $planID     = $('#plan_id').val();
            $merchantID = $('#merchant_id').val();
            $newMerchantID = $('#new_merchant_id').val();

            if(!$merchantID && $newMerchantID){
                $merchantID = $newMerchantID;
            }

            if($planID && $merchantID){
                $.getJSON(
                    '{{route('system.ajax.get')}}',
                    {
                        'type': 'merchantNewContractsDates',
                        'plan_id': $planID,
                        'merchant_id': $merchantID
                    },
                    function(response){
                        if(response.status == false){
                            $('.start-end-date').hide('fast');
                        }else{
                            $('#start_date').val(response.start_date);
                            $('#end_date').val(response.end_date);
                            $('.start-end-date').show('fast');
                        }
                    }
                );
            }else{
                $('.start-end-date').hide('fast');
            }

        });

    </script>

@endsection