<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {
        $category_data = ['name' => 'test_1'];
        $category = Category::create($category_data);
        $category->refresh();

        $this->assertEquals($category_data['name'], $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
        $this->assertNull($category->deleted_at);

        $category_data = ['name' => 'test_2', 'description' => null];
        $category = Category::create($category_data);
        $category->refresh();
        
        $this->assertNull($category->description);

        $category_data = ['name' => 'test_3', 'description' => 'Test description 1'];
        $category = Category::create($category_data);
        $category->refresh();
        
        $this->assertEquals($category_data['description'], $category->description);

        $category_data = ['name' => 'test_4', 'is_active' => false];
        $category = Category::create($category_data);
        $category->refresh();
        
        $this->assertFalse($category->is_active);

        $category_data = ['name' => 'test_5', 'is_active' => true];
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
}
