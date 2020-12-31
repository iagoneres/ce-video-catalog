<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends AppModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
