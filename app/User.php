<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class); 
    }

    public function submittedPosts()
    {
        return $this->hasManyThrough(Submission::class, Post::class);
    }

    public function submit($post)
    {
        if ($this->submissions->contains($post)) {
            // Already submitted
            return;
        }

        $this->submissions()->firstOrCreate([
            'post_id' => $post->id
        ]);
    }
}
