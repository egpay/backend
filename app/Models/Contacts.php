<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacts extends Model
{

    protected $table = 'contacts';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('type', 'value', 'model_id', 'model_type');

    public function contactmodel()
    {
        return $this->morphTo();
    }

}