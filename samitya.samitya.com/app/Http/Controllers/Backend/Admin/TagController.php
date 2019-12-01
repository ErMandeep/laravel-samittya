<?php 

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreTagsRequest;
use App\Http\Requests\Admin\UpdateCategoriesRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{

	 /**
     * Display a listing of Tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Gate::allows('tags_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }


        if (request('show_deleted') == 1) {
            $tags = Tag::onlyTrashed()->get();
        } else {
            $tags = Tag::all();
        }
  

        return view('backend.tags.index', compact('tags'));
    }

	 /**
     * Display a listing of Tags via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {

    	$has_view = true;
        $has_delete = true;
        $has_edit = true;
        $tags = "";


        if (request('show_deleted') == 1) {
 
            $tags = Tag::onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $tags = Tag::orderBy('created_at', 'desc')->get();
        }

        return DataTables::of($tags)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.tags', 'label' => 'tags', 'value' => $q->id]);
                }
//                if ($has_view) {
//                    $view = view('backend.datatable.action-view')
//                        ->with(['route' => route('admin.categories.show', ['category' => $q->id])])->render();
//                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.tags.edit', ['tags' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.tags.destroy', ['tags' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                return $view;

            })
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * Show the form for creating new Tag.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('tags_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        
        return view('backend.tags.create');
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  \App\Http\Requests\StoreCoursesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagsRequest $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        if (!Gate::allows('tags_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $tag = Tag::where('slug','=',str_slug($request->name))->first();
        if($tag == null){
            $tag = new  Tag();
        }
        $tag->name = $request->name;
        $tag->slug = str_slug($request->name);
        $tag->save();

        return redirect()->route('admin.tags.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }


     /**
     * Show the form for editing tag.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('tags_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $tags = Tag::findOrFail($id);
        return view('backend.tags.edit', compact('tags'));
    }

    

    /**
     * Update Category in storage.
     *
     * @param  \App\Http\Requests\UpdateCategorysRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoriesRequest $request, $id)
    {
        if (!Gate::allows('tags_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        
        $tags = Tag::findOrFail($id);
        $tags->name = $request->name;
        $tags->slug = str_slug($request->name);
        $tags->save();

        return redirect()->route('admin.tags.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove tag from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('tags_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.tags.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    public function old_tags()
    {
        if (!Gate::allows('tags_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        
    	$tags = Tag::select('name')->get();
    	$tag = [];

    	foreach ($tags as  $value) {
    		array_push($tag, $value->name);
    	}

    	return json_encode($tag);
    }


}