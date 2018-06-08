<?php

namespace App\Modules\Merchant;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class MerchantNewsController extends MerchantController
{
    public function index(Request $request){
        $this->viewData['pageTitle'] = __('Latest News');
        $this->viewData['allNews'] = News::latestNews()->orderByDesc('id')
            ->paginate(10);
        $this->viewData['lang'] = $this->systemLang;
        return $this->view('news.index',$this->viewData);
    }

    public function news(News $news)
    {
        $this->viewData['pageTitle'] = $news->{'name_'.$this->systemLang};
        $this->viewData['lang'] = $this->systemLang;
        $this->viewData['news'] = $news;

        return $this->view('news.viewone',$this->viewData);
    }

    public function category(NewsCategory $category)
    {
        $this->viewData['pageTitle'] = __('Latest News');
        $this->viewData['allNews'] = News::latestNews()->where('news_categories.id',$category->id)->orderByDesc('news.id','desc')
            ->paginate(10);
        $this->viewData['lang'] = $this->systemLang;
        $this->viewData['category'] = $category;
        return $this->view('news.index',$this->viewData);
    }

}