<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;


class VideoController extends BasicCrudController
{
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
            "duration" => "required|integer"
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
            "duration" => "required|integer"
        ];
    }
}
