<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{


    public function blogs()
    {
        return $this->morphedByMany(Blog::class, 'taggable');
    }

    public function courses()
    {
        return $this->morphedByMany(Course::class, 'taggable');
    }

}
