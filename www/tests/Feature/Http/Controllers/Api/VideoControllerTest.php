<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestValidations;
use Tests\Traits\TestSaves;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = Video::factory()->create();
        $this->sendData = [
            'title' => 'title',
            'description' => 'description',
            'release_year' => 2010,
            'rating' => Video::RATING_LIST[0],
            'duration' => 90
        ];
    }
    
    public function testIndex()
    {
        $response = $this->json('GET', route('videos.index'));
        $response->assertStatus(200)->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->json('GET', route('videos.show', ['video' => $this->video->id]));
        $response->assertStatus(200)
            ->assertJson($this->video->toArray());
    }

    public function testInvalidRequired()
    {
        $data = [
            'title' => '',
            'description' => '',
            'release_year' => '',
            'rating' => '',
            'duration' => ''
        ];

        $this->assertInvalidationStoreAction($data, 'required');
        $this->assertInvalidationUpdateAction($data, 'required');
    }

    public function testInvalidMax()
    {
        $data = ['title' => str_repeat('a', 256)];

        $this->assertInvalidationStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationUpdateAction($data, 'max.string', ['max' => 255]);
    }  

    public function testInvalidInteger()
    {
        $data = ['duration' => 'a'];
        $this->assertInvalidationStoreAction($data, 'integer');
        $this->assertInvalidationUpdateAction($data, 'integer');
    }
    
    public function testInvalidBoolean()
    {
        $data = ['new_release' => 'a'];
        $this->assertInvalidationStoreAction($data, 'boolean');
        $this->assertInvalidationUpdateAction($data, 'boolean');
    }
    
    public function testInvalidReleaseYear()
    {
        $data = ['release_year' => 'a'];
        $this->assertInvalidationStoreAction($data, 'date_format', ['format' => 'Y']);
        $this->assertInvalidationUpdateAction($data, 'date_format', ['format' => 'Y']);
    }

    public function testInvalidRating()
    {
        $data = ['rating' => 0];
        $this->assertInvalidationStoreAction($data, 'in');
        $this->assertInvalidationUpdateAction($data, 'in');
    }
    
    public function testStore()
    {
        $dbData = $this->sendData + ['new_release' => false];
        $this->assertStore($this->sendData, $dbData);

        $this->sendData = $this->sendData + ['new_release' => true];
        $response = $this->assertStore($this->sendData, $this->sendData);

        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
    }

    public function testUpdate()
    {
        $this->sendData = $this->sendData + ['new_release' => true];
        $this->assertUpdate($this->sendData, $this->sendData);
        
        $dbData = $this->sendData + ['new_release' => false];
        $response = $this->assertUpdate($this->sendData, $dbData);

        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('videos.destroy', ['video' => $this->video->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('videos.show', ['video' => $this->video->id]));
        $response->assertStatus(404);

        $this->assertNotNull(Video::withTrashed()->find($this->video->id));

        $response = $this->json('DELETE', route('videos.destroy', ['video' => $this->video->id]));
        $response->assertStatus(404);
    }

    protected function routeStore()
    {
        return route('videos.store');
    }

    protected function routeUpdate()
    {
        return route('videos.update', ['video' => $this->video->id]);
    }

    protected function model()
    {
        return Video::class;
    }
}
