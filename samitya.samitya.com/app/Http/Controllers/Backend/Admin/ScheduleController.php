<?php 

namespace App\Http\Controllers\Backend\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeacherSchedule;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Gate;
use Auth;


class ScheduleController extends Controller
{

	public function index()
    {
        if (!Gate::allows('live_class_access') && !auth()->user()->hasRole('administrator') ) {
            return abort(401);
        }

        // Show the page
        return view('backend.schedule.index');

    }

    public function add_event(Request $request)
    {

    	// print_r($request->all());die;
      $schedule = TeacherSchedule::create($request->all());
      $schedule->user_id = Auth::user()->id;
      $schedule->save();
    }

    public function get_event(Request $request)
    {
        $data =[];
        // print_r($request->all());die;
       $schedule =  TeacherSchedule::where('user_id',Auth::user()->id)->get();

       // print_r($schedule);die;
       foreach ($schedule as $key => $value) {
           $e = array();
        $e['id'] = $value['id'];
        $e['start'] = $value['event_start'];
        $e['end'] = $value['event_end'];
        $e['backgroundColor'] = 'rgba(251, 95, 44, 0.78)';
        $e['textColor'] = '#fff';
        $e['borderColor'] = 'rgb(251, 95, 44, 0.92)';
        $e['overlap'] = false;
        $e['allDay'] = false;
        array_push($data, $e);
       }



     return json_encode($data);

    }

    public function update_event(Request $request)
    {
        // $event = Course::findOrFail($request->id);
        // $event->update($request->all());

        print_r($request->all());die;
    }



}
