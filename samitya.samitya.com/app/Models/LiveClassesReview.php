<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;



class LiveClassesReview extends Model
{
	use SoftDeletes;

	protected $table = 'live_classes_review';

	protected $fillable = ['category_id', 'title', 'slug','requirement','description', 'price', 'course_image', 'published', 'featured', 'trending', 'popular', 'meta_title', 'meta_description', 'meta_keywords','skills_level','language_used','videolink', 'per_batch','avg_price','live_class_id','for_review','sub_price_1','sub_month_1','sub_price_2','sub_month_2','sub_price_3','sub_month_3','trial_price', 'premium' ,'budget'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'live_class_user_review')->withPivot('user_id');
    }

    public function scopeOfTeacher($query)
    {
        if (!Auth::user()->isAdmin()) {
            return $query->whereHas('teachers', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $query;
    }

    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

}