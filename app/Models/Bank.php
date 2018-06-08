<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Bank extends Model
{

    protected $table = 'banks';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'swift_code',
        'account_id',
        'logo'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'swift_code',
        'account_id',
        'logo',
    ];

}