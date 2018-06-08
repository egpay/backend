@extends('system.layouts')
<!-- Modal -->

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
                                    {{ Form::label('transfer_type',__('Transfer Type')) }}
                                    {!! Form::select('transfer_type',[''=>__('Select Transfer Type'),'in'=>__('In'),'out'=>__('Out')],null,['class'=>'form-control','id'=>'transfer_type']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('from_wallet_id',__('From Wallet ID')) }}
                                    {!! Form::number('from_wallet_id',null,['class'=>'form-control','id'=>'from_wallet_id']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('to_wallet_id',__('To Wallet ID')) }}
                                    {!! Form::number('to_wallet_id',null,['class'=>'form-control','id'=>'to_wallet_id']) !!}
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
                                    {{ Form::label('amount',__('Amount To')) }}
                                    {!! Form::number('amount',null,['class'=>'form-control','id'=>'amount']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::select('status',[''=>__('Select Status'),'request'=>__('Request'),'approved'=>__('Approved'),'disapproved'=>__('Disapproved')],null,['class'=>'form-control','id'=>'status']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('action_staff_id',__('Action By')) }}
                                    <div>
                                        {!! Form::select('action_staff_id',[''=>__('Select Staff That take action')],null,['style'=>'width: 100%;' ,'id'=>'action_staff_id','class'=>'form-control col-md-12']) !!}
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('staffSelect2',__('Created By')) }}
                                    <div>
                                        {!! Form::select('staff_id',[''=>__('Select Staff')],null,['style'=>'width: 100%;' ,'id'=>'staffSelect2','class'=>'form-control col-md-12']) !!}
                                    </div>
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

<div class="modal fade text-xs-left" id="take-action-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Take Action')}}</label>
            </div>
            {!! Form::open(['onsubmit'=>'doTakeAction($(this));return false;']) !!}

            {!! Form::hidden('id',null,['id'=>'take-action-id']) !!}
            {!! Form::hidden('takeAction','true') !!}

            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">

                            <div id="take-action-table" class="col-md-12">

                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::select('status',['approved'=>__('Approved'),'disapproved'=>__('Disapproved')],null,['class'=>'form-control','id'=>'take-action-status']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::textarea('action_comment',null,['class'=>'form-control','id'=>'take-action-comment']) !!}
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-outline-primary btn-md" id="take-action-submit-button" value="{{__('Submit')}}">
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
                    <div class="content-header-title mb-0" style="float: right;">
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
                                                <td style="width:150px;"><b>{{__('Total Balance')}}:</b></td>
                                                <td id="SYSTEM_TOTAL"><b>{{amount(0,true)}}</b></td>
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
    <!-- END PAGE VENDOR JS-->

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">
        staffSelect('#staffSelect2');
        staffSelect('#action_staff_id');

        $dataTableVar = $('#egpay-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{!! url()->full() !!}",
                "type": "GET",
                "data": function(data){
                    data.isDataTable = "true";
                }
            },
            "fnPreDrawCallback": function(oSettings) {
                for (var i = 0, iLen = oSettings.aoData.length; i < iLen; i++) {
                    $('#SYSTEM_TOTAL').html('<b>'+oSettings.aoData[i]._aData[7]+'</b>');

                }
            }

        });

        function filterFunction($this){
            if($this == false) {
                $url = '{!! url()->full() !!}?isDataTable=true';
            }else {
                $url = '{!! url()->full() !!}?isDataTable=true&'+$this.serialize();
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


        function take_action($id,$this){
            $this.text('loading ...').attr('disabled','disabled');
            $.getJSON('{{route('system.wallet.requestRechargeWallet')}}?isDataTable=true&returnAjax=true&id='+$id,function($data){
                $('#take-action-id').val($data.id);
                $html = '<table class="table">\n' +
                    '                                <tbody>\n' +
                    '                                    <tr>\n' +
                    '                                        <td>{{__('ID')}}</td>\n' +
                    '                                        <td>'+$data.id+'</td>\n' +
                    '                                    </tr>\n' +
                    '                                    <tr>\n' +
                    '                                        <td>{{__('Amount')}}</td>\n' +
                    '                                        <td>'+$data.amount+' {{__('LE')}}</td>\n' +
                    '                                    </tr>\n' +
                    '                                </tbody>\n' +
                    '                            </table>';

                $('#take-action-table').html($html);
                $('#take-action-modal').modal('show');
                $this.html('<i class="ft-cog"></i>').removeAttr('disabled');
            });
        }

        function doTakeAction($this){
            $('#take-action-submit-button').val('loading ...').attr('disabled','disabled');
            $.getJSON('{{route('system.wallet.requestRechargeWallet')}}',$this.serialize(),function($data){
                if($data.status == true){
                    toastr.success($data.msg, 'Success', {"closeButton": true});
                    $('.action-id-'+$data.id).attr('disabled','disabled').text('{{__('Done')}}');
                    $('#take-action-modal').modal('hide');

                }else{
                    toastr.error($data.msg, 'Error !', {"closeButton": true});
                }

                $('#take-action-submit-button').val('{{__('Submit')}}').removeAttr('disabled','disabled');
            });
        }


    </script>
@endsection
