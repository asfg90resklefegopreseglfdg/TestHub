<?php

namespace Tests\Unit;

use App\Tag;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_getting_attributes()
    {
        $tag = Tag::create([
            'tag' => 'asd'
        ]);
        $this->assertEquals($tag->id, $tag->getId());
    }
}
