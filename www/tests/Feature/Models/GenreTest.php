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
        $data = ['name' => 'test'];
        $genre = Genre::create($data);
        $genre->refresh();

        $this->assertTrue(Str::isUuid($genre->id));
    }

    public function testCreateDefaultFields()
    {
        $data = ['name' => 'test'];
        $genre = Genre::create($data);
        $genre->refresh();

        $this->assertEquals($data['name'], $genre->name);
        $this->assertTrue($genre->is_active);
        $this->assertNull($genre->deleted_at);
    }
    
    public function testCreatePersonalizedIsActive()
    {
        $data = ['name' => 'test_1', 'is_active' => false];
        $genre = Genre::create($data);
        $genre->refresh();
        
        $this->assertFalse($genre->is_active);

        $data = ['name' => 'test_2', 'is_active' => true];
        $genre = Genre::create($data);
        $genre->refresh();
        
        $this->assertTrue($genre->is_active);
    }

    public function testList()
    {
        Genre::factory()->count(1)->create();
        $fields = ['id', 'name', 'is_active', 'created_at', 'updated_at', 'deleted_at'];
        $genres = Genre::all();
        $genreKey = array_keys($genres->first()->getAttributes());

        $this->assertCount(1, $genres);
        $this->assertEqualsCanonicalizing($fields, $genreKey);
    }

    public function testUpdate()
    {
        $genre = Genre::factory()->create([
            'is_active' => false
        ]);

        $data = [
            'name' => 'test name updated',
            'is_active' => true
        ];
        $genre->update($data);

        foreach($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        $genre = Genre::factory()->create();
        $this->assertTrue($genre->delete());

        $notDeletedGenre = Genre::find($genre->id);
        $this->assertNull($notDeletedGenre);

        $deletedGenre = Genre::withTrashed()->find($genre->id);
        $this->assertNotEquals(null, $deletedGenre->deleted_at);
        
        $genre->restore();
        $this->assertNotNull(Genre::find($genre->id));
        
    }
}
