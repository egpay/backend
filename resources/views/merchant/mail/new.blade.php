@extends('merchant.layouts')


@section('content')


    <div class="row">
        <div class="card">
            <div class="card-header">
                <h2>{{$pageTitle}}</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-block">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{__('Arabic Info')}}</h2>
                        </div>
                        <div class="card-block card-dashboard">
                            <div class="form-group col-sm-12{!! formError($errors,'name_ar',true) !!}">
                                <div class="controls">
                                    {!! Form::label('name_ar', __('Name (Arabic)').':') !!}
                                    {!! Form::text('name_ar',isset($result->id) ? $result->name_ar:old('name_ar'),['class'=>'form-control ar']) !!}
                                </div>
                                {!! formError($errors,'name_ar') !!}
                            </div>
                            <div class="form-group col-sm-12{!! formError($errors,'content_ar',true) !!}">
                                <div class="controls">
                                    {!! Form::label('content_ar', __('Content (Arabic)').':') !!}
                                    {!! Form::textarea('content_ar',isset($result->id) ? $result->content_ar:old('content_ar'),['class'=>'form-control ar']) !!}
                                </div>
                                {!! formError($errors,'content_ar') !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')
    <script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('content_ar');
    </script>
@endsection