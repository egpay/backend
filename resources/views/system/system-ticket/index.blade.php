@extends('system.layouts')

<div class="modal fade text-xs-left" id="send-email-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Send Email')}}</label>
            </div>
            {!! Form::open(['id'=>'send-email-form','method'=>'POST','route'=>'system.system-ticket.store','onsubmit'=>'return false;']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">

                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    {{ Form::label('send_to_type',__('Send To')) }}
                                    {!! Form::select('send_to_type',['merchant'=>__('Merchant'),'staff'=>__('Staff')],null,['class'=>'form-control col-md-12']) !!}
                                </fieldset>
                            </div>



                            <div id="merchant-selects">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('merchant_id',__('Merchant')) }}
                                        {!! Form::select('merchant_id',[''=>__('Select Merchant')],null,['style'=>'width: 100%;' ,'id'=>'merchantSelect2','class'=>'form-control col-md-12']) !!}
                                    </fieldset>
                                </div>
                                <div class="col-md-12" style="display: none;" id="created_by_merchant_staff_id_div">
                                <fieldset class="form-group">
                                    {{ Form::label('receivermodel_id',__('Send To Merchant Staff')) }}
                                    <div>
                                        {!! Form::select('receivermodel_id',[''=>__('Send to all Merchant Staff')],null,['style'=>'width: 100%;' ,'id'=>'created_by_merchant_staff_id','class'=>'form-control col-md-12']) !!}
                                    </div>
                                    <p class="text-xs-left"><small id="receivermodel_id-error" class="danger text-muted"></small></p>
                                </fieldset>
                            </div>
                            </div>

                            <div id="staff-selects" style="display: none;">
                                <div class="col-md-6">
                                    <fieldset class="form-group">
                                        {{ Form::label('staff_id',__('Staff')) }}
                                        {!! Form::select('staff_id',[''=>__('Select Staff')],null,['style'=>'width: 100%;' ,'id'=>'staffSelect2','class'=>'form-control col-md-12']) !!}
                                    </fieldset>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('subject',__('Subject')) }}
                                    {!! Form::text('subject',null,['class'=>'form-control','id'=>'subject']) !!}
                                    <p class="text-xs-left"><small id="subject-error" class="danger text-muted"></small></p>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('body',__('Body')) }}
                                    {!! Form::textarea('body',null,['class'=>'form-control','id'=>'body']) !!}
                                    <p class="text-xs-left"><small id="body-error" class="danger text-muted"></small></p>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('file',__('Attachment')) }}
                                    {!! Form::file('file',['class'=>'form-control','id'=>'file']) !!}
                                    <p class="text-xs-left"><small id="file-error" class="danger text-muted"></small></p>
                                    <span style="color: #7b7b7b;float:right;">jpeg,bmp,png,zip,rar,pdf,doc,docx,xls,xlsx</span>
                                </fieldset>
                            </div>




                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="reset" class="btn btn-outline-secondary btn-md" value="{{__('Reset Form')}}">
                <input type="submit" class="btn btn-outline-primary btn-md" value="{{__('Send')}}">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@section('content')
    <div class="app-content content container-fluid">
        <div class="sidebar-left">
            <div class="sidebar">
                <div class="sidebar-content email-app-sidebar">
                    <div class="email-app-menu col-md-5 card hidden-md-down">
                        <div class="form-group form-group-compose text-xs-center">
                            <button data-toggle="modal" data-target="#send-email-modal" type="button" class="btn btn-danger btn-block my-1"><i class="ft-mail"></i> Compose</button>
                        </div>
                        <h6 class="text-muted text-bold-500 mb-1">{{__('Messages')}}</h6>
                        <div class="list-group list-group-messages">

                            <a href="{{route('system.system-ticket.index',['type'=>'inbox'])}}" @if($type == 'inbox') class="list-group-item active no-border" @else class="list-group-item list-group-item-action no-border" @endif>
                                <i class="ft-inbox mr-1"></i> {{__('Inbox')}}
                                @if($inboxCount)
                                    <span class="tag tag-default tag-pill float-xs-right">{{$inboxCount}}</span>
                                @endif
                            </a>
                            <a href="{{route('system.system-ticket.index',['type'=>'sent'])}}" @if($type == 'sent') class="list-group-item active no-border" @else class="list-group-item list-group-item-action no-border" @endif><i class="fa fa-paper-plane-o mr-1"></i> Sent</a>
                            <a href="{{route('system.system-ticket.index',['type'=>'star'])}}" @if($type == 'star') class="list-group-item active no-border" @else class="list-group-item list-group-item-action no-border" @endif><i class="ft-star mr-1"></i> Starred </a>
                        </div>

                    </div>
                    <div class="email-app-list-wraper col-md-7 card p-0">
                        <div class="email-app-list">
                            <div class="card-block chat-fixed-search" style="padding: 1rem !important;">
                                {!! Form::open(['route'=>'system.system-ticket.index','method'=>'GET'])  !!}
                                <input type="hidden" name="type" value="{{request('type')}}">
                                <fieldset class="form-group position-relative has-icon-left m-0 pb-1">
                                    <input value="{{request('q')}}" type="text" class="form-control" id="iconLeft4" name="q" placeholder="{{__('Search email')}}">
                                    <div class="form-control-position">
                                        <i class="ft-search"></i>
                                    </div>
                                </fieldset>
                                <input type="submit" style="display: none;">
                                {!! Form::close() !!}
                            </div>

                            <div id="users-list" class="list-group">
                                <div class="users-list-padding">


                                    @foreach($result as $key => $value)
                                        @if($type == 'inbox')
                                            <a href="javascript:void(0);" onclick="readMail({{$value->id}})" class="list-group-item list-group-item-action media no-border">
                                                <div class="media-body">
                                                    <h6 class="list-group-item-heading @if($value->seen == null)text-bold-500 @endif">
                                                        @if(isset($value->sendermodel->{'name_'.$systemLang}))
                                                            {{$value->sendermodel->{'name_'.$systemLang} }}
                                                        @elseif(isset($value->sendermodel->firstname))
                                                            {{$value->sendermodel->firstname}} {{$value->sendermodel->lastname}}
                                                        @else
                                                            {{__('EGPAY')}}
                                                        @endif
                                                        <span class="float-xs-right">
                                                        @if(!is_null($value->file))
                                                                <i class="fa-paperclip fa"></i>
                                                            @endif
                                                            <span class="font-small-2 primary">{{$value->created_at->diffForHumans()}}</span>
                                                    </span>
                                                    </h6>
                                                    <p class="list-group-item-text text-truncate @if($value->seen == null)text-bold-500 @endif">{{str_limit($value->subject,26)}}</p>
                                                    <p class="list-group-item-text">
                                                        {{str_limit(strip_tags($value->body),20)}}
                                                        @if($value->receiver_star == 'yes')
                                                            <span class="float-xs-right primary"><i class="fa fa-star"></i></span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </a>
                                        @elseif($type == 'star')
                                                <a href="javascript:void(0);" onclick="readMail({{$value->id}})" class="list-group-item list-group-item-action media no-border">
                                                    <div class="media-body">
                                                        <h6 class="list-group-item-heading @if($value->seen == null)text-bold-500 @endif">
                                                            @if(isset($value->sendermodel->{'name_'.$systemLang}))
                                                                {{$value->sendermodel->{'name_'.$systemLang} }}
                                                            @elseif(isset($value->sendermodel->firstname))
                                                                {{$value->sendermodel->firstname}} {{$value->sendermodel->lastname}}
                                                            @else
                                                                {{__('EGPAY')}}
                                                            @endif
                                                            <span class="float-xs-right">
                                                        @if(!is_null($value->file))
                                                                    <i class="fa-paperclip fa"></i>
                                                                @endif
                                                                <span class="font-small-2 primary">{{$value->created_at->diffForHumans()}}</span>
                                                    </span>
                                                        </h6>
                                                        <p class="list-group-item-text text-truncate @if($value->seen == null)text-bold-500 @endif">{{str_limit($value->subject,26)}}</p>
                                                        <p class="list-group-item-text">
                                                            {{str_limit(strip_tags($value->body),20)}}
                                                            @if($value->receiver_star == 'yes')
                                                                <span class="float-xs-right primary"><i class="fa fa-star"></i></span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </a>
                                        @else
                                            <a href="javascript:void(0);" onclick="readMail({{$value->id}})" class="list-group-item list-group-item-action media no-border">
                                                <div class="media-body">
                                                    <h6 class="list-group-item-heading">
                                                        @if(isset($value->receivermodel->{'name_'.$systemLang}))
                                                            {{$value->receivermodel->{'name_'.$systemLang} }}
                                                        @elseif(isset($value->receivermodel->firstname))
                                                            {{$value->receivermodel->firstname}} {{$value->receivermodel->lastname}}
                                                        @else
                                                            {{__('EGPAY')}}
                                                        @endif
                                                        <span class="float-xs-right">
                                                        @if(!is_null($value->file))
                                                            <i class="fa-paperclip fa"></i>
                                                        @endif
                                                        <span class="font-small-2 primary">{{$value->created_at->diffForHumans()}}</span>
                                                    </span>
                                                    </h6>
                                                    <p class="list-group-item-text text-truncate">{{str_limit($value->subject,26)}}</p>
                                                    <p class="list-group-item-text">
                                                        {{str_limit(strip_tags($value->body),20)}}
                                                        @if($value->sender_star == 'yes')
                                                            <span class="float-xs-right primary"><i class="fa fa-star"></i></span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </a>
                                        @endif

                                    @endforeach


                                        <a href="javascript:void(0);" class="list-group-item list-group-item-action media no-border">
                                            <div class="media-body">
                                                <h6 class="list-group-item-heading">
                                                    <span class="float-xs-left">
                                                        <p class="list-group-item-text text-truncate text-bold-500 ">{{$result->total()}} {{__('entries')}}</p>
                                                    </span>
                                                    <span class="float-xs-right">
                                                        @if($result->previousPageUrl())
                                                        <button onclick="location = '{{$result->appends(['type'=> $type,'q'=>request('q')])->previousPageUrl()}}'" type="button" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" data-placement="top" data-original-title="Previous"><i class="fa fa-angle-left"></i></button>
                                                        @endif

                                                        @if($result->nextPageUrl())
                                                        <button onclick="location = '{{$result->appends(['type'=> $type,'q'=>request('q')])->nextPageUrl()}}'" type="button" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" data-placement="top" data-original-title="Next"><i class="fa fa-angle-right"></i></button>
                                                        @endif
                                                    </span>
                                                </h6>
                                            </div>
                                        </a>
                                </div>
                            </div>
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
                    <div class="card email-app-details hidden-md-down">
                        <div class="card-body" id="email-content">
                            <h1 style="color: #00b5b8; padding-top: 50%;"><p style="text-align: center;">Please Select Email To Read it </p></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/email-application.css')}}">
