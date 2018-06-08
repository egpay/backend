@extends('merchant.layouts')

@section('content')
    <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="pull-left">{{$pageTitle}}
                        <a href="{{route('panel.merchant.staff-group.edit',$result->id)}}"><i class="fa fa-edit"></i></a>
                    </h2>
                </div>
            </div>
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2>{{__('Information')}}</h2>
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
                        <td>{{__('Staff group Title')}}</td>
                        <td>{{$result->title}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Staff count')}}</td>
                        <td>{{$result->merchant_staff->count('id')}}</td>
                    </tr>
                    </tbody>
                </table>

                <div>

                    @if($merchant->merchant_staff_group()->first()->id == $result->id)
                        <div>
                            <h3 class="text-success">{{__('By default this group have all permissions')}}</h3>
                        </div>
                    @else
                        <h2>{{__('Group Permissions')}}</h2>
                        <ul style="list-style-type: none;">
                            @foreach($permissions as $key=>$val)
                                @if(!array_diff($val,$currentpermissions))
                                    <label class="col-sm-4 mb-1">
                                        <span class="text-success">{{__('Can')}}</span> {!! __(str_replace('-',' ',$key)) !!}
                                    </label>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                </div>


            </div>
        </div>
    </div>




@endsection


@section('header')

@endsection

@section('footer')

@endsection