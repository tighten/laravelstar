<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter
{
    private $client;

    public function __construct(TwitterOAuth $client)
    {
        $this->client = $client;
    }

    public function getTweet($id)
    {
        // well that was anticlimactic. Let's drop this class.
        return $this->client->get('statuses/show/' . $id); 
    }
}
