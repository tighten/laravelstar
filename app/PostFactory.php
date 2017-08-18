<?php

namespace App;

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
