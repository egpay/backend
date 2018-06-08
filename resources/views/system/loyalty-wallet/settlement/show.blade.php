@extends('system.layouts')
@section('content')

    <style>
        td.details-control {
            background: url('{{asset('assets/system/images/details_open.png')}}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('{{asset('assets/system/images/details_close.png')}}') no-repeat center center;
        }
    </style>

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-1">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
                    <div class="breadcrumb-wrapper col-xs-12">
                        @include('system.breadcrumb')

                    </div>
                </div>
            </div>
            <div class="content-detached">
                <div class="content-body">

                    <section class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-head">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('Data')}}
                                        </h4>
                                    </div>
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
                                                    <td>{{__('System Commission')}}</td>
                                                    <td>{{amount($result->system_commission)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Merchant Commission')}}</td>
                                                    <td>{{amount($result->merchant_commission)}}</td>
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
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Transactions -->
                    </section>

                    <section id="server-processing">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{__('Invoices')}}</h4>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- Transactions -->
{{--
                    <section class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-head">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('Transactions')}}
                                            <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>
                                        </h4>
                                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Model')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Created At')}}</th>
                                                <th>{{__('Type')}}</th>
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Model')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Created At')}}</th>
                                                <th>{{__('Type')}}</th>
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Transactions -->
                    </section>
--}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-overlay-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/users.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/project.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/treegrid/jquery.treegrid.css')}}">

    <style>
        #map{
            height: 500px !important;
            width: 100% !important;
        }
    </style>
@endsection

@section('footer')

    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>



    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}" type="text/javascript" async defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js" type="text/javascript"></script>

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

                $.getJSON('{{route('system.ajax.get',['type'=>'getTransaction'])}}&id='+row.data()[1]+'&ownerMD5={{md5($result->walletowner_id.$result->walletowner_type)}}',function($data){
                    $result = '<table style="width: 100%;" cellspacing="0" border="0">'+
                        '<tr>'+
                        '<td>{{__('Transaction')}}</td>'+
                        '<td>'+ $data.data.fromName +' <i class="fa fa-long-arrow-right"></i> '+ $data.data.toName +'</td>'+
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
