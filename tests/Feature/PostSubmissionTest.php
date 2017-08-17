<?php

namespace Tests\Feature;

use App\Author;
use App\Post;
use App\Services\Twitter;
use App\Submission;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Tests\TestCase;

class PostSubmissionTest extends TestCase
{
    use DatabaseMigrations;

    function setUp()
    {
        parent::setUp();

        app()->instance(Twitter::class, $this->mockTwitter());
    }

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
    function post_can_reveal_its_submitters()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $post = Post::first();
        $submission = Submission::first();

        $this->assertTrue($post->submitters->contains($user));
        $this->assertTrue($post->submissions->contains($submission));
    }

    /** @test */
    function two_users_can_submit_the_same_tweet_and_both_be_attributed_as_submitters()
    {
        $sadith = factory(User::class)->create(['name' => 'Sadith']);
        $andres = factory(User::class)->create(['name' => 'Andres']);

        $sadith_request = new Request;
        $sadith_request->type = 'twitter';
        $sadith_request->source_url = 'http://twitter.com/mytweets/12345';

        $sadith->submit(Post::makeFromManualSubmission($sadith_request));

        $andres_request = new Request;
        $andres_request->type = 'twitter';
        $andres_request->source_url = 'http://twitter.com/mytweets/12345';

        $andres->submit(Post::makeFromManualSubmission($andres_request));

        $post = Post::first();

        $this->assertEquals(Post::count(), 1);
        $this->assertEquals(Submission::count(), 2);
        $this->assertTrue($post->submitters->contains($sadith));
        $this->assertTrue($post->submitters->contains($andres));
        $this->assertEquals($post->originator->id, $sadith->id);
    }

    /** @test */
    function if_author_isnt_in_our_system_submission_creates()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals(1, Author::count());
    }

    /** @test */
    function if_author_exists_second_author_isnt_created()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/67890';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals(1, Author::count());
    }

    /** @test */
    function if_user_submits_post_twice_no_duplicate_created()
    {
        $user = factory(User::class)->create();

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $request = new Request;
        $request->type = 'twitter';
        $request->source_url = 'http://twitter.com/mytweets/12345';

        $user->submit(Post::makeFromManualSubmission($request));

        $this->assertEquals(1, Post::count());
    }
}
