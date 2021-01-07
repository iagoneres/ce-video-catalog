<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
    }
    
    public function testIndex()
    {
        $response = $this->json('GET', route('categories.index'));
        $response->assertStatus(200)->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        $category = Category::factory()->create();
        $response = $this->json('GET', route('categories.show', ['category' => $category->id]));

        $response->assertStatus(200)->assertJson($category->toArray());
    }

    public function testInvalidName()
    {
        $data = ['name' => ''];
        $this->assertInvalidationStoreAction($data, 'required');
        $this->assertInvalidationUpdateAction($data, 'required');

        $data = ['name' => str_repeat('a', 256)];
        $this->assertInvalidationStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationUpdateAction($data, 'max.string', ['max' => 255]);
    }

    public function testInvalidIsActive()
    {
        $data = ['is_active' => 'a'];
        $this->assertInvalidationStoreAction($data, 'boolean');
        $this->assertInvalidationUpdateAction($data, 'boolean');
    }
    
    public function testStore()
    {
        $data = ['name' => 'test'];
        $db_data = $data + ['description' => null, 'is_active' => true, 'deleted_at' => null];
        $response = $this->assertStore($data, $db_data);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

        $data = ['name' => 'test', 'description' => 'description', 'is_active' => false];
        $this->assertStore($data, $data);
    }

    public function testUpdate()
    {
        $this->category = Category::factory()->create([
            'description' => 'test',
            'is_active' => false
        ]);

        $data = ['name' => 'test', 'description' => 'description updated', 'is_active' => true];
        $data_db = $data + ['deleted_at' => null];
        $response = $this->assertUpdate($data, $data_db);
        $response->assertJsonStructure(['created_at', 'updated_at']);

        $data = ['name' => 'test', 'description' => '', 'is_active' => true];
        $data_db = array_merge($data, ['description' => null]);
        $response = $this->assertUpdate($data, $data_db);

        $data['description'] = 'test';
        $response = $this->assertUpdate($data, $data);

        $data['description'] = null;
        $response = $this->assertUpdate($data, $data);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('categories.destroy', ['category' => $this->category->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('categories.show', ['category' => $this->category->id]));
        $response->assertStatus(404);

        $this->assertNotNull(Category::withTrashed()->find($this->category->id));

        $response = $this->json('DELETE', route('categories.destroy', ['category' => $this->category->id]));
        $response->assertStatus(404);
    }

    private function routeStore()
    {
        return route('categories.store');
    }

    private function routeUpdate()
    {
        return route('categories.update', ['category' => $this->category->id]);
    }

    private function model()
    {
        return Category::class;
    }
}
