@extends('merchant.layouts')

@section('content')

            <div class="card">
                <div class="card-header">
                    <h2>{{$pageTitle}}</h2>
                </div>
            </div>

            <div class="content-body">
                <!-- Server-side processing -->
                <div id="server-processing">
                    <div class="row">
                        {!! Form::open(['route' => isset($result->id) ? ['panel.merchant.staff-group.update',$result->id]:'panel.merchant.staff-group.store','files'=>true, 'method' => isset($result->id) ?  'PATCH' : 'POST']) !!}
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>{{__('Group title')}}</h2>
                                    </div>
                                    <div class="card-block card-dashboard">
                                        <div class="form-group col-sm-12{!! formError($errors,'title',true) !!}">
                                            <div class="controls">
                                                {!! Form::label('title', __('Title').':') !!}
                                                {!! Form::text('title',isset($result->id) ? $result->title:old('title'),['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'title') !!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="pull-left">{{__('Permissions')}}</h2>
                                        <div class="pull-right">
                                            <a class="btn btn-primary pull-right m-1" onclick="CheckAllPerms(true);">{{__('Check ALl')}}</a>
                                            <a class="btn btn-outline-warning pull-right m-1" onclick="CheckAllPerms(false);">{{__('unCheck ALl')}}</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-block card-dashboard">
                                            @if((isset($result)) && $merchant->merchant_staff_group()->first()->id == $result->id)
                                                <div>
                                                    <h3 class="text-success">{{__('By default this group have all permissions')}}</h3>
                                                </div>
                                            @else
                                                @foreach($permissions as $permission)
                                                    <div class="bs-callout-primary callout-border-left callout-bordered m-2 p-2 permissions">
                                                        <h4 class="primary pull-left">{{ucfirst($permission['name'])}}</h4>
                                                        <label class="pull-right">
                                                            <input type="checkbox" onclick="CheckPerms(this);">
                                                        </label>
                                                        <p class="primary col-sm-12">{!! $permission['description']!!}</p>
                                                        <div class="row">
                                                            @foreach($permission['permissions'] as $key=>$val)
                                                                <label class="col-sm-4">
                                                                    {!! Form::checkbox("permissions[]", "$key", isset($result->id) ? !count(array_diff($val,$currentpermissions)) : false) !!}
                                                                    {!! __(str_replace('-',' ',$key)) !!}
                                                                </label>
                                                            @endforeach
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
                        {!! Form::close() !!}
                    </div>
                <!--/ Javascript sourced data -->
                </div>
            </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection

@section('header')
@endsection


@section('footer')
    <script>
        function CheckPerms(perm) {
            var permessions = $(perm).parents('.permissions').find('input[type=\'checkbox\']');
            //console.log(permessions);
            if($(perm).is(':checked')){
                $(permessions).prop('checked',true);
            } else {
                $(permessions).prop('checked',false);
            }
        }

        function CheckAllPerms(perm) {
            var permessions = $('input[type="checkbox"]');
            if(perm){
                $(permessions).prop('checked',true);
            } else {
                $(permessions).prop('checked',false);
            }
        }

    </script>
@endsection