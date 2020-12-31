<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateValidUuid()
    {
        $genre_data = ['name' => 'test'];
        $genre = Genre::create($genre_data);
        $genre->refresh();

        $this->assertTrue(Str::isUuid($genre->id));
    }

    public function testCreateDefaultFields()
    {
        $genre_data = ['name' => 'test'];
        $genre = Genre::create($genre_data);
        $genre->refresh();

        $this->assertEquals($genre_data['name'], $genre->name);
        $this->assertTrue($genre->is_active);
        $this->assertNull($genre->deleted_at);
    }
    
    public function testCreatePersonalizedIsActive()
    {
        $genre_data = ['name' => 'test_1', 'is_active' => false];
        $genre = Genre::create($genre_data);
        $genre->refresh();
        
        $this->assertFalse($genre->is_active);

        $genre_data = ['name' => 'test_2', 'is_active' => true];
        $genre = Genre::create($genre_data);
        $genre->refresh();
        
        $this->assertTrue($genre->is_active);
    }

    public function testList()
    {
        Genre::factory()->count(1)->create();
        $fields = ['id', 'name', 'is_active', 'created_at', 'updated_at', 'deleted_at'];
        $genres = Genre::all();
        $genre_key = array_keys($genres->first()->getAttributes());

        $this->assertCount(1, $genres);
        $this->assertEqualsCanonicalizing($fields, $genre_key);
    }

    public function testUpdate()
    {
        $genre = Genre::factory()->create([
            'is_active' => false
        ])->first();

        $update_data = [
            'name' => 'test name updated',
            'is_active' => true
        ];
        $genre->update($update_data);

        foreach($update_data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        $genre_data = ['name' => 'test'];
        $genre = Genre::create($genre_data);
        $genre->refresh();
        $this->assertTrue($genre->delete());

        $not_deleted_genre = Genre::where('id', $genre->id)->first();
        $this->assertNull($not_deleted_genre);

        $deleted_genre = Genre::withTrashed()->where('id', $genre->id)->first();
        $this->assertNotEquals(null, $deleted_genre->deleted_at);
        
    }
}
