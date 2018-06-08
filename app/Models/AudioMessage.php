<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AudioMessage extends Model
{
    protected $table = 'audio_messages';
    public $timestamps = true;
    use SoftDeletes;

    protected $dates = [
        'seen',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'msgsendermodel_id',
        'msgsendermodel_type',
        'path',
        'seen',
        'seenby_id'
    ];

    public function msgsendermodel(){
        return $this->morphTo();
    }


    public function seenBy(){
        return $this->belongsTo('App\Models\Staff','seenby_id');
    }


}