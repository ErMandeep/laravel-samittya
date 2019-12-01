<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use function foo\func;
use Auth;
use App\Models\Offline_cities;
use App\Models\Offline_branches;
use Illuminate\Http\Request;


class BranchesController extends Controller
{

	public function index()
    {

        if (!auth()->user()->hasRole('administrator') ) {
            return abort(401);
        }

        if (request('show_deleted') == 1) {
            $branches = Offline_branches::onlyTrashed()->get();
        } else {
            $branches = Offline_branches::get();
        }
             
        

        return view('backend.branches.index', compact('branches'));

    }

    public function getData(Request $request)
    {


        $branches = "";


        if (request('show_deleted') == 1) {
 
            $branches = Offline_branches::onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $branches = Offline_branches::orderBy('created_at', 'desc')->get();
        }

        // print_r($cities);die;


        return DataTables::of($branches)
            ->addIndexColumn()
            ->addColumn('name', function ($q) {
                return $q->branch;
            })
            ->addColumn('city', function ($q) {
                return $q->city->city;
            })
            ->addColumn('actions', function ($q) use ($request) {
            	$view = '';

                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.branches', 'label' => 'tags', 'value' => $q->id]);
                }
            
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.branches.edit', ['branches' => $q->id])])
                        ->render();
                    $view .= $edit;

                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.branches.destroy', ['branches' => $q->id])])
                        ->render();
                    $view .= $delete;

                return $view;

            })
            ->rawColumns(['actions','name','city'])
            ->make();
    }

    /**
     * Show the form for creating new Tag.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        $city  = Offline_cities::get()->pluck('city','id');

        
        return view('backend.branches.create',compact('city'));
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  \App\Http\Requests\StoreCoursesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    	if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $this->validate($request, [
            'branch' => 'required|unique:offline_branches',
            'city_id'=> 'required',
        ] ,[ 
        	'branch.required' => 'Branch is Required' ,
        	'branch.unique' => 'This Branch is Already Added',	
        	'city_id' => 'City is Required'
        ]);


            $branches = new  Offline_branches();
            $branches->branch = $request->branch;
            $branches->description = $request->description;
            $branches->city_id = $request->city_id;
        	$branches->save();

        return redirect()->route('admin.branches.index')->withFlashSuccess(trans('alerts.backend.general.created'));


    }

    /**
     * Show the form for editing tag.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $branch = Offline_branches::findOrFail($id);
        $city  = Offline_cities::get()->pluck('city','id');

        return view('backend.branches.edit', compact('branch','city'));
    }

    /**
     * Update Category in storage.
     *
     * @param  \App\Http\Requests\UpdateCategorysRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $this->validate($request, [
            'branch' => 'required|unique:offline_branches,branch,'.$id,
            'city_id'=> 'required',
        ] ,[ 
        	'branch.required' => 'Branch is Required' ,
        	'branch.unique' => 'This Branch is Already Added',	
        	'city_id' => 'City is Required'
        ]);
        
        $branches = Offline_branches::findOrFail($id);
        $branches->branch = $request->branch;
        $branches->city_id = $request->city_id;
    	$branches->save();

        return redirect()->route('admin.branches.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove tag from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        
        $branches = Offline_branches::findOrFail($id);
        $branches->delete();

        return redirect()->route('admin.branches.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Restore Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        $branches = Offline_branches::onlyTrashed()->findOrFail($id);
        $branches->restore();

        return redirect()->route('admin.branches.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        $branches = Offline_branches::onlyTrashed()->findOrFail($id);
        $branches->forceDelete();

        return redirect()->route('admin.branches.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

}



