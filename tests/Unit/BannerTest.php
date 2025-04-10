<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BannerTest extends TestCase
{
    use DatabaseMigrations;

    protected $banner;

    public function setUp() : void
    {
        parent::setUp();
        $this->banner = factory('App\Models\Banner')->create();
    }

     /** @test */
     function a_banner_has_an_album()
     {        
         $this->assertInstanceOf('App\Models\Album', $this->banner->album);
     } 
}