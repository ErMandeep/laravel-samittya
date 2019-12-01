<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StorePagesRequest;
use App\Http\Requests\Admin\UpdatePagesRequest;
use App\Models\Gallary;
use App\Models\GallaryMedia;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use Auth;


class GallaryController extends Controller
{
    use FileUploadTrait;
    private $tags;

    public function index()
    {
        // if (!Gate::allows('page_access')) {
        //     return abort(401);
        // }
        // Grab all the pages
        $pages = Gallary::all();
        // Show the page
        return view('backend.gallary.index', compact('pages'));

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
        $pages = "";

        if (request('show_deleted') == 1) {
            // if (!Gate::allows('page_delete')) {
            //     return abort(401);
            // }
            $pages = Gallary::onlyTrashed()->orderBy('created_at', 'desc')->get();

        } else {
            $pages = Gallary::orderBy('created_at', 'desc')->get();

        }


        if (auth()->user()->can('page_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('page_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('page_delete')) {
            $has_delete = true;
        }

        return DataTables::of($pages)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.gallary', 'label' => 'gallary', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.gallary.show', ['gallary' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.gallary.edit', ['gallary' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.gallary.destroy', ['lesson' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                return $view;

            })

            ->editColumn('image', function ($q) {
                return ($q->image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->image) . '">' : 'N/A';
            })
            ->addColumn('status', function ($q) {
                $text = "";
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >".trans('labels.backend.pages.fields.published')."</p>" : "<p class='text-dark mb-1 font-weight-bold text-center bg-light p-1 mr-1' >".trans('labels.backend.pages.fields.drafted')."</p>";

                return $text;
            })
            ->addColumn('created', function ($q) {
                return $q->created_at->diffforhumans();
            })
            ->rawColumns(['image', 'actions','status'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // if (!Gate::allows('page_create')) {
        //     return abort(401);
        // }
        return view('backend.gallary.create');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StorePagesRequest $request)
    {
        ini_set('memory_limit', '-1');
        // if (!Gate::allows('page_create')) {
        //     return abort(401);
        // }
        // $page = new Gallary();
        // echo "<pre>";print_r($request->page_image);die("sdsad");
        // $page->title = $request->title;
        // if($request->slug == ""){
        //     $page->slug = str_slug($request->title);
        // }else{
        //     $page->slug = $request->slug;
        // }
       

// ***********************************************************************

 $lesson = Gallary::create($request->except('page_image', 'lesson_image')
            + ['position' => Gallary::where('user_id', $request->user_id)->max('position') + 1]);

// echo "<pre>";print_r($request->all());die;

        if ($request->position != "") {
            $model_type = Gallary::class;
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
                $media = GallaryMedia::where('url', $video_id)
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

                    $media = GallaryMedia::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();
                }
            } else if ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $lesson->title . ' - video';
            }

            if ($media == null) {
                $media = new GallaryMedia();
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

// ***********************************************************************
       $request = $this->saveall($request, 'page_image', Gallary::class, $lesson);
        // $request = $this->saveall($request);
        $lesson->user_id = auth()->user()->id;
        $lesson->title = $request->title;
        $lesson->image = $request->featured_image;
        $lesson->meta_title = $request->meta_title;
        $lesson->meta_keywords = $request->meta_keywords;
        $lesson->meta_description = $request->meta_description;
        $lesson->published = $request->published;
        $lesson->sidebar = $request->sidebar;
        $lesson->save();



        if ($lesson->id) {
            return redirect()->route('admin.gallary.index')->withFlashSuccess(__('alerts.backend.general.created'));
        } else {
            return redirect()->route('admin.gallary.index')->withFlashDanger(__('alerts.backend.general.error'));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Page $page
     * @return view
     */
    public function show($id)
    {
        // if (!Gate::allows('page_view')) {
        //     return abort(401);
        // }
         $lesson = Gallary::with('media')->findOrFail($id);

        $mediafiles = GallaryMedia::where('model_id', '=', $lesson->id)->get();
        $page = Gallary::findOrFail($id);
        return view('backend.gallary.show', compact('page', 'mediafiles'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Page $page
     * @return view
     */
    public function edit($id)
    {
        // if (!Gate::allows('page_edit')) {
        //     return abort(401);
        // }

        $videos = '';
        // $courses = Course::has('category')->ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');


        // $entries = Gallary::whereIn('id', $request->input('ids'))->get();

        $lesson = Gallary::with('media')->findOrFail($id);

        // echo "<pre>"; print_r($lesson->id);die;

        $mediafiles = GallaryMedia::where('model_id', '=', $lesson->id)->get();
        // $new = GallaryMedia::where('id', '=', 1)->first();





        if ($lesson->media) {
            $videos = $lesson->media()->where('media.type', '=', 'YT')->pluck('url')->implode(',');
        }
        $preview = $lesson->preview;


        $page = Gallary::where('id', '=', $id)->first();

        // echo "<pre>"; print_r($lesson);die;

        return view('backend.gallary.edit', compact('mediafiles','preview' ,'page', 'category', 'tags'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Page $page
     * @return Response
     */
    public function update(UpdatePagesRequest $request,$id)
    {
        ini_set('memory_limit', '-1');
        // if (!Gate::allows('page_edit')) {
        //     return abort(401);
        // }



// **********************************************************

// echo "<pre>";print_r($request->all());die;

 $lesson = Gallary::findOrFail($id);
$lesson->update($request->except('page_image', 'lesson_image'));

 // $lesson = Gallary::create($request->except('page_image', 'lesson_image')
 //            + ['position' => Gallary::where('user_id', $request->user_id)->max('position') + 1]);
        // echo "<pre>";print_r($lesson);die;


        if ($request->position != "") {
            $model_type = Gallary::class;
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
                $media = GallaryMedia::where('url', $video_id)
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

                    $media = GallaryMedia::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();
                }
            } else if ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $lesson->title . ' - video';
            }

            // if ($media == null) {
            //     $media = new GallaryMedia();
            //     $media->model_type = $model_type;
            //     $media->model_id = $model_id;
            //     $media->name = $name;
            //     $media->url = $url;
            //     $media->type = $request->media_type;
            //     $media->file_name = $video_id;
            //     $media->size = 0;
            //     $media->save();
            // }
        }

// ***********************************************************************
       $request = $this->saveall($request, 'page_image', Gallary::class, $lesson);
        // $request = $this->saveall($request);
        $lesson->user_id = auth()->user()->id;
        $lesson->title = $request->title;
        $lesson->image = $request->featured_image;
        $lesson->meta_title = $request->meta_title;
        $lesson->meta_keywords = $request->meta_keywords;
        $lesson->meta_description = $request->meta_description;
        $lesson->published = $request->published;
        $lesson->sidebar = $request->sidebar;
        $lesson->save();





// **********************************************************







        return redirect()->route('admin.gallary.index')->withFlashSuccess(__('alerts.backend.general.updated'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Page $page
     * @return Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('page_delete')) {
            return abort(401);
        }
        $page = Gallary::findOrfail($id);
        $page->delete();
        return redirect()->route('admin.gallary.index')->withFlashSuccess(__('alerts.backend.general.deleted'));

    }



    /**
     * Delete all selected Page at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        // if (!Gate::allows('page_delete')) {
        //     return abort(401);
        // }
        if ($request->input('ids')) {
            $entries = Gallary::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


        public function mediadelete(Request $request){

    
        $media_id =  $request->media_id;
        $media = GallaryMedia::find($media_id);
        if($media){
            //Delete Related data
            $filename = $media->file_name;

            $media->forceDelete();

            //Delete Photo
            $destinationPath = public_path() . '/storage/uploads/'.$filename;
            if (file_exists($destinationPath)) {
                unlink($destinationPath);
            }
            return response()->json(['success'=>'Deleted Successfully']);
        }else{
            return response()->json(['failure'=>'No Gallery']);
        }
    }


    /**
     * Restore Page from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('page_delete')) {
            return abort(401);
        }
        $page = Gallary::onlyTrashed()->findOrFail($id);
        $page->restore();

        return redirect()->route('admin.gallary.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Page from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('page_delete')) {
            return abort(401);
        }
        $page = Gallary::onlyTrashed()->findOrFail($id);
        $page->forceDelete();

        return redirect()->route('admin.gallary.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }




    public function saveall(Request $request, $downloadable_file_input = null, $model_type = null, $model = null)
    {


        // echo "<pre>";print_r($request->all());die;
        if (!file_exists(public_path('storage/uploads'))) {
            mkdir(public_path('storage/uploads'), 0777);
            mkdir(public_path('storage/upload/thumb'), 0777);
        }
        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {

            if ($request->hasFile($key)) {

                if ($key == $downloadable_file_input) {

                   
                    foreach ($request->file($key) as $item) {
                        // $filename = time() . '-' . str_slug($item->getClientOriginalName());
                        $filename = time() . '-' . $item->getClientOriginalName();

                        // print_r($filename);die;

                        $size = $item->getSize() / 1024;
                        $item->move(public_path('storage/uploads'), $filename);
                        GallaryMedia::create([
                            'model_type' => $model_type,
                            'model_id' => $model->id,
                            'name' => $filename,
                            'type' => $item->getClientMimeType(),
                            'file_name' => $filename,
                            'size' => $size,
                        ]);
                    }
                    $finalRequest = $finalRequest = new Request($request->except($downloadable_file_input));


                } else {

                    if($key != 'video_file'){
                        if($key == 'add_pdf'){
                            $file = $request->file($key);

                            $filename = time() . '-' . $file->getClientOriginalName();
                            $size = $file->getSize() / 1024;
                            $file->move(public_path('storage/uploads'), $filename);
                            GallaryMedia::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => 'lesson_pdf',
                                'file_name' => $filename,
                                'size' => $size,
                            ]);
                            $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                        }elseif($key == 'add_audio'){
                            $file = $request->file($key);

                            $filename = time() . '-' . $file->getClientOriginalName();
                            $size = $file->getSize() / 1024;
                            $file->move(public_path('storage/uploads'), $filename);
                            GallaryMedia::create([
                                'model_type' => $model_type,
                                'model_id' => $model->id,
                                'name' => $filename,
                                'type' => 'lesson_audio',
                                'file_name' => $filename,
                                'url' => asset('storage/uploads/'.$filename),
                                'size' => $size,
                            ]);
                            $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                        } else{
                            // echo"<pre>"; print_r($request->all());die("dlfsjdfsjd");
                            $filename = time() . '-' . str_slug($request->file($key)->getClientOriginalName());
                            $request->file($key)->move(public_path('storage/uploads'), $filename);
                            $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                            $model->lesson_image = $filename;
                            $model->save();
                        }

                    }
                }
            }
        }

        return $finalRequest;
    }



}
