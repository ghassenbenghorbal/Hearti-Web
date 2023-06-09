<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'text',
        'sender',
        'receiver',
        'attachement',
    ];

    //get the user of the message receiver or sender doesn't matter


    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'sender');
    }

    public function receiver()
    {
        return $this->hasOne(User::class, 'id', 'receiver');
    }
}
