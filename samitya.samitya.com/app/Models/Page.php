<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class Page extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($page) { // before delete() method call this
            if ($page->isForceDeleting()) {
                if (File::exists(public_path('/storage/uploads/' . $page->image))) {
                    File::delete(public_path('/storage/uploads/' . $page->image));
                }
            }
        });

    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
