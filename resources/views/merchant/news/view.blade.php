@extends('merchant.layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="pull-left">{{$pageTitle}}</h2>
        </div>
    </div>


    <div class="row">

        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{__('Branch Area')}}</h2>
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
                            <td>{{__('Name on Bank ACC')}}</td>
                            <td>{{$result->name}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Acc #')}}</td>
                            <td>{{$result->account_number}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Bank Name')}}</td>
                            <td>{{$result->bank->$bankcol}}</td>
                        </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>



    </div>


@endsection


@section('header')

@endsection

@section('footer')

@endsection