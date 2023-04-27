<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeartRate extends Model
{
    use HasFactory;

    protected $fillable = [
        "patient_id",
        "heart_rate",
        "time",
    ];
}
