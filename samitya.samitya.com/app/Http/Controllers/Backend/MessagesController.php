<?php

namespace App\Http\Controllers\Backend;

use App\Models\Auth\User;
use App\Models\MessageThread;
use App\Models\MessageReport;
use App\Models\Course;
use App\Models\LiveClasses;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
use Messenger;
use Illuminate\Support\Facades\Storage;
use Mail;
use App\Mail\MessageMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use function foo\func;
use Auth;
use Gerardojbaez\Messenger\Models\MessageThreadParticipant;
use Gerardojbaez\Messenger\Models\Message;
// use Gerardojbaez\Messenger\Models\MessageThread;


class MessagesController extends Controller
{
    public function index(Request $request){

     
        $teachers = [];
        $thread="";
        $is_reported = false;
        $is_bann = false;
        $thread_bann = false;  


        if (auth()->user()->hasRole('administrator')) {

          $reg_teacher =  User::role('teacher')->get()->pluck('name', 'id')->all();
          $l_teacher = User::role('live teacher')->get()->pluck('name', 'id')->all();


        }
        else
        {

          $regular_course = User::findOrFail(auth()->user()->id)->purchasedCourses();
          $l_teacher = User::role('live teacher')->get()->pluck('name', 'id')->all();
          $regular_teacher = [];

          foreach ($regular_course as  $value) {
            
            $teaaaaaaa =  Course::where('id',$value->id)->firstOrFail();
            array_push($regular_teacher,$teaaaaaaa->teachers[0]['id']);

          }

          $reg_teacher =  User::role('teacher')->find($regular_teacher)->where('id','<>',auth()->user()->id)->pluck('name', 'id')->all();
        }

        $teachers = $reg_teacher + $l_teacher ;



        auth()->user()->load('threads.messages.sender');

        $unreadThreads = [];
        $threads = [];
        foreach(auth()->user()->threads as $item){
            if($item->unreadMessagesCount > 0){
                $unreadThreads[] = $item;
            }else{
                $threads[] = $item;
            }
        }
        $threads = Collection::make(array_merge($unreadThreads,$threads)) ;

       if(request()->has('thread')){

           if(request('thread')){
               $thread = auth()->user()->threads()
                   ->where('message_threads.id','=',$request->thread)
                   ->first();

               //Read Thread
                   if($thread){
                    auth()->user()->markThreadAsRead($thread->id);

                    $user_id = DB::table('message_thread_participants')->where('thread_id',$thread->id)->where('user_id','<>',auth()->user()->id)->first();

                    $user = User::findOrFail($user_id->user_id);

                   $reported = MessageReport::where('report_for',$user->id)->where('report_by',auth()->user()->id)->first();
                   $bann = MessageReport::where('report_for',auth()->user()->id)->where('can_message',0)->first();
                   $bann2 = MessageReport::where('report_for',$user->id)->where('can_message',0)->first();

                   if ($reported || $user->hasRole('administrator')) {
                       $is_reported = true;
                   }
                   if ($bann) {
                     $is_bann = true;
                   }
                   if ($bann2) {
                     $thread_bann = true;
                   }
                   // echo "<pre>";
                   // print_r($is_reported.'is_reported');
                   // echo "<br>";
                   //  print_r($is_bann.'is_bann');
                   // echo "<br>";
                   // print_r($thread_bann.'thread_bann');die;

                   }
                   else{
                     abort(404);
                   }
               
           }else if($thread == ""){
               abort(404);
           }
       }

        $agent = new Agent();

       if($agent->isMobile()){
           $view = 'backend.messages.index-mobile';
       }else{
           $view = 'backend.messages.index-desktop';
       }
        return view($view, [
//            'threads' => auth()->user()->threads,
            'threads' => $threads,
            'teachers' => $teachers,
            'thread' => $thread,
            'is_reported' => $is_reported,
            'is_bann' => $is_bann,
            'thread_bann' =>$thread_bann
        ]);
    }

