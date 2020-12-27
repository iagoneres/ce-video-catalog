<?php

namespace App\Models;

use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AppModel
 * @package App\Entities
 */
class AppModel extends Model
{
    use SoftDeletes, Uuid;
}
