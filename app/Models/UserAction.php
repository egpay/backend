<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class UserAction extends Model
{
    use LogsActivity;
    protected $table = 'user_actions';
    public $timestamps = true;

    protected $dates = ['deleted_at'];
    protected $fillable = array('type','model_id','model_type','user_id');

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'type',
        'model_id',
        'model_type',
        'user_id',
    ];

   public function model(){
       return $this->morphTo();
   }

    public function user(){
       return $this->belongsTo('App\Models\User','user_id');
    }

}