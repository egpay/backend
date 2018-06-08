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
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="@if(!$result->upload->isEmpty()) col-md-4 @else col-md-12 @endif">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Product Category')}}
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
                                                <td>{{__('Merchant')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('merchant.merchant.show',$result->merchant->id)}}">
                                                        {{$result->merchant->{'name_'.$systemLang} }}
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Category')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('merchant.category.show',$result->category->id)}}">
                                                        {{$result->category->{'name_'.$systemLang} }}
                                                    </a>
                                                </td>
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
                                                <td>{{__('Price')}}</td>
                                                <td>{{$result->price}} {{__('LE')}}</td>
                                            </tr>

                                            @if($result->created_by_merchant_staff_id)
                                            <tr>
                                                <td>{{__('Created By Merchant Staff')}}</td>
                                                <td>
                                                    <a target="_blank" href="{{route('merchant.staff.show',$result->created_by_merchant_staff_id)}}">
                                                        {{__('#ID')}}: {{$result->created_by_merchant_staff_id}} <br>
                                                        {{$result->merchant_staff->firstname}} {{$result->merchant_staff->lastname}}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif

                                            @if($result->approved_by_staff_id)
                                            <tr>
                                                <td>{{__('Approved By')}}</td>
                                                <td>
                                                    <a href="{{url('system/staff/'.$result->staff->id)}}" target="_blank">
                                                        {{__('#ID')}}:{{$result->staff->id}} <br >{{$result->staff->firstname .' '. $result->staff->lastname}}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif

                                            @if($result->approved_at)
                                            <tr>
                                                <td>{{__('Approved At')}}</td>
                                                <td>
                                                    @if($result->approved_at == null)
                                                        --
                                                    @else
                                                        {{$result->approved_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif

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


                    @if(!$result->upload->isEmpty())
                        <div class="col-md-8">

                            <section class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{__('Files')}}</h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>{{__('File')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($result->upload as $value)
                                                    <tr>
                                                        <td><a href="{{url('system/download/'.$value->id)}}" title="{{$value->path}}">{{$value->title}}</a></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </section>


                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection;

@section('footer')
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/system/treegrid/jquery.treegrid.bootstrap3.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#product-list').treegrid({
                expanderExpandedClass: 'fa fa-minus',
                expanderCollapsedClass: 'fa fa-plus'
            });
        });
    </script>
@endsection
