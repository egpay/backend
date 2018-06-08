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
            @foreach($allNews as $onenews)
                <div class="card">
                    <div class="card-header">
                        <h2>{{$onenews->{'name_'.$lang} }}</h2>
                    </div>
                    <div class="card-block">
                        <p>{{Str::words($onenews->{'content_'.$lang},100)}}</p>
                        <a class="pull-right" href="{{route('panel.merchant.news.show',$onenews)}}">{{__('Read more')}}</a>
                    </div>
                </div>
            @endforeach
            <nav aria-label="Page navigation">
                {{$allNews->links()}}
            </nav>
        </div>

    </div>
@endsection

@section('header')
@endsection

@section('footer')
@endsection