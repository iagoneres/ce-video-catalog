<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private $category;

    public static function setUpBeforeClass(): void
    {
        // parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        // parent::tearDownAfterClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals($fillable, $this->category->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [HasFactory::class];
        $categoryTraits = array_keys(class_uses(Category::class));
        
        $this->assertEquals($traits, $categoryTraits);
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse($this->category->getIncrementing());
    }

    public function testCastsAttribute()
    {
        $casts = ['deleted_at' => 'datetime', 'is_active' => 'boolean'];
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testDatesAttribute()
    {
        $categoryDates = ['created_at', 'updated_at', 'deleted_at'];

        $this->assertEqualsCanonicalizing($categoryDates, $this->category->getDates());
        $this->assertCount(count($categoryDates), $this->category->getDates());
    }

}
