@extends('system.layouts')
@if(staffCan('system.staff.add-managed-staff',Auth::id()))
<div class="modal fade text-xs-left" id="addManagedStaff-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Add Managed Staff')}}</label>
            </div>
            {!! Form::open(['route' => ['system.staff.add-managed-staff'],'method' => 'POST','id'=>'add-managed-staff-form','onsubmit'=>'addManagedStaffPOST();return false;']) !!}
            {{Form::hidden('supervisor_id',$result->id)}}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            <div class="alert" id="addManagedStaff-alert"></div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('staff_id',__('Staff ID')) }}
                                    {!! Form::number('staff_id',null,['class'=>'form-control']) !!}
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" id="addManagedStaff-button" class="btn btn-outline-primary btn-md">{{__('Submit')}}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif

@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div id="user-profile">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="card profile-with-cover">
                                <div class="card-img-top img-fluid bg-cover height-300" style="background: url('{{asset('assets/system/images/carousel/22.jpg')}}') 50%;"></div>
                                <div class="media profil-cover-details">
                                    @if($result->image)
                                    <div class="media-left pl-2 pt-2">
                                        <a href="jaascript:void(0);" class="profile-image">
                                            <img title="{{$result->firstname}} {{$result->lastname}}" src="{{asset('storage/app/'.imageResize($result->avatar,70,70))}}"  class="rounded-circle img-border height-100"  />
                                        </a>
                                    </div>
                                    @endif
                                    <div class="media-body media-middle row">
                                        <div class="col-xs-6">
                                            <h3 class="card-title" style="margin-bottom: 0.5rem;">
                                                {{$result->firstname}} {{$result->lastname}}
                                                @if($result->status == 'in-active')
                                                    <b style="color: red;">(IN-ACTIVE)</b>
                                                @endif
                                            </h3>
                                            <span>{{$result->address}}</span>
                                        </div>
                                        <div class="col-xs-6 text-xs-right">
                                            {{--<button type="button" class="btn btn-primary hidden-xs-down"><i class="fa fa-plus"></i> Follow</button>--}}
                                            {{--<div class="btn-group hidden-md-down" role="group" aria-label="Basic example">--}}
                                                {{--<button type="button" class="btn btn-success"><i class="fa fa-dashcube"></i> Message</button>--}}
                                                {{--<button type="button" class="btn btn-success"><i class="fa fa-cog"></i></button>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                </div>
                                <nav class="navbar navbar-light navbar-profile">
                                    <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="exCollapsingNavbar2" aria-expanded="false" aria-label="Toggle navigation"></button>
                                    <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
                                        <ul class="nav navbar-nav float-xs-right">
                                            <li class="nav-item active">
                                                <a class="nav-link"  href="javascript:void(0);" onclick="urlIframe('{{route('system.staff-target.create',['id'=>$result->id])}}')"><i class="fa fa-dot-circle-o"></i> {{__('Add Target to :name',['name'=>$result->firstname.' '.$result->lastname])}} <span class="sr-only">(current)</span></a>
                                            </li>

                                            <li class="nav-item active">
                                                <a class="nav-link"  href="javascript:void(0);" onclick="urlIframe('{{route('system.staff.edit',$result->id)}}')"><i class="fa fa-pencil-square-o"></i> {{__('Edit Staff info')}} <span class="sr-only">(current)</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-4">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Staff Info')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void(0);" onclick="urlIframe('{{route('system.staff.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
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
                                                    <td>
                                                        {{$result->firstname}} {{$result->lastname}} ( {{$result->job_title}} )
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('E-Mail')}}</td>
                                                    <td>
                                                        <a href="mailto:{{$result->email}}">{{$result->email}}</a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Mobile')}}</td>
                                                    <td>
                                                        <a href="tel:{{$result->mobile}}">{{$result->mobile}}</a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Gender')}}</td>
                                                    <td>
                                                        {{ucfirst($result->gender)}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('National ID')}}</td>
                                                    <td>
                                                        {{$result->national_id}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Birthdate')}}</td>
                                                    <td>
                                                        {{$result->birthdate}}
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <td>{{__('Description')}}</td>
                                                    <td>
                                                        <code>{{$result->description}}</code>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Permission Group')}}</td>
                                                    <td>
                                                        <a href="{{route('system.permission-group.edit',$result->permission_group_id)}}">{{$result->permission_group->name}}</a>
                                                    </td>
                                                </tr>



                                                <tr>
                                                    <td>{{__('Last Login')}}</td>
                                                    <td>
                                                        @if($result->lastlogin == null)
                                                            --
                                                        @else
                                                            {{$result->lastlogin->diffForHumans()}}
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
                        <div class="col-md-8 col-xs-12">

                            <div class="row">
                                <div class="col-xl-4 col-lg-6 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block">
                                                <div class="media">
                                                    <div class="media-body text-xs-left">
                                                        <h3 class="primary">{{number_format($result->merchant->count())}}</h3>
                                                        <span>
                                                            <a href="javascript:void(0);" onclick="urlIframe('{{route('merchant.merchant.index',['staff_id'=>$result->id])}}');">{{__('Merchant')}}</a>
                                                        </span>
                                                    </div>
                                                    <div class="media-right media-middle">
                                                        <i class="icon-user-follow primary font-large-2 float-xs-right"></i>
                                                    </div>
                                                </div>
                                                <progress class="progress progress-sm progress-primary mt-1 mb-0" value="{{ @round(($result->merchant->count()*100)/$totalMerchants) }}" max="100"></progress>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block">
                                                <div class="media">
                                                    <div class="media-body text-xs-left">
                                                        <h3 class="danger">{{number_format($result->activity_log->count())}}</h3>
                                                        <span>
                                                            <a href="javascript:void(0);" onclick="urlIframe('{{route('system.activity-log.index',['causer_type'=>'App\Models\Staff','causer_id'=>$result->id])}}');">{{__('System Action')}}</a>
                                                        </span>
                                                    </div>
                                                    <div class="media-right media-middle">
                                                        <i class="icon-social-dropbox danger font-large-2 float-xs-right"></i>
                                                    </div>
                                                    <progress class="progress progress-sm progress-danger mt-1 mb-0" value="{{ @round(($result->activity_log->count()*100)/$totalActivity) }}" max="100"></progress>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-xs-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block">
                                                <div class="media">
                                                    <div class="media-body text-xs-left">
                                                        @php
                                                            $walletTransaction = $result->paymentWallet->allTransaction()->count();
                                                        @endphp

                                                        <h3 class="success">{{number_format($walletTransaction)}}</h3>
                                                        <span>
                                                            <a href="javascript:void(0);" onclick="urlIframe('{{route('system.wallet.show',$result->paymentWallet->id)}}');">{{__('Wallet Transaction')}}</a>
                                                        </span>
                                                    </div>
                                                    <div class="media-right media-middle">
                                                        <i class="icon-layers success font-large-2 float-xs-right"></i>
                                                    </div>
                                                    <progress class="progress progress-sm progress-success mt-1 mb-0" value="{{ @round(($walletTransaction*100)/$totalWalletsTransaction) }}" max="100"></progress>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-block">
                                                <div class="media">
                                                    <div class="media-body text-xs-left">
                                                        <h3 class="success">{{amount($result->paymentWallet->balance,true)}}</h3>
                                                        <span>{{__('Balance')}}</span>
                                                    </div>
                                                    <div class="media-right media-middle">
                                                        <i class="icon-layers success font-large-2 float-xs-right"></i>
                                                    </div>
                                                    <progress class="progress progress-sm progress-success mt-1 mb-0" value="{{ @round(($walletTransaction*100)/$totalWalletsTransaction) }}" max="100"></progress>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                @if($result->is_supervisor())
                                    <div class="col-md-12">
                                        <section id="spacing" class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">
                                                    {{__('Managed Staff')}}
                                                    @if(staffCan('system.staff.add-managed-staff',Auth::id()))
                                                    <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void(0);" onclick="addManagedStaff();"><i class="fa fa-plus"></i> {{__('Add')}}</a></span>
                                                    @endif
                                                </h4>
                                            </div>
                                            <div class="card-body collapse in">
                                                <div class="card-block">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>{{__('ID')}}</th>
                                                                <th>{{__('Image')}}</th>
                                                                <th>{{__('Name')}}</th>
                                                                <th>{{__('Mobile')}}</th>
                                                                <th>{{__('E-mail')}}</th>
                                                                <th>{{__('Permission Group')}}</th>
                                                                <th>{{__('Action')}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($result->managed_staff as $key => $value)
                                                                <tr>
                                                                    <td>{{$value->id}}</td>
                                                                    <td>
                                                                        @if(!$value->image)
                                                                            --
                                                                        @else
                                                                            <img src="{{asset('storage/'.image($value->image,70,70))}}" />
                                                                        @endif
                                                                    </td>

                                                                    <td>{{$value->firstname}} {{$value->lastname}}</td>
                                                                    <td><a href="tel:{{$value->mobile}}">{{$value->mobile}}</a></td>
                                                                    <td><a href="tel:{{$value->email}}">{{$value->email}}</a></td>
                                                                    <td>
                                                                        <a href="{{route('system.permission-group.edit',$value->permission_group->id)}}">{{$value->permission_group->name}}</a>

                                                                    </td>
                                                                    <td>
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="ft-cog icon-left"></i>
                                                                                <span class="caret"></span></button>
                                                                            <ul class="dropdown-menu">
                                                                                <li class="dropdown-item"><a href="{{route('system.staff.show',$value->id)}}">{{__('View')}}</a></li>
                                                                                <li class="dropdown-item"><a href="{{route('system.staff.edit',$value->id)}}">{{__('Edit')}}</a></li>

                                                                                @if(staffCan('system.staff.delete-managed-staff',Auth::id()))
                                                                                    <li class="dropdown-item"><a onclick="deleteRecord({{route('system.staff.delete-managed-staff',['id'=>$value->id])}})" href="javascript:void(0)">{{__('Remove')}}</a></li>
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                    </td>
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

            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/core/menu/menu-types/vertical-overlay-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/users.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/timeline.css')}}">

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

        function addManagedStaff(){
            $('#addManagedStaff-modal').modal('show');
        }

        function addManagedStaffPOST(){
            $formData = $('#add-managed-staff-form').serialize();

            $('#addManagedStaff-button').text('{{__('Loading...')}}').attr('disabled');

            $.post('{{route('system.staff.add-managed-staff')}}',$formData,function($data){
                $('#addManagedStaff-button').text('{{__('Submit')}}').removeAttr('disabled');

                if($data.status == false){
                    $('#addManagedStaff-alert').removeClass('alert-success')
                        .removeClass('alert-danger')
                        .addClass('alert-danger')
                        .text($data.msg);
                }else{
                    $('#addManagedStaff-alert').removeClass('alert-success')
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .text($data.msg);

                    setTimeout(function(){
                        location.reload();
                    },2000);

                }
            },'json');
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
        $(document).ready(function() {
            $('#product-list,#merchant-staff').treegrid({
                expanderExpandedClass: 'fa fa-minus',
                expanderCollapsedClass: 'fa fa-plus'
            });



            $('#merchant-branches').DataTable({
                "iDisplayLength": 10,
                processing: true,
                serverSide: true,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url": "{{url()->full()}}",
                    "type": "GET",
                    "data": function(data){
                        data.isBranches = "true";
                    }
                }
            });

            $('#contract-table').DataTable({
                "iDisplayLength": 10,
                processing: true,
                serverSide: true,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url": "{{url()->full()}}",
                    "type": "GET",
                    "data": function(data){
                        data.isContract= "true";
                    }
                }
            });


        });

    </script>
@endsection