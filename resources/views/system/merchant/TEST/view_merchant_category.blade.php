@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-xs-12">
                            <ol class="breadcrumb">
                                @foreach($breadcrumb as $value)
                                    <li class="breadcrumb-item @if(!isset($value['url'])) active @endif">
                                        @if(isset($value['url']))
                                            <a href="{{$value['url']}}">
                                                {{$value['text']}}
                                            </a>
                                        @else
                                            {{$value['text']}}
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-xs-12">

                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-8">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Merchants')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                <div class="table-responsive">
                                    <table class="table" id="merchant-table">
                                        <thead>
                                        <tr>
                                            <th>{{__('ID')}}</th>
                                            <th>{{__('Logo')}}</th>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Created By')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                </div>

                            </div>
                        </section>

                    </div>
                    <div class="col-md-4">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Merchant Category')}}
                                    <a class="btn btn-outline-primary" href="{{url('system/merchant-category/'.$result->id.'/edit')}}"><i class="fa fa-pencil"></i> {{__('Edit')}}</a>
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

                                            @foreach(listLangCodes() as $key => $value)

                                            <tr>
                                                <td>{{__('Name')}} ({{$value}})</td>
                                                <td>{{ $result->{'name_'.$key} }}</td>
                                            </tr>

                                            @endforeach


                                            @foreach(listLangCodes() as $key => $value)

                                                <tr>
                                                    <td>{{__('Description')}} ({{$value}})</td>
                                                    <td><code>{{ $result->{'description_'.$key} }}</code></td>
                                                </tr>

                                            @endforeach

                                            <tr>
                                                <td>{{__('Commission')}}</td>
                                                <td>
                                                    {{ $result->commission }}
                                                    @if($result->commission_type == 'fixed') LE @else % @endif
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Status')}} </td>
                                                <td>

                                                    @if($result->status == 'active')
                                                        <b style="color: green">{{__('Active')}}</b>
                                                    @else
                                                        <b style="color: red">{{__('In-Active')}}</b>
                                                    @endif

                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                <td>
                                                    <a href="{{url('system/staff/'.$result->staff_id)}}" target="_blank">
                                                        {{__('#ID')}}:{{$result->staff_id}} <br >{{$result->staff->firstname .' '. $result->staff->lastname}}
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    @if($result->created_at == null)
                                                        --
                                                    @else
                                                        {{$result->created_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>
                                                    @if($result->updated_at == null)
                                                        --
                                                    @else
                                                        {{$result->updated_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                        </tbody>
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
@endsection;

@section('footer')
    <script type="text/javascript">
        $('#merchant-table').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isMerchant = "true";
                }
            }
        });
    </script>
@endsection