    public function send(Request $request){
        $this->validate($request,[
           'recipients' => 'required',
           'message' => 'required'
        ],[
           'recipients.required' => 'Please select at least one recipient',
           'message.required' => 'Please input your message'
        ]);

        $message = Messenger::from(auth()->user())->to($request->recipients)->message($request->message)->send();

        $user = User::findOrFail($request->recipients[0]);

        $content['msg_type'] = 'text';
        $content['msg'] = $request->message;
        $content['user_data'] = $user;
       $this->send_mail($user->email,$content);



        return redirect(route('admin.messages').'?thread='.$message->thread_id);
    }

    public function reply(Request $request){

      $this->validate($request,[
            'message' => 'required_without:file',
            // 'file' => 'size:max:4096'
        ],[
            'message.required_without' => 'Please input your message',
            // 'file.size' => 'Please upload file size within 4 MB',
        ]);
     
      if (is_file($request->file)) {

        $file = $request->file;
        $extension = array_last(explode('.',$file->getClientOriginalName()));
        $name = array_first(explode('.',$file->getClientOriginalName()));
        $filename = 'file-'. str_slug($name) . '-' . time().'.'.$extension;
        $size = $file->getSize() / 1024;
        $file->move(public_path('storage/messages'), $filename);

        $thread = auth()->user()->threads()
            ->where('message_threads.id','=',$request->thread_id)
            ->first();

        $message = Messenger::from(auth()->user())->to($thread)->message($filename)->send();

        $user_id = DB::table('message_thread_participants')->where('thread_id',$request->thread_id)->where('user_id','<>',auth()->user()->id)->first();
        $user = User::findOrFail($user_id->user_id);

        $content['msg_type'] = 'file';
        $content['user_data'] = $user;
        $this->send_mail($user->email,$content);


      }
      else{
        $thread = auth()->user()->threads()
            ->where('message_threads.id','=',$request->thread_id)
            ->first();
        $message = Messenger::from(auth()->user())->to($thread)->message($request->message)->send();

        $user_id = DB::table('message_thread_participants')->where('thread_id',$request->thread_id)->where('user_id','<>',auth()->user()->id)->first();
        $user = User::findOrFail($user_id->user_id);

        $content['msg_type'] = 'text';
        $content['msg'] = $request->message;
        $content['user_data'] = $user;
       $this->send_mail($user->email,$content);
      }


        return redirect(route('admin.messages').'?thread='.$message->thread_id)->withFlashSuccess('Message sent successfully');
    }

    public function getUnreadMessages(Request $request){
        $unreadMessageCount = auth()->user()->unreadMessagesCount;
        $unreadThreads = [];
        foreach(auth()->user()->threads as $item){
            if($item->unreadMessagesCount > 0){
                $data = [
                  'thread_id' => $item->id,
                  'message' => str_limit($item->lastMessage->body, 35),
                  'unreadMessagesCount' => $item->unreadMessagesCount,
                  'title' => $item->title
                ];
                $unreadThreads[] = $data;
            }
        }
        return ['unreadMessageCount' =>$unreadMessageCount,'threads' => $unreadThreads];
    }

    public function send_audio(Request $request)
    {


      $file = $request->audio_data;
      $filename = 'audio-'.time() . '-' . auth()->user()->id.'.wav';
      $file->move(public_path('storage/messages'), $filename);

      $thread = auth()->user()->threads()
            ->where('message_threads.id','=',$request->thread_id)
            ->first();
      $message = Messenger::from(auth()->user())->to($thread)->message($filename)->send();

      $user_id = DB::table('message_thread_participants')->where('thread_id',$request->thread_id)->where('user_id','<>',auth()->user()->id)->first();
        $user = User::findOrFail($user_id->user_id);

        $content['msg_type'] = 'audio';
        $content['user_data'] = $user;
       $this->send_mail($user->email,$content);

      return route('admin.messages').'?thread='.$message->thread_id;
    }

