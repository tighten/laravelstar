<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TwitterOAuth::class, function () {
            return new TwitterOAuth(
                config('services.twitter.consumer_key'),
                config('services.twitter.consumer_secret'),
                config('services.twitter.access_token'),
                config('services.twitter.access_secret')
            );
        });
    }
}
