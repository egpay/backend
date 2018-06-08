<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Elasticquent\ElasticquentTrait;

class MerchantKnowledge extends Model
{
    protected $table = 'merchant_knowledge';
    public $timestamps = true;

    use SoftDeletes,ElasticquentTrait;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'content_ar',
        'content_en',
        'merchant_staff_id'
    ];


    public function merchant_staff(){
        return $this->belongsTo('App\Models\MerchantStaff');
    }
}
