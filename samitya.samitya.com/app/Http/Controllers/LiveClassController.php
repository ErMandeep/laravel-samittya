<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use App\Models\LiveClasses;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;
use App\Models\Tag;
use App\Models\Faq;
use App\Models\LiveClassSchedule;
use App\Models\LiveClassScheduleFilter;
use App\Models\TrialClassSchedule;
use App\Models\TrialPayment;
use App\Mail\TrialPaymentMail;
use View;
use DB;

class LiveClassController extends Controller
{

     public function __construct()
    {
        $path = 'frontend';
        if(session()->has('display_type')){
            if(session('display_type') == 'rtl'){
                $path = 'frontend-rtl';
            }else{
                $path = 'frontend';
            }
        }else if(config('app.display_type') == 'rtl'){
            $path = 'frontend-rtl';
        }
        $this->path = $path;

        $cat_menu = Category::where('status', '=', 1)->where('parent_id', '=', '0')->get();
        $sub = Category::where('parent_id', '<>', '0')->get();
        View::share('cat_menu' , $cat_menu);
        View::share('sub' , $sub);
    }
    

    public function all()
    {
        
        if (request('type') == 'popular') {
            $courses = LiveClasses::withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'trending') {
            $courses = LiveClasses::withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'featured') {
            $courses = LiveClasses::withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else {
            $courses = LiveClasses::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'asc')->paginate(6);
            $courses_two = LiveClasses::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(6);

        }
        $purchased_courses = true;
        $purchased_bundles = NULL;
        // if (\Auth::check()) {
        //     $purchased_courses = LiveClasses::withoutGlobalScope('filter')->whereHas('students', function ($query) {
        //         $query->where('id', \Auth::id());
        //     })
        //         ->with('lessons')
        //         ->orderBy('id', 'desc')
        //         ->get();
        // }
        $featured_courses = LiveClasses::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course_tags = Tag::get();
        // print_r($courses);die;
        return view( $this->path.'.live.index', compact('courses', 'purchased_courses', 'recent_news','featured_courses','courses_two','course_tags'));
    }


