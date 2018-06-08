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
                            {!! Form::open(['route' => isset($result->id) ? ['payment.sdk.update',$result->id]:'payment.sdk.store','method' => isset($result->id) ?  'PATCH' : 'POST','files' => true]) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Data')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">


                                        <div class="form-group col-sm-6{!! formError($errors,'adapter_name',true) !!}">
                                            <div class="controls">
                                                @php
                                                    $classes = ['class'=>'form-control'];
                                                if(isset($result)){
                                                    $classes[] = 'readonly';
                                                }
                                                @endphp
                                                {!! Form::label('adapter_name', __('Adapter').':') !!}
                                                {!! Form::text('adapter_name',isset($result->id) ? $result->adapter_name:old('adapter_name'),$classes) !!}
                                            </div>
                                            {!! formError($errors,'adapter_name') !!}
                                        </div>

                                        <div class="form-group col-sm-6{!! formError($errors,'name',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('name', __('Name').':') !!}
                                                {!! Form::text('name',isset($result->id) ? $result->name:old('name'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'name') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'description',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('description', __('Description').':') !!}
                                                {!! Form::textarea('description',isset($result->id) ? $result->description:old('description'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'description') !!}
                                        </div>


                                        <div class="form-group col-sm-12{!! formError($errors,'address',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('address', __('Address').':') !!}
                                                {!! Form::textarea('address',isset($result->id) ? $result->address:old('address'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'address') !!}
                                        </div>

                                        <div class="form-group col-sm-12{!! formError($errors,'logo',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('logo', __('Logo').':') !!}
                                                @if(isset($result->logo))
                                                    <span><a target="_blank" href="{{asset('storage/'.$result->logo)}}">{{__('View Icon')}}</a></span>
                                                @endif
                                                {!! Form::file('logo',['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'logo') !!}
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

    <script type="text/javascript">
        $(document).ready(function(){
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
        });
    </script>
@endsection