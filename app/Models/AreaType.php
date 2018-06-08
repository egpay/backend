<?php

namespace App\Models;

use App\Models\Area;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaType extends Model 
{

    protected $table = 'area_types';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name_ar', 'name_en');


    public function AreaType()
    {
        return $this->hasMany('Area', 'area_type_id');
    }

    public static function getFirstArea($lang){
        $areaType = self::select(['*',\DB::raw("name_$lang as name")])->find(1);
        if(!$areaType){
            return ['type'=>false,'areas'=>false];
        }

        return [
            'type'=> $areaType,
            'areas'=> Area::select(['*',\DB::raw("name_$lang as name")])->where('area_type_id',$areaType->id)->get()
        ];
    }




}