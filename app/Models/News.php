<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{

    protected $table = 'news';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'content_ar',
        'content_en',
        'image',
        'staff_id',
        'status',
        'news_category_id'
    ];


    public function scopeActive($query){
        $query->where('news.status','=','active');
    }

    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public function category(){
        return $this->belongsTo('App\Models\NewsCategory','news_category_id','id');
    }


    public static function latestNews(){
        return self::select([
            'news.id','news_categories.id as category_id','news.name_ar','news.name_en','news.content_ar','news.content_en','news.image','news.created_at',
            'news_categories.name_ar AS category_ar', 'news_categories.name_en AS category_en',
        ])
            ->join('news_categories','news_categories.id','=','news.news_category_id')
            ->where('news_categories.status','active')
            ->where('news.status','active')
            ->where('news_categories.type','merchant');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'news.id',
            'news.news_category_id',
            'news.name_'.$langCode.' as name',
            'news.content_'.$langCode.' as content',
            'news.image',
            'news.staff_id',
            'news.status',
            'news.created_at',
            'news_categories.id as category_id',
            'news_categories.name_'.$langCode.' as category_name',
            'news_categories.descriptin_'.$langCode.' as category_description',
            'news_categories.icon as category_icon',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('news_categories','news.news_category_id','=','news_categories.id');
    }


}