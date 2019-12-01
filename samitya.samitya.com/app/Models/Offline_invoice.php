<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Offline_student;
use App\Models\Offline_payments;


class Offline_invoice extends Model
{

	public function student()
    {
        return $this->belongsTo(Offline_student::class ,'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(Offline_payments::class ,'payment_id');
    }

}