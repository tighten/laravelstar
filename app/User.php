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

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function like($post)
    {
        return $this->likes()->create([
            'post_id' => $post->id,
        ]);
    }

    public function reads()
    {
        return $this->hasMany(Read::class);
    }

    public function read($post)
    {
        return $this->reads()->create([
            'post_id' => $post->id,
        ]);
    }

    // @todo can this be a relationship instead of this janky thing?
    public function unreadPosts()
    {
        return Post::whereNotIn('id', $this->reads()->pluck('post_id'));
    }

    public function getUnreadPostsAttribute()
    {
        return $this->unreadPosts()->get();
    }
}
