@extends('merchant.layouts')

@section('content')
    <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="pull-left">{{$pageTitle}}</h2>
                    <div class="pull-right">
                        @if($result->status=='active')
                            <button class="btn btn-lg btn-success">{{__('Active')}}</button>
                        @else
                            <button class="btn btn-lg btn-danger">{{__('In-Active')}}</button>
                        @endif
                    </div>

                </div>
            </div>
    </div>

    <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h2>{{__('Employee information')}}</h2>
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
                                <td>{{__('Employee name')}}</td>
                                <td>{{$result->firstname}} {{$result->lastname}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Employee National ID')}}</td>
                                <td>{{$result->national_id}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Employee email')}}</td>
                                <td>{{$result->email}}</td>
                            </tr>

                            <tr>
                                <td>{{__('Employee Mobile')}}</td>
                                <td>{{$result->mobile}}</td>
                            </tr>

                            <tr>
                                <td>{{__('Employee Address')}}</td>
                                <td>{{$result->address}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <h2>{{__('Permissions')}}</h2>
            </div>
            <div class="card-block">
                @if($result->merchant_staff_group_id == $result->merchant()->merchant_staff_group->first()->id)
                    <p class="text-success">{{__('User have all permissions')}}</p>
                @else

                    @foreach($permissions as $key=>$val)
                        @if(!array_diff($val,$currentpermissions))
                            <label class="col-sm-6 mb-1">
                                <span class="text-success">{{__('Can')}}</span> {!! ucfirst(str_replace('-',' ',$key)) !!}
                            </label>
                        @endif
                    @endforeach
                @endif

            </div>
        </div>
    </div>


@endsection


@section('header')

@endsection

@section('footer')
@endsection