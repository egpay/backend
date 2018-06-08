@extends('system.layouts')

@section('header')

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/plugins/forms/wizard.css')}}">

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
                        </div>
                        <div>
                        {!! Form::open(['route' =>'merchant.merchant.fast-create.store','files'=>true, 'method' => 'POST','class'=>'number-tab-steps wizard-circle']) !!}
                        <!-- Step 1 -->
                            <h6>{{__('Information')}}</h6>
                            <fieldset style="padding: 0px;">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Arabic Data')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('name_ar', __('Merchant name').':') !!}
                                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'name_ar') !!}
                                            </div>

                                            <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('description_ar', __('Merchant Description').':') !!}
                                                    {!! Form::textarea('description_ar',isset($result->id) ? $result->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                                                </div>
                                                {!! formError($errors,'description_ar') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard">
                                            <div class="form-group col-sm-12{!! formError($errors,'merchant_category_id',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('merchant_category_id', __('Merchant Category').':') !!}


                                                    {!! Form::select('merchant_category_id',$merchant_categories,isset($result->id) ? $result->merchant_category_id:old('merchant_category_id'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'merchant_category_id') !!}
                                            </div>

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
                                                {!! Form::select('area_id[]',array_merge([0=>__('Select Area')],$arrayOfArea),null,['class'=>'form-control','id'=>'area_id','onchange'=>'getNextAreas($(this).val(),"'.$areaData['type']->id.'",\'#nextAreasID\')']) !!}
                                                {!! formError($errors,'area_id') !!}
                                            </div>


                                            <div id="nextAreasID" class="col-md-12">

                                            </div>


                                            <div class="form-group col-sm-12{!! formError($errors,'address',true) !!}{!! formError($errors,'branch_address_en',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('address', __('Address').':') !!}
                                                    {!! Form::textarea('address',isset($result->id) ? $result->address:old('address'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'address') !!}
                                                {!! formError($errors,'branch_address_en') !!}
                                                {!! formError($errors,'branch_address_ar') !!}
                                            </div>

                                            <div class="clearfix"></div>

                                            {!! Form::hidden('staff_id',Auth::id()) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2>{{__('Determine Location')}}</h2>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <input style="width:80%" id="pac-input" class="controls form-control" type="text" placeholder="{{__('Search Box')}}">
                                            <div id="map-events" class="height-400" style="margin-bottom: 15px;"></div>
                                            <div class="form-group col-sm-6{!! formError($errors,'branch_latitude',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('branch_latitude', __('Latitude').':') !!}
                                                    {!! Form::text('branch_latitude',old('branch_latitude'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'branch_latitude') !!}
                                            </div>
                                            <div class="form-group col-sm-6{!! formError($errors,'branch_longitude',true) !!}">
                                                <div class="controls">
                                                    {!! Form::label('branch_longitude', __('Longitude').':') !!}
                                                    {!! Form::text('branch_longitude',old('branch_longitude'),['class'=>'form-control']) !!}
                                                </div>
                                                {!! formError($errors,'branch_longitude') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-block card-dashboard" style="padding-bottom: 160px !important;">
                                            <div class="contactdata">
                                                @if(request('contact'))
                                                    @foreach(request('contact') as $key=>$vals)
                                                        @foreach($vals as $contact)
                                                            <div class="form-group col-sm-12">
                                                                <div class="controls">
                                                                    <label for="{!! $key !!}">{!! ucfirst($key) !!}:</label>
                                                                    <div class="input-group">
                                                                        <input class="form-control" name="contact[{!! $key !!}][]" class="{{$key}}" type="{{(($key=='email')?'email':'text')}}" value="{{$contact}}">
                                                                        <a class="input-group-addon btn btn-danger delcontactinfo"><i class="fa fa-trash"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endforeach

                                                @else

                                                    <div class="form-group col-sm-12">
                                                        <div class="controls">
                                                            <label for="name">{{__('Name')}}:</label>
                                                            <div class="input-group">
                                                                <input class="form-control" name="contact[name][]" type="text" value="{{old('contact[\'name\'][0]')}}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12">
                                                        <div class="controls">
                                                            <label for="mobile">{{__('Mobile')}}:</label>
                                                            <div class="input-group">
                                                                <input class="form-control" name="contact[mobile][]" type="text" value="{{old('contact[\'mobile\'][0]')}}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-sm-12{!! formError($errors,'staff_national_id',true) !!}">
                                                        <div class="controls">
                                                            {!! Form::label('staff_national_id', __('National ID').':') !!}
                                                            {!! Form::number('staff_national_id',old('staff_national_id'),['class'=>'form-control']) !!}
                                                        </div>
                                                        {!! formError($errors,'staff_national_id') !!}
                                                    </div>

                                                @endif
                                            </div>

                                            {!! formError($errors,'contact.name') !!}
                                            {!! formError($errors,'contact.email') !!}
                                            {!! formError($errors,'contact.mobile') !!}

                                            {!! formError($errors,'contact.name.*') !!}
                                            {!! formError($errors,'contact.email.*') !!}
                                            {!! formError($errors,'contact.mobile.*') !!}

                                            <br>
                                            <div class="pull-right">
                                                <div class="input-group">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span>{{__('Add info')}}</span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="name">Name</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="mobile">Mobile</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="phone">Phone</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="email">Email</a>
                                                            <a class="dropdown-item addinput" href="javascript:void(0)" data-type="fax">Fax</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </fieldset>



                            <!-- Step 3 -->
                            <h6>{{__('Contract')}}</h6>
                            <fieldset style="padding: 0px;">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col-sm-6">
                                                <h2>{{__('Contract files')}}</h2>
                                                @if(formError($errors,'contract_file.*',true))
                                                    <p class="text-xs-left"><small class="danger text-muted">{{__('Error File Upload')}}</small></p>
                                                @endif
                                            </div>
                                            <div style="text-align: right;" class="col-sm-6">
                                                <button type="button" class="btn btn-primary fa fa-plus addinputfile">
                                                    <span>{{__('Add File')}}</span>
                                                </button>
                                            </div>

                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="uploaddata">
                                                <div class="col-sm-12 div-with-files">
                                                    <div class="col-sm-5 form-group">
                                                        <label for="title[]">{{__('Title')}}</label>
                                                        {!! Form::select('contractTitle[]',$contract_papers,null,['class'=>'form-control']) !!}
                                                    </div>
                                                    <div class="col-sm-5 form-group">
                                                        <label for="file[]">{{__('File')}}</label>
                                                        <input class="form-control" name="contractFile[]" type="file">
                                                    </div>
                                                    <div class="col-sm-2 form-group">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            {!! Form::close() !!}
                        </div>
                </section>
                <!--/ Javascript sourced data -->
            </div>
        </div>
    </div>

    <div id="popups" class="hidden">
        <div id="contractPapers">
            <div class="onfile col-sm-12 div-with-files">
                <div class="col-sm-5 form-group">
                    <label for="title[]">{{__('Title')}}</label>
                    {!! Form::select('contractTitle[]',$contract_papers,null,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-5 form-group">
                    <label for="file[]">{{__('File')}}</label>
                    <input class="form-control" name="contractFile[]" type="file">
                </div>
                <div class="col-sm-2 form-group">
                    <a href="javascript:void(0);" onclick="$(this).closest('.onfile.col-sm-12.div-with-files').remove();" class="text-danger">
                        <i class="fa fa-lg fa-trash mt-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection

@section('footer')
    <script src="{{asset('assets/system/js/scripts')}}/custom/custominput.js"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>

    <script src="{{asset('assets/system')}}/vendors/js/extensions/jquery.steps.min.js" type="text/javascript"></script>
    <script src="{{asset('assets/system')}}/js/scripts/forms/wizard-steps.js" type="text/javascript"></script>

    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>

    <script>

        @php
            $startWorkWithArea = getLastNotEmptyItem(old('area_id'));
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
    </script>

    <script>


        $('#contract_plan_id').change(function(){
            $planID     = $('#contract_plan_id').val();

            if($planID){
                $.getJSON(
                    '{{route('system.ajax.get')}}',
                    {
                        'type': 'merchantNewContractsDates',
                        'plan_id': $planID
                    },
                    function(response){
                        if(response.status == false){
                            $('.start-end-date').hide('fast');
                        }else{
                            $('#contract_start_date').val(response.start_date);
                            $('#contract_end_date').val(response.end_date);
                            $('.start-end-date').show('fast');
                        }
                    }
                );
            }else{
                $('.start-end-date').hide('fast');
            }

        });

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD'
            });

            ajaxSelect2('#parent_id','merchant');
            if($('#is_reseller').val()=='active'){
                $('label[for="parent_id"]').parents('.form-group').addClass('hidden');
            } else {
                $('label[for="parent_id"]').parents('.form-group').removeClass('hidden');
            }
            $('#is_reseller').on('change',function(){
                if($(this).val()=='active'){
                    $('label[for="parent_id"]').parents('.form-group').addClass('hidden');
                } else {
                    $('label[for="parent_id"]').parents('.form-group').removeClass('hidden');
                }
            });


        });
        // Branch
        markers = [];
        var map = '';
        function initAutocomplete() {
            var pos = {
                lat: 27.02194154036109,
                lng: 31.148436963558197
            };
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position){
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                    var marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                    });
                    markers.push(marker);
                    $('#branch_latitude').val(pos.lat);
                    $('#branch_longitude').val(pos.lng);
                });
            }
            map = new google.maps.Map(document.getElementById('map-events'), {
                @if(old('latitude') && old('longitude'))
                center: { lat: {{old('latitude')}}, lng: {{old('longitude')}} },
                zoom: 16,
                @else
                center: pos,
                zoom: 8,
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

                    @if(old('branch_latitude') && old('branch_longitude'))
            var marker = new google.maps.Marker({
                    position: {lat: {{old('branch_latitude')}}, lng: {{old('branch_longitude')}} },
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
                        //console.log("Returned place contains no geometry");
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
            $('#branch_latitude').val(location.lat());
            $('#branch_longitude').val(location.lng());
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

        $('.addinputfile').on('click',function(){
            var html = $('#contractPapers').clone();
            //console.log(html);
            $($('<hr>'),html).appendTo('.uploaddata');
        });

    </script>

@endsection