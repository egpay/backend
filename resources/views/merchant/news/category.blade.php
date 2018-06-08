<div class="col-sm-4">
    <div class="card">
        <div class="card-body">
            <div class="card-block">
                <h4 class="card-title">{{__('News Categories')}}</h4>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($categories as $onecategory)
                    <a href="{{route('panel.merchant.news.category',$onecategory)}}">
                        <li class="list-group-item {{((isset($category) && ($category->id == $onecategory->id))?'active':null)}}">
                            <span class="tag tag-default tag-pill bg-primary float-xs-right">{{$onecategory->news_count}}</span>{{$onecategory->name_ar}}
                        </li>
                    </a>
                @endforeach

            </ul>
        </div>
    </div>
</div>