<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use tidy;

abstract class BasicCrudController extends Controller
{

    protected abstract function model();
    protected abstract function storeRules();
    protected abstract function updateRules();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
       $validatedData = $this->validate($request, $this->storeRules());
       $obj = $this->model()::create($validatedData);
       return $obj->refresh();
    }

    protected function findOrFail($id){
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();
        return $this->model()::where($keyName, $id)->firstOrFail();
    }


    // public function store(CategoryStoreRequest $request)
    // {
    //     $category = Category::create($request->all());
    //     return $category->refresh();
    // }

    // public function show(Category $category)
    // {
    //     return $category;
    // }

    // public function update(CategoryUpdateRequest $request, Category $category)
    // {
    //     $category->update($request->all());
    //     return $category;
    // }

    // public function destroy(Category $category)
    // {
    //     $category->delete();
    //     return response()->noContent();
    // }
}
