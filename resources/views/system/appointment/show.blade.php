@extends('system.layouts')
<div class="modal fade text-xs-left" id="changeDateTimeModal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Change Date Time')}}</label>
            </div>
            {!! Form::open(['route' => ['system.appointment.change-appointment-datetime',$result->id],'method' => 'POST']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">


                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('date_time',__('Date Time')) }}
                                    {!! Form::text('date_time',null,['class'=>'form-control datepicker']) !!}
                                </fieldset>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-outline-primary btn-md" value="{{__('Submit')}}">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

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
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-12">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Data')}}</h4>
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
                                                <td>{{__('Created By')}}</td>
                                                <td>{!! adminDefineUser($result->model_type,$result->model_id,$result->model->firstname.' '.$result->model->lastname) !!}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Appointment Date Time')}} <a href="javascript:void(0)" onclick="$('#changeDateTimeModal').modal('show');">( {{__('Change')}} )</a></td>
                                                <td>{{$result->appointment_date_time ?? '--'}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Description')}}</td>
                                                <td><code>{{$result->description}}</code></td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>{{ucfirst($result->status)}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>{{$result->created_at->diffForHumans()}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>{{$result->updated_at->diffForHumans()}}</td>
                                            </tr>

                                            </tbody>
                                        </table>


                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>


                    <div class="col-md-12">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Activity')}}</h4>
                            </div>

                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">

                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Created By')}}</th>
                                                <th>{{__('Created At')}}</th>
                                                <th>{{__('Status')}}</th>
                                                <th>{{__('Comment')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($result->appointmentStatus as $key => $value)
                                                    <tr>
                                                        <td>{{$value->id}}</td>
                                                        <td>{!! adminDefineUser($value->model_type,$value->model_id,$value->model->firstname.' '.$value->model->lastname) !!}</td>
                                                        <td>{{$value->created_at->diffForHumans()}}</td>
                                                        <td>{{ucfirst($value->status)}}</td>
                                                        <td>{{$value->comment}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>


                                    </div>


                                </div>

                            </div>

                        </section>
                    </div>

                    <div class="col-md-12">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Status')}}</h4>
                            </div>



                            {!! Form::open(['route' => ['system.appointment.change-status',$result->id],'method' => 'POST']) !!}
                            @if(Session::has('status'))
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="alert alert-{{Session::get('status')}}">
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card-body collapse in">
                                <div class="card-block">

                                    <div class="form-group col-sm-12{!! formError($errors,'status',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('status', __('Status').':') !!}
                                            {!! Form::select('status',[''=>__('Select Status'),'pending'=>__('Pending'),'canceled'=>__('Canceled'),'done'=>__('Done'),'fail'=>__('Fail')],null,['class'=>'form-control','id'=>'status']) !!}
                                        </div>
                                        {!! formError($errors,'status') !!}
                                    </div>

                                    <div class="form-group col-sm-12{!! formError($errors,'comment',true) !!}">
                                        <div class="controls">
                                            {!! Form::label('comment', __('Comment').':') !!}
                                            {!! Form::textarea('comment',null,['class'=>'form-control']) !!}
                                        </div>
                                        {!! formError($errors,'comment') !!}
                                    </div>


                                    <div class="form-group col-sm-12">
                                        <div class="controls">
                                            {!! Form::submit(__('Submit'),['class'=>'btn btn-success pull-right']) !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {!! Form::close() !!}

                        </section>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">

@endsection

@section('footer')
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->

    <script type="text/javascript">
        $(function(){
            @if($errors->any())
                alertError('{{__('Validation Error')}}');
            @elseif(Session::has('changeAppointmentDateTimeStatus'))
                alertSuccess('{{Session::get('changeAppointmentDateTimeMsg')}}');
            @endif

            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD HH:mm:SS'
            });
        });
    </script>
@endsection
