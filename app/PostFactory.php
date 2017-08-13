<?php

namespace App;

use App\Post;
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
            // 'content' => '@todo pull tweet content from api'
        ]);
    }
}
