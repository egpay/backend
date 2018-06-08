@extends('merchant.layouts')
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


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('service_type',__('Service Type')) }}
                                    {!! Form::select('service_type',[''=>__('Select Service Type'),'payment'=>__('Payment'),'inquiry'=>__('Inquiry')],null,['class'=>'form-control','id'=>'service_type']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('payment_services_id',__('Payment Services ID')) }}
                                    {!! Form::select('payment_services_id',[''=>__('Select Payment Services')]+array_column($paymentServices->toArray(),'name','id'),null,['class'=>'form-control','id'=>'payment_services_id']) !!}
                                </fieldset>
                            </div>


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

            <div class="row">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="pull-left">{{$pageTitle}}</h2>
                            <h2 class="pull-right">
                                <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>
                            </h2>
                        </div>
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
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection




@section('header')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

@endsection


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

    <script src="{{asset('assets/system/vendors/js/tables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}" type="text/javascript"></script>
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

                $.getJSON('{{route('panel.merchant.payment.transactions.ajax-details')}}/'+row.data()[2],function($data){
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

    </script>
@endsection
