<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderStatus extends Model 
{

    protected $table = 'order_status';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['deleted_at'];
    protected $fillable = array('statusable_id', 'statusable_type', 'status', 'comment');

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'statusable_id',
        'statusable_type',
        'status',
        'comment',
    ];

    public function statusable()
    {
        return $this->morphTo();
    }

}