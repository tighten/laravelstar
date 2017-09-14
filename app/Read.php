<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Read extends Interaction 
{
    public $table = 'interactions';
    
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('read', function (Builder $builder) {
            $builder->where('type', 'read');
        });

        static::creating(function ($read) {
            $read->type = 'read';
        });
    }
}
