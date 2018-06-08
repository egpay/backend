@extends('system.layouts')

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
                                            <img title="{{$result->firstname}} {{$result->lastname}}" src="{{asset('storage/app/'.imageResize($result->image,70,70))}}"  class="rounded-circle img-border height-100"  />
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
                                                <a class="nav-link"  href="javascript:void();" onclick="urlIframe('{{route('system.users.edit',$result->id)}}')"><i class="fa-line-chart"></i> {{__('Edit User info')}} <span class="sr-only">(current)</span></a>
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
                                        {{__('User Info')}}
                                        <span style="float: right;"><a class="btn btn-outline-primary"  href="javascript:void();" onclick="urlIframe('{{route('system.users.edit',$result->id)}}')"><i class="fa fa-pencil"></i> {{__('Edit')}}</a></span>
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
                                                    <td>{{$result->firstname}} {{$result->firstname}}</td>
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
                                                    <td>{{__('Address')}}</td>
                                                    <td>
                                                        {{$result->address}}
                                                    </td>
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
                                                    <td>{{__('Last Login')}}</td>
                                                    <td>
                                                        @if($result->lastlogin == null)
                                                            --
                                                        @else
                                                            {{$result->lastlogin->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                @if($result->parent)
                                                    <tr>
                                                        <td>{{__('Parent')}}</td>
                                                        <td>
                                                            <a href="" target="_blank">{{$result->parent->firstname}} {{$result->parent->lastname}}</a>
                                                        </td>
                                                    </tr>
                                                @endif

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
