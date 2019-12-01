<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Offline_cities;
use App\Models\Offline_states;
use App\Models\Category;
use App\Models\Auth\User;
use App\Models\Offline_payments;
use App\Models\Offline_branches;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Auth\Traits\Attribute\UserAttribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Auth\Traits\Scope\UserScope;
use App\Models\Auth\Traits\Relationship\UserRelationship;

class Offline_student extends Authenticatable 
{
	use SoftDeletes,
        HasRoles,
        UserRelationship,
        UserScope;

	protected $table = 'offline_student';

    protected $guard_name = 'web';

    protected $fillable = ['id','student_id','supervisor','name' ,'email','joining_date','state_id','city_id','branch_id','category_id','fees','phone_no','paid_this_mnth','temp_off','fee_plan' ];

	 public function city()
    {
        return $this->belongsTo(Offline_cities::class);
    }

    public function branch()
    {
        return $this->belongsTo(Offline_branches::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class ,'category_id');
    }

    public function payments()
    {
        return $this->hasMany(Offline_payments::class ,'id');
    }

     public function state()
    {
        return $this->belongsTo(Offline_states::class);
    }

}