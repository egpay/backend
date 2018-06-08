<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemAttribute extends Model
{
    protected $fillable = ['orderitem_id','attribute_id','attribute_value','attribute_data'];
}
