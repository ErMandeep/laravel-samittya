<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Offline_branches;
use App\Models\Offline_student;
use App\Models\Offline_states;
use App\Models\Offline_supervisor;



class Offline_cities extends Model
{
    use SoftDeletes;

 	public function branches()
 	{
 	
        return $this->hasMany(Offline_branches::class);
    }

    public function students()
    {
        return $this->hasMany(Offline_student::class);
    }

    public function state()
    {
        return $this->belongsTo(Offline_states::class ,'state_id');
    }

    public function supervisor()
    {
        return $this->hasMany(Offline_supervisor::class);
    }


}
