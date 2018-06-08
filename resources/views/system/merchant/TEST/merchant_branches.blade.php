@extends('system.layouts')
<!-- Modal -->
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

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('id',__('ID')) }}
                                        {!! Form::number('id',null,['class'=>'form-control','id'=>'id']) !!}
                                    </fieldset>
                                </div>



                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('merchant_category_id',__('Merchant Categories')) }}
                                        {!! Form::select('merchant_category_id',$merchantCategories,null,['class'=>'form-control','id'=>'merchant_category_id']) !!}
                                    </fieldset>
                                </div>

                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        {{ Form::label('merchant_id',__('Merchant')) }}
                                        {!! Form::select('merchant_id',[''=>__('Select Merchant')],null,['style'=>'width: 100%;' ,'id'=>'merchantSelect2','class'=>'form-control col-md-12']) !!}
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('name',__('Name')) }}
                                        {!! Form::text('name',null,['class'=>'form-control','id'=>'name']) !!}
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('mobile',__('Phone,Mobile or Fax')) }}
                                        {!! Form::email('mobile',null,['class'=>'form-control','id'=>'mobile']) !!}
                                    </fieldset>
                                </div>


                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        {{ Form::label('address',__('Address')) }}
                                        {!! Form::textarea('address',null,['class'=>'form-control','id'=>'address','rows'=>2]) !!}
                                    </fieldset>
                                </div>

                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        {{ Form::label('plan',__('Plan')) }}
                                        {!! Form::select('plan',array_column($merchantPlans,'title','id'),null,['class'=>'form-control','id'=>'address','rows'=>2]) !!}
                                    </fieldset>
                                </div>

                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        {{ Form::label('area_id',$areaData['type']->name) }}
                                        @php
                                            $arrayOfArea = $areaData['areas']->toArray();
                                            if(!$arrayOfArea){
                                                $arrayOfArea = [];
                                            }else{
                                                $arrayOfArea = array_column($arrayOfArea,'name','id');
                                            }
                                        @endphp
                                        {!! Form::select('area_id[]',array_merge([0=>__('Select Area')],$arrayOfArea),null,['class'=>'form-control','id'=>'area_id','onchange'=>'getNextAreas($(this).val(),"'.$areaData['type']->id.'",\'#nextAreasID\')']) !!}
                                    </fieldset>
                                </div>


                                <div id="nextAreasID" class="col-md-12">

                                </div>







                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('admin_name',__('Admin Name')) }}
                                        {!! Form::text('admin_name',null,['class'=>'form-control','id'=>'admin_name']) !!}
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('admin_job_title',__('Admin Job Title')) }}
                                        {!! Form::text('admin_job_title',null,['class'=>'form-control','id'=>'admin_job_title']) !!}
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('admin_email',__('Admin  E-mail')) }}
                                        {!! Form::email('admin_email',null,['class'=>'form-control','id'=>'admin_email']) !!}
                                    </fieldset>
                                </div>



                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('status',__('Status')) }}
                                        {!! Form::select('status',[__('Select Status'),'active'=>__('Active'),'in-active'=>__('In-Active')],null,['class'=>'form-control']) !!}
                                    </fieldset>
                                </div>


                                <div class="col-md-12">
                                    <fieldset class="form-group">
                                        {{ Form::label('staffSelect2',__('Created By')) }}
                                        <div>
                                        {!! Form::select('staff_id',[''=>__('Select Staff')],null,['style'=>'width: 100%;' ,'id'=>'staffSelect2','class'=>'form-control col-md-12']) !!}
                                        </div>
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

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0">
                        @include('system.breadcrumb')
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-xs-12">
                    <div role="group" aria-label="Button group with nested dropdown" class="btn-group float-md-right">
                        <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary"><i class="ft-search"></i> {{__('Filter')}}</a>
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
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block card-dashboard">
                                        <table style="text-align: center;" id="egpay-datatable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                @foreach($tableColumns as $key => $value)
                                                    <th>{{$value}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                @foreach($tableColumns as $key => $value)
                                                    <th>{{$value}}</th>
                                                @endforeach
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




    <!-- Modal -->
    <div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg"" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">View Map</h4>
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

@endsection




@section('header')

<link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

<style>
    #map{
        height: 500px !important;
        width: 100% !important;
    }
</style>

@endsection;

@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>

    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBbD0LpXp1x2hhJskG05TiMh-jB2QV4jG0&callback=initMap" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.25/gmaps.min.js" type="text/javascript"></script>

    <!-- END PAGE VENDOR JS-->

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">
        staffSelect('#staffSelect2');

        ajaxSelect2('#merchantSelect2','merchant')

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
                        oSettings.aoData[i].nTr.className = oSettings.aoData[i]._aData[6];
                    }
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















        function viewMap($latitude,$longitude,$title){
            $('#instructions').html('');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position){

                    $('#modal-map').modal('show');
                    $('#modal-map').on('shown.bs.modal', function (e) {
                        $latitudeMe = position.coords.latitude;
                        $longitudeMe = position.coords.longitude;
                        map = new GMaps({
                            div: '#map',
                            lat: $latitudeMe,
                            lng: $longitudeMe
                        });

                        map.addMarker({
                            lat: $latitude,
                            lng: $longitude,
                            infoWindow: {
                                content: $title
                            }
                        });

                        map.addMarker({
                            lat: $latitudeMe,
                            lng: $longitudeMe,
                            infoWindow: {
                                content: "{{__('My Location')}}"
                            }
                        });

                        map.travelRoute({
                            origin: [$latitudeMe, $longitudeMe],
                            destination: [$latitude, $longitude],
                            travelMode: 'driving',
                            step: function(e){
                                $('#instructions').append('<li class="list-group-item">'+e.instructions+'</li>');
                                $('#instructions li:eq('+e.step_number+')').delay(450*e.step_number).fadeIn(200, function(){
                                    map.setCenter(e.end_location.lat(), e.end_location.lng());
                                    map.drawPolyline({
                                        path: e.path,
                                        strokeColor: '#131540',
                                        strokeOpacity: 0.6,
                                        strokeWeight: 6
                                    });
                                });
                            }
                        });
                    });

                },function () {
                    $('#modal-map').modal('show');
                    $('#modal-map').on('shown.bs.modal', function (e) {
                        map = new GMaps({
                            div: '#map',
                            lat: $latitude,
                            lng: $longitude
                        });

                        map.addMarker({
                            lat: $latitude,
                            lng: $longitude,
                            infoWindow: {
                                content: $title
                            }
                        });
                    });
                });
            } else {
                $('#modal-map').modal('show');
                $('#modal-map').on('shown.bs.modal', function (e) {
                    map = new GMaps({
                        div: '#map',
                        lat: $latitude,
                        lng: $longitude
                    });

                    map.addMarker({
                        lat: $latitude,
                        lng: $longitude,
                        infoWindow: {
                            content: $title
                        }
                    });
                });
            }
        }

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });

    </script>
@endsection