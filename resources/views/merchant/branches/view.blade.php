@extends('merchant.layouts')

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
                                    {{ Form::label('totalmin',__('Total Starts from')) }}
                                    {!! Form::text('totalmin',null,['class'=>'form-control']) !!}
                                </fieldset>
                            </div>


                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('totalmax',__('Total Ends at')) }}
                                    {!! Form::text('totalmax',null,['class'=>'form-control']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('id',__('ID')) }}
                                    {!! Form::number('id',null,['class'=>'form-control','id'=>'id']) !!}
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('status',__('Status')) }}
                                    {!! Form::select('status',[__('Select Status'),'yes'=>__('Paid'),'no'=>__('Not paid')],null,['class'=>'form-control']) !!}
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
    <div class="card">
        <div class="card-header">
            <h2 class="pull-left">{{$pageTitle}}
                <a href="{{route('panel.merchant.branch.edit',$branch->id)}}"><i class="fa fa-edit"></i></a>
            </h2>
            <div class="pull-right">
                @if($branch->status=='active')
                    <button class="btn btn-lg btn-success">{{__('Active')}}</button>
                @else
                    <button class="btn btn-lg btn-danger">{{__('In-Active')}}</button>
                @endif
            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-xl-4 col-lg-6 col-xs-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Total Orders')}}</span>
                                <h3 class="primary">{{$branch->orders->count('id')}}  {{__('Order/s')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-th-large fa-lg primary float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 col-xs-12">
            <div class="card border-success">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Total paid orders')}}</span>
                                <h3 class="success">{{$branch->orders->where('is_paid','yes')->sum('total')}}  {{__('LE')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-th-large fa-lg success float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-xs-12">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="card-block">
                        <div class="media">
                            <div class="media-body text-xs-left">
                                <span>{{__('Total unpaid Orders')}}</span>
                                <h3 class="danger">{{$branch->orders->where('is_paid','no')->sum('total')}} {{__('LE')}}</h3>
                            </div>
                            <div class="media-right media-middle">
                                <i class="fa fa-th-large fa-lg danger float-xs-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-xl-6 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h2>{{__('English')}}</h2>
                    </div>
                    <div class="card-block">

                        <table class="table">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>{{__('Value')}}</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{__('Branch name')}}</td>
                                    <td>{{$branch->name_en}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Branch Description')}}</td>
                                    <td>{{$branch->description_en}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Branch Address')}}</td>
                                    <td>{{$branch->address_en}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h2>{{__('Arabic')}}</h2>
                    </div>
                    <div class="card-block">

                        <table class="table">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>{{__('Value')}}</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{__('Branch name')}}</td>
                                <td>{{$branch->name_ar}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Branch Description')}}</td>
                                <td>{{$branch->description_ar}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Branch Address')}}</td>
                                <td>{{$branch->address_ar}}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{__('Branch Area')}}</h2>
                </div>
                <div class="card-block">
                    <div>
                        <label class="text-bold-700">{{__('Branch Area')}} :</label>
                        <div class="indent">
                                {!!implode(' <ul><li> ',array_reverse(\App\Libs\AreasData::getAreasUp($branch->area_id,true,$lang)))!!}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label class="text-bold-700">{{__('Branch Map')}} :</label>

                        <div id="map-events" class="height-400" style="margin-bottom: 15px;"></div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <section id="spacing" class="card">
                <div class="card-header">
                    <div class="card-header">
                        <h4 class="card-title">{{__('Branch Orders')}}</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a onclick="filterFunction(false);"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="pull-right">
                    <a data-toggle="modal" data-target="#filter-modal" class="btn btn-outline-primary pull-right"><i class="ft-search"></i> {{__('Filter')}}</a>
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table" id="branch-orders">
                                <thead>
                                <tr>
                                    <th>{{__('Order ID')}}</th>
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('created at')}}</th>
                                    <th>{{__('Details')}}</th>
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


@endsection


@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system')}}/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">

@endsection

@section('footer')
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/tables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <script src="{{asset('assets/system/js/scripts/tables/datatables-extensions/datatables-sources.js')}}" type="text/javascript"></script>
    <script src="//maps.googleapis.com/maps/api/js?key={{env('gmap_key')}}&libraries=places&callback=initAutocomplete" type="text/javascript" async defer></script>
    <script src="{{asset('assets/system')}}/vendors/js/charts/gmaps.min.js" type="text/javascript"></script>

    <script>

        markers = [];
        var map = '';
        function initAutocomplete() {
            map = new google.maps.Map(document.getElementById('map-events'), {
                center: {lat: {{$branch->latitude}}, lng: {{$branch->longitude}}},
                zoom: 16,
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

            var marker = new google.maps.Marker({
                    position: {lat: {{$branch->latitude}}, lng: {{$branch->longitude}}},
                    map: map
                });
            markers.push(marker);


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

    <script>

        $dataTableVar = $('#branch-orders').DataTable({
            "iDisplayLength": 10,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.orders = "true";
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
                $url = '{{url()->full()}}?orders=true';
            }else {
                $url = '{{url()->full()}}?orders=true&'+$this.serialize();
            }
            $dataTableVar.ajax.url($url).load();
            $('#filter-modal').modal('hide');
        }
    </script>
@endsection