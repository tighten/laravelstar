<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModeratingPostTest extends TestCase
{
    /** @test */
    function moderator_can_dismiss_posts()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function non_moderators_cannot_dismiss_posts()
    {
        $this->markTestIncomplete();
    }
}
