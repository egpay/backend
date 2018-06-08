@extends('system.layouts')

@section('content')

    <!-- Modal -->
    <div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{__('View Map')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8" id="map"></div>
                        <div class="list-group-item col-md-12" id="instructions"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
                    <!-- Transactions -->
                    <section class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="card">
                                <div class="card-head">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('Transactions')}}
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;" class="table table-striped table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{$result->id}}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Amount')}}</th>
                                                    <th>{{$result->amount}} {{__('LE')}}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Transaction')}}</th>
                                                    <th>
                                                        @if($result->fromWallet)
                                                            [ {{$WalletData::getWalletOwnerType($result->fromWallet->walletowner_type)}} ]
                                                            {!! '<a target="_blank" href="'.route('system.wallet.show',['ID'=> $result->fromWallet->id]).'">'.getWalletOwnerName($result->fromWallet,$systemLang).'</a>' !!}
                                                        @else
                                                            --
                                                        @endif


                                                            <i class="fa fa-long-arrow-right"></i>

                                                        @if($result->toWallet)
                                                                [ {{$WalletData::getWalletOwnerType($result->toWallet->walletowner_type)}} ]
                                                                {!! '<a target="_blank" href="'.route('system.wallet.show',['ID'=> $result->toWallet->id]).'">'.getWalletOwnerName($result->toWallet,$systemLang).'</a>' !!}
                                                            @else
                                                                --
                                                            @endif

                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th>{{__('Model')}}</th>
                                                    <th>{{$WalletData::getModelTypeByModel($result->model_type)}} ( {{$result->model_id}} )</th>
                                                </tr>

                                                <tr>
                                                    <th>{{__('Type')}}</th>
                                                    <th>{{$result->type}}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Status')}}</th>
                                                    <th>{{$result->status}}</th>
                                                </tr>



                                                <tr>
                                                    <th>{{__('Latitude')}}</th>
                                                    <th><a href="javascript:void(0)" onclick="viewMap({{$result->latitude}},{{$result->longitude}},'{{__('Location')}}')">{{$result->latitude}}</a></th>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Longitude')}}</th>
                                                    <th><a href="javascript:void(0)" onclick="viewMap({{$result->latitude}},{{$result->longitude}},'{{__('Location')}}')">{{$result->longitude}}</a></th>
                                                </tr>

                                                <tr>
                                                    <th>{{__('Created At')}}</th>
                                                    <th>{{$result->created_at->diffForHumans()}}</th>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Updated At')}}</th>
                                                    <th>{{$result->updated_at->diffForHumans()}}</th>
                                                </tr>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>









                        <div class="col-md-6 col-xs-12">
                            <div class="card">
                                <div class="card-head">
                                    <div class="card-header">
                                        <h4 class="card-title">
                                            {{__('Status')}}
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Created At')}}</th>
                                                <th>{{__('By')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($result->transactions_status)
                                                @foreach($result->transactions_status as $key => $value)
                                                    <tr>
                                                        <th>{{$value->status}}</th>
                                                        <th>{{$value->created_at->diffForHumans()}}</th>
                                                        <th title="{{$value->user_type}} ({{$value->user_id}})">{{$value->user->firstname.' '.$value->user->lastname}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="3" style="text-align: center;" >
                                                            @if(!empty($value->comment))
                                                                {{$value->comment}}
                                                            @else
                                                                --
                                                            @endif
                                                        </th>
                                                    </t r>
                                                @endforeach
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>








                        <!--/ Transactions -->
                    </section>

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
                    if(oSettings.aoData[i]._aData[6] != ''){
                        oSettings.aoData[i].anCells[6].className = 'details-control';
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

                $.getJSON('{{route('system.ajax.get',['type'=>'getTransaction'])}}&id='+row.data()[0]+'&ownerMD5={{md5($result->walletowner_id.$result->walletowner_type)}}',function($data){
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
