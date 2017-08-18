<?php

namespace Tests\Feature;

use App\Author;
use App\Post;
use App\User;
use App\Services\Twitter\Client as Twitter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Mockery as m;

class TwitterPostImportTest extends TestCase
{
    use DatabaseMigrations;

    function setUp()
    {
        parent::setUp();

        app()->instance(Twitter::class, $this->mockTwitter());
    }

    /** @test */
    function tweet_import_gets_content_from_twitter_api()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals($this->fakeTweet->text, Post::first()->content);
    }

    /** @test */
    function tweet_import_gets_author_name_from_twitter()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/stauffermatt/805940005487132672';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals($this->fakeTweet->user->name, Author::first()->name);
    }

    /** @test */
    function tweet_import_replaces_twitter_url()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/stauffermatt/805940005487132672';

        $user->submit(Post::makeFromManualSubmission($request));

        $replacedUrl = current($this->fakeTweet->user->entities->url->urls)->expanded_url;
        $this->assertEquals($replacedUrl, Author::first()->url);
    }
}
