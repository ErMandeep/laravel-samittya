<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Offline_student;
use App\Models\Offline_invoice;


class Offline_payments extends Model
{

	public function students()
    {
        return $this->belongsTo(Offline_student::class ,'s_id');
    }

    public function invoice()
    {
        return $this->hasOne(Offline_invoice::class ,'payment_id');
    }

}