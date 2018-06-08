<div class="modal fade text-xs-left" id="reply-email-modal"  role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <label class="modal-title text-text-bold-600" id="myModalLabel33">{{__('Send Email')}}</label>
            </div>
            {!! Form::open(['id'=>'reply-email-form','method'=>'POST','route'=>'panel.merchant.mail.store','onsubmit'=>'return false;']) !!}
            <div class="modal-body">

                <div class="card-body">
                    <div class="card-block">
                        <div class="row">
                            {{Form::hidden('reply_to',$result->id)}}

                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('subject',__('Subject')) }}
                                    {!! Form::text('subject',null,['class'=>'form-control','id'=>'subject']) !!}
                                    <p class="text-xs-left"><small id="reply-subject-error" class="danger text-muted"></small></p>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('body',__('Body')) }}
                                    {!! Form::textarea('body',null,['class'=>'form-control','id'=>'body']) !!}
                                    <p class="text-xs-left"><small id="reply-body-error" class="danger text-muted"></small></p>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group">
                                    {{ Form::label('file',__('Attachment')) }}
                                    {!! Form::file('file',['class'=>'form-control','id'=>'file']) !!}
                                    <p class="text-xs-left"><small id="reply-file-error" class="danger text-muted"></small></p>
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



<div class="email-app-options card-block">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="btn-group" role="group" aria-label="Basic example" style="float: right;">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#reply-email-modal" data-placement="top" data-original-title="Replay"><i class="fa fa-reply"></i></button>
                <button onclick="deleteRecord('{{route('panel.merchant.mail.destroy',$result->id)}}')" type="button" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="ft-trash-2"></i></button>
            </div>
        </div>

    </div>
</div>

<div class="email-app-title card-block">
    <h3 class="list-group-item-heading">
        {{$result->subject}}
        <p style="float: right;">
            <a href="javascript:void()" onclick="star()">
                @if($result->starForStaff(Auth::id())->first())
                    <i class="fa fa-star" aria-hidden="true"></i>
                @else
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                @endif
            </a>
        </p>
    </h3>
    <p class="list-group-item-text">
        <span class="primary">
            @if($result->senderType == 'staff')
                    <span class="tag tag-primary">
                        {{__('From Staff')}} : {{$result->sendermodel->firstname}} {{$result->sendermodel->lastname}}
                    </span>
            @elseif($result->senderType  == 'merchant')
                    <span class="tag tag-primary">
                        {{__('From Merchant')}} : {{$result->sendermodel->{'name_'.$systemLang} }}
                    </span>
            @elseif($result->senderType  == 'merchant_staff')
                <a target="_blank" href="{{route('panel.merchant.employee.show',$result->sendermodel->id)}}">
                    <span class="tag tag-primary">
                        {{__('From Merchant Staff')}} : {{$result->sendermodel->firstname}} {{$result->sendermodel->lastname}}
                    </span>
                </a>
            @endif
        </span>

        <span class="success">
            @foreach($receivers as $onereceiver)
                {{dd($onereceiver)}}
                <span class="tag tag-success">
                    @if($onereceiver instanceof \App\Models\Staff)
                        {{__('To Staff')}}
                    @elseif($onereceiver instanceof \App\Models\MerchantStaff)
                        @if($onereceiver->id == \Illuminate\Support\Facades\Auth::user()->id)
                            {{__('To Me')}}
                        @else
                            {{__('To Merchant')}}
                        @endif

                    @endif
                     @if($onereceiver instanceof \App\Models\Merchant)
                            : {{$onereceiver->name_en}} {{$onereceiver->name_ar}}
                     @else
                            : {{$onereceiver->firstname}} {{$onereceiver->lastname}}
                     @endif
                </span>
            @endforeach
        </span>
        <span class="float-xs-right text muted">{{$result->created_at->diffForHumans()}}</span>

    </p>
</div>

<div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1" class="card-collapse collapse in" aria-expanded="false">
    <div class="card-body">
        <div class="card-block">
            @php
            $body = explode("\n",$result->body);
            @endphp

            @foreach($body as $value)
                <p>{{$value}}</p>
            @endforeach


            @if($result->file)
            <div class="col-md-12">
                <a target="_blank" href="{{asset('storage/app/'.$result->file)}}">
                    <i class="fa-paperclip fa"></i> {{$result->file}}
                </a>
            </div>
            @endif

        </div>
    </div>
</div>




@foreach($parent as $key => $value)
@php
    $key = $key+100;
