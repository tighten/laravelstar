<?php

namespace App;

use App\Author;
use App\Post;
use App\Services\Twitter;
use Exception;

class PostFactory
{
    protected $validTypes = [
        'twitter',
    ];

    public function makeFromManualSubmission($request)
    {
        if (! in_array($request->type, $this->validTypes)) {
            throw new Exception('Invalid type');
        }

        $type = ucwords($request->type);
        return $this->{"make{$type}FromManualSubmission"}($request);
    }

    public function makeTwitterFromManualSubmission($request)
    {
        return Post::firstOrCreate([
            'type' => 'twitter',
            'source_url' => $request->source_url,
        ], [
            'content' => $this->contentFromTwitter($request->source_url),
            'author_id' => $this->authorFromTwitter($request)->id,
        ]);
    }

    private function contentFromTwitter($sourceUrl)
    {
        $split = explode('/', $sourceUrl);
        $tweet = app(Twitter::class)->getTweet(end($split));

        return $tweet->content;
    }

    private function authorFromTwitter($request)
    {
        $path = parse_url($request->source_url, PHP_URL_PATH);
        $username = explode('/', ltrim($path, '/'))[0];

        return Author::firstOrCreate([
            'twitter' => $username
        ], [
            // @todo Full name from the API
            'name' => app(\Faker\Generator::class)->name,
        ]);
    }
}
