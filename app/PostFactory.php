<?php

namespace App;

use App\Author;
use App\Post;
use App\Services\Twitter\Client as Twitter;
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

        return app("App\Services\\{$type}\PostFactory")->make($request);
    }
}
