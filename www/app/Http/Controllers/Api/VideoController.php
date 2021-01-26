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
        // return [
        //     'name'        => 'required|max:255',
        //     'is_active'   => 'boolean'
        // ];
    }

    protected function updateRules()
    {
        // return [
        //     'name'        => 'required|max:255',
        //     'is_active'   => 'boolean'
        // ];
    }
}
