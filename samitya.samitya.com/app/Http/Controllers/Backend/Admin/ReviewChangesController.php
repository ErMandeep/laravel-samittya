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
use App\Http\Requests\Admin\StoreCoursesRequest;
use App\Http\Requests\Admin\UpdateCoursesRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Tag;

class ReviewChangesController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Gate::allows('course_access')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            if (!Gate::allows('course_delete')) {
                return abort(401);
            }
            $courses = CourseReview::onlyTrashed()->ofTeacher()->get();
        } else {
            $courses = CourseReview::ofTeacher()->get();
            // $courses = Course::whereIn('reviewchanges', [0])->get();


// echo "<pre>";print_r($courses);echo "</pre>";die;

        }

        return view('backend.reviewchanges.index', compact('courses'));
    }

    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $courses = "";

        if (request('show_deleted') == 1) {
            if (!Gate::allows('course_delete')) {
                return abort(401);
            }
            $courses = Course::onlyTrashed()
                ->whereHas('category')
                ->ofTeacher()->orderBy('created_at', 'desc')->get();

        } else if (request('teacher_id') != "") {
            $id = request('teacher_id');
            print_r($id);die;
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->whereHas('teachers', function ($q) use ($id) {
                    $q->where('course_user.user_id', '=', $id);
                })->orderBy('created_at', 'desc')->get();
        } else if (request('cat_id') != "") {
            $id = request('cat_id');
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->where('category_id', '=', $id)->orderBy('created_at', 'desc')->get();
        } else {
            // $courses = Course::ofTeacher()
            //     ->whereHas('category')
            //     ->orderBy('created_at', 'desc')->get();
                 
                $courses = Course::ofTeacher()
                ->whereHas('category')
                ->whereIn('reviewchanges', [0])
                ->orderBy('created_at', 'desc')->get();  
        }


        if (auth()->user()->can('course_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('course_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }
    // echo "<pre>";
    //     print_r($q);die;

        return DataTables::of($courses)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";

                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.reviewchanges', 'label' => 'lesson', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.reviewchanges.show', ['reviewchanges' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.reviewchanges.edit', ['reviewchanges' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.reviewchanges.destroy', ['reviewchanges' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                $view .= view('backend.datatable.action-publish')
                    ->with(['route' => route('admin.reviewchanges.publish', ['reviewchanges' => $q->id])])->render();
                return $view;

            })

            ->editColumn('teachers', function ($q) {
                $teachers = "";
                foreach ($q->teachers as $singleTeachers) {
                    $teachers .= '<span class="label label-info label-many">' . $singleTeachers->name . ' </span>';
                }
                return $teachers;
            })
            ->addColumn('lessons', function ($q) {
                $lesson = '<a href="' . route('admin.reviewlession.create', ['course_id' => $q->id]) . '" class="btn btn-success mb-1"><i class="fa fa-plus-circle"></i></a>  <a href="' . route('admin.reviewlession.index', ['course_id' => $q->id]) . '" class="btn mb-1 btn-warning text-white"><i class="fa fa-arrow-circle-right"></a>';
                return $lesson;
            })
            ->editColumn('course_image', function ($q) {
                return ($q->course_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->course_image) . '">' : 'N/A';
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
            ->addColumn('email', function ($q) {
                $email = "";
                foreach ($q->teachers as $singleTeachers) {
                    $email .= '<span class="label label-info label-many">' . $singleTeachers->email . ' </span>';
                }
                return $email;
            })

            ->rawColumns(['teachers','email' ,'lessons', 'course_image', 'actions','status'])
            ->make();
    }


    /**
     * Show the form for creating new Course.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('course_create')) {
            return abort(401);
        }

        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 2);
        })->get()->pluck('name', 'email' , 'id');

        $categories = Category::where('status', '=', 1)->pluck('name', 'id');
        // echo "<pre>";print_r($teachers);die;

        return view('backend.reviewchanges.create', compact('teachers', 'categories'));
    }

    /**
     * Store a newly created Course in storage.
     *
     * @param  \App\Http\Requests\StoreCoursesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCoursesRequest $request)
    {
        
        if (!Gate::allows('course_create')) {
            return abort(401);
        }



        $request->all();

        $request = $this->saveFiles($request);
        $course = Course::create($request->all());

        if (($request->slug == "") || $request->slug == null) {
            $course->slug = str_slug($request->title);
            $course->save();
        }
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
                $course->tags()->attach($tag_ids);

            }
        }
        $teachers = \Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [\Auth::user()->id];
        $course->teachers()->sync($teachers);


        return redirect()->route('admin.CourseReview.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Course.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }

        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 2);
        })->get()->pluck('name', 'id');
        $categories = Category::where('status', '=', 1)->pluck('name', 'id');


        $course = Course::findOrFail($id);
        if (count($course->tags) > 0) {
            $tags = $course->tags()->pluck('name')->implode(', ');
        } else {
            $tags = '';
        }


        return view('backend.reviewchanges.edit', compact('course', 'teachers', 'categories','tags'));
    }

    /**
     * Update Course in storage.
     *
     * @param  \App\Http\Requests\UpdateCoursesRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCoursesRequest $request, $id)
    {

        if (!Gate::allows('course_edit')) {
            return abort(401);
        }





        $course = Course::findOrFail($id);
        $course->update($request->all());
        if ($request->hasFile('downloadable_files')) {

                  
                        $extension = array_last(explode('.',$request->file('downloadable_files')->getClientOriginalName()));
                        $name = array_first(explode('.',$request->file('downloadable_files')->getClientOriginalName()));
                        $filename = time() . '-' . str_slug($name).'.'.$extension;
                        $request->file('downloadable_files')->move(public_path('storage/uploads'), $filename);
                        $course->update(array('downloadable_files' => $filename));
                        
                }
     
        
        if (($request->slug == "") || $request->slug == null) {
            $course->slug = str_slug($request->title);
            $course->save();
        }
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
                $course->tags()->detach();
                $course->tags()->attach($tag_ids);
            }
        }
        $teachers = \Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [\Auth::user()->id];
        $course->teachers()->sync($teachers);

// if publish

if($request->published == 1){
 $CourseReview = Course::findOrFail($id)->toArray();
 $CourseReviewobject = Course::findOrFail($id);

 $check = $CourseReview['course_id'];

if(!empty($check)){

        $course = Course::findOrFail($CourseReview['course_id']);
        $course->update($CourseReview);
            $course->published = 1;
            $course->reviewchanges = 1; 
            $course->course_id = NULL; 

            $course->save();

        $CourseReviewobject->forceDelete();

} 

if(empty($check)){


        $course = Course::findOrFail($id);
            $course->published = 1;
            $course->reviewchanges = 1; 
        $course->save();
}

    
        return redirect()->route('admin.reviewchanges.index')->withFlashSuccess(trans('alerts.backend.general.updated'));

    die;
}




// if publish





        return redirect()->route('admin.reviewchanges.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Course.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('course_view')) {
            return abort(401);
        }
        $teachers = User::get()->pluck('name', 'id');
        $lessons = \App\Models\Lesson::where('course_id', $id)->get();
        $tests = \App\Models\Test::where('course_id', $id)->get();

        $course = Course::findOrFail($id);
        $courseTimeline = $course->courseTimeline()->orderBy('sequence', 'asc')->get();

        return view('backend.courses.show', compact('course', 'lessons', 'tests', 'courseTimeline'));
    }


    /**
     * Remove Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.reviewchanges.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
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
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->restore();

        return redirect()->route('admin.reviewchanges.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->forceDelete();

        return redirect()->route('admin.reviewchanges.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Permanently save Sequence from storage.
     *
     * @param  Request
     */
    public function saveSequence(Request $request)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }

        foreach ($request->list as $item) {
            $courseTimeline = CourseTimeline::find($item['id']);
            $courseTimeline->sequence= $item['sequence'];
            $courseTimeline->save();
        }

        return 'success';
    }


    /**
     * Publish / Unpublish courses
     *
     * @param  Request
     */
    public function publish(Request $request, $id)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }

        $CourseReview = Course::findOrFail($id)->toArray();
        $CourseReviewobject = Course::findOrFail($id);
// echo "<pre>"; print_r($CourseReview['course_id']);die;

 $check = $CourseReview['course_id'];


 // if(empty($check)){
 //    echo "NULL";
 // }
 // if(!empty($check)){
 //    echo "not null";
 // }
 // print_r($check);die("test");


if(!empty($check)){

        // print_r($CourseReview->course_id);die;

        $course = Course::findOrFail($CourseReview['course_id']);
        // echo "<pre>"; print_r($course);die;
        $course->update($CourseReview);
        if($course->published == 1){
            $course->published = 0;
        }else{
            $course->published = 1;
        }
        if($course->reviewchanges == 0){
            $course->reviewchanges = 1; 
            $course->course_id = NULL; 
        }

            $course->save();

        $CourseReviewobject->forceDelete();

} 

if(empty($check)){


           // print_r("$CourseReview->course_id");die;

        $course = Course::findOrFail($id);
        // echo "<pre>"; print_r($course);die;
        if($course->published == 1){
            $course->published = 0;
        }else{
            $course->published = 1;
        }
        if($course->reviewchanges == 0){
            $course->reviewchanges = 1; 
        }
        $course->save();
}




        // $CourseReview->forceDelete();







        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }
}