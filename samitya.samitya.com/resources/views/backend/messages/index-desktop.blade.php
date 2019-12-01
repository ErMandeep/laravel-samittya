@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@push('after-styles')
    <style>
        textarea {
            resize: none;
        }
    </style>
@endpush
@section('content')
    <div class="card message-box">
        <div class="card-header">
            <h3 class="page-title mb-0">@lang('labels.backend.messages.title')


                <a href="{{route('admin.messages').'?threads'}}"
                   class="d-lg-none text-decoration-none threads d-md-none float-right">
                    <i class="icon-speech font-weight-bold"></i>
                </a>
            </h3>
            <p>Your all messages will be deleted after 21 days. </p>
        </div>
        <div class="card-body">
            <div class="messaging">
                <div class="inbox_msg">
                    <div class="inbox_people d-md-block d-lg-block ">
                        <div class="headind_srch">
                            @if(request()->has('thread'))
                            <div class="recent_heading btn-sm btn btn-dark">
                                <a class="text-decoration-none" href="{{route('admin.messages')}}">
                                    <h5 class="text-white mb-0"><i class="icon-plus"></i>&nbsp;&nbsp; @lang('labels.backend.messages.compose')</h5>
                                </a>
                            </div>
                            @endif
                            <div class="srch_bar @if(!request()->has('thread')) text-left @endif">
                                <div class="stylish-input-group">
                                    <input type="text" class="search-bar" id="myInput" placeholder="@lang('labels.backend.messages.search_user')">
                                    <span class="input-group-addon">
                                        <button type="button">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="inbox_chat">
                            @if($threads->count() > 0)
                                @foreach($threads as $item)
                                    @if($item->lastMessage)
                                        <a class="@if($item->unreadMessagesCount > 0) unread
                                            @endif" href="{{route('admin.messages').'?thread='.$item->id}}">
                                            <div data-thread="{{$item->id}}"
                                                 class="chat_list @if(($thread != "") && ($thread->id == $item->id))  active_chat @endif" >
                                                <div class="chat_people">
                                                    <div class="chat_ib">
                                                        <h5>{{ $item->title }} <span
                                                                    class="chat_date">{{ $item->lastMessage->created_at->diffForHumans() }}</span>
                                                            @if($item->unreadMessagesCount > 0)
                                                                <span class="badge badge-primary mr-5">{{$item->unreadMessagesCount}}</span>
                                                            @endif
                                                        </h5>
                                                        <p>{{ str_limit($item->lastMessage->body, 35) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @if(request()->has('thread'))
                        <form method="post" action="{{route('admin.messages.reply')}}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" id="thread_id" name="thread_id" value="{{isset($thread->id) ? $thread->id : 0}}">
                            <div class="headind_srch ">
                                <div class="chat_people box-header">
                                    <div class="chat_img float-left">
                                        <img src="https://ptetutorials.com/images/user-profile.png"
                                             alt="" height="35px"></div>

                                    <div class="chat_ib float-left">

                                        <h5 class="mb-0 d-inline float-left">{{$thread->title}}</h5>
                                        <p class="float-right d-inline mb-0">
                                            <a class="" href="{{route('admin.messages',['thread'=>$thread->id])}}">
                                                <i class="icon-refresh font-weight-bold"></i>
                                            </a>
                                        </p>
                    
                                        @if($is_bann || $thread_bann || $is_reported  )
                                        
                                        @else
                                        <a class="btn btn-xs btn-danger text-white mb-1 float-right" id="report_user">
                                            Report User 
                                        </a>
										@endif

										  
                                    </div>
                                </div>
                            </div>
                            <div class="mesgs">
                                <div class="msg_history">
                                    @if(count($thread->messages) > 0 )
                                        @foreach($thread->messages as $message)
                                            @if($message->sender_id == auth()->user()->id)
                                                <div class="outgoing_msg">
                                                    <div class="sent_msg">
                                                        @if (strpos($message->body, 'audio-') !== false) 
                                                           <audio src="{{ asset('storage/messages/'.$message->body) }}" controls></audio>
                                                        @elseif(strpos($message->body, 'file-') !== false)
                                                        	<a href="{{route('admin.messages.download',['message'=>$message->body])}}">  
                                                        		<div class="download_file">
                                                        		 <div class="file_name">{{ str_limit($message->body,35) }}</div>
                                                        		 <div class="download">Download</div>   
                                                        		</div>
                                                        	</a>
                                                        @else 
                                                            <p>{{$message->body}}</p>
                                                        @endif
                                                        <span class="time_date text-right"> {{\Carbon\Carbon::parse($message->created_at)->format('h:i A | M d Y')}}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="incoming_msg">
                                                    <div class="incoming_msg_img"><img
                                                                src="https://ptetutorials.com/images/user-profile.png"
                                                                alt=""></div>
                                                    <div class="received_msg">
                                                        <div class="received_withd_msg">
                                                            @if (strpos($message->body, 'audio-') !== false) 
                                                                <audio src="{{ asset('storage/messages/'.$message->body) }}" controls></audio>
                                                            @elseif(strpos($message->body, 'file-') !== false)
                                                        	<a href="{{route('admin.messages.download',['message'=>$message->body])}}">  
                                                        		<div class="download_file">
                                                        		 <div class="file_name">{{ str_limit($message->body,35)}}</div>
                                                        		 <div class="download">Download</div>   
                                                        		</div>
                                                        	</a>
                                                            @else 
                                                                <p>{{$message->body}}</p>
                                                            @endif
                                                            <span class="time_date">{{\Carbon\Carbon::parse($message->created_at)->format('h:i A | M d Y')}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                <div class="type_msg">


                                  @if($is_bann)
                                  <div class="block_user">You Cannot Reply to any conversation</div>
                                  @elseif($thread_bann)
                                  <div class="block_user">You Can't reply to this conversation</div>
                                    
                                  @else
                                  	<div class="input_msg_write">

                                        <div id="controls" class="col-sm-1" >
                                        	<i class="fas fa-plus-circle" id="uploadefile" data-toggle="tooltip" title="Upload Video"></i>
                                        	<input type="file" name="file" id="file" accept="video/*" style="display: none;">
                                            <i class="fas fa-microphone " id="recordButton" data-toggle="tooltip" title="Record"></i>
                                        </div>

                                        <div class="text_msg col-sm-11" >
                                            <textarea type="text" id="input_msg" name="message" class="write_msg"
                                                  placeholder="Type a message"></textarea>
                                             <div class="write_msg" id="input_file">
                                             	<span class="filename"></span>
                                             	<span id="removefile" class="btn  btn-danger" >Remove<span> </span></span>
                                             </div>
                                            <i class="fas fa-pause disable" id="pauseButton" disabled></i>
                                            <i class="fas fa-stop disable" id="stopButton" disabled></i>
                                            <button class="msg_send_btn" type="submit">
                                                <i class="icon-paper-plane" style="line-height: 2" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="uploade_msg">
                                        <p>Maximun Size Allowed to upload 4Mb</p>
                                    </div>
                                    <div class="msg_loader" style="display: none;">
                                        Please Wait...<img src="{{ asset('assets/images/msg_loading.gif') }}">
                                    </div>
                                  @endif



                                </div>
                                
                            </div>
                        </form>
                    @else
                        <form method="post" action="{{route('admin.messages.send')}}">
                            @csrf

                            <div class="headind_srch bg-dark">
                                <div class="chat_people header row">
                                    <div class="col-12 col-lg-3">
                                        <p class="font-weight-bold text-white mb-0" style="line-height: 35px">{{trans('labels.backend.messages.select_recipients')}}:</p>
                                    </div>
                                    <div class="col-lg-9 col-12 text-dark">
                                          {!! Form::select('recipients[]', $teachers, (request('teacher_id') ? request('teacher_id') : old('recipients')), ['class' => 'form-control ']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="mesgs">
                                <div class="msg_history">
                                    <p class="text-center">{{trans('labels.backend.messages.start_conversation')}}</p>
                                </div>
                                <div class="type_msg">
                                    <div class="input_msg_write">
                                        {{--<input type="text" class="write_msg" placeholder="Type a message"/>--}}
                                        <textarea type="text"  name="message" class="write_msg"
                                                  placeholder="{{trans('labels.backend.messages.type_a_message')}}"></textarea>
                                         
                                        <button class="msg_send_btn" type="submit">
                                            <i class="icon-paper-plane" style="line-height: 2" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
 <form action ="{{route('admin.messages.report_user')}}" id="report"  method="POST" name="report_user" >
										        @csrf
										        <input type="hidden" name="thread_id" value="{{@$thread->id}}">

										    </form>
@endsection
@push('after-scripts')
<script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>

<script>

        $(document).ready(function () {
            //Get to the last message in conversation
            $('.msg_history').animate({
                scrollTop: $('.msg_history')[0].scrollHeight
            }, 1000);

            //Read message
            setTimeout(function () {
                var thread = '{{request('thread')}}';
               var message =  $(".inbox_chat").find("[data-thread='" + thread + "']");
                message.parent('a').removeClass('unread');
                message.find('span.badge').remove();
            }, 500 );

            //Filter in conversation
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $(".chat_list").parent('a').filter(function () {
                    $(this).toggle($(this).find('h5,p').text().toLowerCase().trim().indexOf(value) > -1)
                });
            });

        });

var config = {
        routes: {
            send_audio: "{{route('admin.messages.send_audio')}}"
        }
    };
    </script>
        <script src="{{asset('assets/js/backend/message.js')}}"></script>
@endpush