    public function alllive()
    {



      // day request

if (request('sort') ) {


$query =  LiveClasses::query();
    // $query = DB::table('live_classes');

     if (request('start') && request('last')) {
     // die("333");

        $first = 'sub_price_1 >= '. request('start');
        $second = 'sub_price_1 <= '. request('last');

     $query->whereRaw($first)->whereRaw($second);
   
        } 

     if(request('rating')){
            // die("&rating=5&start=150&last=1264");
        $query
        ->leftJoin('reviews', 'reviews.reviewable_id', '=', 'live_classes.id')
        ->Where('rating', request('rating'))
        ->Where('reviewable_type', '=', 'App\Models\LiveClasses');

            
        }  



       if (request('time') || request('day')) {

  $query->leftJoin('live_classes_schedule_filter', 'live_classes_schedule_filter.live_class_id', '=', 'live_classes.id');

        if(request('time')){

        $day = '';
        $time = '';

        $time = request('time');
        $time = explode(',', $time);

        $filtertime = [];
        $filterday = [];

        if (in_array('morning', $time))
        {
        $data = array('first' => '06:00' ,'last' => '09:00', 'firstquery' => '_start_time');
        
        array_push($filtertime, $data);

        }
        if (in_array('thesaurus', $time))
        {

        $data = array('first' => '09:00' ,'last' => '12:00', 'firstquery' => '_start_time');
     
        array_push($filtertime, $data);
    

        }
 
        if (in_array('afternoon', $time))
        {

        $data = array('first' => '12:00' ,'last' => '15:00', 'firstquery' => '_start_time', 'lastquery' => '_start_time <= 15:00');
      
        array_push($filtertime, $data);

        }      
        if (in_array('gloaming', $time))
        {

        $data = array('first' => '15:00' ,'last' => '18:00', 'firstquery' => '_start_time >= 15:00', 'lastquery' => '_start_time <= 18:00');
 
        array_push($filtertime, $data);
       


        }
        if (in_array('evening', $time))
        {
        $data = array('first' => '18:00' ,'last' => '21:00','firstquery' => '_start_time >= 18:00', 'lastquery' => '_start_time <= 21:00');

        array_push($filtertime, $data);
        


        }
        if (in_array('sunset', $time))
        {
        $data = array('first' => '21:00' ,'last' => '24:00','firstquery' => '_start_time >= 21:00', 'lastquery' => '_start_time <= 24:00');
       
        array_push($filtertime, $data);
        


        }                                      
        if (in_array('night', $time))
        {
        $data = array('first' => '24:00' ,'last' => '03:00','firstquery' => '_start_time >= 24:00', 'lastquery' => '_start_time <= 03:00' );
        
        array_push($filtertime, $data);
        


        } 
        if (in_array('darkness', $time))
        {
        $data = array('first' => '03:00' ,'last' => '06:00', 'firstquery' => '_start_time >= 03:00', 'lastquery' => '_start_time <= 06:00');
          
        array_push($filtertime, $data);
        


        }                  
}

 if (request('day')) {

    $day = request('day');
    $days = explode(',', $day);

    $querydays = []; 


    $querydays['mon'] = 1;

	if (in_array('mon', $days))
	{	
        
        $querydays['mon'] = 1;
       

	}

	if (in_array('tue', $days))
	{	
        $querydays['tue'] = 1;
        
	}

	if (in_array('wed', $days))
	{	
        $querydays['wed'] = 1;
  
	}
	if (in_array('thur', $days))
	{
        $querydays['thur'] = 1;
	}
	if (in_array('fri', $days))
	{    
        $querydays['fri'] = 1;

	}
	if (in_array('sat', $days))
	{	
        $querydays['sat'] = 1;
         
	}
	if (in_array('sun', $days))
	{
        $querydays['sun'] = 1;
       	
	}		



$query->where(function($query) use ($querydays) {
    $query->orwhere(      
$querydays
 );
});



}
   

if(!empty($day) && empty($time) ){

 $courses = $query->paginate(9);

$courses = (count($courses) == 0) ? 'empty' : $courses;

}

else if (!empty($time) && empty ($day)){
 $days = [ 'mon' ,'tue' ,'wed','thur', 'fri' ,'sat' ,'sun' ];   
$count = 0;
$a = count($filtertime);
$a1 = count($days);
$a2 = $a * $a1;

 foreach ($days as $da) {
foreach ($filtertime as $tym) {
 $demo = $tym['first']; 
 $demo2 = $tym['last']; 


  if ($count == 0) {

    $v = $da."_start_time between '". $demo ."' and '". $demo2."' or ";
 }
 elseif($a2 == $count+1){
    $v .= "".$da."_start_time between '". $demo ."' and  '". $demo2."'";
 }
 else{
     $v .= "".$da."_start_time between '". $demo ."' and '". $demo2."' or ";
 }


$count++;
}

}

 $query->Where(function($query) use ($v)  {
     
        $query->WhereRaw(      
            $v
 );
});

}      

else if (!empty($time) && !empty($day)){

$count = 0;

$a = count($filtertime);
$a1 = count($days);
$a2 = $a * $a1;


$keys = array_keys($filtertime);
$lastKey = $keys[count($keys)-1];

  foreach ($days as $da) {



foreach ($filtertime as $tym) {


 $demo = $tym['first']; 
 $demo2 = $tym['last']; 

  if ($count == 0) {
    if ($a1 ==  1 && $a == 1) {
     
    $v = $da."_start_time between '". $demo ."' and '". $demo2."' ";
}else{
    

    $v = $da."_start_time between '". $demo ."' and '". $demo2."' or ";
}
 }
 elseif($a2 == $count+1){
    $v .= "".$da."_start_time between '". $demo ."' and  '". $demo2."'";
 }
 else{
     $v .= "".$da."_start_time between '". $demo ."' and '". $demo2."' or ";
 }


$count++;
}


}
 $query->Where(function($query) use ($v)  {
     
        $query->WhereRaw(      
            $v
 );
});


}    


     if (request('language')) {
        $language = request('language');


$query->where('language_used', '=', $language);
        } 





    }
  // ************************************new****************************************************

        if(request('budgets')){
            if(request('budgets') == 'budget'){
             $query->where('budget', '=', 1);  
         }elseif (request('budgets') == 'premium') {
            $query->where('premium', '=', 1);  
        }
        
    }  
    if(request('skill')){
      
        $query->where('skills_level', '=', request('skill'));
        
    }  




        $courses = $query->paginate(9);

        $courses = (count($courses) == 0) ? 'empty' : $courses;
 
}

         else {
            $courses = LiveClasses::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'asc')->paginate(6);
            $courses_two = LiveClasses::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(6);

        }
        $purchased_courses = NULL;
        $purchased_bundles = NULL;

