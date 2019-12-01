<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Bundle;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\LiveClasses;
use App\Models\Offline_student;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {

        if (\Auth::user()->can('offline_students_access')) {
             return redirect()->route('admin.offline-student.index');
        }

        $purchased_courses = NULL;
        $purchased_live_courses = NULL;
        $students_count = NULL;
        $ofline_students_count = NULL;
        $recent_reviews = NULL;
        $threads = NULL;
        $teachers_count = NULL;
        $live_teachers_count = NULL;
        $courses_count = NULL;
        $live_courses_count = NULL;
        $recent_orders = NULL;
        $recent_contacts = NULL;
        $purchased_bundles = NULL;
        $expire_courses = NULL;
        $review_course_name = NULL;

        // print_r('expression');


        // die();

        if (\Auth::check()) 
        {

            if (!auth()->user()->hasRole('administrator')) {
                $oder_id = [];   
                $unsubscribe_id = [] ;     
                $orders = Order::where('status','=',1)
                ->where('user_id','=',auth()->user()->id)
                // ->where('sub_duration','<>','0')
                ->get();
                if (count($orders) > 0 ) {
                   
                    foreach ($orders as $order) 
                    {
                        // $start  = date_create($order->created_at);
                        // $end    = date_create(); // Current time and date
                        // $diff   = date_diff( $start, $end );
                        // if ($diff->m > $order->sub_duration) 
                        // {
                            array_push($oder_id, $order->id);
                        // }
                    }
                    if ($oder_id) {
                       $courses_id = OrderItem::whereIn('order_id',$oder_id)
                        // ->where('item_type','=',"App\Models\Course")
                        ->where('subscribe','=','1')
                        ->where('duration','<>','0')
                        ->get();

                        if($courses_id){

                            foreach ($courses_id as $courses) {

                                $start  = date_create($courses->created_at);
                                $end    = date_create(); // Current time and date
                                $diff   = date_diff( $start, $end );

                                // print_r($courses);die;  

         
                                if ($diff->m > $courses->duration ) {
                                    if ($courses->item_type == 'App\Models\Course') {
                                        $courses->subscribe = 0;
                                        $courses->save();
                                        $courses_deatach = Course::findOrFail($courses->item_id);
                                        $courses_deatach->students()->detach(auth()->user()->id);
                                        array_push($unsubscribe_id, $courses->id);

                                    }
                                    if ($courses->item_type == 'App\Models\LiveClasses') {
                                        $courses->subscribe = 0;
                                        $courses->save();
                                        $courses_deatach = LiveClasses::findOrFail($courses->item_id);
                                        $courses_deatach->students()->detach(auth()->user()->id);
                                        array_push($unsubscribe_id, $courses->id);

                                    }
                                }
                            }
                        }
                        if ($unsubscribe_id) {
                            # code...
                             $expire_courses = Course::where('published','=',1)
                                        ->whereIn('id',$unsubscribe_id)
                                        ->get();
                        }
                        
                           
                    }
                }
            }
            // $expire_courses = Course::where('published','=',1)
            //                             ->whereIn('id',[2,3])
            //                             ->get();  
            
            if (!auth()->user()->hasRole('administrator')) {
                $oder_id = [];       
                $review_id = [];       
                $orders = Order::where('status','=',1)
                ->where('user_id','=',auth()->user()->id)
                ->where('sub_duration','<>','0')
                ->get();
                if (count($orders) > 0 ) {
                   
                    foreach ($orders as $order) 
                    {
                        $start  = date_create($order->created_at);
                        $end    = date_create(); // Current time and date
                        $diff   = date_diff( $start, $end );

                        
                        if( $diff->days >= 30 ){
                            array_push($oder_id, $order->id);
                        }

                    }
   

                        if ($oder_id) {
                           $courses_id = OrderItem::whereIn('order_id',$oder_id)
                            ->where('item_type','=',"App\Models\Course")
                            ->where('subscribe','=','1')
                            ->pluck('item_id');

                      
                    $course_name = Course::where('published','=',1)
                                            ->whereIn('id',$courses_id)
                                            ->get();                      

                foreach ($course_name as $key => $value) {
                if(auth()->check() && $value->reviews()->where('user_id','=',auth()->user()->id)->first()){
                
                }
                else{
                    array_push($review_id , $value->id);
                }
                }

                 if ($review_id) {
                     $review_course_name = Course::where('published','=',1)
                                            ->whereIn('id',$review_id)
                                            ->get(); 


                 }



                        }
                }

            }                      


            $purchased_courses = auth()->user()->purchasedCourses();
            $purchased_live_courses = auth()->user()->purchasedliveCourses();
            $purchased_bundles = auth()->user()->purchasedBundles();

            if(auth()->user()->hasRole('teacher') ||  auth()->user()->hasRole('live teacher')){
                //IF logged in user is teacher
                $students_count = Course::whereHas('teachers', function ($query) {
                    $query->where('user_id', '=', auth()->user()->id);
                })
                    ->withCount('students')
                    ->get()
                    ->sum('students_count');

                $live_students_count = LiveClasses::whereHas('teachers', function ($query) {
                    $query->where('user_id', '=', auth()->user()->id);
                })
                    ->withCount('students')
                    ->get()
                    ->sum('students_count');

                $live_courses = LiveClasses::where('user_id',auth()->user()->id)->get();
                $count_courses = count($live_courses);


                $courses_id = auth()->user()->courses()->has('reviews')->pluck('id')->toArray();
                $live_courses_id = auth()->user()->live_classes()->has('reviews')->pluck('id')->toArray();



                $recent_reviews = Review::whereIn('reviewable_id',$courses_id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                $recent_live_reviews = Review::whereIn('reviewable_id',$live_courses_id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();



                $unreadThreads = [];
                $threads = [];
                if(auth()->user()->threads){
                    foreach(auth()->user()->threads as $item){
                        if($item->unreadMessagesCount > 0){
                            $unreadThreads[] = $item;
                        }else{
                            $threads[] = $item;
                        }
                    }
                    $threads = Collection::make(array_merge($unreadThreads,$threads))->take(10) ;

                }

            }elseif(auth()->user()->hasRole('administrator')){
                $students_count = User::role('student')->count();
                $ofline_students_count = Offline_student::all()->count();
                $teachers_count = User::role('teacher')->count();
                $live_teachers_count = User::role('live teacher')->count();
                // $courses_count = \App\Models\Course::all()->count() + \App\Models\Bundle::all()->count();
                $courses_count = \App\Models\Course::all()->count(); 
                $live_courses_count = \App\Models\LiveClasses::all()->count();
                $recent_orders = Order::orderBy('created_at','desc')->take(10)->get();
                $recent_contacts = Contact::orderBy('created_at','desc')->take(10)->get();
            }
            elseif(auth()->user()->hasRole('live teacher')){

                $students_count = LiveClasses::whereHas('teachers', function ($query) {
                    $query->where('user_id', '=', auth()->user()->id);
                })
                    ->withCount('students')
                    ->get()
                    ->sum('students_count');
                $live_courses = LiveClasses::where('user_id',auth()->user()->id)->get();
                $count_courses = count($live_courses);


                $courses_id = auth()->user()->courses()->has('reviews')->pluck('id')->toArray();
                $recent_reviews = Review::where('reviewable_type','=','App\Models\LiveClasses')
                    ->whereIn('reviewable_id',$courses_id)
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();



                $unreadThreads = [];
                $threads = [];
                if(auth()->user()->threads){
                    foreach(auth()->user()->threads as $item){
                        if($item->unreadMessagesCount > 0){
                            $unreadThreads[] = $item;
                        }else{
                            $threads[] = $item;
                        }
                    }
                    $threads = Collection::make(array_merge($unreadThreads,$threads))->take(10) ;

                }
            }
        }


       // echo "<pre>"; print_r($live_teachers_count);die;

        return view('backend.dashboard',compact( 'ofline_students_count', 'live_teachers_count', 'live_courses_count','purchased_courses','purchased_live_courses','students_count','recent_reviews','threads','purchased_bundles','teachers_count','courses_count','recent_orders','recent_contacts','expire_courses', 'review_course_name' ,'count_courses','live_students_count' ,'recent_live_reviews' ));
    }
}
