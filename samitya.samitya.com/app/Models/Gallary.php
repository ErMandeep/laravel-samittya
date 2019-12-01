<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\GallaryMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Gallary extends Model
{

    use SoftDeletes;
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($lesson) { // before delete() method call this
            if($lesson->isForceDeleting()){
                $media = $lesson->media;
                foreach ($media as $item) {
                    if (File::exists(public_path('/storage/uploads/' . $item->name))) {
                        File::delete(public_path('/storage/uploads/' . $item->name));
                    }
                }
                $lesson->media()->delete();
            }

        });
    }


    /**
     * Set to null if empty
     * @param $input
     */
    // public function setCourseIdAttribute($input)
    // {
    //     $this->attributes['course_id'] = $input ? $input : null;
    // }


    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPositionAttribute($input)
    {
        $this->attributes['position'] = $input ? $input : null;
    }


    public function readTime()
    {
        $readTime = (new ReadTime($this->full_text))->toArray();
        return $readTime['minutes'];
    }

    public function course()
    {
        return $this->belongsTo(Gallary::class, 'user_id')->withTrashed();
    }

    public function test()
    {
        return $this->hasOne('App\Models\Test');
    }

    public function students()
    {
        return $this->belongsToMany('App\Models\Auth\User', 'lesson_student')->withTimestamps();
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function chapterStudents()
    {
        return $this->morphMany(ChapterStudent::class, 'model');
    }

    public function downloadableMedia()
    {
        $types = ['youtube', 'vimeo', 'upload', 'embed', 'lesson_pdf', 'lesson_audio'];

        return $this->morphMany(Media::class, 'model')
            ->whereNotIn('type', $types);
    }


    public function mediaVideo()
    {
        $types = ['youtube', 'vimeo', 'upload', 'embed'];
        return $this->morphOne(Media::class, 'model')
            ->whereIn('type', $types);

    }

    public function new_media()
    {
        return $this->hasMany(GallaryMedia::class,'model_id');
    }

    // public function mediaPDF()
    // {
    //     return $this->morphOne(Media::class, 'model')
    //         ->where('type', '=', 'lesson_pdf');
    // }

    // public function mediaAudio()
    // {
    //     return $this->morphOne(Media::class, 'model')
    //         ->where('type', '=', 'lesson_audio');
    // }

    // public function courseTimeline()
    // {
    //     return $this->morphOne(CourseTimeline::class, 'model');
    // }

    // public function isCompleted()
    // {
    //     $isCompleted = $this->chapterStudents()->where('user_id', \Auth::id())->count();
    //     if ($isCompleted > 0) {
    //         return true;
    //     }
    //     return false;

    // }


    public function user(){
        return $this->belongsTo(User::class);
    }




}
