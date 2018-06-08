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


                            @php
                                $walletModelTypeArray = [''=>__('Select Model Type')];
                                foreach ($walletModelType as $key => $value){
                                    $walletModelTypeArray[$key] = __(ucfirst($key));
                                }
                            @endphp
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('model_type',__('Model Type')) }}
                                    {!! Form::select('model_type',$walletModelTypeArray,null,['class'=>'form-control','id'=>'model_type']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('model_id',__('Model ID')) }}
                                    {!! Form::number('model_id',null,['class'=>'form-control','id'=>'model_id']) !!}
                                </fieldset>
                            </div>





                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('type',__('Type')) }}
                                    {!! Form::select('type',[''=>__('Select Type'),'wallet'=>__('Wallet'),'cash'=>__('Cash')],null,['class'=>'form-control','id'=>'type']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::select('status',[''=>__('Select Status'),'pending'=>__('Pending'),'paid'=>__('Paid'),'reverse'=>__('Reverse')],null,['class'=>'form-control','id'=>'status']) !!}
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
                <div class="content-body"><section class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-head">
                                    <div class="card-header">
                                        <h4 class="card-title">{{getWalletOwnerName($result,$systemLang)}}</h4>
                                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    </div>
                                    <div class="px-1">
                                        <ul class="list-inline list-inline-pipe text-xs-center p-1 border-bottom-grey border-bottom-lighten-3">
                                            <li>{{__('Created From')}}: <span class="text-muted">{{$result->created_at->diffForHumans()}}</span></li>
                                            <li>{{__('Last Update')}}: <span class="text-muted">{{$result->updated_at->diffForHumans()}}</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- project-info -->
                                <div id="project-info" class="card-block row">
                                    <div class="project-info-count col-lg-6 col-md-12">
                                        <div class="project-info-icon">
                                            <h2>{{number_format($result->balance)}} {{__('LE')}}</h2>
                                            <div class="project-info-sub-icon">
                                                <span class="fa fa-money"></span>
                                            </div>
                                        </div>
                                        <div class="project-info-text pt-1">
                                            <h5>{{__('Balance')}}</h5>
                                        </div>
                                    </div>
                                    <div class="project-info-count col-lg-6 col-md-12">
                                        <div class="project-info-icon">
                                            <h2>{{$result->allTransaction()->count()}}</h2>
                                            <div class="project-info-sub-icon">
                                                <span class="fa fa-info"></span>
                                            </div>
                                        </div>
                                        <div class="project-info-text pt-1">
                                            <h5>{{__('Number of transactions')}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <!-- project-info -->
                                <div class="card-block">
                                    <div class="card-subtitle line-on-side text-muted text-xs-center font-small-3 mx-2 my-1">
                                        <span>{{__('Services Information')}}</span>
                                    </div>

                                    @foreach($info['diffBetweenModelsType'] as $key => $value)
                                        <div class="row py-{{count($value)}}">
                                            @foreach($value as $oneKey => $oneValue)
                                                @if(count($value) == 1)
                                                    <div class="col-lg-12 col-md-12">
                                                        @else
                                                            <div class="col-lg-6 col-md-12">
                                                                @endif
                                                                <div class="insights px-2">
                                                                    @php
                                                                        if($oneKey === 'order'){
                                                                            $newKey = __('E-commerce');
                                                                        }elseif($oneKey === 'invoice'){
                                                                            $newKey = __('E-Payment');
                                                                        }elseif($oneKey === 'settlement'){
                                                                            $newKey = __('Settlement');
                                                                        }else{
                                                                            $newKey= __('Others');
                                                                        }
                                                                    @endphp
                                                                    <div><span class="text-info h3">{{$oneValue['percentage']}}%</span> <span class="float-xs-right">{{$newKey}}</span></div>
                                                                    <progress value="{{$oneValue['percentage']}}" max="100" class="progress progress-md progress-info">{{$oneValue['percentage']}}%</progress>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                    </div>
                                                    @endforeach
                                        </div>

















                                        <div class="card-subtitle line-on-side text-muted text-xs-center font-small-3 mx-2 my-1">
                                            <span>{{__('Transactions Status')}}</span>
                                        </div>

                                        @foreach($info['diffBetweenStatusType'] as $key => $value)
                                            <div class="row py-{{count($value)}}">
                                                @foreach($value as $oneKey => $oneValue)
                                                    @if(count($value) == 1)
                                                        <div class="col-lg-12 col-md-12">
                                                    @else
                                                        <div class="col-lg-6 col-md-12">
                                                    @endif
                                                    <div class="insights px-2">
                                                        @php
                                                            $newKey = __(ucfirst($oneKey));
                                                        @endphp
                                                        <div><span class="text-info h3">{{$oneValue['percentage']}}%</span> <span class="float-xs-right">{{$newKey}}</span></div>
                                                        <progress value="{{$oneValue['percentage']}}" max="100" class="progress progress-md progress-info">{{$oneValue['percentage']}}%</progress>
                                                    </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach


                                </div>


                            </div>
                    </section>
                    <!-- Transactions -->
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
