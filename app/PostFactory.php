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
        return Post::firstOrCreateCallback([
            'type' => 'twitter',
            'source_url' => $request->source_url,
        ], function () use ($request) {
            $tweet = app(Twitter::class)->get($request->source_url);

            return [
                'content' => $tweet->content, 
                // @todo update this method to take the tweet instead
                'author_id' => $this->authorFromTwitter($request)->id,
            ];
        });
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
