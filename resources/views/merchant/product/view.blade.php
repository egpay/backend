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
                    <h2>{{__('English')}}</h2>
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
                                <td>{{__('Product name')}}</td>
                                <td>{{$result->name_en}}</td>
                            </tr>
                            <tr>
                                <td>{{__('Product Description')}}</td>
                                <td>{{$result->description_en}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h2>{{__('Arabic')}}</h2>
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
                            <td>{{__('Product name')}}</td>
                            <td>{{$result->name_ar}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Product Description')}}</td>
                            <td>{{$result->description_ar}}</td>
                        </tr>

                        </tbody>
                    </table>


                </div>
            </div>
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2>{{__('Product Information')}}</h2>
            </div>
            <div class="card-block">
                <div class="col-sm-12">
                    <h3>{{__('Product images')}}</h3>
                    @if(isset($result->uploadmodel))
                        @foreach($result->uploadmodel as $image)
                            <div class="col-sm-4">
                                <span>{{$image->title}}</span>
                                <br>
                                <img src="{{asset('storage/'.str_replace('public/','',$image->path))}}" class="img-responsive">
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="col-sm-12">

                    <table class="table">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>{{__('Values')}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{__('Product Category (EN)')}}</td>
                            <td>{{$result->category->name_en}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Product Category (AR)')}}</td>
                            <td>{{$result->category->name_ar}}</td>
                        </tr>
                        </tbody>
                    </table>


                </div>

            </div>
        </div>
    </div>

    @if(count($attribute))
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2>{{__('Attributes')}}</h2>
            </div>
            <div class="card-block">

                    <div class="nav-vertical">
                        <ul class="nav nav-tabs nav-left" style="height: 104px;">

                    @foreach($attribute->groupBy('attribute_id') as $key=>$value)
                                <li class="nav-item">
                                    <a class="nav-link" id="baseVerticalLeft-tab{{$value[0]->attribute_id}}" data-toggle="tab" aria-controls="tabVerticalLeft{{$value[0]->attribute_id}}" href="#tabVerticalLeft{{$value[0]->attribute_id}}" aria-expanded="false">
                                        {{$value[0]->name}}
                                    </a>
                                </li>
                    @endforeach
                        </ul>
                        <div class="tab-content px-1">
                    @foreach($attribute->groupBy('attribute_id') as $key=>$value)
                            <div class="tab-pane" id="tabVerticalLeft{{$value[0]->attribute_id}}" aria-labelledby="baseVerticalLeft-tab{{$value[0]->attribute_id}}">
                                <p>
                                    {{$value[0]->description}} <span class="text-danger">[{{(($value[0]->required)?__('Required'):__('Not required'))}}]</span>
                                </p>
                                <table class="table table-striped">
                                    <tr>
                                        <td>{{__('Option')}}</td>
                                        <td>{{__('Stock Availability / Quantity')}}</td>
                                        <td>{{__('Price change')}}</td>
                                    </tr>
                                    @foreach($value as $oneattribute)
                                        <tr>
                                            <td>{{$oneattribute->value_text}}</td>
                                            <td>{{(($oneattribute->quantity)?$oneattribute->quantity:__('Infinity'))}}</td>
                                            <td>{{$oneattribute->plus_price}} {{(($oneattribute->plus_price)?__('LE'):null)}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                    @endforeach
                        </div>
                    </div>

            </div>
        </div>
    </div>
    @endif


@endsection


@section('header')

@endsection

@section('footer')
    <script>
        $(function(){
            $('.nav-tabs.nav-left li:last a').trigger('click');
        });
    </script>
@endsection