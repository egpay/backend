@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-4 col-xs-12">
                    <h4>
                        {{$pageTitle}}
                    </h4>
                </div>
                <div class="content-header-right col-md-8 col-xs-12 mb-2">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    @if(Session::has('status'))
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="alert alert-{{Session::get('status')}}">
                                    {{ Session::get('msg') }}
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-8">

                        <!-- Invoice -->
                        <div class="card">
                            <div class="card-header">
                                <h2>{{__('Invoice')}}</h2>
                                <a href="" class="btn btn-info btn-lg pull-right" data-toggle="modal" data-target="#order-info">{{__('Creation Information')}}</a>
                            </div>
                            <div class="card-block">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>{{__('Product')}}</td>
                                        <td>{{__('Price')}}</td>
                                        <td>{{__('Qty')}}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->orderitems as $key=>$oneitem)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>
                                                {{link_to_route('merchant.product.show',$oneitem->merchant_product->name_en.' - '.$oneitem->merchant_product->name_ar,['id'=>$oneitem->merchant_product_id])}}

                                                <div class="help-block">
                                                    @if(($oneitem->orderItemAttribute->pluck('attribute_data') !== null) &&count($oneitem->orderItemAttribute->pluck('attribute_data')))
                                                        {{implode(' | ',$oneitem->orderItemAttribute->pluck('attribute_data')->toArray())}}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{$oneitem->price}} {{__('LE')}}</td>
                                            <td>{{$oneitem->qty}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td>#</td>
                                        <td>{{__('Total')}}</td>
                                        <td>{{$order->total}}</td>
                                        <td>{{$order->orderitems->sum('qty')}}</td>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>

                        <!-- Users -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="pull-left">{{__('Users Info')}}</h2>
                                <h2 class="pull-right">
                                    <a href="" data-toggle="modal" data-target="#qr-code"><i class="fa fa-qrcode fa-lg"></i></a>


                                </h2>
                            </div>
                            <div class="card-block">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>{{__('Mobile')}}</td>
                                        <td>{{__('Amount')}}</td>
                                        <td>{{__('Pay via')}}</td>
                                        <td>{{__('status')}}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transactions as $onetras)
                                        <tr>
                                            <td>{{__('User')}}</td>
                                            <td>{{link_to_route('system.users.show',\App\Models\Wallet::where('id',$onetras->from_id)->first()->walletowner->mobile,['id'=>\App\Models\Wallet::where('id',$onetras->from_id)->first()->walletowner->id])}}
                                                </td>
                                            <td>{{$onetras->amount}}</td>
                                            <td>{{(($onetras->type=='wallet')?__('Wallet'):__('Cash'))}}</td>
                                            <td class="{{(($onetras->status=='paid')?'text-success':'text-danger')}}">
                                                {{link_to_route('system.wallet.transactions.show',$onetras->status,['id'=>$onetras->id])}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>

                    <div class="col-sm-4">

                        <div class="card">
                            <div class="card-header"><h2>{{__('Order current status')}}</h2></div>
                            <div class="card-block">
                                <div style="text-align: center">
                                    @if(count($status))
                                        <a href="#" class="btn btn-float btn-float-lg {{(($status->first()->status=='approved')?'btn-success':(($status->first()->status=='requested')?'btn-info':'btn-danger'))}}">
                                            <span>{{ucfirst($status->first()->status)}}</span>
                                        </a>
                                    @endif
                                </div>
                                <hr>

                                {!! Form::open(['route' => ['merchant.order.show',$order->id], 'method' =>'GET','id'=>'changeStatus']) !!}
                                <div class="form-group">
                                    <label for="status">{{__('Status')}}</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="0">{{__('Select Status')}}</option>
                                        <option value="requested">{{__('Requested')}}</option>
                                        <option value="approved">{{__('Approved')}}</option>
                                        <option value="disapproved">{{__('Disapproved')}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="comment">{{__('Comment')}}</label>
                                    {!! Form::text('comment',null,['class'=>'form-control','id'=>'comment']) !!}
                                </div>
                                {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2>{{__('Order Status')}}</h2>
                            </div>
                            <div class="card-block">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <td>{{__('Status')}}</td>
                                        <td>{{__('Comment')}}</td>
                                        <td>{{__('Time')}}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($status))
                                        @foreach($status as $onestatus)
                                            <tr class="{{(($onestatus->status=='approved')?'table-success':(($onestatus->status=='requested')?'table-info':'table-danger'))}}">
                                                <td>{{ucfirst($onestatus->status)}}</td>
                                                <td>{{$onestatus->comment}}</td>
                                                <td>{{$onestatus->created_at->diffForHumans()}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade text-xs-left" id="order-info"  role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Creation information')}}</label>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('Branch Information')}}</h2>
                                </div>
                                <div class="card-block">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Value</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{__('Branch Name')}}</td>
                                            <td>{{$order->merchant_branch->name_en.' - '.$order->merchant_branch->name_ar}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{__('Branch Address')}}</td>
                                            <td>{{$order->merchant_branch->address_en.' - '.$order->merchant_branch->address_ar}}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">

                            <p><b>{{__('Created by')}}:</b>
                                @if(isset($order->id))
                                    @if($order->creatable instanceof \App\Models\MerchantStaff)
                                        {{$order->creatable->firstname.' '.$order->creatable->lastname}}
                                        <small>
                                            {{$order->creatable->merchant()->name_en}}
                                        </small>
                                    @elseif($order->creatable instanceof \App\Models\Staff)
                                        {{link_to_route('system.staff.show',$order->creatable->fullname,['id'=>$order->creatable->id])}}
                                    @else
                                        {{link_to_route('system.users.show',$order->creatable->mobile,['id'=>$order->creatable->id])}}
                                    @endif
                                @endif
                                , {{$order->created_at->diffForHumans()}}
                                ({{$order->created_at}})
                            </p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-xs-left" id="qr-code"  role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('QR Code')}}</label>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h2>{{__('QR Code')}}</h2>
                                </div>
                                <div class="card-block">
                                    @if(isset($order->qr_code))
                                        <div style="text-align: center">
                                            <img src="{{Base64PngQR($order->qr_code,['350','350'])}}">
                                        </div>
                                    @else
                                        <div id="generate-qr">
                                            {{__('Generate QR code')}}
                                            <div>
                                                <a href="javascript:void(0);" onclick="GenerateQR('{{route('merchant.order.qrcode',$order->id)}}');">{{__('Generate')}}</a>
                                            </div>
                                        </div>

                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection

@section('footer')
    <script type="text/javascript" src="{{asset('assets/merchant/order/view.order.js')}}"></script>
@endsection