<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;



class TrialPayment extends Model
{
	protected $table = 'trial_payment';

	protected $fillable = ['user_id','teacher_id','time','pending','razorpay_payment_id'];

}