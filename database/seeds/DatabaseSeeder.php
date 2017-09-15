<?php

use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = factory(User::class)->create();

        $post1 = factory(Post::class)->create();
        $post2 = factory(Post::class)->create();
        $post3 = factory(Post::class)->create();

        $user1->submit($post1);
        $user1->submit($post2);
        $user1->submit($post3);

        $user2 = factory(User::class)->create();

        $post4 = factory(Post::class)->create();

        $user2->submit($post3);
        $user2->submit($post4);

        $user1->like($post4);
        $user1->read($post4);

        $user2->like($post1);
        $user2->read($post2);
    }
}
