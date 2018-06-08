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
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-6">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Information')}}
                                    <a class="btn btn-outline-primary" href="javascript:void();" onclick="urlIframe('{{route('system.loyalty-programs.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a>
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
                                                    <td>{{__('Name')}}</td>
                                                    <td>{{$result->{'name_'.$systemLang} }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Description')}}</td>
                                                    <td><code>{{$result->{'description_'.$systemLang} }}</code></td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Type')}}</td>
                                                    <td>{{__(ucfirst($result->type))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Transaction Type')}}</td>
                                                    <td>{{__(ucfirst($result->transaction_type))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Status')}}</td>
                                                    <td>{{statusColor($result->status)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Pay Type')}}</td>
                                                    <td>{{__(ucfirst($result->pay_type))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Owner')}}</td>
                                                    <td>{{__(ucfirst($result->owner))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>
                                                        <a href="{{route('system.staff.show',$result->staff_id)}}">{{$result->staff->firstname}} {{$result->staff->lastname}}</a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>
                                                        {{$result->created_at->diffForHumans()}}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>


                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>
                    <div class="col-md-6">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    @if($result->list['type'] == 'dynamic')
                                        {{__('Dynamic Points')}}
                                    @else
                                        {{__('Static Points')}}
                                    @endif
                                </h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        @if($result->list['type'] == 'dynamic')
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{__('From Amount')}}</th>
                                                <th>{{__('To Amount')}}</th>
                                                <th>{{__('Point')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($result->list['list'] as $key => $value)
                                                <tr>
                                                    <td>{{amount($value['from_amount'],true)}}</td>
                                                    <td>{{amount($value['to_amount'],true)}}</td>
                                                    <td>{{number_format($value['point'])}}</td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        @else
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td>{{__('Amount')}}</td>
                                                        <td>{{amount($result->list['list']['amount'],true)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{__('Points')}}</td>
                                                        <td>{{number_format($result->list['list']['point'])}}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        @endif

                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Ignore Data')}}
                                    <a class="btn btn-outline-primary" href="javascript:void();" onclick="urlIframe('{{route('system.loyalty-program-ignore.create',['id'=>$result->id])}}')"><i class="fa fa-plus"></i> {{__('Create')}}</a>
                                </h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                        <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{__('Model')}}</td>
                                                    <td>{{__('Description')}}</td>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>{{__('Action')}}</td>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td>{{__('ID')}}</td>
                                                    <td>{{__('Model')}}</td>
                                                    <td>{{__('Description')}}</td>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>{{__('Action')}}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
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
@endsection

@section('footer')
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
            }

        });

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
