<?php

namespace Tests\Feature;

use App\User;
use App\Post;
use App\Like;
use App\Read;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserInteractingWithPostsTest extends TestCase
{
    use DatabaseMigrations;

    // @todo this is all programming by wishful thinking so far
    
    /** @test */
    function user_can_like_posts()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user->like($post);

        $this->assertEquals(1, Like::count());
        $this->assertTrue($user->likes->pluck('post_id')->contains($post->id));
        $this->assertTrue($post->likers->contains($user));
    }

    /** @test */
    function user_can_read_posts()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $user->read($post);

        $this->assertEquals(1, Read::count());
        $this->assertTrue($user->reads->contains($post));
        $this->assertTrue($post->reads->contains($user));
    }

    /** @test */
    function can_get_list_of_posts_unread_by_user()
    {
        $user = factory(User::class)->create();
        $post1 = factory(Post::class)->create();
        $post2 = factory(Post::class)->create();
        $post3 = factory(Post::class)->create();

        $user->read($post3);

        $this->assertEquals(2, $user->unreadPosts()->count()); 
        $this->assertTrue($user->unreadPosts->contains($post1));
        $this->assertTrue($user->unreadPosts->contains($post2));
    }
}
