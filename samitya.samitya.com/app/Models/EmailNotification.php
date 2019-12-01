<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{


    protected $table = 'email_notification' ;

    protected $fillable = ['item_type' ,'users_id','teacher_id','item_id','duration','subscribe','mail_send','review_mail' ];
}
