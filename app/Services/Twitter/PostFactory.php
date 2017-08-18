<?php

namespace App\Services\Twitter;

use App\Author;
use App\Post;
use App\Services\Twitter\Client as Twitter;

class PostFactory
{
    public $twitter;

    public function __construct(Twitter $twitter)
    {
        $this->twitter = $twitter;
    }

    public function make($request)
    {
        return Post::firstOrCreateCallback([
            'type' => 'twitter',
            'source_url' => $request->source_url,
        ], function () use ($request) {
            return $this->tweetFromRequest($request);
        });
    }

    public function tweetFromRequest($request)
    {
        $split = explode('/', $request->source_url);
        $tweet = $this->twitter->getTweet(end($split));

        return [
            'content' => $tweet->text, 
            'author_id' => $this->authorFromTweet($tweet)->id, 
        ];
    }

    public function authorFromTweet($tweet)
    {
        return Author::firstOrCreate([
            'twitter_id' => $tweet->user->id,
        ], [
            'twitter_screen_name' => $tweet->user->screen_name,
            'name' => $tweet->user->name,
            'url' => $this->replaceTwitterUrlEntities(
                $tweet->user->url,
                $tweet->user->entities->url
            ),
        ]);
    }

    public function replaceTwitterUrlEntities($string, $entities)
    {
        return collect($entities->urls)->reduce(function ($carry, $entity) {
            return str_replace($entity->url, $entity->expanded_url, $carry);
        }, $string);
    }
}
