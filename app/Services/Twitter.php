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
        // @todo: Make it so none of the tests hit the real twitter
        $tweet = $this->client->get('statusus/show/' . $id); 
        dd($tweet);
        return (object) [
            'content' => 'a b c d e f g'
        ];
    }
}
