<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use App\Models\Offline_student;
use App\Models\Offline_states;
use App\Models\Offline_cities;
use App\Models\Offline_invoice;
use App\Models\Auth\User;
use App\Models\Offline_branches;
use App\Models\Offline_supervisor;
use App\Models\Offline_payments;
use App\Models\Category;
use Illuminate\Http\Request;
use function foo\func;
use Auth;
use Mail;
use Newsletter;
use App\Models\Auth\Role;
use App\Mail\OfflineRegisterMail;
use App\Mail\OfflinePaymentReminderMail;
use App\Mail\PaymentDoneMail;
use App\Models\Config;
use Response;
use SendFile;
use Illuminate\Support\Str; 

class OfflineStudentController extends Controller
{
    public $month;
    public $date;

    public function __construct()
    {
        date_default_timezone_set("Asia/Kolkata");
        $this->date ='5';
        $this->month = date('F Y');
        if (date('d') < 5 ) {
                $this->month = date('F Y' ,strtotime('-1 Month' ));
        }

        view()->share('month', $this->month);
        view()->share('date', $this->date);
    }

	public function index(Request $request)
	{

		if (!Gate::allows('offline_students_access') &&  !auth()->user()->hasRole('administrator') ) {
            return abort(401);
        }
        // print_r($request->state);
        // die;
        $states  = Offline_states::get()->pluck('name','id')->prepend('All', '');
        $category  = Category::get()->pluck('name','id')->prepend('All', '');
        $city = ['' => ''];
        $branch = ['' => ''];
        $status = ['' => '' ,'0' => 'Active' ,'1' => 'Temporary Leave'];
        $fees = ['' => '' ,'1' => 'Paid' , '0' => 'Pending'];

        if ($request->state) {

            $city = Offline_cities::where('state_id',$request->state)->pluck('city','id')->prepend('', '');
        }
        if ($request->city) {

            $branch =  Offline_branches::where('city_id',$request->city)->pluck('branch','id')->prepend('', '');
        }

        return view('backend.offline_student.index', compact('states','category','branch','city','status','fees'));
	}

