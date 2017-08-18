<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Like extends Interaction 
{
    public $table = 'interactions';
    
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('like', function (Builder $builder) {
            $builder->where('type', 'like');
        });

        static::creating(function ($like) {
            $like->type = 'like';
        });
    }
}
