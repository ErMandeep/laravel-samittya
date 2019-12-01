<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;
use App\Models\Tag;
use App\Models\Faq;
use View;

class CoursesController extends Controller
{

    private $path;

    public function __construct()
    {


        $cat_menu = Category::where('status', '=', 1)->where('parent_id', '=', '0')->get();
        $sub = Category::where('parent_id', '<>', '0')->get();
        View::share('cat_menu' , $cat_menu);
        View::share('sub' , $sub);

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
    }

    public function all()
    {
        if (request('type') == 'popular') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'trending') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else if (request('type') == 'featured') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(9);

        } else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(9);
        }
        $purchased_courses = NULL;
        $purchased_bundles = NULL;
        if (\Auth::check()) {
            $purchased_courses = Course::withoutGlobalScope('filter')->whereHas('students', function ($query) {
                $query->where('id', \Auth::id());
            })
                ->with('lessons')
                ->orderBy('id', 'desc')
                ->get();
        }
        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        return view( $this->path.'.courses.index', compact('courses', 'purchased_courses', 'recent_news','featured_courses'));
    }


    public function allshow()
    {

if (request('sort')) {


            // $query =  Course::query();
            $query =  Course::withoutGlobalScope('filter');

        if (request('language')) {
            $query->where('language_used', '=', request('language'));
        }

        if (request('free')) {
            $query->where('free', '=', request('free'));
        }        
  
     if (request('start') && request('last')) {
  
        $first = 'sub_price_1 >= '. request('start');
        $second = 'sub_price_1 <= '. request('last');

     $query->whereRaw($first)->whereRaw($second);
   
        } 
 
        if(request('rating')){
            $query->leftJoin('reviews', 'reviews.reviewable_id', '=', 'courses.id')
            ->Where('rating', request('rating'))
            ->Where('reviewable_type', '=', 'App\Models\course');
            
        }  

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
       // print_r($query->toSql()); die;
       // print_r($courses); die;
}



 else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('reviewchanges', 1)->orWhereNull('reviewchanges')->orderBy('id', 'desc')->paginate(9);


        }
        $purchased_courses = NULL;
        $purchased_bundles = NULL;
        if (\Auth::check()) {
            $purchased_courses = Course::withoutGlobalScope('filter')->whereHas('students', function ($query) {
                $query->where('id', \Auth::id());
            })
                ->with('lessons')
                ->orderBy('id', 'desc')
                ->get();
        }
        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();
            $categories = Category::where('status', '=', 1)->pluck('name', 'id');
        $languages = Course::groupBy('language_used')->get()->pluck('language_used', 'id');
        $prices = Course::distinct('avg_price')->get()->pluck('avg_price');
        $popular_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)->where('category_id','<>',env('live_class_id'))
    ->where('popular', '=', 1)->take(9)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        // echo "<pre>"; print_r($courses);die;
        return view( $this->path.'.courses.regularcourse', compact('categories' ,'courses', 'purchased_courses', 'recent_news','featured_courses', 'languages', 'prices', 'popular_courses'));
    }



    public function show($course_slug)
    {
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course = Course::withoutGlobalScope('filter')->where('slug', $course_slug)->with('publishedLessons')->firstOrFail();
        $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0 ;
        $course_rating = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
        $teacher = User::role('teacher')->where('id', '=', $course->teachers[0]['id'])->first();

        if(auth()->check() && $course->reviews()->where('user_id','=',auth()->user()->id)->first()){
            $is_reviewed = true;
        }
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }
        $lessons = $course->courseTimeline()->orderby('sequence','asc')->get();

        if (\Auth::check()) {

            $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
            $continue_course  = $course->courseTimeline()->orderby('sequence','asc')->whereNotIn('model_id',$completed_lessons)->first();
            if($continue_course == ""){
                $continue_course = $course->courseTimeline()->orderby('sequence','asc')->first();
            }

        }
    $livecourses = Course::where('category_id','=',env('live_class_id'))->where('id','<>',$course->id)->where('published', '=', 1)->get();
    $faqs = Faq::where('category_id','=',env('live_class_id'))->get();

        return view( $this->path.'.courses.course', compact('course', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons','total_ratings','is_reviewed','lessons','continue_course','teacher','livecourses','faqs'));
    }


    public function rating($course_id, Request $request)
    {
        $course = Course::findOrFail($course_id);
        $course->students()->updateExistingPivot(\Auth::id(), ['rating' => $request->get('rating')]);

        return redirect()->back()->with('success', 'Thank you for rating.');
    }

    public function getByCategory(Request $request)
    {

if (request('sort')) {

    return $this->allshow();

}  else{
    
        $category = Category::where('slug', '=', $request->category)->first();
        
        if ($category != "") {
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $featured_courses = Course::where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            $courses = $category->courses()->where('published', '=', 1)->paginate(9);
            $faqs = Faq::where('category_id','=',env('live_class_id'))->get();
            $courses = (count($courses) == 0) ? 'empty' : $courses;
            return view( $this->path.'.courses.index', compact('courses', 'category', 'recent_news','featured_courses','faqs'));
        }
        return abort(404);
}      
    }

    public function addReview(Request $request)
    {
        $this->validate($request, [
            'review' => 'required'
        ]);
        $course = Course::findORFail($request->id);
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $course->id;
        $review->reviewable_type = Course::class;
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
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
            $course_rating = 0;
            $total_ratings = 0;
            $lessons = $course->courseTimeline()->orderby('sequence','asc')->get();
            $teacher = User::role('teacher')->where('id', '=', $course->teachers[0]['id'])->first();

            if ($course->reviews->count() > 0) {
                $course_rating = $course->reviews->avg('rating');
                $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
            }
            if (\Auth::check()) {

                $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
                $continue_course  = $course->courseTimeline()->orderby('sequence','asc')->whereNotIn('model_id',$completed_lessons)->first();
                if($continue_course == ""){
                    $continue_course = $course->courseTimeline()->orderby('sequence','asc')->first();
                }

            }
            return view( $this->path.'.courses.course', compact('course', 'purchased_course', 'recent_news','completed_lessons','continue_course', 'course_rating', 'total_ratings','lessons', 'review','teacher'));
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

            return redirect()->route('courses.show', ['slug' => $review->reviewable->slug]);
        }
        return abort(404);

    }

    public function deleteReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $slug = $review->reviewable->slug;
            $review->delete();
            return redirect()->route('courses.show', ['slug' => $slug]);
        }
        return abort(404);
    }
    public function getByTag(Request $request)
    {
        

        $tags = Tag::where('slug', '=', $request->tag)->first();
        if ($tags != "") {
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $featured_courses = Course::where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            $courses = $tags->courses()->where('published', '=', 1)->paginate(9);
            return view( $this->path.'.courses.index', compact('courses', 'tags', 'recent_news','featured_courses'));
        }
        return abort(404);
    }
}
