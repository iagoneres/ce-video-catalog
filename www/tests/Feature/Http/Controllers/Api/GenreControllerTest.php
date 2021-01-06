<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testIndex()
    {
        $genre = Genre::factory()->create();
        $response = $this->json('GET', route('genres.index'));

        $response->assertStatus(200)->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        $genre = Genre::factory()->create();
        $response = $this->json('GET', route('genres.show', ['genre' => $genre->id]));

        $response->assertStatus(200)->assertJson($genre->toArray());
    }

    public function testStoreInvalidCategory()
    {
        $response = $this->json('POST', route('genres.store'), []);
        $this->assertInvalidationRequired($response);
        

        $response = $this->json('POST', route('genres.store'), ['name' => str_repeat('a', 256), 'is_active' => 'a']);
        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);


        $genre = Genre::factory()->create();
        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id]), []);
        $this->assertInvalidationRequired($response);
        

        $response = $this->json(
            'PUT', 
            route('genres.update', ['genre' => $genre->id]), 
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
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(201)
            ->assertJson($genre->toArray());
        $this->assertTrue($response->json('is_active'));

        $data_store = ['name' => 'test', 'is_active' => false];
        $response = $this->json('POST', route('genres.store'), $data_store);

        $this->assertFalse($response->json('is_active'));
    }

    public function testUpdate()
    {
        $genre = Genre::factory()->create(['is_active' => false]);

        $data_update = ['name' => 'test', 'is_active' => true];
        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id]), $data_update);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(200)
            ->assertJson($genre->toArray());
        $this->assertTrue($response->json('is_active'));
    }

    public function testDelete()
    {
        $genre = Genre::factory()->create();
        $response = $this->json('DELETE', route('genres.destroy', ['genre' => $genre->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('genres.show', ['genre' => $genre->id]));
        $response->assertStatus(404);

        $this->assertNotNull(Genre::withTrashed()->find($genre->id));

        $response = $this->json('DELETE', route('genres.destroy', ['genre' => $genre->id]));
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
