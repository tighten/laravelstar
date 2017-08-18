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

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array|callable  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function firstOrCreateCallback(array $attributes, $values = [])
    {
        if (! is_null($instance = self::where($attributes)->first())) {
            return $instance;
        }

        // callable check?
        if (! is_array($values)) {
            $values = $values();
        }

        return tap(self::newModelInstance($attributes + $values), function ($instance) {
            $instance->save();
        });
    }

    public function likers()
    {
        dd('@todo');
    }
}
