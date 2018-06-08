@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-xs-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-xs-12">
                            <ol class="breadcrumb">
                                @foreach($breadcrumb as $value)
                                    <li class="breadcrumb-item @if(!isset($value['url'])) active @endif">
                                        @if(isset($value['url']))
                                            <a href="{{$value['url']}}">
                                                {{$value['text']}}
                                            </a>
                                        @else
                                            {{$value['text']}}
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-xs-12">

                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-4">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Category')}}</h4>
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
                                                <td>{{__('Icon')}}</td>
                                                <td><img src="{{asset('storage/'.imageResize($result->icon,70,70))}}" /></td>
                                            </tr>


                                            @foreach(listLangCodes() as $key => $value)
                                                <tr>
                                                    <td>{{__('Name')}} {{$value}}</td>
                                                    <td>{{ $result->{'name_'.$key} }}</td>
                                                </tr>
                                            @endforeach

                                            @foreach(listLangCodes() as $key => $value)
                                                <tr>
                                                    <td>{{__('Description')}} {{$value}}</td>
                                                    <td><code>{{ $result->{'description_'.$key} }}</code></td>
                                                </tr>
                                            @endforeach



                                            <tr>
                                                <td>{{__('Approved At')}}</td>
                                                <td>
                                                    @if($result->approved_at == null)
                                                        <b style="color: red;">{{__('Not Yet'))}}</b>
                                                    @else
                                                        {{$result->approved_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Approved By')}}</td>
                                                <td>
                                                    @if($result->approved_by_staff_id == null)
                                                        <b style="color: red;">{{__('Not Yet'))}}</b>
                                                    @else
                                                        <a href="{{url('system/staff/'.$result->approved_by_staff_id)}}" target="_blank">
                                                            {{__('#ID')}}:{{$result->approved_by_staff_id}} <br >{{$result->staff->firstname .' '. $result->staff->lastname}}
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    @if($result->created_at == null)
                                                        --
                                                    @else
                                                        {{$result->created_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>
                                                    @if($result->updated_at == null)
                                                        --
                                                    @else
                                                        {{$result->updated_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </section>


                    </div>
                    <div class="col-md-4">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Merchant')}}
                                </h4>

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
                                                <td><a target="_blank" href="{{url('system/merchant/'.$result->merchant->id)}}">{{$result->merchant->id}}</a></td>
                                            </tr>

                                            @foreach(listLangCodes() as $key => $value)
                                                <tr>
                                                    <td>{{__('Name')}} {{$value}}</td>
                                                    <td>{{ $result->merchant->{'name_'.$key} }}</td>
                                                </tr>
                                            @endforeach

                                            @foreach(listLangCodes() as $key => $value)
                                                <tr>
                                                    <td>{{__('Description')}} {{$value}}</td>
                                                    <td><code>{{ $result->merchant->{'description_'.$key} }}</code></td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td>{{__('Address')}}</td>
                                                <td><code>{{$result->merchant->address}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Status')}}</td>
                                                <td>
                                                    @if($result->merchant->status == 'active')
                                                        <b style="color: green;">Active</b>
                                                    @else
                                                        <b style="color: red;">In-Active</b>
                                                    @endif
                                                </td>
                                            </tr>



                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    @if($result->merchant->created_at == null)
                                                        --
                                                    @else
                                                        {{$result->merchant->created_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>
                                                    @if($result->merchant->updated_at == null)
                                                        --
                                                    @else
                                                        {{$result->merchant->updated_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                    <div class="col-md-4">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Merchant Plan')}}
                                </h4>
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
                                                <td>{{$result->plan->id}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Title')}}</td>
                                                <td>{{$result->plan->title}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('description')}}</td>
                                                <td><code>{{$result->plan->description}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Months')}}</td>
                                                <td>{{$result->plan->months}} {{__('Mo.')}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Amount')}}</td>
                                                <td>{{$result->plan->amount}} {{__('LE')}}</td>
                                            </tr>


                                            <tr>
                                                <td>{{__('Service Type')}}</td>
                                                <td>
                                                    <table class="table">
                                                        <tbody>
                                                        @foreach($result->plan->type as $value)
                                                            <tr>
                                                                <td>{{__($value)}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created At')}}</td>
                                                <td>
                                                    @if($result->plan->created_at == null)
                                                        --
                                                    @else
                                                        {{$result->plan->created_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Updated At')}}</td>
                                                <td>
                                                    @if($result->plan->updated_at == null)
                                                        --
                                                    @else
                                                        {{$result->plan->updated_at->diffForHumans()}}
                                                    @endif
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>

                <div class="row">
                    <div class="@if($result->upload->isEmpty()) col-md-12 @else col-md-6  @endif">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Contacts')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{__('Type')}}</th>
                                                <th>{{__('Value')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($result->contact as $value)
                                                <tr>
                                                    <td>{{contactType($value)}}</td>
                                                    <td>{!! contactValue($value) !!}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </section>


                    </div>
                    @if(!$result->upload->isEmpty())
                    <div class="col-md-6">

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{__('Files')}}</h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>{{__('File')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($result->upload as $value)
                                                <tr>
                                                    <td><a href="{{url('system/download/'.$value->id)}}" title="{{$value->path}}">{{$value->title}}</a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </section>


                    </div>
                    @endif
                </div>


            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection;

@section('footer')

@endsection