    // Show Students to admin 
	public function getData(Request $request)
    {   

        $students = new Offline_student;

        if (request('show_deleted') == 1) 
        {
            if (Auth::user()->isAdmin()) {
               $user = Offline_student::onlyTrashed()->orderBy('created_at', 'desc')->get();
            }
        } 
        else{
             if (Auth::user()->isAdmin()) {
                $user = Offline_student::orderBy('created_at', 'desc')->get();
            }
            else{
            	$supervisor = Offline_supervisor::where('user_id',Auth::user()->id)->first();

                // $user = Offline_student::orderBy('created_at', 'desc')->get();
                $user = Offline_student::where('branch_id',$supervisor->branch_id)->orderBy('created_at', 'desc')->get();
                // $user = Offline_student::orderBy('created_at', 'desc')->where('supervisor_id',Auth::user()->id)->get();
            }
        }

        return DataTables::of($user)
            ->addIndexColumn()
            ->editColumn('category', function ($q) {
                return $q->category->name;
            })
            ->filter(function ($q) use ($request) {
                        if ($request->filled('state')) 
                        {
                            $q->collection = $q->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['state_id'], $request->get('state')) ? true : false;
                            });
                        }
                        if ($request->filled('city')) 
                        {
                            $q->collection = $q->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['city_id'], $request->get('city')) ? true : false;
                            });
                        }
                        if ($request->filled('branch')) 
                        {
                            $q->collection = $q->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['branch_id'], $request->get('branch')) ? true : false;
                            });
                        }
                        if ($request->filled('category')) 
                        {
                            $q->collection = $q->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['category_id'], $request->get('category')) ? true : false;
                            });
                        }
                        if ($request->filled('status')) 
                        {
                            $q->collection = $q->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['temp_off'], $request->get('status')) ? true : false;
                            });
                        }
                        if ($request->filled('fees')) 
                        {
                            $q->collection = $q->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['paid_this_month'],$request->get('fees')) ? true : false;
                            });
                        }
                    })

            ->editColumn('payment', function ($q) {
                if ($q->temp_off) {
                    $payment = '-';
                }
                else{
                    if ($q->paid_this_month) {
                     $payment = 'Paid';
                    }
                  else{
                    $payment = 'Pending';
                  }
                }
            	  
                return $payment;
            })
            ->editColumn('status', function ($q) {
                  if ($q->temp_off) {
                     $status = 'Temporary on leave';
                  }
                  else{
                    $status = 'Active';
                  }
                return $status;
            })
            ->editColumn('fee_plan', function ($q) {

                return $q->fee_plan.' Month';
            })
            ->addColumn('actions', function ($q) use ($request) {
            	$view = '';
                $temp_off = '';

                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.offline-student', 'label' => 'offline-student', 'value' => $q->id]);
                }
                $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.offline-student.edit', ['cities' => $q->id])])
                        ->render();
                    $view .= $edit;
                if (Auth::user()->isAdmin()) {
                     

                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.offline-student.destroy', ['cities' => $q->id])])
                        ->render();
                    $view .= $delete;
                }


                $view .= view('backend.datatable.action-view')
                      ->with(['route' => route('admin.offline-student.show', ['student' => $q->id])])->render();


                  if ($q->temp_off) {
                      $temp_off = true ;
                  }
                $view .=view('backend.datatable.action-temp-off')
                      ->with(['route' => route('admin.offline-student.temp_off', ['student' => $q->id]) , 'temp_off' => $temp_off ])->render();
                if (!$q->paid_this_month && $q->temp_off == 0) {
                     $view .=view('backend.datatable.action-mail-reminder')
                      ->with(['route' => route('admin.offline-student.payment_reminder', ['student' => $q->id])])->render();
                    }
            
                return $view;

            })
            ->rawColumns(['actions','city','branch','supervisor','status'])
            ->make();
    }

    // Show Student Create Page
    public function create()
    {
        if (!Gate::allows('offline_students_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }

        $supervisor = User::whereHas('roles', function ($q) {
            $q->where('role_id', 7);
        })->get()->pluck('name', 'id')->prepend('', '');


        // $city  = Offline_cities::get()->pluck('city','id')->prepend('', '');
        $states  = Offline_states::get()->pluck('name','id')->prepend('', '');
        $category  = Category::get()->pluck('name','id')->prepend('', '');
        $branch = ['' => ''];
        $city = ['' => ''];
      
        return view('backend.offline_student.create',compact('city','category','branch','states','supervisor'));
    }

    // Get branches of city with ajax 
    public function get_cities(Request $request)
    {

        $branches  = Offline_cities::select('id','city as text')->where('state_id',$request->id)->get();
        
        return $branches;

    }
    // Get branches of branch with ajax 
    public function get_branches(Request $request)
    {

        $branches  = Offline_branches::select('id','branch as text')->where('city_id',$request->id)->get();

        return $branches;

    }



     // Store Student to Db
    public function store(Request $request)
    {   

        if (!Gate::allows('offline_students_access') & !auth()->user()->hasRole('administrator')) {
            return abort(401);
        }
        if (!auth()->user()->hasRole('administrator')) {

        	$supervisor = Offline_supervisor::where('user_id',Auth::user()->id)->first();

	        $request->request->add(['state_id' => $supervisor->state_id]);
	        $request->request->add(['city_id' => $supervisor->city_id]);
	        $request->request->add(['branch_id' => $supervisor->branch_id]);
        }

        $this->validate($request, [
            'email' => 'required|unique:offline_student,email',
            'joining_date'=> 'required',
            'name'=> 'required',
            'state_id'=> 'required',
            'city_id'=> 'required',
            'branch_id'=> 'required',
            'category_id'=> 'required',
            'fees'=> 'required',
            'phone_no'=> 'required',


        ] ,[ 
            'name.required' => 'Student Name is Required' ,
            'email.required' => 'Student Email is Required' ,
            'email.unique' => 'This Email is Already in Use',  
            'state_id.required' => 'State is Required',
            'city_id.required' => 'City is Required',
            'branch_id.required' => 'Branch is Required',
            'category_id.required' => 'Category is Required',
            'joining_date.required' => 'Date is Required',
            'fees.required' => 'Fees is Required',
            'phone_no.required' => 'Phone is Required',
        ]);


        $get_state = Offline_states::find($request->state_id);
        $get_city = Offline_cities::find($request->city_id);
        $get_branch = Offline_branches::find($request->branch_id);
        $no_of_students = $get_branch->students()->count();
        $digits_of_students = strlen((string) $no_of_students); 

        if ($digits_of_students == 1 || $digits_of_students = 2 ) {
           $serial =  str_pad($no_of_students + 1, 3, 0, STR_PAD_LEFT);
        }
        else{
            $serial = $no_of_students;
        }

        $state = substr($get_state->name, 0, 2);
        $city = substr($get_city->city, 0, 2);
        $branch = substr($get_branch->branch, 0, 2);
        $student = substr($request->name, 0, 2);

        $student_id = strtoupper($state.$city.$branch.$student.$serial);


        $data = Offline_student::create($request->all());
        $data->student_id = $student_id;
        $data->paid_this_month = 0;
        $data->assignRole('offline users');
        $data->save();

        $content['name'] = $request->name;
        $content['student_id'] = $student_id;

        Mail::to($request->email)->send(new OfflineRegisterMail($content));

         if (config('mail_provider') != "" && config('mail_provider') == "mailchimp") {

                    Newsletter::subscribe($request->email);
                }

        return redirect()->route('admin.offline-student.index')->withFlashSuccess(trans('alerts.backend.general.created'));

    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('administrator') & !Gate::allows('offline_students_access')) {
            return abort(401);
        }

        $supervisor = User::whereHas('roles', function ($q) {
            $q->where('role_id', 7);
        })->get()->pluck('name', 'id')->prepend('', '');

        $student = Offline_student::findorfail($id);

        $states  = Offline_states::get()->pluck('name','id')->prepend('', '');
        $category  = Category::get()->pluck('name','id')->prepend('', '');
        $city  = Offline_cities::where('state_id',$student->state_id)->get()->pluck('city','id')->prepend('', '');
        $branch  = Offline_branches::where('city_id',$student->city_id)->get()->pluck('branch','id')->prepend('', '');

        return view('backend.offline_student.edit',compact('city','category','branch','states','supervisor','student'));
    }

    public function update(Request $request, $id)
    {   
    
        if (!auth()->user()->hasRole('administrator') & !Gate::allows('offline_students_access')) {
            return abort(401);
        }

        if (!auth()->user()->hasRole('administrator')) {

            $supervisor = Offline_supervisor::where('user_id',Auth::user()->id)->first();

            $request->request->add(['state_id' => $supervisor->state_id]);
            $request->request->add(['city_id' => $supervisor->city_id]);
            $request->request->add(['branch_id' => $supervisor->branch_id]);
        }

        $this->validate($request, [
            'joining_date'=> 'required',
            'name'=> 'required',
            'state_id'=> 'required',
            'city_id'=> 'required',
            'branch_id'=> 'required',
            'category_id'=> 'required',
            'fees'=> 'required',
            'phone_no'=> 'required',
        ] ,[ 
            'name.required' => 'Student Name is Required' ,
            'state_id.required' => 'State is Required',
            'city_id.required' => 'City is Required',
            'branch_id.required' => 'Branch is Required',
            'category_id.required' => 'Category is Required',
            'joining_date.required' => 'Date is Required',
            'fees.required' => 'Fees is Required',
            'phone_no.required' => 'Phone is Required',
        ]);

        $data = Offline_student::findorfail($id);

        $data->update($request->except('email'));



        return redirect()->route('admin.offline-student.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    // show 
    public function show($id)
    {

        $user = Offline_student::findorfail($id);
        $payments = Offline_payments::where('s_id',$user->id)->orderBy('created_at', 'desc')->get();

        return view('backend.offline_student.show',compact('user','payments'));
    }

    public function destroy($id)
    {

        $student = Offline_student::findOrFail($id);
        $student->delete();

        // return view('backend.offline_student.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
        return redirect()->route('admin.offline-student.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }


    public function restore($id)
    {
        $student = Offline_student::onlyTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->route('admin.offline-student.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    public function perma_del($id)
    {

        $student = Offline_student::onlyTrashed()->findOrFail($id);
        $student->forceDelete();

        return redirect()->route('admin.offline-student.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    // Show Dashboard To students 
    public function show_dashboard()
    {   
        $old_payment = Offline_payments::where('s_id',Auth('offline')->user()->id)->where('status',1)->latest()->first();
        $pending_payments = Offline_payments::where('s_id',Auth('offline')->user()->id)->where('status',0)->get();
        // print_r($pending_payments);die;

        return view('backend.offline_student.payment',compact('old_payment','pending_payments'));
    }

    // Store payment done by students
    public function payment(Request $request)
    {
        
        $user  = Auth('offline')->user();

        $payment = new Offline_payments();

        $payment->s_id = $user->id;
        $payment->fees = $user->fees;
        $payment->month = $this->month;
        // $payment->expire_on = date('F Y' , strtotime($user->fee_plan.'Month'));
        $payment->expire_on = date('F Y' , strtotime($user->fee_plan.'Month' , strtotime($this->month)));
        $payment->fee_plan = $user->fee_plan;
        // $payment->year = date('Y');
        $payment->paid_at = date('m/d/Y H:i:s', time());
        $payment->status = 1;
        $payment->razorpay_payment_id = $request->razorpay_payment_id;
        $payment->save();


        $pay =  Offline_student::findorfail($user->id);
        $pay->paid_this_month = 1 ;
        $pay->save(); 

        $this->invoice($payment,$user);

        return redirect()->route('offline.dashboard')->withFlashSuccess('Payment Done');
    }
    public function pending_payment(Request $request, $id)
    {

       $payment =  Offline_payments::findorfail($id);
        if ($payment->status == 0 && $payment->s_id == Auth('offline')->user()->id ) {
                $payment->paid_at = date('m/d/Y H:i:s', time());
                $payment->status = 1;
                $payment->razorpay_payment_id = $request->razorpay_payment_id;
                $payment->save();

                $this->invoice($payment,$user);

                return redirect()->route('offline.dashboard')->withFlashSuccess('Payment Done');
        }
        return abort(404);
    }

    // Show payments To Student
    public function show_payment()
    {

        $payments = Offline_payments::where('s_id',auth('offline')->user()->id)->orderBy('created_at', 'desc')->get();

        return view('backend.offline_student.show_payment',compact('payments'));
    }


    public function temp_off($id)
    {

        $student =  Offline_student::findorfail($id);
        if ($student->temp_off) {
             $student->temp_off = 0;
        }
        else{
            $student->temp_off = 1;
        }
            $student->save();

        return redirect()->route('admin.offline-student.index')->withFlashSuccess(trans('alerts.backend.general.updated'));


    }

    public function payment_reminder($id)
    {
        $student = Offline_student::findorfail($id);

        $content['name'] = $student->name;
        $content['month'] = Date('F');

        Mail::to($student->email)->send(new OfflinePaymentReminderMail($content));

        return redirect()->route('admin.offline-student.index')->withFlashSuccess('Mail Send Successfully');
    }

    public function invoice($payment,$user)
    {
        $invoice = \ConsoleTVs\Invoices\Classes\Invoice::make();
            $title = $user->category->name.' Class';
            $price = $payment->fees;
            $qty = 1;
            $id = $user->category->id;
            $invoice->addItem($title, $price, $qty, $id);

        $invoice->number($payment->id)
            ->notes('Hi '.$user->name.'<br> Here is your receipt for '.$date.' '.$payment->month.' - '.$data.' '.$payment->expire_on)
            ->customer([
                'name' =>$user->name,
                'id' => $user->id,
                'email' => $user->email
            ])
            ->save('public/offline-invoices/invoice-'.$payment->id.'.pdf');
               // ->download('invoice-'.'offline'.'.pdf');

        $invoiceEntry = Offline_invoice::where('payment_id','=',$payment->id)->first();
        if($invoiceEntry == ""){
            $invoiceEntry = new Offline_invoice;
            $invoiceEntry->user_id = $user->id;
            $invoiceEntry->payment_id = $payment->id;
            $invoiceEntry->name = 'invoice-'.$payment->id.'.pdf';
            $invoiceEntry->save();
        }
        $content['name'] = $user->name;
        $content['month'] = $payment->month;
        $invoice = public_path() . "/storage/offline-invoices/" . $invoiceEntry->name;
        // $invoice = SendFile::get($file);
        Mail::to($user->email)->send(new PaymentDoneMail($content,$invoice));
    }

    public function getInvoice(Request $request)
    {

        if (auth()->check() ) {
            $invoice = Offline_invoice::where('payment_id','=',$request->id)->first();


            if (auth('web')->user()->isAdmin() || ($invoice->user_id == auth('offline')->user()->id) ) {
                $file = public_path() . "/storage/offline-invoices/" . $invoice->name;
                return Response::download($file);
            }
        }
        elseif (auth('offline')->check()) {

            $invoice = Offline_invoice::where('payment_id','=',$request->id)->first();

            if ($invoice->user_id == auth('offline')->user()->id ) {
                $file = public_path() . "/storage/offline-invoices/" . $invoice->name;
                return Response::download($file);
            }
        }
        return abort(404);
    }


}
