<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// use App\Scopes\OrderByNewerScope;

/**
 * Class AppModel
 * @package App\Entities
 */
class AppModel extends Model
{
    use SoftDeletes, Uuid;

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope(new OrderByNewerScope());
    // }
}
