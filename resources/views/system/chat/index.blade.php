@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="sidebar-left sidebar-fixed">
            <div class="sidebar"><div class="sidebar-content card hidden-md-down">
                    <div class="card-block chat-fixed-search">
                        <fieldset class="form-group position-relative has-icon-left m-0">
                            <input type="text" class="form-control" id="iconLeft4" placeholder="Search user">
                            <div class="form-control-position">
                                <i class="ft-search"></i>
                            </div>
                        </fieldset>
                    </div>
                    <div id="users-list" class="list-group position-relative">
                        <div class="users-list-padding" id="conversation_list">


                            @foreach($result as $key => $value)
                                <a href="javascript:void(0)" onclick="openChat({{$value->id}});" id="conversation_id_{{$value->id}}" class="chat-list-a list-group-item list-group-item-action media no-border">
                                    <div class="media-body">
                                        <h6 class="list-group-item-heading">
                                            @if($value->from_type == Auth::user()->modelPath && $value->from_id == Auth::id())
                                                {{str_limit($value->to->firstname.' '.$value->to->lastname,15)}}  <span class="font-small-3 float-xs-right primary"> {{$value->updated_at->diffForHumans()}}</span>
                                            @else
                                                {{str_limit($value->from->firstname.' '.$value->from->lastname,12)}}  <span class="font-small-3 float-xs-right primary"> {{$value->updated_at->diffForHumans()}}</span>
                                            @endif
                                        </h6>
                                        <p class="list-group-item-text text-muted">

                                            @php
                                            $getModelPosition = $value->getModelPosition(Auth::user()->modelPath,Auth::id());
                                            @endphp

                                            @if(!is_null($value->lastMessage))

                                                @php
                                                    if($getModelPosition == 'to' && $value->from_seen == 'yes'){
                                                        $seenTO = true;
                                                    }elseif($getModelPosition == 'from' && $value->to_seen == 'yes'){
                                                        $seenTO = true;
                                                    }else{
                                                        $seenTO = false;
                                                    }
                                                @endphp

                                                @if($seenTO)
                                                    <i class="ft-check primary font-small-2"></i>
                                                @elseif(!$getModelPosition)
                                                    <i class="fa fa-circle"></i>
                                                @else
                                                    <i class="fa fa-share"></i>
                                                @endif
                                                {{str_limit($value->lastMessage->message,20)}}
                                            @else
                                                <span class="tag tag-pill tag-success">{{__('New Conversation')}}</span>
                                            @endif


                                            @php
                                            $seen = $value->countUnseen(Auth::user()->modelPath,Auth::id());
                                            @endphp

                                            @if($seen)
                                                <span class="float-xs-right primary" id="conversation_new_id_{{$value->id}}">
                                                    <span class="float-xs-right primary"><span class="tag tag-pill tag-primary">{{$seen}}</span></span>
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="content-right">
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <section class="chat-app-window">

                    </section>

                    <div id="typing" style="background: #FFF;padding-left: 15px;"></div>



                    <section class="chat-app-form" id="chat-section" style="display: none;">
                        <form class="chat-app-input" onsubmit="sendMessageChat($('#chat-text-input').val()); return false;" id="chat-form">
                            <fieldset class="form-group position-relative has-icon-left col-xs-10 m-0">
                                <div class="form-control-position">
                                    <i class="icon-emoticon-smile"></i>
                                </div>
                                <input type="text" onkeyup="sendTyping($(this).val());" class="form-control" autocomplete="off" id="chat-text-input" placeholder="{{__('Type your message')}}">
                                <div class="form-control-position control-position-right">
                                    <i class="ft-image"></i>
                                </div>
                            </fieldset>
                            <fieldset class="form-group position-relative has-icon-left col-xs-2 m-0">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o hidden-lg-up"></i> <span class="hidden-md-down">Send</span></button>
                            </fieldset>
                        </form>
                    </section>



                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="chat-open-now">
@endsection
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/chat-application.css')}}">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
    <style>
        html, body{
            overflow: hidden;
            height: 100%;
        }
        .chat-app-window{
            height: 100% !important;
        }
        .load-more{
            background-color: cadetblue;
            padding: 10px 200px;
            border-radius: 40px !important;
        }

        chat-application-arrow-left{
            right: auto;
            left: -10px;
            border-right-color: #EDEEF0;
            border-left-color: transparent;
            position: absolute;
            top: 10px;
            width: 0;
            height: 0;
            content: '';
        }

    </style>
