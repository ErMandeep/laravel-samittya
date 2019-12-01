<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSchedule extends Model
{
   protected $table = 'teacher_schedule';

   protected $fillable = ['event_id', 'event_start', 'event_end'];

}