<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\LiveClasses;
use App\Models\Offline_student;
use App\Models\Offline_payments;
use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Mail\OfflinePaymentMail;

use Mail;
use Illuminate\Support\Facades\DB;

class OfflineCronController extends Controller
{
    public function index()
    {   
        date_default_timezone_set("Asia/Kolkata");

        // if (date("d") == 5 ) {      

        echo "<pre>";
           $students =  Offline_student::get();

           $month = date('F Y' ,strtotime('-1 Month' )); 

           // print_r($month);die;
 

           foreach ($students as $student) {

              $payment = $student->payments()->latest()->first();
            if ($payment) {
              if ($payment->expire_on == date('F Y')) {
                    $student->paid_this_month = 0;
                    $student->save();
                }
            }

            if ($student->paid_this_month == 0 && $student->temp_off == 0) {

                if (date('F Y') == date('F Y' , strtotime($student->fee_plan.'Month' , strtotime($month)))) {
                  $payment = new Offline_payments();
                  $payment->s_id = $student->id;
                  $payment->fees = $student->fees;
                  $payment->month = $month;
                  $payment->expire_on = date('F Y' , strtotime($student->fee_plan.'Month' , strtotime($month)));
                  $payment->fee_plan = $student->fee_plan;
                  $payment->status = 0;
                  $payment->save();
                }
                  
            }
            
            if ($student->temp_off) {

                if (date('F Y') == date('F Y' , strtotime($student->fee_plan.'Month' , strtotime($month)))) {
                    $newpayment = new Offline_payments();
                    $newpayment->s_id = $student->id;
                    $payment->fees = $student->fees;
                    $newpayment->month = $month;
                    $newpayment->expire_on = date('F Y' , strtotime($student->fee_plan.'Month' , strtotime($month)));
                    $newpayment->fee_plan = $student->fee_plan;
                    $newpayment->status = 2;
                    $newpayment->save();
                }
            }

           }
           die;
                $newstudents =  Offline_student::where('paid_this_month','0')->get();
                foreach ($newstudents as $st) {

                  $content['name'] = $st->name;
                  $content['category'] = $st->category->name;
                  $content['month'] = $month;
                  Mail::to($st->email)->send(new OfflinePaymentMail($content));
                }

                

            // mail send krni hai 
        // }

    }


}

              //  $student->paid_this_month = 0;
              //  if ($student->temp_off !== 0) {
              //      // Send Mail 
              //   $content['name'] = $student->name;
              //   $content['category'] = $student->category->name;
              //   $content['month'] = date('F');
              //   Mail::to($student->email)->send(new OfflinePaymentMail($content));
              //  }
              //  else{
              //   $payment = new Offline_payments();
              //   $payment->s_id = $student->id;
              //   $payment->month = date('F');
              //   $payment->year = date('Y');
              //   $payment->temp_off = 1;
              //   $payment->save();
              //  }
              // $student->save();