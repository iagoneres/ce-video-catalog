<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPUnit\Framework\TestCase;

class CastMemberTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'type'];
        $this->assertEquals($fillable, $this->castMember->getFillable());
    }

    public function testIfUseTraits()
    {
        $traits = [HasFactory::class];
        $castMemberTraits = array_keys(class_uses(CastMember::class));
        
        $this->assertEquals($traits, $castMemberTraits);
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse($this->castMember->getIncrementing());
    }

    public function testCastsAttribute()
    {
        $casts = ['deleted_at' => 'datetime', 'type' => 'integer'];
        $this->assertEquals($casts, $this->castMember->getCasts());
    }

    public function testDatesAttribute()
    {
        $dates = ['created_at', 'updated_at', 'deleted_at'];

        $this->assertEqualsCanonicalizing($dates, $this->castMember->getDates());
        $this->assertCount(count($dates), $this->castMember->getDates());
    }
}
