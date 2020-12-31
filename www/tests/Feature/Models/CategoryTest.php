<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateValidUuid()
    {
        $category_data = ['name' => 'test'];
        $category = Category::create($category_data);
        $category->refresh();

        $this->assertTrue(Str::isUuid($category->id));
    }

    public function testCreateDefaultFields()
    {
        $category_data = ['name' => 'test'];
        $category = Category::create($category_data);
        $category->refresh();

        $this->assertEquals($category_data['name'], $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
        $this->assertNull($category->deleted_at);
    }

    public function testCreatePersonalizedDescription()
    {
        $category_data = ['name' => 'test_1', 'description' => null];
        $category = Category::create($category_data);
        $category->refresh();
        
        $this->assertNull($category->description);

        $category_data = ['name' => 'test_2', 'description' => 'Test description'];
        $category = Category::create($category_data);
        $category->refresh();
        
        $this->assertEquals($category_data['description'], $category->description);
    }
    
    public function testCreatePersonalizedIsActive()
    {
        $category_data = ['name' => 'test_1', 'is_active' => false];
        $category = Category::create($category_data);
        $category->refresh();
        
        $this->assertFalse($category->is_active);

        $category_data = ['name' => 'test_2', 'is_active' => true];
        $category = Category::create($category_data);
        $category->refresh();
        
        $this->assertTrue($category->is_active);
    }

    public function testList()
    {
        Category::factory()->count(1)->create();
        $fields = ['id', 'name', 'description', 'is_active', 'created_at', 'updated_at', 'deleted_at'];
        $categories = Category::all();
        $category_key = array_keys($categories->first()->getAttributes());

        $this->assertCount(1, $categories);
        $this->assertEqualsCanonicalizing($fields, $category_key);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create([
            'description' => 'test description',
            'is_active' => false
        ])->first();

        $update_data = [
            'name' => 'test name updated',
            'description' => 'test description updated',
            'is_active' => true
        ];
        $category->update($update_data);

        foreach($update_data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $category_data = ['name' => 'test', 'description' => 'Test description'];
        $category = Category::create($category_data);
        $category->refresh();
        $this->assertTrue($category->delete());

        $not_deleted_category = Category::where('id', $category->id)->first();
        $this->assertNull($not_deleted_category);

        $deleted_category = Category::withTrashed()->where('id', $category->id)->first();
        $this->assertNotEquals(null, $deleted_category->deleted_at);
        
    }
}
