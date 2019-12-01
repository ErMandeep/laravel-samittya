<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonsRequest;
use App\Http\Requests\Admin\UpdateLessonsRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class ReviewLessionController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('lesson_access')) {
            return abort(401);
        }
        $courses = $courses = Course::has('category')->ofTeacher()->whereIn('reviewchanges', [0])->pluck('title', 'id')->prepend('Please select', '');

        // echo "<pre>"; print_r($courses);die("dfdf");

        return view('backend.reviewlession.index', compact('courses'));
    }

    /**
     * Display a listing of Lessons via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {

        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $lessons = "";
        $lessons = Lesson::whereIn('course_id', Course::ofTeacher()->pluck('id'));


        if ($request->course_id != "") {
         $lessons = $lessons->where('course_id', (int)$request->course_id)->orderBy('created_at', 'desc')->whereIn('reviewlession', [0]) ->get();
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $lessons = Lesson::query()->with('course')->orderBy('created_at', 'desc')->onlyTrashed()->get();
        }


        if (auth()->user()->can('lesson_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('lesson_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }

        return DataTables::of($lessons)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.reviewlession', 'label' => 'reviewlession', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.reviewlession.show', ['reviewlession' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.reviewlession.edit', ['reviewlession' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.reviewlession.destroy', ['reviewlession' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                if (auth()->user()->can('test_view')) {
                    if ($q->test != "") {
                        $view .= '<a href="' . route('admin.tests.index', ['lesson_id' => $q->id]) . '" class="btn btn-success btn-block mb-1">' . trans('labels.backend.tests.title') . '</a>';
                    }
                }

                $view .= view('backend.datatable.action-publish')
                    ->with(['route' => route('admin.reviewlession.publish', ['reviewlession' => $q->id])])->render();

                return $view;

            })
            ->editColumn('course', function ($q) {
                return ($q->course) ? $q->course->title : 'N/A';
            })
            ->editColumn('lesson_image', function ($q) {
                return ($q->lesson_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->lesson_image) . '">' : 'N/A';
            })
            ->editColumn('free_lesson', function ($q) {
                return ($q->free_lesson == 1) ? "Yes" : "No";
            })
            ->editColumn('published', function ($q) {
                return ($q->published == 1) ? "Yes" : "No";
            })
            ->rawColumns(['lesson_image', 'actions'])
            ->make();
    }

    /**
     * Show the form for creating new Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }
        $courses = Course::has('category')->ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');
        return view('backend.reviewlession.create', compact('courses'));
    }

    /**
     * Store a newly created Lesson in storage.
     *
     * @param  \App\Http\Requests\StoreLessonsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessonsRequest $request)
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }
        // print_r($request->all());die;

        $lesson = Lesson::create($request->except('downloadable_files', 'lesson_image')
            + ['position' => Lesson::where('course_id', $request->course_id)->max('position') + 1]);


        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';

            if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                $video = $request->video;
                $url = $video;
                $video_id = array_last(explode('/', $request->video));
                $media = Media::where('url', $video_id)
                    ->where('type', '=', $request->media_type)
                    ->where('model_type', '=', 'App\Models\Lesson')
                    ->where('model_id', '=', $lesson->id)
                    ->first();
                $size = 0;

            } elseif ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();
                }
            } else if ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $lesson->title . ' - video';
            }

            if ($media == null) {
                $media = new Media();
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }
        }

        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        if (($request->slug == "") || $request->slug == null) {
            $lesson->slug = str_slug($request->title);
            $lesson->save();
        }

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ($lesson->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $request->course_id)->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $request->course_id;
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }

        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Lesson.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }


        $videos = '';
        $courses = Course::has('category')->ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');

        $lesson = Lesson::with('media')->findOrFail($id);
        if ($lesson->media) {
            $videos = $lesson->media()->where('media.type', '=', 'YT')->pluck('url')->implode(',');
        }
        $preview = $lesson->preview;

        return view('backend.reviewlession.edit', compact('lesson', 'courses', 'videos','preview'));
    }

    /**
     * Update Lesson in storage.
     *
     * @param  \App\Http\Requests\UpdateLessonsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLessonsRequest $request, $id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }


   $lessonarray = Lesson::findOrFail($id)->toArray();

// update without publish
        if($request->published == 0){
          
        $lesson = Lesson::findOrFail($id);
        $lesson->update($request->except('downloadable_files', 'lesson_image'));

        if (!$request->preview) {
             $lesson->preview = 0;
            $lesson->save();
        }


        if (($request->slug == "") || $request->slug == null) {
            $lesson->slug = str_slug($request->title);
            $lesson->save();
        }

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';
            $media = $lesson->mediavideo;
            if ($media == "") {
                $media = new  Media();
            }
            if ($request->media_type != 'upload') {
                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                    $video = $request->video;
                    $url = $video;
                    $video_id = array_last(explode('/', $request->video));
                    $size = 0;

                } else if ($request->media_type == 'embed') {
                    $url = $request->video;
                    $filename = $lesson->title . ' - video';
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();

                    if ($media == null) {
                        $media = new Media();
                    }
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->url = $url;
                    $media->type = $request->media_type;
                    $media->file_name = $video_id;
                    $media->size = 0;
                    $media->save();

                }
            }
        }
        if($request->hasFile('add_pdf')){
            $pdf = $lesson->mediaPDF;
            if($pdf){
                $pdf->delete();
            }
        }


        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ($lesson->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $lessonarray['oldcourse_id'])->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $lessonarray['oldcourse_id'];
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }


        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));

        }

   // if published by admin     
  if($request->published == 1){


    // ***************************
            $lesson = Lesson::findOrFail($id);
        $lesson->update($request->except('downloadable_files', 'lesson_image'));

        if (!$request->preview) {
             $lesson->preview = 0;
            $lesson->save();
        }


        if (($request->slug == "") || $request->slug == null) {
            $lesson->slug = str_slug($request->title);
            $lesson->save();
        }

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';
            $media = $lesson->mediavideo;
            if ($media == "") {
                $media = new  Media();
            }
            if ($request->media_type != 'upload') {
                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                    $video = $request->video;
                    $url = $video;
                    $video_id = array_last(explode('/', $request->video));
                    $size = 0;

                } else if ($request->media_type == 'embed') {
                    $url = $request->video;
                    $filename = $lesson->title . ' - video';
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();

                    if ($media == null) {
                        $media = new Media();
                    }
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->url = $url;
                    $media->type = $request->media_type;
                    $media->file_name = $video_id;
                    $media->size = 0;
                    $media->save();

                }
            }
        }
        if($request->hasFile('add_pdf')){
            $pdf = $lesson->mediaPDF;
            if($pdf){
                $pdf->delete();
            }
        }


        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ($lesson->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $lessonarray['oldcourse_id'])->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $lessonarray['oldcourse_id'];
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }      
    // ***************************

        $lessonarray = Lesson::findOrFail($id)->toArray();
        $check_lesson = Lesson::where('oldcourse_id', '=', $lessonarray['oldcourse_id'])->get()->toArray();


        if(count($check_lesson) > 1){
            
        $lessonarray = Lesson::findOrFail($id)->toArray();
        $lessonReviewobject = Lesson::findOrFail($id);

 $check = $lessonarray['lession_id'];

// if oldcourse_id not empty

if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] == NULL){
 

 
$lessonReviewobject->course_id = $lessonarray['oldcourse_id'];
$lessonReviewobject->reviewlession = 1;
$lessonReviewobject->published = 1;
$lessonReviewobject->oldcourse_id = NULL;
$lessonReviewobject->save();


        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
die;
}



if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] != ""){




// *************


// *************




$lessonsarray = Lesson::findOrFail($id)->toArray();
$lessonobject = Lesson::findOrFail($id);

  // echo "<pre>"; print_r($lessonsarray);die("d");  


        $lessons = Lesson::findOrFail($lessonarray['lession_id']);
        $lessons->update($lessonsarray);
        // echo "<pre>"; print_r($course);die;
        $lessons->course_id = $lessonarray['oldcourse_id'];
        $lessons->published = 1;
        $lessons->reviewlession = 1;
        $lessons->oldcourse_id = NULL;
        $lessons->lession_id = NULL;
        $lessons->save();
        $lessonobject->forceDelete();


        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
die;
}


if(!empty($check)){

        // print_r($lessonarray['lession_id']);die;

        $lesson = Lesson::findOrFail($lessonarray['lession_id']);
        // echo "<pre>"; print_r($lesson);die;
        $lesson->update($lessonarray);
        if($lesson->published == 0){
            $lesson->published = 1;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
            $lesson->lession_id = NULL; 
        }

            $lesson->save();

        $lessonReviewobject->forceDelete();

} 

if(empty($check)){

        $lesson = Lesson::findOrFail($id);
        // echo "<pre>"; print_r($lesson);die("kkkkkk");
        if($lesson->published == 1){
            $lesson->published = 0;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
        }
        $lesson->save();
}



               return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));


            die;
        }

        if(count($check_lesson) == 1){


// *********************
        $lesson = Lesson::findOrFail($id);
        $lesson->update($request->except('downloadable_files', 'lesson_image'));

        if (!$request->preview) {
             $lesson->preview = 0;
            $lesson->save();
        }


        if (($request->slug == "") || $request->slug == null) {
            $lesson->slug = str_slug($request->title);
            $lesson->save();
        }

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';
            $media = $lesson->mediavideo;
            if ($media == "") {
                $media = new  Media();
            }
            if ($request->media_type != 'upload') {
                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                    $video = $request->video;
                    $url = $video;
                    $video_id = array_last(explode('/', $request->video));
                    $size = 0;

                } else if ($request->media_type == 'embed') {
                    $url = $request->video;
                    $filename = $lesson->title . ' - video';
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();

                    if ($media == null) {
                        $media = new Media();
                    }
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->url = $url;
                    $media->type = $request->media_type;
                    $media->file_name = $video_id;
                    $media->size = 0;
                    $media->save();

                }
            }
        }
        if($request->hasFile('add_pdf')){
            $pdf = $lesson->mediaPDF;
            if($pdf){
                $pdf->delete();
            }
        }


        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ($lesson->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $lessonarray['oldcourse_id'])->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $lessonarray['oldcourse_id'];
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }            
// *********************


            

        $lessonarray = Lesson::findOrFail($id)->toArray();
        $lessonReviewobject = Lesson::findOrFail($id);

 $check = $lessonarray['lession_id'];

// if oldcourse_id not empty

if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] == NULL){
 

 
$lessonReviewobject->course_id = $lessonarray['oldcourse_id'];
$lessonReviewobject->reviewlession = 1;
$lessonReviewobject->published = 1;
$lessonReviewobject->oldcourse_id = NULL;
$lessonReviewobject->save();

$courseobject = Course::findOrFail($lessonarray['course_id']);
$courseobject->forceDelete();

        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
die;
}



if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] != ""){



$lessonsarray = Lesson::findOrFail($id)->toArray();
$lessonobject = Lesson::findOrFail($id);

  // echo "<pre>"; print_r($lessonsarray);die("d00000000");  


        $lessons = Lesson::findOrFail($lessonarray['lession_id']);
        // echo "<pre>"; print_r($lessonsarray);die;
        $lessons->update($lessonsarray);
        $lessons->course_id = $lessonarray['oldcourse_id'];
        $lessons->published = 1;
        $lessons->reviewlession = 1;
        $lessons->oldcourse_id = NULL;
        $lessons->lession_id = NULL;
        $lessons->save();
        $lessonobject->forceDelete();

        $courseobject = Course::findOrFail($lessonarray['course_id']);
        $courseobject->forceDelete();

        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
die;
}


if(!empty($check)){

        // print_r($lessonarray['lession_id']);die;

        $lesson = Lesson::findOrFail($lessonarray['lession_id']);
        // echo "<pre>"; print_r($lesson);die;
        $lesson->update($lessonarray);
        if($lesson->published == 0){
            $lesson->published = 1;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
            $lesson->lession_id = NULL; 
        }

            $lesson->save();

        $lessonReviewobject->forceDelete();

} 

if(empty($check)){

        $lesson = Lesson::findOrFail($id);
        // echo "<pre>"; print_r($lesson);die("kkkkkk");
        if($lesson->published == 1){
            $lesson->published = 0;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
        }
        $lesson->save();
}



               return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
die;
        }

        die;

 // ***************

  $request->request->add(['reviewlession' => 1]);
 

        $lesson = Lesson::findOrFail($id);

        $lesson->update($request->except('downloadable_files', 'lesson_image'));

        if (!$request->preview) {
             $lesson->preview = 0;
            $lesson->save();
        }


        if (($request->slug == "") || $request->slug == null) {
            $lesson->slug = str_slug($request->title);
            $lesson->save();
        }

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';
            $media = $lesson->mediavideo;
            if ($media == "") {
                $media = new  Media();
            }
            if ($request->media_type != 'upload') {
                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                    $video = $request->video;
                    $url = $video;
                    $video_id = array_last(explode('/', $request->video));
                    $size = 0;

                } else if ($request->media_type == 'embed') {
                    $url = $request->video;
                    $filename = $lesson->title . ' - video';
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();

                    if ($media == null) {
                        $media = new Media();
                    }
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->url = $url;
                    $media->type = $request->media_type;
                    $media->file_name = $video_id;
                    $media->size = 0;
                    $media->save();

                }
            }
        }
        if($request->hasFile('add_pdf')){
            $pdf = $lesson->mediaPDF;
            if($pdf){
                $pdf->delete();
            }
        }


        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ($lesson->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $lessonarray['oldcourse_id'])->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $lessonarray['oldcourse_id'];
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }







// for published 1
   $lessonarray = Lesson::findOrFail($id)->toArray();
   $lessonReviewobject = Lesson::findOrFail($id);

 $check = $lessonarray['lession_id'];

// if oldcourse_id not empty

if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] == NULL){
 

 
$lessonReviewobject->course_id = $lessonarray['oldcourse_id'];
$lessonReviewobject->reviewlession = 1;
$lessonReviewobject->published = 1;
$lessonReviewobject->oldcourse_id = NULL;
$lessonReviewobject->save();

$courseobject = Course::findOrFail($lessonarray['course_id']);
$courseobject->forceDelete();


        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
 // return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
}



if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] != ""){



$lessonsarray = Lesson::findOrFail($id)->toArray();
$lessonobject = Lesson::findOrFail($id);


        $lessons = Lesson::findOrFail($lessonarray['lession_id']);
        $lessons->update($lessonsarray);
        $lessons->course_id = $lessonarray['oldcourse_id'];
        $lessons->published = 1;
        $lessons->reviewlession = 1;
        $lessons->oldcourse_id = NULL;
        $lessons->lession_id = NULL;
        $lessons->save();
        $lessonobject->forceDelete();

        $courseobject = Course::findOrFail($lessonarray['course_id']);
        $courseobject->forceDelete();

 // return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
                return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
die;
}


if(!empty($check)){


        $lesson = Lesson::findOrFail($lessonarray['lession_id']);
        $lesson->update($lessonarray);
        if($lesson->published == 0){
            $lesson->published = 1;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
            $lesson->lession_id = NULL; 
        }

            $lesson->save();

        $lessonReviewobject->forceDelete();

} 

if(empty($check)){


        $lesson = Lesson::findOrFail($id);
        if($lesson->published == 1){
            $lesson->published = 0;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
        }
        $lesson->save();
}
// for published 1



        return redirect()->route('admin.reviewlession.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
    }


    }


    /**
     * Display Lesson.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        $courses = Course::get()->pluck('title', 'id')->prepend('Please select', '');

        $tests = Test::where('lesson_id', $id)->get();

        $lesson = Lesson::findOrFail($id);


        return view('backend.reviewlession.show', compact('lesson', 'tests', 'courses'));
    }


    /**
     * Remove Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return back()->withFlashSuccess(__('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Lesson at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Lesson::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $lesson->restore();

        return back()->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);

        if(File::exists(public_path('/storage/uploads/'.$lesson->lesson_image))) {
            File::delete(public_path('/storage/uploads/'.$lesson->lesson_image));
            File::delete(public_path('/storage/uploads/thumb/'.$lesson->lesson_image));
        }

        $timelineStep = CourseTimeline::where('model_id', '=', $id)
            ->where('course_id', '=', $lesson->course->id)->first();
        if($timelineStep){
            $timelineStep->delete();
        }

        $lesson->forceDelete();



        return back()->withFlashSuccess(trans('alerts.backend.general.deleted'));
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

        $lessonarray = Lesson::findOrFail($id)->toArray();
        $check_lesson = Lesson::where('oldcourse_id', '=', $lessonarray['oldcourse_id'])->get()->toArray();


        if(count($check_lesson) > 1){
            
        $lessonarray = Lesson::findOrFail($id)->toArray();
        $lessonReviewobject = Lesson::findOrFail($id);

 $check = $lessonarray['lession_id'];

// if oldcourse_id not empty

if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] == NULL){
 

 
$lessonReviewobject->course_id = $lessonarray['oldcourse_id'];
$lessonReviewobject->reviewlession = 1;
$lessonReviewobject->published = 1;
$lessonReviewobject->oldcourse_id = NULL;
$lessonReviewobject->save();


 return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
}



if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] != ""){



$lessonsarray = Lesson::findOrFail($id)->toArray();
$lessonobject = Lesson::findOrFail($id);

  // echo "<pre>"; print_r($lessonsarray);die("d");  


        $lessons = Lesson::findOrFail($lessonarray['lession_id']);
        $lessons->update($lessonsarray);
        // echo "<pre>"; print_r($course);die;
        $lessons->course_id = $lessonarray['oldcourse_id'];
        $lessons->published = 1;
        $lessons->reviewlession = 1;
        $lessons->oldcourse_id = NULL;
        $lessons->lession_id = NULL;
        $lessons->save();
        $lessonobject->forceDelete();


 return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
}


if(!empty($check)){

        // print_r($lessonarray['lession_id']);die;

        $lesson = Lesson::findOrFail($lessonarray['lession_id']);
        // echo "<pre>"; print_r($lesson);die;
        $lesson->update($lessonarray);
        if($lesson->published == 0){
            $lesson->published = 1;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
            $lesson->lession_id = NULL; 
        }

            $lesson->save();

        $lessonReviewobject->forceDelete();

} 

if(empty($check)){

        $lesson = Lesson::findOrFail($id);
        // echo "<pre>"; print_r($lesson);die("kkkkkk");
        if($lesson->published == 1){
            $lesson->published = 0;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
        }
        $lesson->save();
}



        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));


            die;
        }

        if(count($check_lesson) == 1){
            

        $lessonarray = Lesson::findOrFail($id)->toArray();
        $lessonReviewobject = Lesson::findOrFail($id);

 $check = $lessonarray['lession_id'];

// if oldcourse_id not empty

if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] == NULL){
 

 
$lessonReviewobject->course_id = $lessonarray['oldcourse_id'];
$lessonReviewobject->reviewlession = 1;
$lessonReviewobject->published = 1;
$lessonReviewobject->oldcourse_id = NULL;
$lessonReviewobject->save();

$courseobject = Course::findOrFail($lessonarray['course_id']);
$courseobject->forceDelete();

 return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
}



if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] != ""){



$lessonsarray = Lesson::findOrFail($id)->toArray();
$lessonobject = Lesson::findOrFail($id);

  // echo "<pre>"; print_r($lessonsarray);die("d");  


        $lessons = Lesson::findOrFail($lessonarray['lession_id']);
        $lessons->update($lessonsarray);
        // echo "<pre>"; print_r($course);die;
        $lessons->course_id = $lessonarray['oldcourse_id'];
        $lessons->published = 1;
        $lessons->reviewlession = 1;
        $lessons->oldcourse_id = NULL;
        $lessons->lession_id = NULL;
        $lessons->save();
        $lessonobject->forceDelete();

        $courseobject = Course::findOrFail($lessonarray['course_id']);
        $courseobject->forceDelete();

 return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
}


if(!empty($check)){

        // print_r($lessonarray['lession_id']);die;

        $lesson = Lesson::findOrFail($lessonarray['lession_id']);
        // echo "<pre>"; print_r($lesson);die;
        $lesson->update($lessonarray);
        if($lesson->published == 0){
            $lesson->published = 1;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
            $lesson->lession_id = NULL; 
        }

            $lesson->save();

        $lessonReviewobject->forceDelete();

} 

if(empty($check)){

        $lesson = Lesson::findOrFail($id);
        // echo "<pre>"; print_r($lesson);die("kkkkkk");
        if($lesson->published == 1){
            $lesson->published = 0;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
        }
        $lesson->save();
}



        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
        }


die;
        echo "<pre>"; print_r(count($check_lesson));die;



// ***********************************

        $lessonarray = Lesson::findOrFail($id)->toArray();
        $lessonReviewobject = Lesson::findOrFail($id);

 $check = $lessonarray['lession_id'];

// if oldcourse_id not empty

if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] == NULL){
 

 
$lessonReviewobject->course_id = $lessonarray['oldcourse_id'];
$lessonReviewobject->reviewlession = 1;
$lessonReviewobject->published = 1;
$lessonReviewobject->oldcourse_id = NULL;
$lessonReviewobject->save();

$courseobject = Course::findOrFail($lessonarray['course_id']);
$courseobject->forceDelete();

 return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
}



if($lessonarray['oldcourse_id'] != "" && $lessonarray['lession_id'] != ""){



$lessonsarray = Lesson::findOrFail($id)->toArray();
$lessonobject = Lesson::findOrFail($id);

  // echo "<pre>"; print_r($lessonsarray);die("d");  


        $lessons = Lesson::findOrFail($lessonarray['lession_id']);
        $lessons->update($lessonsarray);
        // echo "<pre>"; print_r($course);die;
        $lessons->course_id = $lessonarray['oldcourse_id'];
        $lessons->published = 1;
        $lessons->reviewlession = 1;
        $lessons->oldcourse_id = NULL;
        $lessons->lession_id = NULL;
        $lessons->save();
        $lessonobject->forceDelete();

        $courseobject = Course::findOrFail($lessonarray['course_id']);
        $courseobject->forceDelete();

 return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
die;
}


if(!empty($check)){

        // print_r($lessonarray['lession_id']);die;

        $lesson = Lesson::findOrFail($lessonarray['lession_id']);
        // echo "<pre>"; print_r($lesson);die;
        $lesson->update($lessonarray);
        if($lesson->published == 0){
            $lesson->published = 1;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
            $lesson->lession_id = NULL; 
        }

            $lesson->save();

        $lessonReviewobject->forceDelete();

} 

if(empty($check)){

        $lesson = Lesson::findOrFail($id);
        // echo "<pre>"; print_r($lesson);die("kkkkkk");
        if($lesson->published == 1){
            $lesson->published = 0;
        }else{
            $lesson->published = 1;
        }
        if($lesson->reviewlession == 0){
            $lesson->reviewlession = 1; 
        }
        $lesson->save();
}



        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }    
}