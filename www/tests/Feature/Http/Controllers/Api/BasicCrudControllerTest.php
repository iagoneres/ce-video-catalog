<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();
        $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();   
    }
    
    public function testIndex()
    {
        $category = CategoryStub::create(['name' => 'test name', 'description' => 'test description']);

        $category->refresh();

        $result = $this->controller->index()->toArray();
        $this->assertEquals([$category->toArray()], $result);
    }

    public function testInvalidDataStore()
    {
        $this->expectException(ValidationException::class);
        
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn(['name' => '']);
        $this->controller->store($request);
    }
    
    public function testStore()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn(['name' => 'test_name', 'description' => 'test_description']);
        
        $obj = $this->controller->store($request);
        $this->assertEquals(CategoryStub::find(1)->toArray(), $obj->toArray());
    }

    public function testShow()
    {
        $category = CategoryStub::create(['name' => 'test name', 'description' => 'test description']);
        $category->refresh();

        $result = $this->controller->show($category->id);
        $this->assertEquals(CategoryStub::find(1)->toArray(), $result->toArray());
        
    }

    public function testUpdate()
    {
        $category = CategoryStub::create(['name' => 'test name', 'description' => 'test description']);
        $category->refresh();

        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn(['name' => 'test_name', 'description' => 'test_description']);
        
        $result = $this->controller->update($request, $category->id);

        $this->assertEquals(CategoryStub::find(1)->toArray(), $result->toArray());
    }

    public function testDestroy()
    {
        $category = CategoryStub::create(['name' => 'test name', 'description' => 'test description']);
        $category->refresh();

        $response = $this->controller->destroy($category->id);

        $this->createTestResponse($response)
            ->assertStatus(204);
        
        $this->assertCount(0, CategoryStub::all());


    }
    
    public function testIfFindOrFailFetchModel()
    {
        $category = CategoryStub::create(['name' => 'test name', 'description' => 'test description']);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [$category->id]);
        $this->assertInstanceOf(CategoryStub::class, $result);
    }
    
    public function testIfFindOrFailThrowExceptionWhenIdInvalid()
    {
        $this->expectException(ModelNotFoundException::class);

        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invokeArgs($this->controller, [0]);
    }
}
