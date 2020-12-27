<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
