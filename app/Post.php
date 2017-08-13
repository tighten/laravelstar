<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function submitters()
    {
        return User::select('users.*')
            ->join('submissions', 'users.id', '=', 'submissions.user_id')
            ->where('submissions.post_id', $this->id);
    }

    public function getSubmittersAttribute()
    {
        return $this->submitters()->get();
    }

    public function getOriginatorAttribute()
    {
        return $this->submitters()->first();
    }

//    public function submitters()
//    {
//        return $this->hasManyThrough(User::class, Submission::class, 'post_id', 'id');
//    }

    public static function makeFromManualSubmission($request)
    {
        return app(PostFactory::class)
            ->makeFromManualSubmission($request);
    }
}
