@extends('merchant.layouts')

@section('content')
        <div class="card">
            <div class="card-header">
                <h2>{{$pageTitle}}</h2>
            </div>
        </div>

    {!! Form::open(['route' => 'panel.merchant.update','files'=>false, 'method' => 'PATCH']) !!}
   <div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <h2>{{__('English info')}}</h2>
            </div>
            <div class="card-block card-dashboard">
                <div class="form-group col-sm-12{!! formError($errors,'name_en',true) !!}">
                    <div class="controls">
                        {!! Form::label('name_en', __('Merchant name (English)').':') !!}
                        {!! Form::text('name_en',isset($merchant->id) ? $merchant->name_en:old('name_ar'),['class'=>'form-control']) !!}
                    </div>
                    {!! formError($errors,'name_en') !!}
                </div>

                <div class="form-group col-sm-12{!! formError($errors,'description_en',true) !!}">
                    <div class="controls">
                        {!! Form::label('description_en', __('Merchant description (English)').':') !!}
                        {!! Form::textarea('description_en',isset($merchant->id) ? $merchant->description_en:old('description_en'),['class'=>'form-control']) !!}
                    </div>
                    {!! formError($errors,'description_en') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <h2>{{__('Arabic info')}}</h2>
            </div>
            <div class="card-block card-dashboard">
                <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                    <div class="controls">
                        {!! Form::label('name_ar', __('Merchant name (Arabic)').':') !!}
                        {!! Form::text('name_ar',isset($merchant->id) ? $merchant->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                    </div>
                    {!! formError($errors,'name_ar') !!}
                </div>

                <div class="form-group col-sm-12{!! formError($errors,'description_ar',true) !!}">
                    <div class="controls">
                        {!! Form::label('description_ar', __('Merchant name (Arabic)').':') !!}
                        {!! Form::textarea('description_ar',isset($merchant->id) ? $merchant->description_ar:old('description_ar'),['class'=>'form-control ar']) !!}
                    </div>
                    {!! formError($errors,'description_ar') !!}
                </div>
            </div>
        </div>
    </div>


    <div class="col-sm-12">
        <div class="card">
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
                    {!! Form::select('area_id[]',array_merge([0=>__('Select Area')],$arrayOfArea),((isset($merchant->id))?$merchant->area_id:null),['class'=>'form-control','id'=>'area_id','onchange'=>'getNextAreas($(this).val(),"'.$areaData['type']->id.'",\'#nextAreasID\')']) !!}
                    {!! formError($errors,'area_id') !!}
                </div>


                <div id="nextAreasID" class="col-md-12">

                </div>


                <div class="form-group col-sm-12{!! formError($errors,'address',true) !!}">
                    <div class="controls">
                        {!! Form::label('address', __('Address').':') !!}
                        {!! Form::textarea('address',isset($merchant->id) ? $merchant->address:old('address'),['class'=>'form-control']) !!}
                    </div>
                    {!! formError($errors,'address') !!}
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
@endsection


@section('header')

@endsection

@section('footer')
    <script>
    @php
        $startWorkWithArea = (isset($merchant->area_id)) ? $merchant->area_id : getLastNotEmptyItem(old('area_id'));
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
@endsection