<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

class CategoryController extends BasicCrudController
{

    protected function model()
    {
        return Category::class;
    }

    protected function storeRules()
    {
        return [
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'is_active'   => 'boolean'
        ];
    }

    protected function updateRules()
    {
        return [
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'is_active'   => 'boolean'
        ];
    }

}