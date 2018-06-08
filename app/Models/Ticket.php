<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\User;

class Ticket extends Model
{

    protected $table = 'tickets';
    public $timestamps = true;

    use LogsActivity;

    protected $fillable = ['merchant_id','invoiceable_type','invoiceable_id','subject','details','to_type','to_id','status','created_by_staff_id',
        'is_seen_by_sender','is_seen_by_receiver',
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'merchant_id',
        'invoiceable_type',
        'invoiceable_id',
        'subject',
        'details',
        'to_type',
        'to_id',
        'created_by_staff_id',
        'is_seen_by_sender',
        'is_seen_by_receiver',
    ];


    public function invoiceable(){
        return $this->morphTo('invoiceable');
    }

    public function merchant(){
        return $this->belongsTo('App\Models\Merchant');
    }

    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public function forwardTo(){
        return $this->morphTo('to');
    }

    public function comments(){
        return $this->hasMany('App\Models\TicketComment','ticket_id')->orderByDesc('created_at');
    }

    public function createdBy(){
        return $this->belongsTo('App\Models\Staff','created_by_staff_id','id');
    }

    /*
     * Return all status
     */
    public function AllStatus(){
        return $this->hasMany('App\Models\TicketStatus','ticket_id')->orderByDesc('created_at');
    }

    /*
     * Return last status only
     */
    public function TicketStatus(){
        return $this->hasOne('App\Models\TicketStatus','ticket_id')->latest('ticket_status.id');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'tickets.id',
            'tickets.merchant_id',
            'tickets.invoiceable_type',
            'tickets.invoiceable_id',
            'tickets.created_at',
            'tickets.subject',
            'tickets.to_id',
            'tickets.to_type',
            'tickets.status AS ticket_status',
            'tickets.created_by_staff_id',
            'merchants.name_'.$langCode.' as merchant_name',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->leftJoin('merchants','merchants.id','=','tickets.merchant_id')
            ;
    }
}