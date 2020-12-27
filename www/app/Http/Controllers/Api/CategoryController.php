<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index()
    {
        return Category::all();
    }

    public function store(CategoryStoreRequest $request)
    {
        return Category::create($request->all());
    }

    public function show(Category $category)
    {
        return $category;
    }
    
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->all());
        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
