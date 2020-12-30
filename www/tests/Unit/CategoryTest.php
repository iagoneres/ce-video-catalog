<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    public function testFillableAttribute()
    {
        $category = new Category();
        $fillable = ['name', 'description', 'is_active'];
        
        $this->assertEquals($fillable, $category->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [HasFactory::class];
        $category_traits = array_keys(class_uses(Category::class));
        
        $this->assertEquals($traits, $category_traits);
    }

    public function testIncrementingAttribute()
    {
        $category = new Category();
        $this->assertFalse($category->getIncrementing());
    }

    public function testDatesAttribute()
    {
        $category = new Category();
        $category_dates = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($category_dates as $date) {
            $this->assertContains($date, $category->getDates());
        }

        $this->assertCount(count($category_dates), $category->getDates());
    }

}
