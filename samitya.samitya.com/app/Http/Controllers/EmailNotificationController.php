<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\LiveClasses;
use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Bundle;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\TrialPayment;
use App\Models\EmailNotification;
use App\Models\LiveClassSchedule;
use App\Mail\OfflineOrderMail;
use App\Mail\ClassNotificationUserMail;
use App\Mail\TrialMail;
use Mail;
use Illuminate\Support\Facades\DB;

class EmailNotificationController extends Controller
{



    public function index()
    {  
        date_default_timezone_set("Asia/Kolkata");

        ////////     Email Notification For Live Classes 
        $order_id = [];   
        $unsubscribe_id = [] ;     
        $orders = EmailNotification::where('subscribe','=',1)->where('item_type','App\Models\LiveClasses')
                 ->Join('live_classes_schedule','live_classes_schedule.live_class_id', '=', 'email_notification.item_id')
                 ->get();

			$days = ['Monday'=> 'mon','Tuesday' =>'tue','Wednesday' =>'wed','Thursday' =>'thur','Friday' =>'fri','Saturday' => 'sat','Sunday' => 'sun'];

			
        	foreach ($orders as $order ) 
            {
                foreach ($days as $key => $value) {
                    if (date("l") ==  $key && $order->{$value} && $order->{$value.'_start_time'} ) {

                        $date= date('Y-m-d H:i:s',strtotime($order->{$value.'_start_time'}));
                        $start  = date_create($date);
                        $end    = date_create(); // Current time and date
                        $diff   = date_diff( $start, $end );
                        if ($diff->h <= '1') {
                            $user = User::findOrFail($order->users_id);
                            $teacher = User::findOrFail($order->teacher_id);
                            $course = LiveClasses::findOrFail($order->item_id);

                            $content['user'] = $user->first_name.''.$user->last_name;
                            $content['teacher'] =  $teacher->first_name.''.$teacher->last_name;
                            $content['course'] =   $course->title;
                            $content['time'] = $order->{$value.'_start_time'};

                                  Mail::to($user->email)->send(new ClassNotificationUserMail($content));

                            $order->mail_send = 1;
                            $order->save();
                        }
                        elseif ($diff->h >= '2') {
                            $order->mail_send = 0;
                            $order->save();
                        }  
                    } 
                }   
        	}
        ////////     Email Notification For Live Classes 




        ////////     Email Notification For Trial Lesson

        	$payments =	TrialPayment::where('pending','1')->get();

        		foreach ($payments as $payment) {
        			$time = explode('_', $payment->time);
        			$day = $time[0];

			        if (date('l') == $day) {
			        	$datetime1 = date_create($time[1]);
				        $datetime2 = date_create(date('Y-m-d'));
				        $diff = date_diff($datetime1, $datetime2);
				        if ($diff->h == 0 && $diff->i < 10 ) {

				        	$user  = User::find($payment->user_id);
				        	$teacher  = User::find($payment->teacher_id);

				        	$content['user'] = $user->name;
				        	$content['teacher'] = $teacher->name;

				        	Mail::to($user->email)->send(new TrialMail($content));

				        	$payment->pending = 0;
				        	$payment->save();
				        }

			        }
        		}


        ////////    Email Notification For Trial Lesson 





/////////  Delete all Messages after 21 days 
    $messages = DB::table('messages')->get();

      foreach ($messages as $message ) {
        $datetime1 = date_create($message->created_at);
        $datetime2 = date_create(date('Y-m-d'));
        $interval = date_diff($datetime1, $datetime2);

        if ($interval->d >= 21) {
          if (strpos($message->body, 'audio-') !== false) {
           unlink(storage_path('app/public/messages/'.$message->body));      
          }
          $messages_delete = DB::table('messages')->where('id',$message->id)->delete();
        }
      }
/////////  Delete all Messages after 21 days 


////////////// Unsubscribe Live Classes
        $oder_id = [];   
        $unsubscribe_id = [] ;     
        $courses_id = OrderItem::where('item_type','=',"App\Models\LiveClasses")
                      ->where('subscribe','=','1')
                      ->where('duration','<>','0')
                      ->leftJoin('orders','orders.id','order_items.order_id')
                      ->get();

                if($courses_id)
                {
                    foreach ($courses_id as $courses) 
                    {
                        $start  = date_create($courses->created_at);
                        $end    = date_create(); // Current time and date
                        $diff   = date_diff( $start, $end );
                        if ($diff->m > $courses->duration ) 
                        {
                            $courses->subscribe = 0;
                            $courses->save();
                            $courses_deatach = LiveClasses::findOrFail($courses->item_id);
                            $courses_deatach->students()->detach($courses->user_id);
                            array_push($unsubscribe_id, $courses->id);
                        }
                    }
                }
        $orders = EmailNotification::where('subscribe','=',1)->where('item_type','App\Models\LiveClasses')->get();

        		foreach ($orders as $order) {
        				$start  = date_create($order->created_at);
                        $end    = date_create(); // Current time and date
                        $diff   = date_diff( $start, $end );
                        if ($diff->m > $order->duration ) 
                        {
                            $order->subscribe = 0;
                            $order->save();
                        }
        		}
////////////// Unsubscribe Live Classes

    }


}