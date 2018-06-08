<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AreaType;
class Area extends Model 
{

    protected $table = 'areas';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('area_type_id', 'name_ar', 'name_en', 'latitude', 'longitude', 'parent_id');

    public function areaType()
    {
        return $this->belongsTo('App\Models\AreaType');
    }

    public function merchant()
    {
        return $this->hasMany('App\Models\Merchant');
    }

    public function merchant_branches()
    {
        return $this->hasMany('App\Models\MerchantBranch', 'area_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Area', 'parent_id')->with('parent');
    }

    public function child()
    {
        return $this->hasMany('App\Models\Area', 'parent_id');
    }

    public function area()
    {
        return $this->hasMany('App\Models\Area', 'area_type_id');
    }

}