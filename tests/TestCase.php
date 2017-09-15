<?php

namespace Tests;

use App\Services\Twitter\Client as Twitter;
use Faker\Generator as Faker;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;
use Mockery as m;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $baseUrl = 'http://localhost/';

    protected function mockTwitter()
    {
        $faker = app(Faker::class);

        $this->fakeTweet = (object) [
            'id' => 805940005487132672,
            'text' => 'abc',
            'user' => (object) [
                'id' => 14280918,
                'screen_name' => $faker->userName,
                'name' => $faker->name,
                'url' => 'http://replaceme.com/',
                'entities' => (object) [
                    'url' => (object) [
                        'urls' => [
                            (object) [
                                'url' => 'http://replaceme.com/',
                                'expanded_url' => 'http://replaced.com/',
                            ]
                        ]
                    ]
                ]
            ],
        ];

        $twitter = m::mock(Twitter::class)->shouldIgnoreMissing();
        $twitter->shouldReceive('getTweet')
            ->andReturn($this->fakeTweet);
        
        $this->twitter = $twitter;
        
        return $twitter;
    }
}
