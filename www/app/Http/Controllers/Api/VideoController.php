<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends BasicCrudController
{
    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->storeRules());
        $obj = $this->model()::create($validatedData);

        $obj->categories()->sync($request->get('categories_id'));
        $obj->genres()->sync($request->get('genres_id'));
        
        return $obj->refresh();
    }

    public function update(Request $request, $id)
    {
        $obj = $this->findOrFail($id);
        $validatedData = $this->validate($request, $this->updateRules());
        $obj->update($validatedData);

        $obj->categories()->sync($request->get('categories_id'));
        $obj->genres()->sync($request->get('genres_id'));
        
        return $obj;
    }
    
    
    protected function model()
    {
        return Video::class;
    }

    protected function storeRules()
    {
        $array = implode(',', Video::RATING_LIST);
        return [
            "title"  => "required|max:255",
            "description" => "required",
            "release_year" => "required|date_format:Y",
            "new_release" => "boolean",
            "rating"  => "required|in:{$array}",
            "duration" => "required|integer",
            "categories_id" => "required|array|exists:categories,id",
            "genres_id"  => "required|array|exists:genres,id"
        ];
    }

    protected function updateRules()
    {
        $array = implode(',', Video::RATING_LIST);
        return [
            "title"  => "required|max:255",
            "description" => "required",
            "release_year" => "required|date_format:Y",
            "new_release" => "boolean",
            "rating"  => "required|in:{$array}",
            "duration" => "required|integer",
            "categories_id" => "required|array|exists:categories,id",
            "genres_id"  => "required|array|exists:genres,id"
        ];
    }
}
