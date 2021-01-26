<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CastMember extends AppModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];

    protected $dates = [
        'deleted_at'
    ];

    const TYPE_DIRECTOR = 1;
    const TYPE_ACTOR = 2;

}
