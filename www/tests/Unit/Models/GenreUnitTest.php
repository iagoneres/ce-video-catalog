<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPUnit\Framework\TestCase;

class GenreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [HasFactory::class];
        $genreTraits = array_keys(class_uses(Genre::class));
        
        $this->assertEquals($traits, $genreTraits);
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse($this->genre->getIncrementing());
    }

    public function testCastsAttribute()
    {
        $casts = ['deleted_at' => 'datetime', 'is_active' => 'boolean'];
        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testDatesAttribute()
    {
        $genreDates = ['created_at', 'updated_at', 'deleted_at'];

        $this->assertEqualsCanonicalizing($genreDates, $this->genre->getDates());
        $this->assertCount(count($genreDates), $this->genre->getDates());
    }
}
