<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;



class TrialClassScheduleReview extends Model
{
	protected $table = 'trial_classes_schedule_review';

	protected $fillable = ['trial_class_review_id','user_id','mon','trial_mon_start_time','tue','trial_tue_start_time','wed',
				'trial_wed_start_time','thur','trial_thur_start_time','fri','trial_fri_start_time','sat','trial_sat_start_time', 'sun','trial_sun_start_time'];

}