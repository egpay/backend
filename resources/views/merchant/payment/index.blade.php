@extends('merchant.payment.layout')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="pull-left">{{$pageTitle}}</h2>
                    <h2 class="pull-right">
                        <label>{{__('Balance')}}</label>
                        <span id="balance" class="font-weight-bold">{{number_format($balance,2)}} {{__('LE')}}</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-4">
            <div class="card p-2" id="services-sidebar">
                <!--Menu Start-->
                <div id="left" class="span3">
                    <ul id="menu-group-1" class="nav menu">

                        @foreach($services->groupBy('category_id') as $serviceCategory)
                            <li class="item-8 deeper parent">
                                <a class="" href="javascript:void(0);">
                                    <span data-toggle="collapse" data-parent="#menu-group-{{$serviceCategory[0]->category_id}}" href="#parent-item-{{$serviceCategory[0]->category_id}}" class="lbl">
                                        @if(file_exists('storage/'.$serviceCategory[0]->category_icon))
                                            <img src="{{asset('storage/'.$serviceCategory[0]->category_icon)}}">
                                        @endif
                                        {{$serviceCategory[0]->category_name}}
                                    </span>
                                </a>
                                <ul class="children nav-child unstyled small collapse" id="parent-item-{{$serviceCategory[0]->category_id}}">
                                    @foreach($serviceCategory->groupBy('provider_id') as $ServiceProvider)
                                    <li class="item-2 deeper parent active">
                                        <a class="" href="javascript:void(0);">
                                            <span data-toggle="collapse" data-parent="#menu-group-{{$ServiceProvider[0]->category_id}}" href="#sub-item-{{$ServiceProvider[0]->provider_id}}">
                                                @if($ServiceProvider[0]->provider_logo)
                                                    <img src="{{asset('storage/'.$ServiceProvider[0]->provider_logo)}}">
                                                @endif
                                                {{$ServiceProvider[0]->provider_name}}</span>
                                        </a>
                                        <ul class="children nav-child unstyled small collapse" id="sub-item-{{$ServiceProvider[0]->provider_id}}">
                                            @foreach($ServiceProvider as $Service)
                                            <li class="item-3">
                                                <a class="" href="javascript:void(0);" onclick="ShowService('{{$Service->id}}','{{route('panel.merchant.payment.service',$Service->id)}}');">
                                                    @if($Service->icon)
                                                        <img src="{{asset('storage/'.$Service->icon)}}">
                                                    @endif
                                                    <span class="lbl">{{$Service->name}}</span>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!--Menu End-->
            </div>
        </div>



        <div class="col-xs-8">
            <div class="card p-1" id="services" style="min-height: 300px;">
                <h2>{{__('Services')}}</h2>
            </div>
        </div>
    </div>
    <div class="row clearfix"></div>

@endsection


@section('header')
    <link rel="stylesheet" href="{{asset('assets/merchant/payment/payment.css')}}">
    <link rel="stylesheet" href="{{asset('assets/merchant/fonts/receipt.css')}}">
    <link rel="stylesheet" href="{{asset('assets/system/vendors/css/extensions/sweetalert.css')}}">
@endsection

@section('footer')
    <script src="{{asset('assets/merchant/form/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/system/vendors/js/extensions/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/merchant/payment/payment.js')}}"></script>
    <script src="{{asset('assets/merchant/js/jQuery.print.min.js')}}"></script>
@endsection