<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends AppModel
{
    use HasFactory;

    const RATING_LIST = ['L', '10', '12', '14', '16', '18'];

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'new_release',
        'rating',
        'duration'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'release_year' => 'integer',
        'new_release' => 'boolean',
        'duration' => 'integer'
    ];

}
