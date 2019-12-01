<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;

use App\Models\Offline_cities;
use App\Models\Offline_branches;
use App\Models\Offline_states;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use function foo\func;
use Auth;


class CitiesController extends Controller
{


	public function index()
    {

        if (!auth()->user()->hasRole('administrator') ) {
            return abort(401);
        }

        if (request('show_deleted') == 1) {
            $cities = Offline_cities::onlyTrashed()->get();
        } else {
            $cities = Offline_cities::get();
        }
             
        

        return view('backend.cities.index', compact('cities'));

    }

    public function getData(Request $request)
    {


        $cities = "";


        if (request('show_deleted') == 1) {
 
            $cities = Offline_cities::onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $cities = Offline_cities::orderBy('created_at', 'desc')->get();
        }

        // print_r($cities);die;


        return DataTables::of($cities)
            ->addIndexColumn()
            ->addColumn('name', function ($q) {
                return $q->city;
            })
            ->addColumn('state', function ($q) {
                return $q->state->name;
            })
            ->addColumn('actions', function ($q) use ($request) {
            	$view = '';

                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.cities', 'label' => 'tags', 'value' => $q->id]);
                }
            
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.cities.edit', ['cities' => $q->id])])
                        ->render();
                    $view .= $edit;

                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.cities.destroy', ['cities' => $q->id])])
                        ->render();
                    $view .= $delete;

                return $view;

            })
            ->rawColumns(['actions','name'])
            ->make();
    }


    /**
     * Show the form for creating new Tag.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ( !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        $states = Offline_states::get()->pluck('name','id');
        
        return view('backend.cities.create',compact('states'));
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
            'city' => 'required|unique:offline_cities',
        ] ,[ 
        	'city.required' => 'City  is Required' ,
        	'city.unique' => 'This City is Already Added',	
        ]);


            $cities = new  Offline_cities();
            $cities->city = $request->city;
            $cities->state_id = $request->state_id;
        	$cities->save();

        return redirect()->route('admin.cities.index')->withFlashSuccess(trans('alerts.backend.general.created'));


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

        $cities = Offline_cities::findOrFail($id);
        $states = Offline_states::get()->pluck('name','id');

        return view('backend.cities.edit', compact('cities','states'));
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
            'city' => 'required|unique:offline_cities,city,'.$id,
        ] ,[ 
        	'city.required' => 'City  is Required' ,
        	'city.unique' => 'This City is Already Added',	
        ]);
        
        $cities = Offline_cities::findOrFail($id);
        $cities->city = $request->city;
        $cities->state_id = $request->state_id;
        $cities->save();

        return redirect()->route('admin.cities.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
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
        
        $cities = Offline_cities::findOrFail($id);
        $cities->delete();

        return redirect()->route('admin.cities.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
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
        $cities = Offline_cities::onlyTrashed()->findOrFail($id);
        $cities->restore();

        return redirect()->route('admin.cities.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
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
        $cities = Offline_cities::onlyTrashed()->findOrFail($id);
        $cities->forceDelete();

        return redirect()->route('admin.cities.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