    public function send_mail($user_email,$content)
    {


       Mail::to($user_email)->send(new MessageMail($content));
    }


    public function download(Request $request)
    {


                $file = public_path() . "/storage/messages/" . $request->message;

                return Response::download($file);
           
        
    }

    public function report_user(Request $request)
    {


      $user_id = DB::table('message_thread_participants')->where('thread_id',$request->thread_id)->where('user_id','<>',auth()->user()->id)->first();

      $user = User::findOrFail($user_id->user_id);

      MessageReport::create([
        'report_for' => $user->id,
        'report_by' => auth()->user()->id,
        'can_message' => '1',
        'ignore_user' => '0',

      ]);

      return redirect(route('admin.messages').'?thread='.$request->thread_id)->withFlashSuccess('Your Reported has Successfully Submitted For Review');

    }

    public function show_report()
    {
      if(!auth()->user()->hasRole('administrator')){
            return abort(401);
          }

      return view('backend.messages.reportedusers');
    }

    public function get_report(Request $request)
    {
          if(!auth()->user()->hasRole('administrator')){
           return abort(401);
          }

       $report = MessageReport::where('can_message','1')
                 ->where('ignore_user','0')
                 ->groupBy('report_for')
                 ->orderBy('created_at', 'desc')
                 ->get();

        return DataTables::of($report)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) {
  

                $view = view('backend.datatable.action-report-user')
                    ->with(['route' => route('admin.messages.report_by_admin',['user' => $q->report_for ])])->render();

                $view .= view('backend.datatable.action-ignore-user')
                    ->with(['route' => route('admin.messages.ignore_by_admin',['user' => $q->report_for ])])->render();
                return $view;

            })
            ->editColumn('reported_user', function ($q) {


                $user = User::findOrFail($q->report_for);
                $reported_user = '<span class="label label-info label-many">' . $user->name . ' </span>';
     
                return $reported_user;
            })

            ->editColumn('no_of_reports', function ($q) {

                $count = MessageReport::where('report_for',$q->report_for)->count();
                $no_of_reports = '<span class="label label-info label-many">' . $count  . ' </span>';

  
                return $no_of_reports;
            })
            ->rawColumns(['reported_user', 'no_of_reports', 'actions'])
            ->make();
    }


    public function report_by_admin($id)
    {
      if(!auth()->user()->hasRole('administrator')){
            return abort(401);
          }

       $r =  MessageReport::where('report_for',$id)->update(array('can_message' => '0'));

          return view('backend.messages.reportedusers')->withFlashSuccess('User Messages Blocked');

        
    }

      public function ignore_by_admin($id)
    {
      if(!auth()->user()->hasRole('administrator')){
            return abort(401);
          }
       $r =  MessageReport::where('report_for',$id)->update(array('ignore_user' => '0'));

          return view('backend.messages.reportedusers')->withFlashSuccess('');   
    }

    public function  show_messages(Request $request)
    {


        $thread="";
        $threads = [];
        $user_id = [];
        $user_one =[];
        $user_two= [];

        $threads = MessageThread::get();

       if(request()->has('thread')){

          if(request('thread')){

                  $thread = Message::where('thread_id',$request->thread)->get()->all();
              
              
                if ($thread) {
                  $get_user_id = MessageThreadParticipant::where('thread_id',$request->thread)->get();
                  foreach ($get_user_id as  $value) {

                     array_push($user_id, $value->user_id);
                  }
                  $user_one = User::find($user_id[0]) ;
                  $user_two = User::find($user_id[1]) ;
              }
              else{
               abort(404);

              }
                
              
          }
          else if($thread == ""){
               abort(404);
          }
       }

        $agent = new Agent();

       if($agent->isMobile()){
           $view = 'backend.messages.showmessages-mobile';
       }else{
           $view = 'backend.messages.showmessages';
       }
        return view($view, [
            'threads' => $threads,
            'thread' => $thread,
            'user_one'=> $user_one,
            'user_two'=> $user_two,
        ]);
    }



}
