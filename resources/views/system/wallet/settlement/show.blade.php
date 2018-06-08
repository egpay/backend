@extends('system.layouts')

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
            <div class="content-body"><!-- Spacing -->
                <div class="row">



                    <div class="col-md-12">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Data')}}
                                </h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{__('ID')}}</td>
                                                <td>{{$result->id}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>{{statusColor($result->status)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Period')}}</td>
                                                <td>{{$result->from_date_time}}<br>{{$result->to_date_time}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('System Commission')}}</td>
                                                <td>{{amount($result->system_commission)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Agent Commission')}}</td>
                                                <td>{{amount($result->agent_commission)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Merchant Commission')}}</td>
                                                <td>{{amount($result->merchant_commission)}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Success Settlement')}}</td>
                                                <td>{{$result->num_success}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Fail Settlement')}}</td>
                                                <td>{{$result->num_error}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>{{$result->created_at->diffForHumans()}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>{{$result->updated_at->diffForHumans()}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Wallet')}}</td>
                                                <td><a href="{{route('system.wallet.show',$result->wallet->id)}}" target="_blank">{{getWalletOwnerName($result->wallet,$systemLang)}}</a></td>
                                            </tr>


                                            @if(!is_null($result->agent_wallet))
                                                <tr>
                                                    <td>{{__('Agent Wallet')}}</td>
                                                    <td><a href="{{route('system.wallet.show',$result->agent_wallet->id)}}" target="_blank">{{getWalletOwnerName($result->agent_wallet,$systemLang)}}</a></td>
                                                </tr>
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-12">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Invoices')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table id="egpay-datatable" class="table table-striped table-bordered">
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
                                    </div>
                                </div>

                            </div>
                        </section>

                    </div>

                    <div class="col-md-12">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Wallet Transaction')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table id="egpay-datatable-WalletTransaction" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                @foreach($tableColumnsWalletTransaction as $key => $value)
                                                    <th>{{$value}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                @foreach($tableColumnsWalletTransaction as $key => $value)
                                                    <th>{{$value}}</th>
                                                @endforeach
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </section>

                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <style>
        td.details-control {
            background: url('{{asset('assets/system/images/details_open.png')}}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('{{asset('assets/system/images/details_close.png')}}') no-repeat center center;
        }
    </style>
@endsection

@section('footer')
    <script type="text/javascript">
        // Payment Invoice
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


        // Wallet Transaction
        $dataTableVarWalletTransaction = $('#egpay-datatable-WalletTransaction').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isWalletTransaction = "true";
                }
            },
            "fnPreDrawCallback": function(oSettings) {
                for (var i = 0, iLen = oSettings.aoData.length; i < iLen; i++) {
                    if(oSettings.aoData[i]._aData[0] != ''){
                        oSettings.aoData[i].anCells[0].className = 'details-control';
                    }
                }
            }

        });
        $('#egpay-datatable-WalletTransaction tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $dataTableVarWalletTransaction.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            } else {

                $.getJSON('{{route('system.ajax.get',['type'=>'getTransaction'])}}&id='+row.data()[1],function($data){
                    $from = '['+$data.data.fromType+'] '+$data.data.fromName;
                    $to = '['+$data.data.toType+'] '+$data.data.toName;

                    $result = '<table style="width: 100%;" cellspacing="0" border="0">'+
                        '<tr>'+
                        '<td>{{__('Transaction')}}</td>'+
                        '<td>'+$from+' <i class="fa fa-long-arrow-right"></i> '+$to+ '</td>'+
                        '</tr>'+

                        '<tr>'+
                        '<td>{{__('Last Update')}}</td>'+
                        '<td>'+ $data.data.updated_at +'</td>'+
                        '</tr>';


                    if($data.data.modelName == 'order'){
                        $result+=
                            '<tr>'+
                            '<td>{{__('Total')}}</td>'+
                            '<td>'+ $data.data.model.total +'</td>'+
                            '</tr>'+


                            '<tr>'+
                            '<td>{{__('Order Status')}}</td>'+
                            '<td>'+ $data.data.model.status +'</td>'+
                            '</tr>';
                    }else if($data.data.modelName == 'invoice'){
                        $result+=
                            '<tr>'+
                            '<td>{{__('Total')}}</td>'+
                            '<td>'+ $data.data.model.total +'</td>'+
                            '</tr>'+
                            '<tr>'+
                            '<td>{{__('Invoice Status')}}</td>'+
                            '<td>'+ $data.data.model.status +'</td>'+
                            '</tr>'+
                            '<tr>'+
                            '<td>{{__('Service Name')}}</td>'+
                            '<td>'+ $data.data.payment_services_name +'</td>'+
                            '</tr>';
                    }

                    $result+= '</table>';

                    row.child($result).show();
                    tr.addClass('shown');
                });
            }
        } );


        // Filter
        function filterFunction($this){
            if($this == false) {
                $url = '{{url()->full()}}?isDataTable=true';
            }else {
                $url = '{{url()->full()}}?isDataTable=true&'+$this.serialize();
            }

            $dataTableVar.ajax.url($url).load();
            $('#filter-modal').modal('hide');
        }

    </script>

@endsection
