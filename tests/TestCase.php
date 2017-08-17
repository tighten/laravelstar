<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery as m;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function mockTwitter()
    {
        $twitter = m::mock(Twitter::class)->shouldIgnoreMissing();
        $twitter->shouldReceive('getTweet')
            ->andReturn((object) ['content' => 'abc']);

        app()->instance(Twitter::class, $twitter);
    }
}
