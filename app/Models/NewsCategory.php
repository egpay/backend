<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsCategory extends Model
{

    protected $table = 'news_categories';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'descriptin_ar',
        'descriptin_en',
        'icon',
        'staff_id',
        'status',
        'type'
    ];


    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public function category(){
        return $this->belongsTo('App\Models\NewsCategory');
    }

    public static function CategoriesWithCount(){
        return self::select([
            'id','name_ar','name_en','icon',
            \DB::raw("(SELECT COUNT(*) FROM `news` WHERE news.news_category_id = news_categories.id AND news.status='active') as `news_count`")
            ])
        ->where('status','active')
        ->where('type','merchant');
    }

}