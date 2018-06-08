@extends('system.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
@endsection
@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
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
                            @if($errors->any())
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-danger">
                                            {{__('Some fields are invalid please fix them')}}
                                        </div>
                                    </div>
                                </div>
                            @elseif(Session::has('status'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-{{Session::get('status')}}">
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {!! Form::open(['route' => isset($result->id) ? ['system.area.update',$result->id]:'system.area.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                                @if(isset($area))
                                    {!! Form::hidden('parent_id',$area->id) !!}
                                @endif

                                @if(isset($area_type))
                                    {!! Form::hidden('area_type_id',$area_type->id) !!}
                                @endif


                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">

                                        @if(isset($area_type))
                                            <div class="form-group col-sm-12">
                                                <div class="controls">
                                                    <label>{{__('Area Type')}}</label>
                                                    <input disabled="disabled" value="{{implode(' -> ',\App\Libs\AreasData::getAreaTypesUp($area_type->id,$systemLang))}}" class="form-control">
                                                </div>
                                            </div>
                                            {!! Form::hidden('area_type_id',$area_type->id) !!}
                                        @endif

                                        @if(isset($area))
                                            <div class="form-group col-sm-12">
                                                <div class="controls">
                                                    <label>{{__('Area Route')}}</label>
                                                    <input disabled="disabled" value="{{implode(' -> ',\App\Libs\AreasData::getAreasUp($area->id,$systemLang))}}" class="form-control">
                                                </div>
                                            </div>
                                            {!! Form::hidden('area_id',$area->id) !!}
                                        @endif
                                        @foreach(listLangCodes() as $key => $value)

                                        <div class="form-group col-sm-6{!! formError($errors,'name_'.$key,true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_'.$key, __('Name').' ('.$value.') '.':') !!}
                                                {!! Form::text('name_'.$key,isset($result->id) ? $result->{'name_'.$key}:old('name_'.$key),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_'.$key) !!}
                                        </div>

                                        @endforeach

                                    </div>
                                </div>
                            </div>






                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Determine Location')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <input id="pac-input" class="controls form-control" type="text" placeholder="{{__('Search Box')}}">
                                            <div id="map-events" class="height-400" style="margin-bottom: 15px;"></div>
                                            <div class="form-group col-sm-6{!! formError($errors,'latitude',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('latitude', __('Latitude').':') !!}
                                                    {!! Form::text('latitude',isset($result->id) ? $result->latitude:old('latitude'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'latitude') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'longitude',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('longitude', __('Longitude').':') !!}
                                                    {!! Form::text('longitude',isset($result->id) ? $result->longitude:old('longitude'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'longitude') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>









                                <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/select2/select2.custom.js" type="text/javascript"></script>
    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>
    <script>

        markers = [];
        var map = '';
        function initAutocomplete() {
            map = new google.maps.Map(document.getElementById('map-events'), {
                @if(isset($result->id))
                center: {lat: {{$result->latitude}}, lng: {{$result->longitude}}},
                zoom: 16,
                @else
                center: {lat: 27.02194154036109, lng: 31.148436963558197},
                zoom: 6,
                @endif

                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });

            map.addListener('click', function(e) {
                placeMarker(e.latLng,map);
            });

                    @if(isset($result->id))
            var marker = new google.maps.Marker({
                    position: {lat: {{$result->latitude}}, lng: {{$result->longitude}}},
                    map: map
                });
            markers.push(marker);
            @endif

            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach(function(marker) {
                    marker.setMap(null);
                });


                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }

        function placeMarker(location,map) {
            clearOverlays();
            var marker = new google.maps.Marker({
                position: location,
                map: map,
            });
            var lng = location.lng();
            $('#latitude').val(location.lat());
            $('#longitude').val(location.lng());
            //console.log(lat+' And Long is: '+lng);
            markers.push(marker);
            //map.setCenter(location);
        }

        function clearOverlays() {
            for (var i = 0; i < markers.length; i++ ) {
                markers[i].setMap(null);
            }
            markers.length = 0;
        }
    </script>
@endsection