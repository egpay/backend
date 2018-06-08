<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\User;

class TicketStatus extends Model
{

    protected $table = 'ticket_status';
    public $timestamps = true;

    use LogsActivity;

    protected $fillable = [
        'ticket_id',
        'status',
        'staff_id',
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'ticket_id',
        'status',
        'staff_id',
    ];


    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public function ticket(){
        return $this->belongsTo('App\Models\Ticket');
    }

}