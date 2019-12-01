<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Offline_cities;
use App\Models\Offline_student;



class Offline_branches extends Model
{
    use SoftDeletes;
   

    public function city()
    {
        return $this->belongsTo(Offline_cities::class);
    }

    public function students()
    {
        return $this->hasMany(Offline_student::class,'branch_id');
    }

    

}