@endsection
@section('footer')
    <script src="{{asset('assets/system')}}/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/system/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <script src="{{asset('assets/system/js/email-application.js')}}" type="text/javascript"></script>
    <script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){


            $('#send_to_type').change(function(){
                $value = $(this).val();
                if($value == 'staff'){
                    $('#merchant-selects').hide();
                    $('#staff-selects').show();
                }else{
                    $('#staff-selects').hide();
                    $('#merchant-selects').show();
                }
            });

            $('#send-email-form').submit(function(){
                $('#merchant_id-error,#send_to_id-error,#subject-error,#body-error,#file-error').html('');

                $(this).ajaxSubmit({
                    success:function(response){

                        if(response.status == true){
                            toastr.success(response.msg, '{{__('Success')}}', {"closeButton": true});
                        }else{
                            toastr.error(response.msg, '{{__('Error !')}}', {"closeButton": true});
                        }

                        $('#send-email-form')[0].reset();
                        $('#send-email-modal').modal('hide');

                    },
                    error: function(jqXHR,textStatus,errorThrown){
                        $errors = jqXHR.responseJSON;
                        console.log($errors);
                        $.each($errors,function($key,$value){
                            $errorMsgForOneField = '';
                            $.each($value,function($mKey,$mValue){
                                $errorMsgForOneField += $mValue+'<br />';
                            });
                            $('#'+$key+'-error').html($errorMsgForOneField);
                        });
                    }
                });


            });

        });

