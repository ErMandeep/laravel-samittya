<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use function foo\func;
use Auth;
use App\Models\Offline_cities;
use App\Models\Offline_branches;
use App\Models\Offline_states;
use Illuminate\Http\Request;


class StatesController extends Controller
{

	public function index()
    {

        if (!auth()->user()->hasRole('administrator') ) {
            return abort(401);
        }

        if (request('show_deleted') == 1) {
            $states = Offline_states::onlyTrashed()->get();
        } else {
            $states = Offline_states::get();
        }
      

        return view('backend.states.index', compact('states'));

    }

    public function getData(Request $request)
    {
        $states = "";
        if (request('show_deleted') == 1) {
 
            $states = Offline_states::onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $states = Offline_states::orderBy('created_at', 'desc')->get();
        }

        return DataTables::of($states)
            ->addIndexColumn()
            ->addColumn('name', function ($q) {
                return $q->name;
            })
            ->addColumn('actions', function ($q) use ($request) {
            	$view = '';

                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.states', 'label' => 'state', 'value' => $q->id]);
                }
            
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.states.edit', ['states' => $q->id])])
                        ->render();
                    $view .= $edit;

                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.states.destroy', ['states' => $q->id])])
                        ->render();
                    $view .= $delete;

                return $view;
            })
            ->rawColumns(['actions','name'])
            ->make();
    }

    /**
     * Show the form for creating new Sates.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
       
        return view('backend.states.create');
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
            'state' => 'required|unique:offline_states,name',
        ] ,[ 
        	'state.required' => 'Branch is Required' ,
        	'state.unique' => 'This State is Already Added',	
        ]);


            $state = new  Offline_states();
            $state->name = $request->state;
        	$state->save();

        return redirect()->route('admin.states.index')->withFlashSuccess(trans('alerts.backend.general.created'));
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

        $states = Offline_states::findOrFail($id);

        return view('backend.states.edit', compact('states'));
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
            'name' => 'required|unique:offline_states,name,'.$id,
        ] ,[ 
        	'name.required' => 'State is Required' ,
        	'name.unique' => 'This State is Already Added',	
        ]);
        
        $state = Offline_states::findOrFail($id);
        $state->name = $request->name;
    	$state->save();

        return redirect()->route('admin.states.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
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
        
        $state = Offline_states::findOrFail($id);
        $state->delete();

        return redirect()->route('admin.states.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
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
        $state = Offline_states::onlyTrashed()->findOrFail($id);
        $state->restore();

        return redirect()->route('admin.states.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
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
        $state = Offline_states::onlyTrashed()->findOrFail($id);
        $state->forceDelete();

        return redirect()->route('admin.states.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

}



