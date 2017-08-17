<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Services\Twitter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Mockery as m;

class TwitterPostImportTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function tweet_import_gets_content_from_twitter_api()
    {
        $fakeTweet = (object) [
            'content' => 'abcdefg'
        ];

        $twitter = m::mock(Twitter::class);
        $twitter->shouldReceive('getTweet')->andReturn($fakeTweet);

        app()->instance(Twitter::class, $twitter);

        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals($fakeTweet->content, Post::first()->content);
    }
}