@endsection
@section('footer')
    <script src="{{asset('assets/system/js/chat-application.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/socket.io.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript">

        $lastModelType = '';
        $lastModelID   = '';
        $setTimeOutVar = [];
        // Socket
        socket = io("127.0.0.1:3000");
        socket.on('connect',function() {
            toastr.success('{{__("Successfully connect to chat server")}}', '{{__('Success')}}', {"closeButton": true});
        });

        socket.on('sendMessageSuccess',function($data) {
            $('#chat_spinner_'+$data).remove();
        });

        socket.on('adminLiftSide',function($data){
            $data['id'] = $data[1];
            $data['userName'] = $data[0];
            $data['date_time'] = $data[3];
            $data['message'] = $data[2];


            $seen = "<i class=\"fa fa-circle\"></i>";

            if($data['message'].length > 20){
                $message = $data['message'].substring(0,20)+'...';
            }else{
                $message = $data['message'];
            }

            if($('#chat-open-now').val() != $data['id']){
                $new = "<span class=\"float-xs-right primary\" id=\"conversation_new_id_"+$data['id']+"\" >\n" +
                    "        <span class=\"float-xs-right primary\"><span class=\"tag tag-pill tag-primary\">{{__('New')}}</span></span>\n" +
                    "      </span>";
            }else{
                $new = '';
            }


            $html = "<a href=\"javascript:void(0)\" onclick=\"openChat("+$data['id']+");\" id=\"conversation_id_"+$data['id']+"\" class=\"chat-list-a list-group-item list-group-item-action media no-border\">\n" +
                "  <div class=\"media-body\">\n" +
                "    <h6 class=\"list-group-item-heading\">\n" +
                "      "+$data['userName']+"\n" +
                "      <span class=\"font-small-3 float-xs-right primary\"> "+$data['date_time']+"</span>\n" +
                "    </h6>\n" +
                "    <p class=\"list-group-item-text text-muted\">\n" +
                "\n" +
                "      "+$seen+"\n" +
                "\n" +
                "      "+$message+"\n" +
                "\n" +
                "      "+$new+"\n" +
                "\n" +
                "    </p>\n" +
                "  </div>\n" +
                "</a>\n";

            $('#conversation_id_'+$data['id']).remove();
            $('#conversation_list').prepend($html);
            $('#conversation_id_'+$data['id']).effect("highlight", {}, 1000);
        });

        socket.on('message',function($data) {
            $mID = rand(10000,999999999)+rand()+rand();

            // Handle HTML Data
            $html = new Array;

            $html.push('<div class="chat-left" id="chat_id_'+$mID+'">');

            $myImage = $data[2];

            if(!empty($myImage) && !($lastModelType == $data[0] && $lastModelID == $data[1])){
                $html.push('<div class="chat-avatar">');
                $html.push('<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">');
                $html.push('<img src="{{asset('storage')}}/'+$myImage+'" title="'+$data[3]+'" />');
                $html.push('</a>');
                $html.push('</div>');
            }else{
                $html.push('<div class="chat-avatar">');
                $html.push('<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">');
                $html.push('</a>');
                $html.push('</div>');
            }

            $html.push('<div class="chat-body">');
            $html.push('<div class="chat-content">');

            if(!($lastModelType == $data[0]&& $lastModelID == $data[1])){
                $html.push('<i class="chat-application-arrow"></i>');
            }

            $html.push('<p>'+$data[4]+'</p>');
            $html.push('</div>');
            $html.push('</div>');
            $html.push('</div>');

            $('.chats').append($html.join("\n"));

            $(".chat-app-window").scrollTop($(".chat-app-window").height()+9999);

            $lastModelType = $data[0];
            $lastModelID   = $data[1];

        });

        socket.on('typing',function($data) {
            clearTimeout($setTimeOutVar[$data[0]]);
            if(empty($data[2])){
                $('#typing_'+$data[0]).remove();
            }else{
                if(empty($('#typing_'+$data[0]).html())){
                    $('#typing').append('<i id="typing_'+$data[0]+'">'+$data[1]+' {{__('is typing ...')}}</i>');
                }

                $setTimeOutVar[$data[0]] = setTimeout(function(){
                    $('#typing_'+$data[0]).remove();
                },3000);
            }
        });



        // Socket
        function openChat($id,$pageID){
            // Remove Typing
            if(!$pageID){
                $('#typing').html('');
                $url = '{{url('system/chat/get-conversation')}}/'+$id;
            }else{
                $('.load-more').text('{{__('Loading...')}}');
                $url = $pageID;
            }

            $.getJSON($url,function($data){
                if(!empty($data.conversation)){
                    if(!$pageID) {
                        $('#chat-open-now').val($id);
                        socket.emit('start', $data.accessID);
                    }else{
                        $('.load-more').remove();
                    }

                    $html = new Array;
                    if(!$pageID) {
                        $html.push('<div class="tag tag-default mb-1">' + $data.conversation['created_at'] + '</div>');
                        $html.push('<div class="chats">');
                    }

                    if(isset($data.messages['next_page_url'])){
                        $html.push(
                            '<a class="load-more-click" href="javascript:void(0);" onclick="openChat('+$id+',\''+$data.messages['next_page_url']+'\')">'+
                            '<div class="load-more tag tag-default mb-1">'+
                            '<i class="fa fa-long-arrow-up" aria-hidden="true"></i> {{__('Load More')}}...'+
                            '</div>'+
                            '</a>');
                    }

                    $lastModelType = '';
                    $lastModelID   = '';

                    if($data.messages['data']){
                        $.each($data.messages['data'],function ($key,$value) {

                            if($value.model_type == 'App\\Models\\Staff' && $value.model_id == '{{Auth::id()}}'){
                                $html.push('<div id="chat_messages_id_'+$value.id+'" class="chat">');
                            }else{
                                $html.push('<div id="chat_messages_id_'+$value.id+'" class="chat-left">');
                            }

                            if(!empty($value.user.avatar) && !($lastModelType == $value.model_type && $lastModelID == $value.model_id)){
                                $html.push('<div class="chat-avatar">');
                                $html.push('<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">');
                                $html.push('<img src="{{asset('storage')}}/'+$value.user.avatar+'" title="'+$value.user.name+'" />');
                                $html.push('</a>');
                                $html.push('</div>');
                            }else{
                                $html.push('<div class="chat-avatar">');
                                $html.push('<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">');
                                $html.push('</a>');
                                $html.push('</div>');
                            }

                            $html.push('<div class="chat-body">');
                            $html.push('<div class="chat-content">');


                            if(!($lastModelType == $value.model_type && $lastModelID == $value.model_id)){
                                $html.push('<i class="chat-application-arrow"></i>');
                            }


                            $html.push('<p>'+$value.message+'</p>');
                            $html.push('</div>');
                            $html.push('</div>');
                            $html.push('</div>');
                            if(isset($data.seenData[$value.id])){
                                $seenByHTML = new Array();
                                $.each($data.seenData[$value.id],function($seenKey,$seenValue){
                                    $seenByHTML.push(adminDefineUser($seenValue.model_type,$seenValue.model_id,$seenValue.model.firstname+' '+$seenValue.model.lastname));
                                });
                                $html.push('<p style="margin: auto;" class="time">{{__('Seen By')}}: ');
                                $html.push($seenByHTML.join(", "));
                                $html.push('</p>');
                            }


                            $lastModelType = $value.model_type;
                            $lastModelID   = $value.model_id;


                        });
                    }

                    if(!$pageID) {
                        $html.push('</div>');
                    }
                    // -- SHOW DATA
                    if(!$pageID) {
                        $('#chat-section').show();
                        $('.chat-app-window').html($html.join("\n"));
                    }else{
                        $('.chats').prepend($html.join("\n"));
                    }

                    // -- SHOW DATA
                    if(!$pageID) {
                        $('#chat-form')[0].reset();
                        $('#chat-text-input').focus();
                        $(".chat-app-window").attr('style', 'height:calc(100% - 112px) !important');
                        $(".chat-app-window").scrollTop($(".chat-app-window").height() + 9999);

                        $('#conversation_new_id_' + $id).remove();
                        $('.chat-list-a').removeAttr('style');
                        $('#conversation_id_' + $id).attr('style', 'background-color: #edeef0;');
                    }

                }else{
                    toastr.error('{{__("There are no conversation with this #ID:")}} '+$id, 'Error !', {"closeButton": true});
                }
            });
        }

        function sendMessageChat($message){
            $mID = rand(10000,999999999)+rand()+rand();

            // Send Data To socket
            socket.emit('sendMessage',[$mID,$('#chat-text-input').val()]);

            // Clear Form
            $('#chat-form')[0].reset();

            // Handle HTML Data
            $html = new Array;
            $html.push('<div class="chat" id="chat_id_'+$mID+'">');

            $myImage = '{{Auth::user()->avatar}}';

            if(!empty($myImage) && !($lastModelType == 'App\\Models\\Staff' && $lastModelID == '{{Auth::id()}}')){
                $html.push('<div class="chat-avatar">');
                $html.push('<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">');
                $html.push('<img src="{{asset('storage')}}/'+$myImage+'" title="{{Auth::user()->firstname.' '.Auth::user()->lastname}}" />');
                $html.push('</a>');
                $html.push('</div>');
            }else{
                $html.push('<div class="chat-avatar">');
                $html.push('<a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="" data-original-title="">');
                $html.push('</a>');
                $html.push('</div>');
            }

            $html.push('<div class="chat-body">');
            $html.push('<div class="chat-content">');

            if(!($lastModelType == 'App\\Models\\Staff' && $lastModelID == '{{Auth::id()}}')){
                $html.push('<i class="chat-application-arrow"></i>');
            }

            $html.push('<p>'+$message+' <i id="chat_spinner_'+$mID+'" class="fa fa-spinner"></i></p>');
            $html.push('</div>');
            $html.push('</div>');
            $html.push('</div>');

            $('.chats').append($html.join("\n"));

            $(".chat-app-window").scrollTop($(".chat-app-window").height()+9999);

            $lastModelType = 'App\\Models\\Staff';
            $lastModelID   = '{{Auth::id()}}';
        }

        function sendTyping($message){
            socket.emit('typing',$message);
        }

    </script>

@endsection