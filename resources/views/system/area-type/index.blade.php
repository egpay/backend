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
            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{$pageTitle}}</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            @foreach($result as $key => $value)
                                                <tr>
                                                    <td>{{$value->id}}</td>
                                                    <td>
                                                        {{implode(' -> ',\App\Libs\AreasData::getAreaTypesUp($value->id,$systemLang))}}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="ft-cog icon-left"></i>
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu">
                                                                <li class="dropdown-item"><a href="{{route('system.area.index',['area_type'=>$value->id])}}">{{__('View')}}</a></li>
                                                                <li class="dropdown-item"><a href="{{route('system.area-type.edit',$value->id)}}">{{__('Edit')}}</a></li>
                                                                @if($key == 0)
                                                                <li class="dropdown-item"><a href="{{route('system.area.create',['area_type_id'=>$value->id])}}">{{__('Add Sub Area')}}</a></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

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