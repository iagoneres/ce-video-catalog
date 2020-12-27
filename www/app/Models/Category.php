<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends AppModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $dates = [
        'deleted_at'
    ];
}
