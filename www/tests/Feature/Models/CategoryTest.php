<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateValidUuid()
    {
        $data = ['name' => 'test'];
        $category = Category::create($data);
        $category->refresh();

        $this->assertTrue(Str::isUuid($category->id));
    }

    public function testCreateDefaultFields()
    {
        $data = ['name' => 'test'];
        $category = Category::create($data);
        $category->refresh();

        $this->assertEquals($data['name'], $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
        $this->assertNull($category->deleted_at);
    }

    public function testCreatePersonalizedDescription()
    {
        $data = ['name' => 'test_1', 'description' => null];
        $category = Category::create($data);
        $category->refresh();
        
        $this->assertNull($category->description);

        $data = ['name' => 'test_2', 'description' => 'Test description'];
        $category = Category::create($data);
        $category->refresh();
        
        $this->assertEquals($data['description'], $category->description);
    }
    
    public function testCreatePersonalizedIsActive()
    {
        $data = ['name' => 'test_1', 'is_active' => false];
        $category = Category::create($data);
        $category->refresh();
        
        $this->assertFalse($category->is_active);

        $data = ['name' => 'test_2', 'is_active' => true];
        $category = Category::create($data);
        $category->refresh();
        
        $this->assertTrue($category->is_active);
    }

    public function testList()
    {
        Category::factory()->count(1)->create();
        $fields = ['id', 'name', 'description', 'is_active', 'created_at', 'updated_at', 'deleted_at'];
        $categories = Category::all();
        $categoryKey = array_keys($categories->first()->getAttributes());

        $this->assertCount(1, $categories);
        $this->assertEqualsCanonicalizing($fields, $categoryKey);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create([
            'description' => 'test description',
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'test name updated',
            'description' => 'test description updated',
            'is_active' => true
        ];
        $category->update($data);

        foreach($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $category = Category::factory()->create();
        $this->assertTrue($category->delete());

        $notDeletedCategory = Category::find($category->id);
        $this->assertNull($notDeletedCategory);

        $deletedCategory = Category::withTrashed()->find($category->id);
        $this->assertNotEquals(null, $deletedCategory->deleted_at);

        $category->restore();
        $this->assertNotNull(Category::find($category->id));
        
    }
}
