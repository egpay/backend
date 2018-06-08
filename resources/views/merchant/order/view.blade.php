@extends('merchant.layouts')
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
@endsection
@section('content')
            <div class="card">
                <div class="card-header">
                    <h2 class="pull-left">{{$pageTitle}}</h2>
                    <a href="" class="btn btn-info btn-lg pull-right" data-toggle="modal" data-target="#order-info">{{__('Creation Information')}}</a>
                </div>
            </div>

            <div class="content-body">
                <!-- Server-side processing -->
                <section id="server-processing">
                    <div class="row">

                        <!--

                        -->
                        <div class="col-sm-8">

                            <!-- Invoice -->
                            <div class="card">
                                <div class="card-header"><h2>{{__('Invoice')}}</h2></div>
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
                                                    <a href="{{route('panel.merchant.product.show',$oneitem->merchant_product_id)}}">{{$oneitem->merchant_product->name_en.' - '.$oneitem->merchant_product->name_ar}}</a>
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
                                            <td>{{__('Value')}}</td>
                                            <td>{{__('Amount')}}</td>
                                            <td>{{__('Pay via')}}</td>
                                            <td>{{__('status')}}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($transactions as $onetras)
                                            <tr>
                                                <td>{{__('User')}}</td>
                                                <td>{{\App\Models\Wallet::where('id',$onetras->from_id)->first()->walletowner->mobile}}</td>
                                                <td>{{$onetras->amount}}</td>
                                                <td>{{(($onetras->type=='wallet')?__('Egpay'):__('Cash'))}}</td>
                                                <td class="{{(($onetras->status=='paid')?'text-success':'text-danger')}}">{{$onetras->status}}</td>
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

                                    {!! Form::open(['route' => ['panel.merchant.order.show',$order->id], 'method' =>'GET','id'=>'changeStatus']) !!}
                                    <div class="form-group">
                                        <label for="status">{{__('Status')}}</label>
                                        <select name="status" class="form-control">
                                            <option value="0">{{__('Select Status')}}</option>
                                            <option value="requested">{{__('Requested')}}</option>
                                            <option value="approved">{{__('Approved')}}</option>
                                            <option value="disapproved">{{__('Disapproved')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="comment">{{__('Comment')}}</label>
                                        {!! Form::text('comment',null,['class'=>'form-control']) !!}
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
                </section>
                <!--/ Javascript sourced data -->
            </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
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
                                            @else
                                                {{$order->creatable->mobile}}
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
                                                        <a href="javascript:void(0);" onclick="GenerateQR('{{route('panel.merchant.order.qrcode',$order->id)}}');">{{__('Generate')}}</a>
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
@section('footer')
    <script src="{{asset('assets/merchant')}}/order/view.order.js"></script>
    <script>

        $('#changeStatus').onsubmit(function(e){
            e.preventDefault();
            formSubmit($(btn).closest('form'),'{{route('panel.merchant.order.show',$order->id)}}','get','{{__('Could not change order status')}}','{{__('Order status changed')}}');
        });
    </script>
@endsection