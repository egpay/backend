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
                    <div class="content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{$pageTitle}}</h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;"  class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Unique Name')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Description')}}</th>
                                                    <th>{{__('Balance')}}</th>
                                                    <th>{{__('Transfer')}}</th>
                                                    <th>{{__('Last updated')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($result as $key => $value)
                                                    <tr>
                                                        <td>{{$value->id}}</td>
                                                        <td>{{strtoupper($value->unique_name)}}</td>
                                                        <td>{{$value->name}}</td>
                                                        <td><code>{{$value->description}}</code></td>
                                                        <td>{{amount($value->wallet->balance,true)}}</td>
                                                        <td>
                                                            <table class="table">
                                                                <tbody>
                                                                <tr>
                                                                    <td>{{__('In')}}</td>
                                                                    <td>{{__(ucfirst($value->transfer_in))}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>{{__('Out')}}</td>
                                                                    <td>{{__(ucfirst($value->transfer_out))}}</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td>{{$value->updated_at->diffForHumans()}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>

                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Unique Name')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Description')}}</th>
                                                    <th>{{__('Balance')}}</th>
                                                    <th>{{__('Transfer')}}</th>
                                                    <th>{{__('Last updated')}}</th>
                                                </tr>

                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection




@section('header')
@endsection


@section('footer')
@endsection
