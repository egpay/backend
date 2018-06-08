@extends('system.layouts')
<div class="modal fade text-xs-left" id="filter-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Filter')}}</label>
            </div>
            {!! Form::open(['onsubmit'=>'filterFunction($(this));return false;']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('created_at1',__('Created From')) }}
                                    {!! Form::text('created_at1',null,['class'=>'form-control datepicker','id'=>'created_at1']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('created_at2',__('Created To')) }}
                                    {!! Form::text('created_at2',null,['class'=>'form-control datepicker','id'=>'created_at2']) !!}
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('id',__('ID')) }}
                                    {!! Form::number('id',null,['class'=>'form-control','id'=>'id']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('response_type',__('Request Status')) }}
                                    {!! Form::select('response_type',[''=>__('Select Request Status'),'request'=>__('Request'),'done'=>__('Done'),'fail'=>__('Fail')],null,['class'=>'form-control','id'=>'response_type']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('service_type',__('Service Type')) }}
                                    {!! Form::select('service_type',[''=>__('Select Service Type'),'payment'=>__('Payment'),'inquiry'=>__('Inquiry')],null,['class'=>'form-control','id'=>'service_type']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('external_system_id',__('External System ID')) }}
                                    {!! Form::text('external_system_id',null,['class'=>'form-control','id'=>'external_system_id']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                        {{ Form::label('payment_sdk_id',__('Payment SDK')) }}
                                    {!! Form::select('payment_sdk_id',[''=>__('Select Payment SDK')]+array_column($paymentSDK->toArray(),'name','id'),null,['class'=>'form-control','id'=>'payment_sdk_id']) !!}
                                </fieldset>
                            </div>


                            @include('system.partial._paymentService')


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('amount1',__('Amount From')) }}
                                    {!! Form::number('amount1',null,['class'=>'form-control','id'=>'amount1']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('amount2',__('Amount To')) }}
                                    {!! Form::number('amount2',null,['class'=>'form-control','id'=>'amount2']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('total_amount1',__('Total Amount From')) }}
                                    {!! Form::number('total_amount1',null,['class'=>'form-control','id'=>'total_amount1']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('total_amount2',__('Total Amount To')) }}
                                    {!! Form::number('total_amount2',null,['class'=>'form-control','id'=>'total_amount2']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('model_type',__('Model')) }}
                                    {!! Form::select('model_type',[''=>__('Select Model'),'App\Models\Staff'=>__('Staff'),'App\Models\User'=>__('Users'),'App\Models\MerchantStaff'=>__('Merchant Staff')],null,['class'=>'form-control','id'=>'service_type']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('model_id',__('Model ID')) }}
                                    {!! Form::number('model_id',null,['class'=>'form-control','id'=>'model_id']) !!}
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="reset" class="btn btn-outline-secondary btn-md" value="{{__('Reset Form')}}">
                <input type="submit" class="btn btn-outline-primary btn-md" value="{{__('Filter')}}">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                        <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>

                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
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
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{$pageTitle}}</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                @foreach($tableColumns as $key => $value)
                                                    <th>{{$value}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                @foreach($tableColumns as $key => $value)
                                                    <th>{{$value}}</th>
                                                @endforeach
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                            <tr>
                                                <td style="width:150px;"><b>{{__('Amount')}}:</b></td>
                                                <td id="SYSTEM_TOTAL"><b>{{amount(0,true)}}</b></td>
                                            </tr>
                                            <tr>
                                                <td style="width:150px;"><b>{{__('Total Amount')}}:</b></td>
                                                <td id="SYSTEM_TOTAL_AMOUNT"><b>{{amount(0,true)}}</b></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

@endsection;


@section('footer')

    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">

        $dataTableVar = $('#egpay-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isDataTable = "true";
                }
            },
            "fnPreDrawCallback": function(oSettings) {
                for (var i = 0, iLen = oSettings.aoData.length; i < iLen; i++) {
                    if(oSettings.aoData[i]._aData[0] != ''){
                        oSettings.aoData[i].anCells[0].className = 'details-control';
                    }
                    $('#SYSTEM_TOTAL').html('<b>'+oSettings.aoData[i]._aData[9]+'</b>');
                    $('#SYSTEM_TOTAL_AMOUNT').html('<b>'+oSettings.aoData[i]._aData[10]+'</b>');
                }
            }

        });

        $('#egpay-datatable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $dataTableVar.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            } else {

                $.getJSON('{{route('payment.transactions.ajax-details')}}/'+row.data()[2],function($data){
                    $result = '<table style="width: 100%;" cellspacing="0" border="0">';

                    if(!empty($data.parameter)){
                        $result+= '<tr style="background: aliceblue;text-align: center;">'+
                            '<td colspan="2">{{__('Request Map')}}</td>'+
                            '</tr>';

                        $.each($data.parameter,function($key,$value){
                            $result+= '<tr>'+
                                '<td>'+$data.parameterData[$key]+'</td>'+
                                '<td>'+ $value +'</td>'+
                                '</tr>';
                        });
                    }

                    if(!empty($data.response)){
                        $result+= '<tr style="background: aliceblue;text-align: center;">'+
                            '<td colspan="2">{{__('Response')}}</td>'+
                            '</tr>';

                        $.each($data.response,function($key,$value){
                            if(typeof $value === "object" && !Array.isArray($value) && $value !== null){
                                $.each($value,function($key1,$value1){
                                    if(typeof $value1 === "object" && !Array.isArray($value1) && $value1 !== null) {
                                        $.each($value1, function ($key2, $value2) {
                                            $result += '<tr>' +
                                                '<td>' + $key2 + '</td>' +
                                                '<td>' + $value2 + '</td>' +
                                                '</tr>';
                                        });
                                    }else{
                                        $result+= '<tr>'+
                                            '<td>'+$key1+'</td>'+
                                            '<td>'+ $value1 +'</td>'+
                                            '</tr>';
                                    }

                                });
                            }else{
                                $result+= '<tr>'+
                                    '<td>'+$key+'</td>'+
                                    '<td>'+ $value +'</td>'+
                                    '</tr>';
                            }

                        });
                    }

                    $result+= '</table>';

                    row.child($result).show();
                    tr.addClass('shown');
                });
            }
        } );

        function filterFunction($this){
            if($this == false) {
                $url = '{{url()->full()}}?isDataTable=true';
            }else {
                $url = '{{url()->full()}}?isDataTable=true&'+$this.serialize();
            }

            $dataTableVar.ajax.url($url).load();
            $('#filter-modal').modal('hide');
        }

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });

        /*
         *  Payment Service select start
         */
        var AjaxUrl = '{{route('system.ajax.get')}}';
        $(function(){
            $.getJSON(AjaxUrl,{type:'payment_service_categories'},function(data){
                $CategorySelect = $('select#payment_service_provider_category_id');
                $CategorySelect.empty();
                $CategorySelect.append('<option value="0">{{__('Select Service Category')}}</option>');
                $.each(data,function(key, value) {
                    $CategorySelect.append('<option value=' + key + '>' + value + '</option>');
                });
            });
        });

        $('select#payment_service_provider_category_id').on('change',function () {
            $categoryId = $('#payment_service_provider_category_id').val();
            $.getJSON(AjaxUrl,{type:'payment_service_providers',category_id:$categoryId},function(data){
                $providerSelect = $('select#payment_service_provider_id');
                $providerSelect.empty();
                $providerSelect.append('<option value="0">{{__('Select Service Provider')}}</option>');
                $.each(data,function(key, value) {
                    $providerSelect.append('<option value=' + key + '>' + value + '</option>');
                });
            });
        });

        $('select#payment_service_provider_id').on('change',function () {
            $providerId = $('#payment_service_provider_id').val();
            $.getJSON(AjaxUrl,{type:'payment_services',provider_id:$providerId},function(data){
                $serviceSelect = $('select#payment_services_id');
                $serviceSelect.empty();
                $serviceSelect.append('<option value="0">{{__('Select Service ID')}}</option>');
                $.each(data,function(key, value) {
                    $serviceSelect.append('<option value=' + key + '>' + value + '</option>');
                });
            });
        });

        /*
         *   Payment Service select end
         */

    </script>
@endsection
