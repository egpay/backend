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
                            {!! Form::open(['route' => isset($result->id) ? ['merchant.branch.update',$result->id]:'merchant.branch.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">

                                        <div class="form-group col-sm-12 {!! formError($errors,'merchant_id',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('merchant_id', __('Merchant').':') !!}

                                                @if(isset($merchantData))
                                                    {!! Form::text('merchant_text', $merchantData->{'name_'.$systemLang}.' #ID: '.$merchantData->id,['class'=>'form-control','readonly'=>'readonly']) !!}
                                                    {!! Form::hidden('merchant_id',null,['id'=>'new_merchant_id']) !!}
                                                @else

                                                    @if(isset($result->id))
                                                        {!! Form::select('merchant_id',[$result->merchant->id => $result->merchant->{'name_'.$systemLang}.' #ID: '.$result->merchant_id],isset($result->id) ? $result->merchant_id:old('merchant_id'),['class'=>'select2 form-control']) !!}
                                                    @else
                                                        {!! Form::select('merchant_id',[__('Select Merchant')],old('merchant_id'),['style'=>'width: 100%;','class'=>'select2 form-control']) !!}
                                                    @endif
                                                @endif


                                            </div>
                                            {!! formError($errors,'merchant_id') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('English Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_en', __('English Name').':') !!}
                                                {!! Form::text('name_en',isset($result->id) ? $result->name_en:old('name_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name_en') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'address_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('address_en', __('Address (English)').':') !!}
                                                {!! Form::text('address_en',isset($result->id) ? $result->address_en:old('address_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'address_en') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_en', __('Description (English)').':') !!}
                                                {!! Form::textarea('description_en',isset($result->id) ? $result->description_en:old('description_en'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'description_en') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Arabic Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name_ar', __('Name (Arabic)').':') !!}
                                                {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'name_ar') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'address_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('address_ar', __('Address (Arabic)').':') !!}
                                                {!! Form::text('address_ar',isset($result->id) ? $result->address_ar:old('address_ar'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'address_ar') !!}
                                        </div>
                                        <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description_ar', __('Description (Arabic)').':') !!}
                                                {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'description_ar') !!}
                                        </div>
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
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Branch Info')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'area_id',true) !!}">
                                            {{ Form::label('area_id',$areaData['type']->name) }}
                                            @php
                                                $arrayOfArea = $areaData['areas']->toArray();
                                                if(!$arrayOfArea){
                                                    $arrayOfArea = [];
                                                }else{
                                                    $arrayOfArea = array_column($arrayOfArea,'name','id');
                                                }
                                            @endphp
                                            {!! Form::select('area_id[]',[0=>__('Select Area')]+$arrayOfArea,null,['class'=>'form-control','id'=>'area_id','onchange'=>'getNextAreas($(this).val(),"'.$areaData['type']->id.'",\'#nextAreasID\')']) !!}
                                            {!! formError($errors,'area_id') !!}
                                        </div>


                                        <div id="nextAreasID" class="col-md-12">

                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('status', __('Branch Status').':') !!}
                                                {!! Form::select('status',['active'=>__('Active'),'in-active'=>__('In-Active')],isset($result->id) ? $result->status:old('status'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
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

        // Area ON CHANGE OR UPDATE
        // Area ON CHANGE OR UPDATE
        // Area ON CHANGE OR UPDATE
        @php
            $startWorkWithArea = (isset($result->area_id)) ? $result->area_id : getLastNotEmptyItem(old('area_id'));
            if($startWorkWithArea){
                $areaData = \App\Libs\AreasData::getAreasUp($startWorkWithArea);
                echo '$runAreaLoop = true;$areaLoopData = [];';
                if($areaData){
                    foreach ($areaData as $key => $value){
                        echo '$areaLoopData['.$key.'] = '.$value.';';
                    }
                    echo '$(\'#area_id\').val(next($areaLoopData)).change();';
                }
            }

        @endphp
        // Area ON CHANGE OR UPDATE
        // Area ON CHANGE OR UPDATE
        // Area ON CHANGE OR UPDATE
        // Area ON CHANGE OR UPDATE


        ajaxSelect2('#merchant_id','merchant');

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