//        CKEDITOR.replace('body');

        ajaxSelect2('#merchantSelect2','merchant');
        ajaxSelect2('#staffSelect2','staff');

        $('#merchantSelect2').change(function(){
            // Staff
            $.getJSON('{{route('system.ajax.get')}}',{
                'type': 'getMerchantStaff',
                'merchant_id': $(this).val()
            },function($data){

                $newData = new Array;
                $newData.push('<option value="">{{__('Send to all Merchant Staff')}}</option>');
                $.each($data,function(key,value){
                    $newData.push('<option value="'+value.id+'">'+value.firstname+' '+value.lastname+'</option>');
                });

                $('#created_by_merchant_staff_id').html($newData.join("\n"));
                $('#created_by_merchant_staff_id_div').show();
            });


        });


        function readMail($id){

            $('#email-content').html(
                '<h1 style="color: #00b5b8; padding-top: 35%;"><p style="text-align: center;"><img src="{{asset('assets/system/loading.gif')}}"></p></h1>'
            );

            $actionURL = '{{url('system/system-ticket/')}}';
            console.log($actionURL);
            $.ajax({
                method: 'GET',
                url: $actionURL+'/'+$id,
                success: function(response){
                    if(response.status == true){
                        $('#email-content').html(response.html);
                    }else{
                        toastr.error(response.msg, '{{__('Error !')}}', {"closeButton": true});
                        $('#email-content').html(
                            '<h1 style="color: #00b5b8; padding-top: 50%;"><p style="text-align: center;">Please Select Email To Read it </p></h1>'
                        );
                    }
                },
                error: function(jqXHR,textStatus,errorThrown){
                    toastr.error(textStatus, '{{__('Error !')}}', {"closeButton": true});
                    $('#email-content').html(
                      '<h1 style="color: #00b5b8; padding-top: 50%;"><p style="text-align: center;">Please Select Email To Read it </p></h1>'
                    );
                }
            });


        }


    </script>
@endsection