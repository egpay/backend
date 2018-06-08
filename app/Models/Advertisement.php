<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Advertisement extends Model
{

    protected $table = 'advertisements';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at','from_date','to_date'];
    protected $fillable = [
        'name',
        'image',
        'width',
        'height',
        'route',
        'route_id',
        'comment',
        'status',
        'type',
        'total_amount',
        'merchant_id',
        'staff_id',
        'from_date',
        'to_date'
    ];

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }

    public function user_action(){
        return $this->morphMany('App\Models\UserAction', 'model');
    }

    public function analytics($ID,$year = null,$month = null,$day = null){
        $sql = "SELECT
              user_actions.type as `user_action_type`,
              user_actions.created_at as `user_action_date_time`,
              users.gender,
              users.birthdate
            FROM 
              `user_actions` 
            LEFT JOIN `users` ON `users`.`id` = `user_actions`.`user_id`
            
            WHERE
              `user_actions`.`model_type` = 'App\\\Models\\\Advertisement' AND
              `user_actions`.`model_id` = '$ID'
        ";

        if($year){
            $sql.= ' AND YEAR(`user_actions`.`created_at`) = "'.$year.'" ';
        }

        if($month){
            $sql.= ' AND MONTH(`user_actions`.`created_at`) = "'.$month.'" ';
        }

        if($day){
            $sql.= ' AND DAY(`user_actions`.`created_at`) = "'.$day.'" ';
        }

        return DB::select($sql);
    }

}