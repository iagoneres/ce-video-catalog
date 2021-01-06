<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testIndex()
    {
        $category = Category::factory()->create();
        $response = $this->json('GET', route('categories.index'));

        $response->assertStatus(200)->assertJson([$category->toArray()]);
    }

    public function testShow()
    {
        $category = Category::factory()->create();
        $response = $this->json('GET', route('categories.show', ['category' => $category->id]));

        $response->assertStatus(200)->assertJson($category->toArray());
    }

    public function testStoreInvalidCategory()
    {
        $response = $this->json('POST', route('categories.store'), []);
        $this->assertInvalidationRequired($response);
        

        $response = $this->json('POST', route('categories.store'), ['name' => str_repeat('a', 256), 'is_active' => 'a']);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);


        $category = Category::factory()->create();
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), []);
        $this->assertInvalidationRequired($response);
        

        $response = $this->json(
            'PUT', 
            route('categories.update', ['category' => $category->id]), 
            [
                'name' => str_repeat('a', 256),
                'is_active' => 'a'
            ]
        );

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('categories.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $category = Category::find($id);

        $response->assertStatus(201)
            ->assertJson($category->toArray());
        $this->assertTrue($response->json('is_active'));
        $this->assertNull($response->json('description'));

        $data_store = [
            'name' => 'test',
            'description' => 'description',
            'is_active' => false
        ];
        $response = $this->json('POST', route('categories.store'), $data_store);

        $response->assertJsonFragment([
            'description' => $data_store['description'],
            'is_active' => false
        ]);
    }

    public function testUpdate()
    {
        $category = Category::factory()->create([
            'description' => 'test',
            'is_active' => false
        ]);

        $data_update = ['name' => 'test', 'description' => 'description updated', 'is_active' => true];
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), $data_update);

        $id = $response->json('id');
        $category = Category::find($id);

        $response->assertStatus(200)
            ->assertJson($category->toArray())
            ->assertJsonFragment([
                'description' => $data_update['description'],
                'is_active' => true
            ]);
        
        $data_update = ['name' => 'test', 'description' => ''];
        $response = $this->json('PUT', route('categories.update', ['category' => $category->id]), $data_update);

        $response->assertJsonFragment([
            'description' => null
        ]);
    }

    public function testDelete()
    {
        $category = Category::factory()->create();
        $response = $this->json('DELETE', route('categories.destroy', ['category' => $category->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('categories.show', ['category' => $category->id]));
        $response->assertStatus(404);

        $this->assertNotNull(Category::withTrashed()->find($category->id));

        $response = $this->json('DELETE', route('categories.destroy', ['category' => $category->id]));
        $response->assertStatus(404);
    }

    private function assertInvalidationRequired(TestResponse $response)
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.required', ['attribute' => 'name'])
            ]);
    }

    private function assertInvalidationMax(TestResponse $response)
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                \Lang::get('validation.max.string', ['attribute' => 'name', 'max'=> 255])
            ]);
    }

    private function assertInvalidationBoolean(TestResponse $response)
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::get('validation.boolean', ['attribute' => 'is active'])
            ]);
    }
}
