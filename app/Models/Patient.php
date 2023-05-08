<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'secret_phrase',
        'name',
        'relative_name',
        'relative_contact',
        'age',
        'address',
        'bracelet_url',
        'user_id',
        'doctor_id',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i, d M Y');
    }

    public function user(){

        return $this->belongsTo(User::class, 'user_id');

    }
}
