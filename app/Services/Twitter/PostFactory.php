<?php

namespace App\Services\Twitter;

use App\Author;
use App\Post;
use App\Services\Twitter\Client as Twitter;
use Exception;

class PostFactory
{
    public function make($request)
    {
        return Post::firstOrCreateCallback([
            'type' => 'twitter',
            'source_url' => $request->source_url,
        ], function () use ($request) {
            $split = explode('/', $request->source_url);
            $tweet = app(Twitter::class)->getTweet(end($split));

            return [
                'content' => $tweet->text, 
                'author_id' => $this->authorFromTweet($tweet), 
            ];
        });
    }

    private function authorFromTweet($tweet)
    {
        return Author::firstOrCreate([
            'twitter_id' => $tweet->user->id,
        ], [
            'twitter_screen_name' => $tweet->user->screen_name,
            'name' => $tweet->user->name,
            'url' => $this->convertTwitterEntity($tweet->user->url, $tweet->user->entities->url),
        ]);
    }

    private function convertTwitterEntity($string, $entities)
    {
        foreach ($entities->urls as $entity) {
            $string = str_replace($entity->url, $entity->expanded_url, $string);
        }

        return $string;
    }
}
