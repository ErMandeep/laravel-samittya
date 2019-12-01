@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@push('after-styles')
    <style>
        textarea {
            resize: none;
        }
        .message-box .srch_bar {
         text-align: left; 
    }
    </style>
@endpush
@section('content')
    <div class="card message-box" id="mobile_view">
        <div class="card-header">
            <h3 class="page-title mb-0">All Messages
            </h3>
            <p>All Messages For Last 21 days. </p> 
        </div>
        <div class="card-body">
            <div class="messaging">
                <div class="inbox_msg">
                    <div class="inbox_people @if(request('thread'))  d-none  @endif d-md-block d-lg-block ">
                        <div class="headind_srch">
                            <div class="srch_bar">
                                <div class="stylish-input-group">
                                    <input type="text" class="search-bar" id="myInput" placeholder="Search">
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
                                        <a  href="{{route('admin.messages.show_messages').'?thread='.$item->id}}" class="">
                                            <div data-thread="{{$item->id}}"
                                                 class="chat_list ">
                                                <div class="chat_people">

                                                    <div class="chat_ib">
                                                        <h5>{{ $item->fulltitle }} <span
                                                                    class="chat_date">{{ $item->lastMessage->created_at->diffForHumans() }}</span>
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
                            <div class="headind_srch ">
                                <div class="chat_people box-header">
                                    <div class="chat_img float-left">
                                        <img src="https://ptetutorials.com/images/user-profile.png"
                                             alt="" height="35px"></div>
                                    <div class="chat_ib float-left">

                                        <h5 class="mb-0 d-inline float-left">{{ $user_one->name .' - '. $user_two->name}}</h5>
                                        <p class="float-right d-inline mb-0">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="mesgs">
                                <div class="msg_history">
                                        @foreach($thread as $message)
                                            @if($message->sender_id ==$user_one->id)
                                                <div class="outgoing_msg">
                                                    <div class="sent_msg">
                                                             @if (strpos($message->body, 'audio-') !== false) 
                                                                <audio src="{{ asset('storage/messages/'.$message->body) }}" controls></audio>
                                                            @elseif(strpos($message->body, 'file-') !== false)
                                                            <a href="{{route('admin.messages.download',['message'=>$message->body])}}">  
                                                                <div class="download_file">
                                                                 <div class="file_name">{{ str_limit($message->body,35)}}</div>
                                                                 <div class="download">Downlaod</div>   
                                                                </div>
                                                            </a>
                                                            @else 
                                                                <p>{{$message->body}}</p>
                                                            @endif
                                                        <span class="time_date text-right">  {{ $user_one->name }}  &nbsp{{\Carbon\Carbon::parse($message->created_at)->format('h:i A | M d Y')}}
                                                    </span></div>
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
                                                                 <div class="download">Downlaod</div>   
                                                                </div>
                                                            </a>
                                                            @else 
                                                                <p>{{$message->body}}</p>
                                                            @endif
                                                            <span class="time_date">{{ $user_two->name }}  &nbsp {{\Carbon\Carbon::parse($message->created_at)->format('h:i A | M d Y')}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
    
                                </div>
                            </div>
                    @endif
                    

                </div>
            </div>
        </div>
    </div>
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
                $(".inbox_chat").find("[data-thread='" + thread + "']").removeClass('unread');
            }, 1000);

            //Get compose message screen
            $(document).on('click', '.compose', function (e) {
                e.preventDefault();
                $('.inbox_people').addClass('d-none');
                $('.compose-message').removeClass('d-none');
                $('.compose-reply').addClass('d-none')
            });

            //Refresh page to get updated conversation
            $(document).on('click', '.refresh', function (e) {
                e.preventDefault();
                location.reload();
            });

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