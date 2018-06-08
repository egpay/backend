<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Elasticquent\ElasticquentTrait;

class SystemKnowledge extends Model
{

    protected $table = 'system_knowledge';
    public $timestamps = true;

    use SoftDeletes,ElasticquentTrait;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'content_ar',
        'content_en',
        'image',
        'staff_id'
    ];


    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

}