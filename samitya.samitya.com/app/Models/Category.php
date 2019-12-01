<?php

namespace App\Models;

use App\Models\Course;
use App\Models\LiveClasses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function courses(){
        return $this->hasMany(Course::class);
    }

    public function liveclasses(){
        return $this->hasMany(LiveClasses::class);
    }

    public function blogs(){
        return $this->hasMany(Blog::class);
    }

    public function faqs(){
        return $this->hasMany(Faq::class);
    }


}
