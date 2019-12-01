<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class MessageReport extends Model
{
	protected $table = 'message_report';

	protected $fillable = ['report_for','report_by','can_message','ignore_user'];

	
}