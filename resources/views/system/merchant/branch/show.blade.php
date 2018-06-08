@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        @if($result->icon)
                            <img src="{{asset('storage/app/'.imageResize($result->icon,70,70))}}">
                        @endif
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
                    <div class="col-md-8">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Branch Info')}}
                                    <a style="float: right;" class="btn btn-outline-primary" href="{{route('merchant.branch.edit',$result->id)}}"><i class="fa fa-pencil"></i> {{__('Edit')}}</a>
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
                                                <td>{{__('Area')}}</td>
                                                <td><code>{{ implode(' -> ',\App\Libs\AreasData::getAreasUp($result->area_id,true,$systemLang)) }}</code></td>
                                            </tr>

                                            @foreach(listLangCodes() as $key => $value)

                                                <tr>
                                                    <td>{{__('Address')}} ({{$value}})</td>
                                                    <td><code>{{ $result->{'address_'.$key} }}</code></td>
                                                </tr>

                                            @endforeach


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


                                            {{--@if($result->latitude && $result->longitude)--}}
                                            {{--<tr>--}}
                                                {{--<td>{{__('Map')}}</td>--}}
                                                {{--<td>--}}
                                                    {{--<a href="javascript:void(0);" onclick="viewMap({{$result->latitude}},{{$result->longitude}},'{{$result->{'name_'.$systemLang} }} ( {{$result->merchant->{'name_'.$systemLang} }} ) ')">{{__('View')}}</a>--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}
                                            {{--@endif--}}

                                            <tr>
                                                <td>{{__('Longitude')}}</td>
                                                <td>
                                                    {{$result->longitude}}
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
                    <div class="col-md-4">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Merchant Staff')}}
                                </h4>

                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table" id="product-list">

                                            @foreach($merchantStaffGroups as $key => $value)

                                                <tr class="treegrid-{{$value->id}}">
                                                    <td>
                                                        <b>
                                                            <a target="_blank" href="{{route('merchant.staff-group.show',$value->id)}}">{{$value->title }}</a>
                                                        </b>
                                                    </td>
                                                </tr>

                                                @if(isset($merchantStaff[$value->id]))
                                                    @foreach($merchantStaff[$value->id] as $staffKey => $staffValue)
                                                        <tr class="treegrid-2{{$staffValue->id}} treegrid-parent-{{$value->id}}">
                                                            <td>
                                                                <a target="_blank" href="{{route('merchant.staff.show',$staffValue->id)}}">
                                                                    {{$staffValue->firstname}} {{$staffValue->lastname}} <small>({{$staffValue->username}})</small>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                            @endforeach

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
    {{--<script type="text/javascript">--}}
        {{--$('#merchant-table').DataTable({--}}
            {{--"iDisplayLength": 25,--}}
            {{--processing: true,--}}
            {{--serverSide: true,--}}
            {{--"order": [[ 0, "desc" ]],--}}
            {{--"ajax": {--}}
                {{--"url": "{{url()->full()}}",--}}
                {{--"type": "GET",--}}
                {{--"data": function(data){--}}
                    {{--data.isMerchant = "true";--}}
                {{--}--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}
@endsection
