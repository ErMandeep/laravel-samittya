<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Offline_cities;
use App\Models\Offline_student;



class Offline_states extends Model
{
    use SoftDeletes;

    protected $table = 'offline_states';

    protected $fillable = ['name'];
   

    public function cities()
    {
        return $this->hasMany(Offline_cities::class ,'city_id');
    }

    

}
