@extends('system.layouts')

@section('content')

    <div class="app-content content container-fluid">
        <div class="content-wrapper">



            <div class="content-body"><!-- Search form-->
                <section id="search-website" class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">{{$pageTitle}}</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block pb-0" style="padding-bottom: 0px !important;">
                            {!! Form::open(['id'=>'s_form','route' => 'system.system-knowledge.search','method'=>'GET','style'=>'margin-bottom: 0px !important;']) !!}
                            <fieldset class="form-group position-relative mb-0">
                                <input type="text" name="q" class="form-control form-control-lg input-lg" id="iconLeft" value="{{request('q')}}" placeholder="{{__('Search Here')}}">
                                <div class="form-control-position">
                                    <a id="m_v" href="javascript:void(0);"><i class="ft-mic font-medium-4"></i></a>
                                </div>
                            </fieldset>
                            <input type="submit" style="visibility: hidden;" />

                            {!! Form::close() !!}

                        </div>
                        <!--Search Navbar-->

                        <!--/ Search Navbar-->
                        <!--Search Result-->
                        @if(isset($result))
                        <div id="search-results" class="card-block">
                            <div class="col-lg-12">
                                <p class="text-muted font-small-3">{{__('About')}} {{$result['total']}} {{__('results')}} ({{ number_format((microtime(true) - LARAVEL_START),7) }} seconds) </p>
                                <ul class="media-list row">
                                    <!--search with list-->
                                    @foreach($result['data'] as $key=> $value)
                                    <li class="media">
                                        <div class="media-body">
                                            <p class="lead mb-0"><a href="javascript:void();" onclick="urlIframe('{{route('system.system-knowledge.show',$value['id'])}}')">{{$value['name_'.$systemLang]}}</a></p>
                                            <p>{{str_limit(strip_tags($value['content_'.$systemLang]),280)}}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="text-xs-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-separate pagination-round pagination-flat">
                                            @if($result['prev_page_url'])
                                            <li class="page-item">
                                                <a class="page-link" href="{{$result['prev_page_url']}}&q={{request('q')}}" aria-label="Previous">
                                                    <span aria-hidden="true">« {{__('Prev')}}</span>
                                                    <span class="sr-only">{{__('Previous')}}</span>
                                                </a>
                                            </li>
                                            @endif
                                            @if($result['next_page_url'])
                                                <li class="page-item">
                                                    <a class="page-link" href="{{$result['next_page_url']}}&q={{request('q')}}" aria-label="Next">
                                                        <span aria-hidden="true">{{__('Next')}} »</span>
                                                        <span class="sr-only">{{__('Next')}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </section>
                <!--/ Search form -->

            </div>


        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

@endsection




@section('header')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/pages/search.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/pace.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/forms/selects/select2.min.css')}}">

@endsection;


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

    {{--<script src="{{asset('assets/system/js/scripts/pickers/dateTime/picker-date-time.js')}}" type="text/javascript"></script>--}}

    <script type="text/javascript">
        staffSelect('#staffSelect2');

        $dataTableVar = $('#egpay-datatable').DataTable({
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isDataTable = "true";
                }
            },
            "fnPreDrawCallback": function(oSettings) {
                for (var i = 0, iLen = oSettings.aoData.length; i < iLen; i++) {
                    if(oSettings.aoData[i]._aData[7] != ''){
                        oSettings.aoData[i].nTr.className = oSettings.aoData[i]._aData[7];
                    }
                }
            }

        });

        function filterFunction($this){
            if($this == false) {
                $url = '{{url()->full()}}?isDataTable=true';
            }else {
                $url = '{{url()->full()}}?isDataTable=true&'+$this.serialize();
            }

            $dataTableVar.ajax.url($url).load();
            $('#filter-modal').modal('hide');
        }

        $(function(){
            $('.datepicker').datetimepicker({
                viewMode: 'months',
                format: 'YYYY-MM-DD'
            });
        });







        var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition
        var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList
        var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent

        var recognition = new SpeechRecognition();

        $('#m_v').click(function(){
            recognition.start();
            $('#m_v').css('color','red');
        });

        recognition.onresult = function(event) {
            var last = event.results.length - 1;
            var word = event.results[last][0].transcript;
            $('#iconLeft').val(word);
            $('#s_form').submit();
        };

        recognition.onspeechend = function() {
            $('#m_v').removeAttr('style');
            recognition.stop();
        };

        recognition.onnomatch = function(event) {
            toastr.error('I didn\'t recognise that color.' , 'Error !', {"closeButton": true});
        };

        recognition.onerror = function(event) {
            toastr.error('Error occurred in recognition: ' + event.error, 'Error !', {"closeButton": true});
        };

    </script>
@endsection
