@extends('merchant.layouts')

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
                                    @if($result->logo)
                                    <div class="media-left pl-2 pt-2">
                                        <a href="jaascript:void(0);" class="profile-image">
                                            <img title="{{$result->{'name_'.$systemLang} }}" src="{{asset('storage/'.imageResize($result->logo,70,70))}}"  class="rounded-circle img-border height-100"  />
                                        </a>
                                    </div>
                                    @endif
                                    <div class="media-body media-middle row">
                                        <div class="col-xs-6">
                                            <h3 class="card-title" style="margin-bottom: 0.5rem;">
                                                {{$result->{'name_'.$systemLang} }}
                                                @if($result->status == 'in-active')
                                                    <b style="color: red;">(IN-ACTIVE)</b>
                                                @endif
                                            </h3>
                                            <span>{{$result->{'description_'.$systemLang} }}</span>
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
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Info')}}
                                        <span style="float: right;">
                                            <a class="btn btn-outline-primary"  href="javascript:void(0);" onclick="urlIframe('{{route('panel.merchant.edit',$result->id)}}')">
                                                <i class="fa fa-pencil"></i> {{__('Edit')}}</a>
                                        </span>
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

                                                <tr>
                                                    <td>{{__('Address')}} </td>
                                                    <td><code>{{$result->address}}</code></td>
                                                </tr>

                                                @if($result->merchant_contract_id)
                                                <tr>
                                                    <td>{{__('Contract ID')}} </td>
                                                    <td><a href="{{route('merchant.contract.show',$result->merchant_contract_id)}}">{{$result->merchant_contract_id}}</a></td>
                                                </tr>
                                                @endif


                                                <tr>
                                                    <td>{{__('Is Reseller')}}</td>
                                                    <td>
                                                        @if($result->is_reseller == 'active')
                                                            <b style="color: green;">{{__('Yes')}}</b>
                                                        @else
                                                            <b style="color: red;">{{__('No')}}</b>
                                                        @endif
                                                    </td>
                                                </tr>



                                                @if($result->parent_id)
                                                    <tr>
                                                        <td>{{__('Parent From')}}</td>
                                                        <td>
                                                            {{$result->parent->{'name_'.$systemLang} }}
                                                        </td>
                                                    </tr>
                                                @endif



                                                <tr>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>
                                                        <a href="{{route('system.staff.show',$result->staff_id)}}" target="_blank">
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
                        <div class="col-md-6">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Category Info')}}
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
                                                    <td>{{$result->category->id}} ( <a target="_blank" href="{{route('merchant.category.show',$result->category->id)}}">{{__('View')}}</a> )</td>
                                                </tr>

                                                @foreach(listLangCodes() as $key => $value)

                                                    <tr>
                                                        <td>{{__('Name')}} ({{$value}})</td>
                                                        <td>{{ $result->category->{'name_'.$key} }}</td>
                                                    </tr>

                                                @endforeach


                                                @foreach(listLangCodes() as $key => $value)

                                                    <tr>
                                                        <td>{{__('Description')}} ({{$value}})</td>
                                                        <td><code>{{ $result->category->{'description_'.$key} }}</code></td>
                                                    </tr>

                                                @endforeach

                                                <tr>
                                                    <td>{{__('Commission')}}</td>
                                                    <td>
                                                        {{ $result->category->commission }}
                                                        @if($result->category->commission_type == 'fixed') LE @else % @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Status')}} </td>
                                                    <td>
                                                        @if($result->category->status == 'active')
                                                            <b style="color: green">{{__('Active')}}</b>
                                                        @else
                                                            <b style="color: red">{{__('In-Active')}}</b>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Created By')}}</td>
                                                    <td>
                                                        <a href="{{url('merchant/staff/'.$result->category->staff_id)}}" target="_blank">
                                                            {{__('#ID')}}:{{$result->category->staff_id}} <br >{{$result->category->staff->firstname .' '. $result->category->staff->lastname}}
                                                        </a>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Created At')}}</td>
                                                    <td>
                                                        @if($result->category->created_at == null)
                                                            --
                                                        @else
                                                            {{$result->category->created_at->diffForHumans()}}
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>{{__('Updated At')}}</td>
                                                    <td>
                                                        @if($result->category->updated_at == null)
                                                            --
                                                        @else
                                                            {{$result->category->updated_at->diffForHumans()}}
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

                    <div class="row">

                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Wallets')}}
                                    </h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>{{__('Wallet ID')}}</th>
                                                        <th>{{__('Wallet Type')}}</th>
                                                        <th>{{__('Balance')}}</th>
                                                        <th>{{__('Last Update')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($result->wallet as $key => $value)
                                                        <tr>
                                                            <th>{{$value->id}}</th>
                                                            <th>{{ucfirst(__($value->type))}}</th>
                                                            <th>{{number_format($value->balance,2)}} {{__('LE')}}</th>
                                                            <th>{{$value->updated_at->diffForHumans()}}</th>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>{{__('Wallet ID')}}</th>
                                                        <th>{{__('Wallet Type')}}</th>
                                                        <th>{{__('Balance')}}</th>
                                                        <th>{{__('Last Update')}}</th>
                                                    </tr>
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
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
                                                                {{$value->title}}
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    @if(isset($merchantStaff[$value->id]))
                                                        @foreach($merchantStaff[$value->id] as $staffKey => $staffValue)
                                                            <tr class="treegrid-2{{$staffValue->id}} treegrid-parent-{{$value->id}}">
                                                                <td>
                                                                    {{$staffValue->firstname}} {{$staffValue->lastname}} <small>({{$staffValue->id}})</small>
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
                        <div class="col-md-6">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Contacts')}}
                                    </h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table" id="product-list">
                                                <!-- Start Email -->
                                                @php
                                                    $name = $result->contact->where('type','name');
                                                @endphp
                                                @if($name->isNotEmpty())
                                                    <tr class="treegrid-name">
                                                        <td>
                                                            <b>{{__('Name')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($name as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-name">
                                                            <td>
                                                                {{$value->value}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            <!-- End Email -->

                                                <!-- Start Email -->
                                                @php
                                                    $email = $result->contact->where('type','email');
                                                @endphp
                                                @if($email->isNotEmpty())
                                                    <tr class="treegrid-email">
                                                        <td>
                                                            <b>{{__('Email')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($email as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-email">
                                                            <td>
                                                                <a href="mailto:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            <!-- End Email -->
                                                <!-- Start Mobile -->
                                                @php
                                                    $mobile = $result->contact->where('type','mobile');
                                                @endphp
                                                @if($mobile->isNotEmpty())
                                                    <tr class="treegrid-mobile">
                                                        <td>
                                                            <b>{{__('Mobile')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($mobile as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-mobile">
                                                            <td>
                                                                <a href="tel:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <!-- End Mobile -->
                                                <!-- Start phone -->
                                                @php
                                                    $phone = $result->contact->where('type','phone');
                                                @endphp
                                                @if($phone->isNotEmpty())
                                                    <tr class="treegrid-phone">
                                                        <td>
                                                            <b>{{__('Phone')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($phone as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-phone">
                                                            <td>
                                                                <a href="tel:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                @endforeach
                                            @endif
                                            <!-- End phone -->
                                            <!-- Start fax -->
                                                @php
                                                    $fax = $result->contact->where('type','fax');
                                                @endphp
                                                @if($fax->isNotEmpty())
                                                    <tr class="treegrid-fax">
                                                        <td>
                                                            <b>{{__('Fax')}}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach($fax as $key => $value)
                                                        <tr class="treegrid-m{{$key}} treegrid-parent-fax">
                                                            <td>
                                                                <a href="tel:{{$value->value}}">{{$value->value}}</a>
                                                            </td>
                                                        </tr>
                                                @endforeach
                                            @endif
                                            <!-- End fax -->
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Branches')}}
                                    </h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table" id="merchant-branches">
                                                <thead>
                                                <tr>
                                                    <th>{{__('ID')}}</th>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Address')}}</th>
                                                    <th>{{__('Map')}}</th>
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
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <section id="spacing" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        {{__('Merchant Products')}}
                                    </h4>
                                </div>
                                <div class="card-body collapse in">
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table" id="product-list">
                                                @foreach($productsCategories as $key => $value)
                                                    <tr class="treegrid-{{$value->id}}">
                                                        <td>
                                                            @if($value->icon)
                                                                <img src="{{asset('storage/app/'.imageResize($value->icon,70,70))}}" >
                                                            @endif
                                                                <b>
                                                                    <a  @if($value->approved_at == null) style="color: red;" @endif target="_blank" href="{{route('merchant.category.show',$value->id)}}">{{$value->{'name_'.$systemLang} }}</a>
                                                                </b>
                                                        </td>
                                                        <td colspan="2">{{$value->{'description_'.$systemLang} }}</td>
                                                    </tr>

                                                    @if(isset($products[$value->id]))
                                                        @foreach($products[$value->id] as $productKey => $productValue)
                                                            <tr class="treegrid-2{{$productValue->id}} treegrid-parent-{{$value->id}}">
                                                                <td>
                                                                    @if($productValue->icon)
                                                                        <img src="{{asset('storage/app/'.imageResize($productValue->icon,70,70))}}" >
                                                                    @endif
                                                                    <a target="_blank" href="{{route('merchant.product-category.show',$productValue->id)}}" @if($productValue->approved_at == null) style="color: red;" @endif>
                                                                        {{$productValue->{'name_'.$systemLang} }}
                                                                    </a>
                                                                </td>
                                                                <td>{{$productValue->price}} {{__('LE')}}</td>
                                                                <td>{{$productValue->{'description_'.$systemLang} }}</td>
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
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{__('View Map')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8" id="map"></div>
                    <div class="list-group-item col-md-12" id="instructions"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/treegrid/jquery.treegrid.css')}}">

    <style>
        #map{
            height: 500px !important;
            width: 100% !important;
        }
    </style>
@endsection

@section('footer')
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/tables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    <script src="{{asset('assets/system/js/scripts/tables/datatables-extensions/datatables-sources.js')}}" type="text/javascript"></script>
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
