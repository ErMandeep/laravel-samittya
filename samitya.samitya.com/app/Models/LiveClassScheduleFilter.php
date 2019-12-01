<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;



class LiveClassScheduleFilter extends Model
{
	protected $table = 'live_classes_schedule_filter';

	protected $fillable = ['live_class_id','user_id','mon','mon_start_time','mon_end_time','tue','tue_start_time','	tue_end_time','wed',
				'wed_start_time','wed_end_time','thur','thur_start_time','thur_end_time','fri','fri_start_time','fri_end_time','sat','sat_start_time','sat_end_time', 'sun','sun_start_time','sun_end_time'];

}