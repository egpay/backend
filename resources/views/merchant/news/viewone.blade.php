@extends('merchant.layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>{{$pageTitle}}</h2>
        </div>
    </div>

    <div class="row match-height">
        @include('merchant.news.category')
        <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">
                        <h2>{{$news->{'name_'.$lang} }}</h2>
                    </div>
                    <div class="card-block">
                        <p>{{$news->{'content_'.$lang} }}</p>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('header')
@endsection

@section('footer')
@endsection