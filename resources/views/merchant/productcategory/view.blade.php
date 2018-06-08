@extends('merchant.layouts')

@section('content')
    <div class="col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="pull-left">{{$pageTitle}}</h2>
                    <div class="pull-right">
                        <h3>{{__('Category Status')}}</h3>
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
                                <td>{{__('Category name')}}</td>
                                <td>{{$result->name_en}}</td>
                            </tr>

                            <tr>
                                <td>{{__('Category Description')}}</td>
                                <td>{{$result->description_en}}</td>
                            </tr>

                            <tr>
                                <td>{{__('Category icon')}}</td>
                                <td>{{$result->icon}}</td>
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
                            <td>{{__('Category name')}}</td>
                            <td>{{$result->name_ar}}</td>
                        </tr>

                        <tr>
                            <td>{{__('Category Description')}}</td>
                            <td>{{$result->description_ar}}</td>
                        </tr>

                        <tr>
                            <td>{{__('Category Icon')}}</td>
                            <td>{{$result->icon}}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
    </div>


@endsection


@section('header')

@endsection

@section('footer')
@endsection