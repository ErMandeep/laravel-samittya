<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Auth\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseReview;
use App\Models\CourseTimeline;
use function foo\func;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tag;
use App\Models\LiveClasses;
use App\Models\LiveClassesReview;
use App\Models\LiveClassSchedule;
use App\Models\LiveClassScheduleFilter;
use App\Models\LiveClassScheduleReview;
use App\Models\TrialClassSchedule;
use App\Models\TrialClassScheduleReview;
use Auth;

class LiveClassController extends Controller
{
use FileUploadTrait;
     /**
     * Display a listing of Live Classes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Gate::allows('live_class_access') && !auth()->user()->hasRole('administrator') ) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (!Gate::allows('live_class_access') && !auth()->user()->hasRole('administrator') ) {
                return abort(401);
            }
            $courses = LiveClasses::onlyTrashed()->ofTeacher()->get();
        } else {
            $courses = LiveClasses::ofTeacher()->get();
        }

        

        return view('backend.live.index', compact('courses'));
    }

        /**
     * Display a listing of live Classes via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {

        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $courses = "";
        $CourseReview = "";

        if (request('show_deleted') == 1) {
            // if (!Gate::allows('course_delete')) {
            //     return abort(401);
            // }
            $courses = LiveClasses::onlyTrashed()
                ->where('is_review','1')
                ->whereHas('category')
                ->ofTeacher()->orderBy('created_at', 'desc')->get();

        } else if (request('teacher_id') != "") {
            $id = request('teacher_id');
            $courses = LiveClasses::ofTeacher()
                ->whereHas('category')
                ->where('is_review','1')
                ->whereHas('teachers', function ($q) use ($id) {
                    $q->where('course_user.user_id', '=', $id);
                })->orderBy('created_at', 'desc')->get();
        } else if (request('cat_id') != "") {
            $id = request('cat_id');
            $courses = LiveClasses::ofTeacher()
                ->whereHas('category')
                ->where('is_review','1')
                ->where('category_id', '=', $id)->orderBy('created_at', 'desc')->get();
        } else {
            $courses = LiveClasses::ofTeacher()
                ->whereHas('category')
                ->where('is_review','1')
                ->orderBy('created_at', 'desc')->get();
        }


        if (Gate::allows('live_class_view') || auth()->user()->hasRole('administrator')) {
            $has_view = true;
        }
        if (Gate::allows('live_class_edit') || auth()->user()->hasRole('administrator')) {
            $has_edit = true;
        }
        if (Gate::allows('live_class_delete') || auth()->user()->hasRole('administrator')) {
            $has_delete = true;
        }

        return DataTables::of($courses)
            ->addIndexColumn() // action-trashed
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-live-trashed')->with(['route_label' => 'admin.liveclasses', 'label' => 'liveclasses', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.liveclasses.show', ['course' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.liveclasses.edit', ['course' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if (auth()->user()->hasRole('administrator')) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.liveclasses.destroy', ['course' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

            // if (auth()->user()->hasRole('administrator')) {

            //     $view .= view('backend.datatable.action-publish')
            //         ->with(['route' => route('admin.courses.publish', ['course' => $q->id])])->render();

            //     }
                return $view;

            })
            ->editColumn('teachers', function ($q) {
                $teachers = "";
                foreach ($q->teachers as $singleTeachers) {
                    $teachers .= '<span class="label label-info label-many">' . $singleTeachers->name . ' </span>';
                }
                return $teachers;
            })
            ->editColumn('email', function ($q) {
                $email = "";
                foreach ($q->teachers as $singleTeachers) {
                    $email .= '<span class="label label-info label-many">' . $singleTeachers->email . ' </span>';
                }
                return $email;
            })
            // ->addColumn('lessons', function ($q) {
            //     $lesson = '<a href="' . route('admin.lessons.create', ['course_id' => $q->id]) . '" class="btn btn-success mb-1"><i class="fa fa-plus-circle"></i></a>  <a href="' . route('admin.lessons.index', ['course_id' => $q->id]) . '" class="btn mb-1 btn-warning text-white"><i class="fa fa-arrow-circle-right"></a>';
            //     return $lesson;
            // })
            // ->editColumn('course_image', function ($q) {
            //     return ($q->course_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->course_image) . '">' : 'N/A';
            // })
            ->editColumn('status', function ($q) {
                $text = "";
                
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-dark p-1 mr-1' >".trans('labels.backend.courses.fields.published')."</p>" : "";
                
                $text .= ($q->featured == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-warning p-1 mr-1' >".trans('labels.backend.courses.fields.featured')."</p>" : "";
                $text .= ($q->trending == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-success p-1 mr-1' >".trans('labels.backend.courses.fields.trending')."</p>" : "";
                $text .= ($q->popular == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >".trans('labels.backend.courses.fields.popular')."</p>" : "";

                return $text;
            })
            ->addColumn('category', function ($q) {
                return $q->category->name;
            })
            ->rawColumns(['teachers','actions','status','email'])
            ->make();
    }


     /**
     * Show the form for creating new Class.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // $old_date_timestamp = strtotime('10:00 AM');
        // $new = date('h ',strtotime('11:00 AM') );
        // $new_date = date('h A',$old_date_timestamp ); 
        // print_r($new.'-'.$new_date);die;
        if (!Gate::allows('live_class_create') && !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 6);
        })->get()->pluck('name', 'id');
        $current_user = Auth::user()->id;

        $categories = Category::where('status', '=', 1)->pluck('name', 'id');

        return view('backend.live.create', compact('teachers', 'categories'));
    }

    
    public function store(Request $request)
    {

     // echo "<pre>";  print_r($request->all());die;

       $this->validate($request, [
        'title'         => 'required',
        'description' => 'required',
        'requirement' => 'required',
        'language_used'=> 'required',
        'per_batch'=> 'required',
        'avg_price'=> 'required',
        'videolink' => 'required',
        'mon_start_time' => 'required_with:mon',
        'tue_start_time' => 'required_with:tue',
        'wed_start_time' => 'required_with:wed',
        'thur_start_time' => 'required_with:thur',
        'fri_start_time' => 'required_with:fri',
        'sat_start_time' => 'required_with:sat',
        'sun_start_time' => 'required_with:sun',
            ],[
                'videolink' => 'Add Youtube Video Link',
            ],[ 
                'title' => 'Title',
                'description' => 'Description',
                'requirement' => 'Requirement',
                'language_used'=> 'Language',
                'per_batch'=> 'Student Per batch',
                'avg_price'=> 'Price',
                'videolink'=> 'Media Intro'
            ]);

        if (!Gate::allows('live_class_create') && !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $request->all();

        $request = $this->saveFiles($request); 

        $teachers = Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [Auth::user()->id];


        $class = LiveClasses::create($request->all());
        $class->user_id = Auth::user()->id;

        if(auth()->user()->hasRole('administrator')){   
            $class->is_review = 1;
        }
        else{
            $class->is_review = 0;
        }
       
        $class_review = LiveClassesReview::create($request->all());
        $class_review->live_class_id = $class->id;
        $class_review->user_id = Auth::user()->id;

        if(auth()->user()->hasRole('administrator')){
            $class_review->for_review = 1;
        }
        else{
            $class_review->for_review = 0;
        }



        $trial = new TrialClassSchedule;
        $trial->user_id = Auth::user()->id;
        $trial->trial_class_id = $class->id;
        
        if ($request->input('mon1')) {
            $trial->mon1 = 1;
            $trial->trial_mon_start_time = implode(',', $request->input('trial_mon_start_time'));
        }
        if ($request->input('tue1')) {
            $trial->tue1 = '1';
           $trial->trial_tue_start_time = implode(',', $request->input('trial_tue_start_time'));
        }
        if ($request->input('wed1')) {
            $trial->wed1 = '1';
           $trial->trial_wed_start_time = implode(',', $request->input('trial_wed_start_time'));
        }
        if ($request->input('thur1')) {
            $trial->thur1 = '1';
            $trial->trial_thur_start_time = implode(',', $request->input('trial_thur_start_time'));
        }
        if ($request->input('fri1')) {
            $trial->fri1 = '1';
            $trial->trial_fri_start_time = implode(',', $request->input('trial_fri_start_time'));
        }
        if ($request->input('sat1')) {
            $trial->sat1 = '1';
            $trial->trial_sat_start_time = implode(',', $request->input('trial_sat_start_time'));
        }
        if ($request->input('sun1')) {
            $trial->sun1 = '1';
           $trial->trial_sun_start_time = implode(',', $request->input('trial_sun_start_time'));
        }
        $trial->save();  

        $trial_review = new TrialClassScheduleReview;
        $trial_review->user_id = Auth::user()->id;
        $trial_review->trial_class_review_id = $class->id;
        

        if ($request->input('mon1')) {
            $trial_review->mon1 = 1;
            $trial_review->trial_mon_start_time = implode(',', $request->input('trial_mon_start_time'));
        }
        if ($request->input('tue1')) {
            $trial_review->tue1 = '1';
           $trial_review->trial_tue_start_time = implode(',', $request->input('trial_tue_start_time'));
        }
        if ($request->input('wed1')) {
            $trial_review->wed1 = '1';
           $trial_review->trial_wed_start_time = implode(',', $request->input('trial_wed_start_time'));
        }
        if ($request->input('thur1')) {
            $trial->thur1 = '1';
            $trial->trial_thur_start_time = implode(',', $request->input('trial_thur_start_time'));
        }
        if ($request->input('fri1')) {
            $trial_review->fri1 = '1';
            $trial_review->trial_fri_start_time = implode(',', $request->input('trial_fri_start_time'));
        }
        if ($request->input('sat1')) {
            $trial_review->sat1 = '1';
            $trial_review->trial_sat_start_time = implode(',', $request->input('trial_sat_start_time'));
        }
        if ($request->input('sun1')) {
            $trial_review->sun1 = '1';
           $trial_review->trial_sun_start_time = implode(',', $request->input('trial_sun_start_time'));
        }
        $trial_review->save(); 

        $schedule = LiveClassSchedule::create($request->all());
        $schedule->user_id = Auth::user()->id;
        $schedule->live_class_id = $class->id;
        $schedule->save();

        $schedule_review = LiveClassScheduleReview::create($request->all());
        $schedule_review->user_id = Auth::user()->id;
        $schedule_review->live_class_review_id = $class->id;
        $schedule_review->save();

        

            $class->teachers()->sync($teachers);
            $class_review->teachers()->sync($teachers);

        // if (($request->slug == "") || $request->slug == null)jj
        //             $class_review->save();
        //         }

        if ($request->tags != null) {
            $tag_ids = [];
            $tags = explode(',', $request->tags);
            foreach ($tags as $item) {
                $tag = Tag::where('slug', '=', str_slug($item, '-'))->first();
                if ($tag == null) {
                    $tag = new Tag();
                    $tag->name = $item;
                    $tag->slug = str_slug($item, '-');
                    $tag->save();
                }
                $tag_ids[] = $tag->id;
            }
            if ($tag_ids != null) {
                $class->tags()->attach($tag_ids);
                $class_review->tags()->attach($tag_ids);

            }
        }
        $class->slug = str_slug($request->title);
        $class_review->slug = str_slug($request->title);
         $class->save();
         $class_review->save();

        return view('backend.live.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

        /**
     * Show the form for editing Course.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('live_class_edit') && !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 6);
        })->get()->pluck('name', 'id');
        $categories = Category::where('status', '=', 1)->pluck('name', 'id');


        $oldcourse = LiveClasses::findOrFail($id);

        if ($oldcourse->is_review == 0) {
            $course = LiveClassesReview::findOrFail($id);
            $schedule = LiveClassScheduleReview::where('live_class_review_id',$id)->get();
            $trialschedule = TrialClassScheduleReview::where('trial_class_review_id',$id)->get();

        }
        else{
            $course = LiveClasses::findOrFail($id);
            $schedule = LiveClassSchedule::where('live_class_id',$id)->get();
            $trialschedule = TrialClassSchedule::where('trial_class_id',$id)->get();
        }
        // print_r($schedule[0]['mon']);die;
        
$mon = explode(',', @$trialschedule[0]['trial_mon_start_time']);
$tues = explode(',', @$trialschedule[0]['trial_tue_start_time']);
$wed = explode(',', @$trialschedule[0]['trial_wed_start_time']);
$thur = explode(',', @$trialschedule[0]['trial_thur_start_time']);
$fri = explode(',', @$trialschedule[0]['trial_fri_start_time']);
$sat = explode(',', @$trialschedule[0]['trial_sat_start_time']);
$sun = explode(',', @$trialschedule[0]['trial_sun_start_time']);

        // echo "<pre>"; print_r($thur);die;

        // return view('backend.courses.edit', compact('course', 'teachers', 'categories'));
       

        return view('backend.live.edit', compact('course', 'teachers', 'categories','schedule', 'trialschedule', 'mon', 'tues', 'wed', 'thur', 'fri', 'sat', 'sun'));
    }

    /**
     * Update Course in storage.
     *
     * @param  \App\Http\Requests\UpdateCoursesRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	// echo "<pre>"; print_r($request->all());die;

    	$this->validate($request, [
        'title'         => 'required',
        'description' => 'required',
        'requirement' => 'required',
        'language_used'=> 'required',
        'per_batch'=> 'required',
        'avg_price'=> 'required',
        'videolink' => 'required',
        'mon_start_time' => 'required_with:mon',
        'tue_start_time' => 'required_with:tue',
        'wed_start_time' => 'required_with:wed',
        'thur_start_time' => 'required_with:thur',
        'fri_start_time' => 'required_with:fri',
        'sat_start_time' => 'required_with:sat',
        'sun_start_time' => 'required_with:sun',
            ],[
                'videolink' => 'Add Youtube Video Link',
            ],[ 
                'title' => 'Title',
                'description' => 'Description',
                'requirement' => 'Requirement',
                'language_used'=> 'Language',
                'per_batch'=> 'Student Per batch',
                'avg_price'=> 'Price',
                'videolink'=> 'Media Intro'
            ]);

        if (!Gate::allows('live_class_create') && !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $request->all();

        $request = $this->saveFiles($request);

        $course = LiveClasses::findOrFail($id);
        $course_review = LiveClassesReview::findOrFail($id); 
        $schedule = LiveClassSchedule::findOrFail($request->sehedule_id);
        $schedule_review = LiveClassScheduleReview::findOrFail($request->sehedule_id);
        $trial = TrialClassSchedule::findOrFail($request->trial_id);
        $trial_review = TrialClassScheduleReview::findOrFail($request->trial_id);
// print_r($trial);die;

       if (auth()->user()->hasRole('administrator')) {
       	  
       	  // $course = LiveClasses::findOrFail($id);
       	  $course->update($request->all());
       	  $course->is_review = 1;
       	  $course->slug = str_slug($request->title);
       	  $msg = trans('alerts.backend.general.created');
       	  $schedule->update($request->all());

        if ($request->mon1) {
            $trial->mon1 = '1';
            $trial->trial_mon_start_time = implode(',', $request->trial_mon_start_time);
        }
        if ($request->tue1) {
            $trial->tue1 = '1';
           $trial->trial_tue_start_time = implode(',', $request->trial_tue_start_time);
        }
        if ($request->wed1) {
            $trial->wed1 = '1';
           $trial->trial_wed_start_time = implode(',', $request->trial_wed_start_time);
        }
        if ($request->thur1) {
            $trial->thur1 = '1';
            $trial->trial_thur_start_time = implode(',', $request->trial_thur_start_time);
        }
        if ($request->fri1) {
            $trial->fri1 = '1';
            $trial->trial_fri_start_time = implode(',', $request->trial_fri_start_time);
        }
        if ($request->sat1) {
            $trial->sat1 = '1';
            $trial->trial_sat_start_time = implode(',', $request->trial_sat_start_time);
        }
        if ($request->sun1) {
            $trial->sun1 = '1';
           $trial->trial_sun_start_time = implode(',', $request->trial_sun_start_time);
        }          
          $trial->save();
       }
       else{
       	  $course_review->update($request->all());
       	  $course->is_review = 0; 
       	  $course_review->for_review = 0;
       	  $course_review->slug = str_slug($request->title);
       	  $msg = 'Course Update For Review';
       	  $schedule_review->update($request->all());
          // $trial_review->update($request->all());
          $schedule_review->trial_mon_start_time = implode(',', $request->trial_mon_start_time);
          $schedule_review->trial_tue_start_time = implode(',', $request->trial_tue_start_time);
          $schedule_review->trial_wed_start_time = implode(',', $request->trial_wed_start_time);
          $schedule_review->trial_thur_start_time = implode(',', $request->trial_thur_start_time);
          $schedule_review->trial_fri_start_time = implode(',', $request->trial_fri_start_time);
          $schedule_review->trial_sat_start_time = implode(',', $request->trial_sat_start_time);
          $schedule_review->trial_sun_start_time = implode(',', $request->trial_sun_start_time);
          $schedule_review->save();
       }

       $course->save();
       $course_review->save();


        $teachers = Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [Auth::user()->id];

            $course->teachers()->sync($teachers);
            $course_review->teachers()->sync($teachers);

        if ($request->tags != null) {
            $tag_ids = [];
            $tags = explode(',', $request->tags);
            foreach ($tags as $item) {
                $tag = Tag::where('slug', '=', str_slug($item, '-'))->first();
                if ($tag == null) {
                    $tag = new Tag();
                    $tag->name = $item;
                    $tag->slug = str_slug($item, '-');
                    $tag->save();
                }
                $tag_ids[] = $tag->id;
            }
            if ($tag_ids != null) {
                $class->tags()->attach($tag_ids);
                $class_review->tags()->attach($tag_ids);

            }
        }

        return view('backend.live.index')->withFlashSuccess($msg);
        



    }



    /**
     * Display Course.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        if (!Gate::allows('live_class_view') && !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        $teachers = User::get()->pluck('name', 'id');
        $oldcourse = LiveClasses::findOrFail($id);

        if ($oldcourse->is_review == 0) {
            $course = LiveClassesReview::findOrFail($id);
        }
        else{
            $course = LiveClasses::findOrFail($id);
        }
 
        return view('backend.live.show', compact('course', 'lessons', 'tests'));
    }
     /**
     * Display a listing of Live Classes.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewindex()
    {

        if (!Gate::allows('live_class_access') && !auth()->user()->hasRole('administrator') ) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (!Gate::allows('live_class_access') && !auth()->user()->hasRole('administrator') ) {
                return abort(401);
            }
            $courses = LiveClasses::onlyTrashed()->ofTeacher()->get();
        } else {
            $courses = LiveClasses::ofTeacher()->get();
        }

        

        return view('backend.live.reviewindex', compact('courses'));
    }

            /**
     * Display a listing of live Classes via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviewgetData(Request $request)
    {
        

        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $courses = "";
        $CourseReview = "";

        if (request('show_deleted') == 1) {
            if (!Gate::allows('live_class_delete') && !auth()->user()->hasRole('administrator')) {
            return 401;
        }
            $courses = LiveClasses::onlyTrashed()
                ->whereHas('category')
                ->where('is_review','0')
                ->ofTeacher()->orderBy('created_at', 'desc')->get();

        } else if (request('teacher_id') != "") {
            $id = request('teacher_id');
            $courses = LiveClasses::ofTeacher()
                ->whereHas('category')
                ->where('is_review','0')
                ->whereHas('teachers', function ($q) use ($id) {
                    $q->where('course_user.user_id', '=', $id);
                })->orderBy('created_at', 'desc')->get();
        } else if (request('cat_id') != "") {
            $id = request('cat_id');
            $courses = LiveClasses::ofTeacher()
                ->whereHas('category')
                ->where('is_review','0')
                ->where('category_id', '=', $id)->orderBy('created_at', 'desc')->get();
        } else {
            $courses = LiveClasses::ofTeacher()
                ->whereHas('category')
                ->where('is_review','0')
                ->orderBy('created_at', 'desc')->get();
        }


        if (Gate::allows('live_class_view') || auth()->user()->hasRole('administrator')) {
            $has_view = true;
        }
        if (Gate::allows('live_class_edit') || auth()->user()->hasRole('administrator')) {
            $has_edit = true;
        }
        if (Gate::allows('live_class_delete') || auth()->user()->hasRole('administrator')) {
            $has_delete = true;
        }

        return DataTables::of($courses)
            ->addIndexColumn() // action-trashed
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.courses', 'label' => 'lesson', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.liveclasses.show', ['course' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.liveclasses.edit', ['course' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if (auth()->user()->hasRole('administrator')) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.liveclasses.destroy', ['course' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                // if (auth()->user()->hasRole('administrator')) {

                //     $view .= view('backend.datatable.action-publish')
                //         ->with(['route' => route('admin.courses.publish', ['course' => $q->id])])->render();

                //     }
                    return $view;

            })
            ->editColumn('teachers', function ($q) {
                $teachers = "";
                foreach ($q->teachers as $singleTeachers) {
                    $teachers .= '<span class="label label-info label-many">' . $singleTeachers->name . ' </span>';
                }
                return $teachers;
            })
            ->editColumn('email', function ($q) {
                $email = "";
                foreach ($q->teachers as $singleTeachers) {
                    $email .= '<span class="label label-info label-many">' . $singleTeachers->email . ' </span>';
                }
                return $email;
            })
            ->editColumn('status', function ($q) {
                $text = "";
                
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-dark p-1 mr-1' >".trans('labels.backend.courses.fields.published')."</p>" : "";
                
                $text .= ($q->featured == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-warning p-1 mr-1' >".trans('labels.backend.courses.fields.featured')."</p>" : "";
                $text .= ($q->trending == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-success p-1 mr-1' >".trans('labels.backend.courses.fields.trending')."</p>" : "";
                $text .= ($q->popular == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >".trans('labels.backend.courses.fields.popular')."</p>" : "";
                return $text;
            })
            ->addColumn('category', function ($q) {
                return $q->category->name;
            })
            ->rawColumns(['teachers','actions','status','email'])
            ->make();
    }


    /**
     * Remove Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!Gate::allows('course_delete')) {
        //     return abort(401);
        // }
        // print_r($id);die;
        $course = LiveClasses::findOrFail($id);
        $course_review = LiveClassesReview::findOrFail($id);
        $course->delete();
        $course_review->delete();

        return redirect()->route('admin.liveclasses.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Course at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Course::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
         $course = LiveClasses::onlyTrashed()->findOrFail($id);
        $course_review = LiveClassesReview::onlyTrashed()->findOrFail($id);

        $course->restore();
        $course_review->restore();

        return redirect()->route('admin.liveclasses.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        $course = LiveClasses::onlyTrashed()->findOrFail($id);
        $course_review = LiveClassesReview::onlyTrashed()->findOrFail($id);   

        $course->forceDelete();
        $course_review->forceDelete();

        return redirect()->route('admin.liveclasses.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }


    


}