<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sender extends Model
{

    protected $table = 'sender';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'type',
        'from_name',
        'from_email',
        'send_to',
        'subject',
        'body',
        'file',
        'staff_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

}