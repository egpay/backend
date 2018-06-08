@extends('system.layouts')

@section('content')
    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-4 col-xs-12">
                </div>
                <div class="content-header-right col-md-8 col-xs-12">
                    <div class=" content-header-title mb-0" style="float: right;">
                        @include('system.breadcrumb')
                    </div>
                </div>
            </div>

            <div class="content-body"><!-- Spacing -->
                <div class="row">
                    <div class="col-md-12">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Ticket Info')}}
                                    {{link_to_route('system.tickets.edit',' '.__('Edit'),['id'=>$result->id],['class'=>'btn btn-outline-primary pull-right fa fa-pencil'])}}
                                </h4>
                            </div>
                            <div class="card-body collapse in">
                                <div class="card-block">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('Value')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{__('ID')}}</td>
                                                <td>{{$result->id}}</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Ticket subject')}}</td>
                                                <td><code>{{$result->subject}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Ticket details')}}</td>
                                                <td><code>{{$result->details}}</code></td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Issued date')}}</td>
                                                <td>{{$result->created_at}} ({{$result->created_at->diffForHumans()}})</td>
                                            </tr>

                                            <tr>
                                                <td>{{__('Created By')}}</td>
                                                <td>
                                                    {{link_to_route('system.staff.show',$result->createdBy->Fullname,['id'=>$result->created_by_staff_id])}}
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
                        </section>

                    </div>


                </div>

                <div class="row">
                    <div class="col-md-6">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Ticket comments')}}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="card-block">

                                    @foreach($result->comments as $comment)
                                    <div class="card card-outline-primary">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                {{__('By')}} {{$comment->staff->Fullname}}
                                            </h4>
                                        </div>
                                        <div class="card-block">
                                            {{$comment->comment}}
                                        </div>
                                        <div class="card-footer"><i class="fa fa-clock-o"></i> {{__('On')}} {{$comment->created_at}}</div>
                                    </div>
                                    @endforeach

                                    <hr>



                                </div>
                            </div>
                        </section>

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Add comment')}}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="card-block">
                                    <div class="row">
                                        {!! Form::open(['route' =>['system.tickets.comment',$result->id], 'method' => 'POST']) !!}

                                        <div class="col-md-12 form-group {!! formError($errors,'comment',true) !!}">
                                            <div class="controls">
                                                {{ Form::label('comment',__('Comment')) }}
                                                {!! Form::textarea('comment',isset($result->id) ? $result->comment:old('comment'),['class'=>'form-control ar']) !!}
                                            </div>
                                            {!! formError($errors,'comment') !!}
                                        </div>

                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                        </div>

                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>

                    <div class="col-md-6">
                        <section id="spacing" class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Ticket status')}}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="card-block">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>{{__('Date')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('By')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($result->AllStatus as $status)
                                        <tr>
                                            <td>{{$status->created_at}}</td>
                                            <td>
                                            @if($status->status == 'open')
                                                <span class="btn btn-outline-danger">{{ucfirst($status->status)}}</span>
                                            @elseif($status->status == 'closed')
                                                <span class="btn btn-outline-warning">{{ucfirst($status->status)}}</span>
                                            @elseif($status->status == 'done')
                                                <span class="btn btn-outline-success">{{ucfirst($status->status)}}</span>
                                            @endif
                                            </td>
                                            <td>{{$status->staff->Fullname}}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{__('Add status')}}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="card-block">
                                    <div class="row">
                                        {!! Form::open(['route' =>['system.tickets.status',$result->id], 'method' => 'POST']) !!}

                                        <div class="col-md-12 form-group {!! formError($errors,'status',true) !!}">
                                            <div class="controls">
                                                {{ Form::label('status',__('Comment')) }}
                                                {!! Form::select('status',$CommentStatus,null,['class'=>'form-control']) !!}
                                            </div>
                                            {!! formError($errors,'status') !!}
                                        </div>

                                        <div class="card-block card-dashboard">
                                            {!! Form::submit(__('Save'),['class'=>'btn btn-success pull-right']) !!}
                                        </div>

                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection

@section('header')
@endsection;

@section('footer')
@endsection
