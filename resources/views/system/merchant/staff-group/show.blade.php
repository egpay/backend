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

                    <div class="col-md-4">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Staff Group')}}
                                    <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.staff-group.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>

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
                                                <td>{{__('Title')}}</td>
                                                <td>{{$result->title}}</td>
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


                    <div class="col-md-8">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Staff')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table">
                                            @foreach($result->staff as $key => $value)
                                                <tr>
                                                    <td>
                                                        <a target="_blank" href="{{route('merchant.staff.show',$value->id)}}">
                                                            {{$value->firstname}} {{$value->lastname}} <small>({{$value->username}})</small>
                                                        </a>
                                                    </td>
                                                </tr>
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