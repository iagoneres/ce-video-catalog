<?php

namespace App\Http\Controllers\Api;

use App\Models\CastMember;

class CastMemberController extends BasicCrudController
{

    protected function model()
    {
        return CastMember::class;
    }

    protected function storeRules()
    {
        $array = implode(',', [CastMember::TYPE_ACTOR, CastMember::TYPE_DIRECTOR]);
        return [
            "name"  => "required|max:255",
            "type"  => "required|in:{$array}"
        ];
    }

    protected function updateRules()
    {
        $array = implode(',', [CastMember::TYPE_ACTOR, CastMember::TYPE_DIRECTOR]);
        return [
            "name"  => "required|max:255",
            "type"  => "required|in:{$array}"
        ];
    }
}