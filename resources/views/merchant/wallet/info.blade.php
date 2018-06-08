@extends('merchant.layouts')
@section('content')
    <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{__('Merchant balance')}}</h2>
                </div>
            </div>
    </div>

    <div class="col-sm-12">
            <div class="card">
                <div class="card-block">
                    <div>
                        <label class="text-bold-700">{{__('Merchant Balance')}} :</label>
                        <div class="indent">{{number_format($balance,2)}} {{__('EGP')}}</div>
                    </div>

                    <h2>{{__('Transactions')}}</h2>
                    <table class="table treegrid">
                        <thead>
                            <tr>
                                <th>{{__('Order#')}}</th>
                                <th>{{__('Amount (EGP)')}}</th>
                                <th>{{__('From')}}</th>
                                <th>{{__('TO')}}</th>
                                <th>{{__('Time')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $countkey=1;
                        @endphp
                        @foreach($trans->get()->groupBy('model_id') as $key=>$onetrans)
                            <tr class="treegrid-{{$parent = $countkey}} font-weight-bold">
                                <td>{{$key}}</td>
                                <td>{{$onetrans->sum('amount')}}</td>
                                <td>{!! implode(',',array_unique($trans->pluck('from_id')->toArray())) !!}</td>
                                <td>--</td>
                                <td>--</td>
                                <td>{!!(($merchant->order()->first()->is_paid=='yes'))?'<span class="text-success">Paid</span>':'<span class="text-danger">NotPaid</span>'!!}</td>

                            </tr>
                            @if(count($onetrans))
                                @foreach($onetrans as $onerow)
                                <tr class="treegrid-{{++$countkey}} treegrid-parent-{{$parent}}">
                                    <td>{{$onerow->model_id}}</td>
                                    <td>{{$onerow->amount}}</td>
                                    <td>
                                        @if($onerow->from_id == $merchant->wallet()->first()->id)
                                            {{__('Me')}}
                                        @else
                                            {!! $onerow->from_id !!}
                                        @endif
                                    </td>
                                    <td>
                                        @if($onerow->to_id == $merchant->wallet()->first()->id)
                                            {{__('Me')}}
                                        @else
                                            {!! $onerow->to_id !!}
                                        @endif
                                    </td>
                                    <td>{{$onerow->created_at->diffForHumans()}}</td>
                                    <td>{{$onerow->status}}</td>
                                </tr>
                                @endforeach
                            @endif
                            <?$countkey++;?>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr class="font-weight-bold">
                            <td>{{__('Total')}}</td>
                            <td colspan="5">{{$trans->sum('amount')}}</td>
                        </tr>
                        </tfoot>

                    </table>

                </div>
            </div>
    </div>


@endsection


@section('header')
    <link rel="stylesheet" href="{{asset('assets/system/vendors/treegrid/css/jquery.treegrid.css')}}">
@endsection

@section('footer')
    <script src="{{asset('assets/system/vendors/treegrid/js/jquery.treegrid.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.treegrid').treegrid({
                'initialState': 'collapsed',
            });
        });
    </script>
@endsection