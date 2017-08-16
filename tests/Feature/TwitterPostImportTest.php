<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TwitterPostImportTest extends TestCase
{
    /** @test */
    function user_can_submit_tweets()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals(Post::count(), 1);
        $this->assertEquals('twitter', Post::first()->type);
    }

    /** @test */
    function tweet_import_gets_content_from_twitter_api()
    {
        $fakeTweet = (object) [
            'content' => 'abcdefg'
        ];

        $twitter = m::mock(Twitter::class)
            ->shouldReceive('get')
            ->once()
            ->andReturn($fakeTweet);

        $this->markTestIncomplete("Got too tired here");

        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals(Post::count(), 1);
        $this->assertEquals('twitter', Post::first()->type);
    }
}
