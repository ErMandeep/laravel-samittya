<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;



class TrialClassSchedule extends Model
{
	protected $table = 'trial_classes_schedule';

	protected $fillable = ['trial_class_id','user_id','mon1','trial_mon_start_time','tue1','trial_tue_start_time','wed1',
				'trial_wed_start_time','thur1','trial_thur_start_time','fri1','trial_fri_start_time','sat1','trial_sat_start_time', 'sun1','trial_sun_start_time'];

}