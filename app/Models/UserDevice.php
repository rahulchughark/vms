<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $table = 'user_devices';

    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

   
}
