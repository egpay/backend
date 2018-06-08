<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyProgramIgnore extends Model{

    protected $table = 'loyalty_program_ignore';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'id',
        'loyalty_program_id',
        'ignoremodel_id',
        'ignoremodel_type',
        'description_ar',
        'description_en',
        'staff_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function ignoremodel(){
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(){
        return $this->belongsTo('App\Models\Staff','staff_id');
    }

    public function loyaltyProgram(){
        return $this->belongsTo('App\Models\LoyaltyPrograms','loyalty_program_id');
    }

}