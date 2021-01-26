<?php

namespace Tests\Feature;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

use function PHPSTORM_META\type;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = CastMember::factory()->create([
            'type' => CastMember::TYPE_DIRECTOR
        ]);
    }
    
    public function testIndex()
    {
        $response = $this->json('GET', route('cast-members.index'));
        $response->assertStatus(200)->assertJson([$this->castMember->toArray()]);
    }

    public function testShow()
    {
        $response = $this->json('GET', route('cast-members.show', ['cast_member' => $this->castMember->id]));
        $response->assertStatus(200)
            ->assertJson($this->castMember->toArray());
    }

    public function testInvalidName()
    {
        $data = ['name' => '', 'type' => ''];
        $this->assertInvalidationStoreAction($data, 'required');
        $this->assertInvalidationUpdateAction($data, 'required');

        $data = ['name' => str_repeat('a', 256)];
        $this->assertInvalidationStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationUpdateAction($data, 'max.string', ['max' => 255]);
    }

    public function testInvalidType()
    {
        $data = ['type' => 'other'];
        $this->assertInvalidationStoreAction($data, 'in');
        $this->assertInvalidationUpdateAction($data, 'in');
    }
    
    public function testStore()
    {
        $data = ['name' => 'test'];
        $types = [CastMember::TYPE_DIRECTOR, CastMember::TYPE_ACTOR];

        foreach ($types as $type){
            $insertData = $data + ['type' => $type];
            $expectedData = $insertData + ['deleted_at' => null];
            $response = $this->assertStore($insertData, $expectedData);
            $response->assertJsonStructure([
                'created_at', 'updated_at'
            ]);
        }
    }

    public function testUpdate()
    {
        $data = ['name' => 'test', 'type' => CastMember::TYPE_ACTOR];
        $expectedData = $data + ['deleted_at' => null];
        $response = $this->assertUpdate($data, $expectedData);
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('cast-members.destroy', ['cast_member' => $this->castMember->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('cast-members.show', ['cast_member' => $this->castMember->id]));
        $response->assertStatus(404);

        $this->assertNotNull(CastMember::withTrashed()->find($this->castMember->id));

        $response = $this->json('DELETE', route('cast-members.destroy', ['cast_member' => $this->castMember->id]));
        $response->assertStatus(404);
    }

    protected function routeStore()
    {
        return route('cast-members.store');
    }

    protected function routeUpdate()
    {
        return route('cast-members.update', ['cast_member' => $this->castMember->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}