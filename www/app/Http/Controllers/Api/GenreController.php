<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;

class GenreController extends BasicCrudController
{

    protected function model()
    {
        return Genre::class;
    }

    protected function storeRules()
    {
        return [
            'name'        => 'required|max:255',
            'is_active'   => 'boolean'
        ];
    }

    protected function updateRules()
    {
        return [
            'name'        => 'required|max:255',
            'is_active'   => 'boolean'
        ];
    }
}