@endphp
<div id="headingCollapse{{$key}}" class="card-header p-0">
    <a data-toggle="collapse" href="#collapse{{$key}}" aria-expanded="false" aria-controls="collapse{{$key}}" class="">
        <div class="email-app-sender list-group-item list-group-item-action media no-border bg-blue-grey bg-lighten-5">
            <div class="media-body">
                <h6 class="list-group-item-heading">{{$value->subject}}</h6>
                <p class="list-group-item-text">
        <span class="primary">
            @if($value->senderType == 'staff')
                <a target="_blank" href="{{route('system.staff.show',$value->sendermodel->id)}}">
                    <span class="tag tag-primary">
                        {{__('From Staff')}} : {{$value->sendermodel->firstname}} {{$value->sendermodel->lastname}}
                    </span>
                </a>
            @elseif($value->senderType == 'merchant')
                <a target="_blank" href="{{route('merchant.merchant.show',$value->sendermodel->id)}}">
                    <span class="tag tag-primary">
                        {{__('From Merchant')}} : {{$value->sendermodel->{'name_'.$systemLang} }}
                    </span>
                </a>
            @elseif($value->senderType == 'merchant_staff')
                <a target="_blank" href="{{route('merchant.staff.show',$value->sendermodel->id)}}">
                    <span class="tag tag-primary">
                        {{__('From Merchant Staff')}} : {{$value->sendermodel->firstname}} {{$value->sendermodel->lastname}}
                    </span>
                </a>
            @else
                <span class="tag tag-success">
                    {{__('To EGPAY')}}
                </span>
            @endif
        </span>

                    <span class="success">
            @if($value->receiverType == 'staff')
                            <a target="_blank" href="{{route('system.staff.show',$value->receivermodel->id)}}">
                    <span class="tag tag-success">
                        {{__('To Staff')}} : {{$value->receivermodel->firstname}} {{$value->receivermodel->lastname}}
                    </span>
                </a>
                        @elseif($value->receiverType == 'merchant')
                            <a target="_blank" href="{{route('merchant.merchant.show',$value->receivermodel->id)}}">
                    <span class="tag tag-success">
                        {{__('To Merchant')}} : {{$value->receivermodel->{'name_'.$systemLang} }}
                    </span>
                </a>
                        @elseif($value->receiverType == 'merchant_staff')
                            <a target="_blank" href="{{route('merchant.staff.show',$value->receivermodel->id)}}">
                    <span class="tag tag-success">
                        {{__('To Merchant Staff')}} : {{$value->receivermodel->firstname}} {{$value->receivermodel->lastname}}
                    </span>
                </a>
                        @else
                            <span class="tag tag-success">
                       {{__('To EGPAY')}}
                    </span>
                        @endif
        </span>
                    <span class="float-xs-right text muted">{{$value->created_at->diffForHumans()}}</span>
                </p>

            </div>
        </div>
    </a>
</div>
<div id="collapse{{$key}}" role="tabpanel" aria-labelledby="headingCollapse{{$key}}" class="card-collapse collapse" aria-expanded="true">
    <div class="card-body">
        <div class="email-app-text card-block">
            <div class="email-app-message">
                @php
                    $body = explode("\n",$value->body);
                @endphp

                @foreach($body as $valueBody)
                    <p>{{$valueBody}}</p>
                @endforeach


                @if($value->file)
                <div class="col-md-12">
                    <a target="_blank" href="{{asset('storage/app/'.$value->file)}}">
                        <i class="fa-paperclip fa"></i> {{$value->file}}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endforeach

<div class="email-app-text-action card-block">

</div>

<script type="text/javascript">


    function star(){
        $.getJSON('{{route('panel.merchant.mail.show',[$result->id,'star'=>'true'])}}',function($data){
           if($data.status == true){
               toastr.success($data.msg, '{{__('Success')}}', {"closeButton": true});
           }else{
               toastr.error($data.msg, '{{__('Error !')}}', {"closeButton": true});
           }
        });
    }

    $(document).ready(function(){

        $('#reply-email-form').submit(function(){
            $('#reply-subject-error,#reply-body-error,#reply-file-error').html('');

            $(this).ajaxSubmit({
                success:function(response){
                    if(response.status == true){
                        toastr.success(response.msg, '{{__('Success')}}', {"closeButton": true});
                    }else{
                        toastr.error(response.msg, '{{__('Error !')}}', {"closeButton": true});
                    }
                    $('#reply-email-form')[0].reset();
                    $('#reply-email-modal').modal('hide');

                },
                error: function(jqXHR,textStatus,errorThrown){
                    $errors = jqXHR.responseJSON;
                    console.log($errors);
                    $.each($errors,function($key,$value){
                        $errorMsgForOneField = '';
                        $.each($value,function($mKey,$mValue){
                            $errorMsgForOneField += $mValue+'<br />';
                        });
                        $('#reply-'+$key+'-error').html($errorMsgForOneField);
                    });
                }
            });


        });

    });
</script>