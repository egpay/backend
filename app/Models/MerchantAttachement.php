<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantAttachement extends Model 
{

    protected $table = 'merchant_attachements';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('merchant_id', 'title', 'file_path', 'staff_id');

}