        $languages = LiveClasses::groupBy('language_used')->get()->pluck('language_used');
        $prices = LiveClasses::distinct('avg_price')->get()->pluck('avg_price');
        // if (\Auth::check()) {
            // $purchased_courses = LiveClasses::withoutGlobalScope('filter')->whereHas('students', function ($query) {
            //     $query->where('id', \Auth::id());
            // })
                // ->with('lessons')
                // ->orderBy('id', 'desc')
                // ->get();
        // }
        $featured_courses = LiveClasses::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course_tags = Tag::get();
        return view( $this->path.'.live.coursesindex', compact('courses', 'purchased_courses', 'recent_news','featured_courses','courses_two','course_tags', 'languages', 'prices'));
    }    

    public function show($course_slug)
    {

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course = LiveClasses::withoutGlobalScope('filter')->where('slug', $course_slug)->firstOrFail();
        $purchased_course = Null ;
        if (\Auth::check()) {
        $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0 ;

        $user =	User::where('id',auth()->user()->id);
        }


        $total_purchase = false;
        $course_rating = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
        $is_trial_taken = false;
        $teacher = User::role('live teacher')->where('id', '=', $course->teachers[0]['id'])->first();

        if(auth()->check() && $course->reviews()->where('user_id','=',auth()->user()->id)->first()){
            $is_reviewed = true;
        }	
        if (auth()->check() && TrialPayment::where('user_id',auth()->user()->id)->where('teacher_id',$teacher->id)->first() ) {
        	$is_trial_taken = true;
        }
        if ($course->students()->count() >= $course->per_batch) {
            $total_purchase = true;
        }


        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }

        $othercourses = LiveClasses::where('id','<>',$course->id)->where('published', '=', 1)->get();
        $faqs = Faq::where('category_id','=',env('live_class_id'))->get();
        $schedule = LiveClassSchedule::where('live_class_id',$course->id)->get();
        $trail_schedule = TrialClassSchedule::where('trial_class_id',$course->id)->get();
        

        return view( $this->path.'.live.course', compact('course', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons','total_ratings','is_reviewed','lessons','continue_course','teacher','othercourses','faqs','schedule','trail_schedule','is_trial_taken','total_purchase'));
    }

    public function getByCategory(Request $request)
    {
        $category = Category::where('slug', '=', $request->category)->first();
        
        if ($category != "") {


if(request('sort')){
    return $this->alllive();
}else{


            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $featured_courses = Course::where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            $courses = $category->courses()->where('published', '=', 1)->paginate(9);
            $courses = LiveClasses::where('category_id',$category->id)->where('published', '=', 1)->paginate(9);
            $faqs = Faq::where('category_id','=',env('live_class_id'))->get();




            $purchased_courses = NULL;
            $purchased_bundles = NULL;

            $languages = LiveClasses::groupBy('language_used')->get()->pluck('language_used');
            $prices = LiveClasses::distinct('avg_price')->get()->pluck('avg_price');            
            $featured_courses = LiveClasses::withoutGlobalScope('filter')->where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $course_tags = Tag::get();

            $courses = (count($courses) == 0) ? 'empty' : $courses;


            return view( $this->path.'.live.coursesindex', compact( 'course_tags' ,'recent_news' ,'featured_courses','prices' ,'languages' ,'courses', 'category', 'recent_news','featured_courses','faqs'));
        
        }
        }
        return abort(404);
    }

    

    public function addReview(Request $request)
    {
        $this->validate($request, [
            'review' => 'required'
        ]);
        $course = LiveClasses::findORFail($request->id);
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $course->id;
        $review->reviewable_type = LiveClasses::class;
        $review->rating = $request->rating;
        $review->content = $request->review;
        $review->save();

        return back();
    }
    public function editReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $course = $review->reviewable;
            $purchased_course = "";
            $course_rating = 0;
            $total_ratings = 0;
            $is_reviewed = true;
            $teacher = User::role('live teacher')->where('id', '=', $course->teachers[0]['id'])->first();

        
            if ($course->reviews->count() > 0) {
                $course_rating = $course->reviews->avg('rating');
                $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
            }
            $othercourses = LiveClasses::where('id','<>',$course->id)->where('published', '=', 1)->get();
        $faqs = Faq::where('category_id','=',env('live_class_id'))->get();
        $schedule = LiveClassSchedule::where('live_class_id',$course->id)->get();
        $trail_schedule = TrialClassSchedule::where('trial_class_id',$course->id)->get();
       
            
            return view( $this->path.'.live.course', compact('course', 'purchased_course', 'recent_news','completed_lessons','continue_course', 'course_rating', 'total_ratings','lessons', 'review','teacher','othercourses','faqs','schedule','trail_schedule','is_reviewed'));
        }
        return abort(404);

    }


    public function updateReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $review->rating = $request->rating;
            $review->content = $request->review;
            $review->save();

            return redirect()->route('liveclass.show', ['slug' => $review->reviewable->slug]);
        }
        return abort(404);

    }

    public function deleteReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $slug = $review->reviewable->slug;
            $review->delete();
            return redirect()->route('liveclass.show', ['slug' => $slug]);
        }
        return abort(404);
    }


    public function selected_time(Request $request)
    {
        session()->put('selected_time', $request->time);
        session()->put('selected_course', $request->course);

        return  route('trialpayment') ;
    }

    public function trial_payment(Request $request){
            
        if (session()->get('selected_time')) {
            $selected_time = explode('_', session()->get('selected_time'));     
            $day = $selected_time[0];
            $time = $selected_time[1];
            $date = date('d M', strtotime("next ".$day));

            $course = LiveClasses::findORFail(session()->get('selected_course')); 
            $teacher = User::role('live teacher')->where('id', '=', $course->teachers[0]['id'])->first();

            return view($this->path.'.live.trialpayment',compact('course' , 'day','time','teacher','date'));
        }
        return abort(404);
        
    } 

    public function trial_confirm(Request $request)
    {

    	$trial_payment = new TrialPayment;

    	$trial_payment->user_id  = auth()->user()->id;

    	$course = LiveClasses::findORFail(session()->get('selected_course')); 
        $teacher = User::role('live teacher')->where('id', '=', $course->teachers[0]['id'])->first();

    	$trial_payment->teacher_id = $teacher->id;
    	$trial_payment->time = session()->get('selected_time');
    	$trial_payment->pending = 1;
    	$trial_payment->razorpay_payment_id = $request->razorpay_payment_id;

    	$trial_payment->save();

    	session()->forget('selected_course');
    	session()->forget('selected_time');

    	$content['courese_name'] = $course->title;

    	try {
            \Mail::to(Auth()->user()->email)->send(new TrialPaymentMail($content));
        } catch (\Exception $e) {
            
        }

        \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            return redirect()->route('status');
    }






// ****************************************** filter codes


// controller part

public function indeccccx(Request $request)
{
    $sortBy = 'id';
    $orderBy = 'desc';
    $perPage = 20;
    $q = null;

    if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
    if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
    if ($request->has('perPage')) $perPage = $request->query('perPage');
    if ($request->has('q')) $q = $request->query('q');

    $users = User::search($q)->orderBy($sortBy, $orderBy)->paginate($perPage);
    return view('users.index', compact('users', 'orderBy', 'sortBy', 'q', 'perPage'));
}


// modal part
public function scopeSearch($query, $q)
{
    if ($q == null) return $query;
    return $query
            ->where('name', 'LIKE', "%{$q}%")
            ->orWhere('email', 'LIKE', "%{$q}%")
            ->orWhere('website', 'LIKE', "%{$q}%")
            ->orWhere('twitter', 'LIKE', "%{$q}%")
            ->orWhere('notes', 'LIKE', "%{$q}%");
}





}









