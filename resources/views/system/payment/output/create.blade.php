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
                                            <ul>
                                                @foreach($errors->all() as $key => $value)
                                                    <li>{{$key}}: {{$value}}</li>
                                                @endforeach
                                            </ul>
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
                            {!! Form::open(['route' => isset($result->id) ? ['payment.output.update',$result->id]:'payment.output.store','method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-block card-dashboard">


                                        <div class="form-group col-sm-12{!! formError($errors,'name',true) !!}">
                                            <div class="controls">

                                                {!! Form::label('name', __('Name').':') !!}
                                                {!! Form::text('name',isset($result->id) ? $result->name:old('name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name') !!}
                                        </div>

                                    </div>
                                </div>
                            </div>


                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col-sm-6">
                                                <h2>{{__('Output')}}</h2>
                                                @if(formError($errors,'key.*',true))
                                                    <p class="text-xs-left"><small class="danger text-muted">{{__('Error Key')}}</small></p>
                                                @endif
                                                @if(formError($errors,'language.*',true))
                                                    <p class="text-xs-left"><small class="danger text-muted">{{__('Error Language')}}</small></p>
                                                @endif
                                            </div>
                                            <div style="text-align: right;" class="col-sm-6">
                                                <button type="button" class="btn btn-primary fa fa-plus addinputfile">
                                                    <span>{{__('Add Parameter')}}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-block card-dashboard">
                                            <div class="uploaddata">
                                                @if(isset($result->id))

                                                    @foreach($result->parameters as $key => $value)
                                                        <div class="div-with-files">
                                                            <div class="form-group col-sm-10">
                                                                <div class="controls">
                                                                    <label>{{__('Key')}} (Snake Case)</label>
                                                                    <input type="text" value="{{$value['key']}}" class="form-control" name="key[]">
                                                                </div>
                                                            </div>

                                                            <div style="padding-top: 40px;" class="col-sm-2 form-group">
                                                                <a style="color: red;" href="javascript:void(0);" class="remove-file"><i class="fa fa-trash"></i></a>
                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <div class="controls">
                                                                    <label>{{__('Language')}} (AR)</label>
                                                                    <input type="text" value="{{$value['language']['ar']}}" class="form-control" name="language[ar][]">
                                                                </div>
                                                            </div>

                                                            <div class="form-group col-sm-6">
                                                                <div class="controls">
                                                                    <label>{{__('Language')}} (EN)</label>
                                                                    <input type="text" value="{{$value['language']['en']}}" class="form-control" name="language[en][]">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                @endif
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
    <script src="{{asset('assets/system/js/scripts')}}/custom/CustomInputSystemOutput.js"></script>

@endsection