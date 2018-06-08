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

                    <div class="col-md-12">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Merchant Staff')}}
                                    <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('merchant.staff.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
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
                                                <td>{{$result->firstname}} {{$result->lastname}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Username')}}</td>
                                                <td>{{$result->username}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('National ID')}}</td>
                                                <td>{{$result->national_id}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Email')}}</td>
                                                <td><a href="mailto:{{$result->email}}">{{$result->email}}</a></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Mobile')}}</td>
                                                <td><a href="tel:{{$result->mobile}}">{{$result->mobile}}</a></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Address')}}</td>
                                                <td><code>{{$result->address}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Birthdate')}}</td>
                                                <td>{{$result->birthdate}}</td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>
                                                    @if($result->status == 'active')
                                                        <b style="color: green;">Active</b>
                                                    @else
                                                        <b style="color: red;">In-Active</b>
                                                    @endif
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Branches can access')}}</td>
                                                <td>
                                                    @foreach($branches as $value)
                                                        <a href="{{route('merchant.branch.show',$value->id)}}">{{$value->{'name_'.$systemLang} }}</a> <hr>
                                                    @endforeach
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Last Login')}}</td>
                                                <td>
                                                    @if(!$result->lastlogin)
                                                        --
                                                    @else
                                                        {{$result->lastlogin->diffForHumans()}}
                                                    @endif
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
