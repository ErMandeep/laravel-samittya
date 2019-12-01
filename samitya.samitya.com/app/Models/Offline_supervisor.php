<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Offline_cities;
use App\Models\Offline_states;

use App\Models\Offline_branches;




class Offline_supervisor extends Model 
{

    public function city()
    {
        return $this->belongsTo(Offline_cities::class,'city_id');
    }

    public function branch()
    {
        return $this->belongsTo(Offline_branches::class,'branch_id');
    }
    public function state()
    {
        return $this->belongsTo(Offline_states::class,'state_id');
    }

}