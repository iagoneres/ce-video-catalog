<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Tests\Stubs\Models\CategoryStub;


class CategoryControllerStub extends BasicCrudController
{
    protected function model()
    {
        return CategoryStub::class;
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
