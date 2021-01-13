<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;

class CategoryStub extends Model
{
    protected $table = 'category_stubs';
    protected $fillable = ['name', 'description'];
    protected $casts = ['is_active' => 'boolean'];
    protected $dates = ['deleted_at'];

    public static function createTable()
    {
        \Schema::create('category_stubs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public static function dropTable()
    {
        \Schema::dropIfExists('category_stubs');
    }
}