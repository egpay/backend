<!DOCTYPE html>
<html lang='en'>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet prefetch' href='//cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
    <script src='//cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js'></script>
    <link rel='stylesheet prefetch' href='//cdn.plyr.io/2.0.13/plyr.css'>
    <style class="cp-pen-styles">/* Font Family
================================================== */

        @import url('https://fonts.googleapis.com/css?family=Oxygen:300,400,700');


        /* Global Styles
        ================================================== */

        html,body {
            -webkit-font-smoothing:antialiased;
            -webkit-text-size-adjust:100%;
            background-color:#00A5A8;
            color:#fff;
            font-size:105%;
            font-family:"Oxygen", HelveticaNeue, "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight:300;
            letter-spacing:.025rem;
            line-height:1.618;
            padding:1rem 0;
        }

        * {
            -webkit-tap-highlight-color:rgba(0,0,0,0);
            -webkit-tap-highlight-color:transparent;
        }


        /* Setup
        ================================================== */

        .container { position:relative; margin:0 auto; max-width:800px; width:100%; }
        .column { width:inherit; }


        /* Typography / Links
        ================================================== */

        p { color:#fff; display:block; font-size:.9rem; font-weight:400; margin:0 0 2px; }

        a,a:visited { color:#8cc3e6; outline:0; text-decoration:underline; }
        a:hover,a:focus { color:#bbdef5; }
        p a,p a:visited { line-height:inherit; }


        /* Misc.
        ================================================== */

        .add-bottom { margin-bottom:2rem !important; }
        .left { float:left; }
        .right { float:right; }
        .center { text-align:center; }


        /* Audio Player Styles
        ================================================== */

        audio {
            margin:0 15px 0 14px;
            width:670px;
        }

        #mainwrap {}

        #audiowrap,
        #plwrap {
            margin:0 auto;
        }

        #tracks {
            position:relative;
            text-align:center;
        }

        #nowPlay {
            display:inline;
        }

        #npAction {
            padding:21px;
            position:absolute;
        }

        #npTitle {
            padding:21px;
        }

        #plList li {
            cursor:pointer;
            display:block;
            margin:0;
            padding:21px 0;
        }

        #plList li:hover {
            background-color:rgba(0,0,0,.1);
        }

        .plItem {
            position:relative;
        }

        .plTitle {
            left:50px;
            overflow:hidden;
            position:absolute;
            right:65px;
            text-overflow:ellipsis;
            top:0;
            white-space:nowrap;
        }

        .plNum {
            padding-left:21px;
            width:25px;
        }

        .plLength {
            padding-left:21px;
            position:absolute;
            right:21px;
            top:0;
        }

        .plSel,
        .plSel:hover {
            background-color:rgba(0,0,0,.1);
            color:#fff;
            cursor:default !important;
        }

        a[id^="btn"] {
            border-radius:3px;
            color:#fff;
            cursor:pointer;
            display:inline-block;
            font-size:2rem;
            height:35px;
            line-height:.8;
            margin:0 20px 20px;
            padding:10px;
            text-decoration:none;
            transition:background .3s ease;
            width:35px;
        }

        a[id^="btn"]:last-child {
            margin-left:-4px;
        }

        a[id^="btn"]:hover,
        a[id^="btn"]:active {
            background-color:rgba(0,0,0,.1);
            color:#fff;
        }

        a[id^="btn"]::-moz-focus-inner {
            border:0;
            padding:0;
        }


        /* Plyr Overrides
        ================================================== */

        .plyr--audio .plyr__controls {
            background-color:transparent;
            border:none;
            color:#fff;
            padding:20px 20px 20px 13px;
            width:100%;
        }

        .plyr--audio .plyr__controls button.tab-focus:focus,
        .plyr--audio .plyr__controls button:hover,
        .plyr__play-large {
            background:rgba(0,0,0,.1);
        }

        .plyr__progress--played, .plyr__volume--display {
            color:rgba(0,0,0,.1);
        }

        .plyr--audio .plyr__progress--buffer,
        .plyr--audio .plyr__volume--display {
            background:rgba(0,0,0,.1);
        }

        .plyr--audio .plyr__progress--buffer {
            color:rgba(0,0,0,.1);
        }


        /* Media Queries
        ================================================== */

        @media only screen and (max-width:850px) {
            #nowPlay { display:none; }
        }
    </style>


