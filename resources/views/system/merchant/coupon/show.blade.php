@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>

            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-8">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Coupon Info')}}
                                    {{link_to_route('merchant.coupon.edit',' '.__('Edit'),['id'=>$result->id],['class'=>'btn btn-outline-primary pull-right fa fa-pencil'])}}
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
                                                <td>{{$result->id}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Code')}}</td>
                                                <td><code>{{$result->code}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Type')}}</td>
                                                <td>{{ (($result->type=='product')?__('E-commerce coupon'):__('E-payment coupon')) }}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Reward')}}</td>
                                                <td>{{(($result->reward_type=='fixed')?$result->reward.' '.__('LE'):'% '.$result->reward)}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Start date')}}</td>
                                                <td>{{$result->start_date}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('End date')}}</td>
                                                <td>{{$result->end_date}}</td>
                                            </tr>


                                            @foreach(listLangCodes() as $key => $value)

                                                <tr>
                                                    <td>{{__('Description')}} ({{$value}})</td>
                                                    <td><code>{{ $result->{'description_'.$key} }}</code></td>
                                                </tr>

                                            @endforeach

                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                <td>
                                                    <a href="{{url('system/staff/'.$result->staff_id)}}" target="_blank">
                                                        {{__('#ID')}}:{{$result->staff_id}} <br >{{$result->staff->firstname .' '. $result->staff->lastname}}
                                                    </a>
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
                                    {{__('Code available to')}}
                                </h4>

                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <ul class="list-group">
                                            @if(count(array_filter($result->users)))
                                                @foreach($result->objUsers as $user)
                                                    <li>{{link_to_route('system.users.show',$user->mobile,['id'=>$user->id])}}</li>
                                                @endforeach
                                            @else
                                                <li>{{__('All users')}}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>

                    <div class="col-md-4">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Items available')}}
                                </h4>

                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                    <div class="table-responsive">
                                        <ul class="list-group">
                                            @if(count(array_filter($result->items)))
                                                @foreach($result->objItems as $item)
                                                    @if($result->type=='product')
                                                        <li>{{link_to_route('merchant.product.show',$item->{'name_'.$lang},['id'=>$item->id])}}</li>
                                                    @else
                                                        <li>{{link_to_route('payment.service.show',$item->{'name_'.$lang},['id'=>$item->id])}}</li>
                                                    @endif
                                                @endforeach
                                            @else
                                                <li>{{__('All merchant items')}}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>



                </div>
            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection;

@section('footer')
@endsection
