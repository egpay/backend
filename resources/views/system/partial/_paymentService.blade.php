<div>
    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('payment_service_provider_category_id',__('Payment Service Category')) }}
            {!! Form::select('payment_service_provider_category_id',(isset($payment_service_provider_categories)?$payment_service_provider_categories:['0'=>__('Select Service Category')]),null,['class'=>'form-control','id'=>'payment_service_provider_category_id']) !!}
        </fieldset>
    </div>

    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('payment_service_provider_id',__('Payment Service Provider')) }}
            {!! Form::select('payment_service_provider_id',['0'=>__('Select Service Provider')],null,['class'=>'form-control','id'=>'payment_service_provider_id']) !!}
        </fieldset>
    </div>

    <div class="col-md-6">
        <fieldset class="form-group">
            {{ Form::label('payment_services_id',__('Payment Service ID')) }}
            {!! Form::select('payment_services_id',['0'=>__('Select Service ID')],null,['class'=>'form-control','id'=>'payment_services_id']) !!}
        </fieldset>
    </div>
</div>