</head>
<body>
<div class="container">
    <div class="column add-bottom">
        <div id="mainwrap">
            <div id="nowPlay">
                <span class="left" id="npAction">{{__('Paused')}}...</span>
                <span class="right" id="npTitle"></span>
            </div>
            <div id="audiowrap">
                <div id="audio0">
                    <audio preload id="audio1" controls="controls">{{__('Your browser does not support HTML5 Audio!')}}</audio>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src='//api.html5media.info/1.1.8/html5media.min.js'></script>
<script src='//cdn.plyr.io/2.0.13/plyr.js'></script>
<script >// External Files:
    // https://api.html5media.info/1.1.8/html5media.min.js (enables <video> and <audio> tags in all major browsers)
    // https://cdn.plyr.io/2.0.13/plyr.js


    // HTML5 audio player + playlist controls...
    // Inspiration: http://jonhall.info/how_to/create_a_playlist_for_html5_audio
    // Mythium Archive: https://archive.org/details/mythium/
    jQuery(function ($) {
        'use strict'
        var supportsAudio = !!document.createElement('audio').canPlayType;
        if (supportsAudio) {
            var index = 0,
                playing = false,
                mediaPath = '{{asset('storage/app/')}}',
                extension = '',
                tracks = [
                    {
                    "track": 1,
                    "name": '{{$result->msgsendermodel->firstname.' '.$result->msgsendermodel->lastname.' ( '.$result->created_at->diffForHumans().' )'}}',
                    "length": "2:46",
                    "file": "/{{$result->path}}"
                    }],
                buildPlaylist = $.each(tracks, function(key, value) {
                    var trackNumber = value.track,
                        trackName = value.name,
                        trackLength = value.length;
                    if (trackNumber.toString().length === 1) {
                        trackNumber = '0' + trackNumber;
                    } else {
                        trackNumber = '' + trackNumber;
                    }
                    $('#plList').append('<li><div class="plItem"><div class="plNum">' + trackNumber + '.</div><div class="plTitle">' + trackName + '</div><div class="plLength">' + trackLength + '</div></div></li>');
                }),
                trackCount = tracks.length,
                npAction = $('#npAction'),
                npTitle = $('#npTitle'),
                audio = $('#audio1').bind('play', function () {
                    playing = true;
                    npAction.text('Now Playing...');
                }).bind('pause', function () {
                    playing = false;
                    npAction.text('Paused...');
                }).bind('ended', function () {
                    npAction.text('Paused...');
                    if ((index + 1) < trackCount) {
                        index++;
                        loadTrack(index);
                        audio.play();
                    } else {
                        audio.pause();
                        index = 0;
                        loadTrack(index);
                    }
                }).get(0),
                btnPrev = $('#btnPrev').click(function () {
                    if ((index - 1) > -1) {
                        index--;
                        loadTrack(index);
                        if (playing) {
                            audio.play();
                        }
                    } else {
                        audio.pause();
                        index = 0;
                        loadTrack(index);
                    }
                }),
                btnNext = $('#btnNext').click(function () {
                    if ((index + 1) < trackCount) {
                        index++;
                        loadTrack(index);
                        if (playing) {
                            audio.play();
                        }
                    } else {
                        audio.pause();
                        index = 0;
                        loadTrack(index);
                    }
                }),
                li = $('#plList li').click(function () {
                    var id = parseInt($(this).index());
                    if (id !== index) {
                        playTrack(id);
                    }
                }),
                loadTrack = function (id) {
                    $('.plSel').removeClass('plSel');
                    $('#plList li:eq(' + id + ')').addClass('plSel');
                    npTitle.text(tracks[id].name);
                    index = id;
                    audio.src = mediaPath + tracks[id].file + extension;
                },
                playTrack = function (id) {
                    loadTrack(id);
                    audio.play();
                };
            extension = '';
            loadTrack(index);
        }
    });

    //initialize plyr
    plyr.setup($('#audio1'), {});

    //# sourceURL=pen.js
</script>

<div class="column add-bottom center">
    @if($result->seen !== null)
        <p>
            {{__('Seen By')}}: <a href="{{route('system.staff.show',$result->seenby_id)}}" target="_blank">{{$result->seenBy->firstname}} {{$result->seenBy->lastname}}</a>
        </p>
        <p>
            {{__('Seen At')}}: {{$result->seen->diffForHumans()}}
        </p>
    @endif
</div>
</body></html>