<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreTeachersRequest;
use App\Http\Requests\Admin\UpdateTeachersRequest;
use App\Models\Auth\User;
use App\Models\Offline_supervisor;
use App\Models\Offline_states;
use App\Models\Offline_cities;
use App\Models\Offline_branches;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use Mail;
use App\Mail\SupervisorRegisterMail;

class SupervisorController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request('show_deleted') == 1) {

            $users = User::role('teacher')->onlyTrashed()->get();
        } else {
            $users = User::role('teacher')->get();
        }

        return view('backend.supervisor.index', compact('users'));
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
        $teachers = "";


        if (request('show_deleted') == 1) {

            $teachers = User::role('supervisor')->onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $teachers = User::role('supervisor')->orderBy('created_at', 'desc')->get();
        }

        return DataTables::of($teachers)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.supervisor', 'label' => 'supervisor', 'value' => $q->id]);
                }
//                if ($has_view) {
//                    $view = view('backend.datatable.action-view')
//                        ->with(['route' => route('admin.teachers.show', ['teacher' => $q->id])])->render();
//                }

                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.supervisor.edit', ['teacher' => $q->id])])
                        ->render();
                    $view .= $edit;


                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.supervisor.destroy', ['teacher' => $q->id])])
                        ->render();
                    $view .= $delete;

                // $view .= '<a class="btn btn-warning mb-1" href="' . route('admin.courses.index', ['teacher_id' => $q->id]) . '">' . trans('labels.backend.courses.title') . '</a>';

                return $view;

            })
            ->rawColumns(['actions', 'image'])
            ->make();
    }


    public function create()
    {
        $states  = Offline_states::get()->pluck('name','id')->prepend('', '');
        $branch = ['' => ''];
        $city = ['' => ''];

        return view('backend.supervisor.create',compact('states','branch','city'));
    }


    public function store(StoreTeachersRequest $request)
    {


        $user = User::create($request->all());
        $user->confirmed = 1;
        $user->save();

        $user->assignRole('supervisor');

        $supervisor = new Offline_supervisor;
        $supervisor->user_id = $user->id;
        $supervisor->state_id = $request->state_id;
        $supervisor->city_id = $request->city_id;
        $supervisor->branch_id = $request->branch_id;
        $supervisor->save();

        $content['name'] =  $request->first_name;
        $content['email'] = $request->email;
        $content['password'] = $request->password;
        Mail::to($request->email)->send(new SupervisorRegisterMail($content));

        return redirect()->route('admin.supervisor.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }



    public function edit($id)
    {

        $teacher = User::findOrFail($id);

        $supervisor =  Offline_supervisor::where('user_id',$id)->first();


        $states  = Offline_states::get()->pluck('name','id')->prepend('', '');
        $city  = Offline_cities::where('state_id',$supervisor->state_id)->get()->pluck('city','id')->prepend('', '');
        $branch  = Offline_branches::where('city_id',$supervisor->city_id)->get()->pluck('branch','id')->prepend('', '');


        return view('backend.supervisor.edit', compact('teacher','supervisor','states','city','branch'));
    }

    /**
     * Update Category in storage.
     *
     * @param  \App\Http\Requests\UpdateTeachersRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeachersRequest $request, $id)
    {

        $teacher = User::findOrFail($id);
        $teacher->update($request->except('email'));
        $teacher->save();

        $supervisor = Offline_supervisor::where('user_id',$id)->first();
        $supervisor->state_id = $request->state_id;
        $supervisor->city_id = $request->city_id;
        $supervisor->branch_id = $request->branch_id;
        $supervisor->save();

        return redirect()->route('admin.supervisor.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Category.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = User::findOrFail($id);

        return view('backend.supervisor.show', compact('teacher'));
    }


    /**
     * Remove Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $teacher = User::findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.supervisor.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Category at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {

        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $teacher = User::onlyTrashed()->findOrFail($id);
        $teacher->restore();

        return redirect()->route('admin.supervisor.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {

        $teacher = User::onlyTrashed()->findOrFail($id);
        $teacher->forceDelete();

        $supervisor = Offline_supervisor::where('user_id',$id)->first();
        $supervisor->delete();

        return redirect()->route('admin.supervisor.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
