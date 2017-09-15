<?php

namespace Tests\Feature;

use App\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function post_list_page_shows_first_posts_title()
    {
        $post = factory(Post::class)->create();

        $this->visit(route('posts.index'))
            ->see($post->title);
    }
}
