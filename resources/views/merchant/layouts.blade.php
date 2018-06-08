<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ajax-post" content="{{ route('panel.merchant.post') }}">
    <meta name="author" content="EGPAY">
    <title>{{$pageTitle}}</title>
    <link rel="apple-touch-icon" href="{{asset('assets/system/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/system/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css'.((app()->getLocale()=='ar')?'-rtl':null).'/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/fonts/feather/style.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/fonts/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/fonts/flag-icon-css/css/flag-icon.min.css')}}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css'.((app()->getLocale()=='ar')?'-rtl':null).'/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css'.((app()->getLocale()=='ar')?'-rtl':null).'/app.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css'.((app()->getLocale()=='ar')?'-rtl':null).'/colors.css')}}">
    <!-- END STACK CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css'.((app()->getLocale()=='ar')?'-rtl':null).'/core/menu/menu-types/horizontal-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css'.((app()->getLocale()=='ar')?'-rtl':null).'/core/menu/menu-types/vertical-overlay-menu.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/vendors/css/extensions/toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/css/plugins/extensions/toastr.css')}}">

    <!-- Audio Message -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/merchant/vendors/audio/css/style.css')}}">
    <!-- End Audio Message -->

    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/system/assets/css/style.css')}}">
    <!-- END Custom CSS-->
    <!-- Custom header -->
    <style>
        #modal-iframe-url{
            width: 100%;
            border: none;
        }
    </style>
@yield('header')
<!-- Custom header -->
</head>
<body data-open="click" data-menu="horizontal-menu" data-col="2-columns" class="horizontal-layout horizontal-menu 2-columns ">
<div class="modal fade text-xs-left" id="modal-iframe" role="dialog" aria-labelledby="myModalLabe" aria-hidden="true">
    <div class="modal-dialog" id="modal-iframe-width" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="card-body">
                    <div class="card-block">
                        <div class="row" style="text-align: center;">
                            <img id="modal-iframe-image" src="{{asset('assets/system/loading.gif')}}">
                            <iframe id="modal-iframe-url" style="display: none;" src=""></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- navbar-fixed-top-->
<nav class="header-navbar navbar navbar-with-menu navbar-static-top navbar-light navbar-border navbar-brand-center">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a href="#" class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item">
                    <a href="{{route('panel.merchant.home')}}" class="navbar-brand">
                        <img alt="{{__('Egpay logo')}}" src="{{asset('android-icon-36x36.png')}}" class="brand-logo">
                    </a>
                </li>
                <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="fa fa-ellipsis-v"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container container center-layout">
            <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
                <ul class="nav navbar-nav float-xs-right">
                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" class="nav-link nav-link-label text-primary">
                            <i class="ficon ft-info"></i>
                            <span>{{__('Wallet ID')}}# {{auth()->user()->merchant()->paymentWallet->id}}</span>
                        </a>
                    </li>

                    <li class="dropdown dropdown-language nav-item">
                        <a id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link">
                            <i class="flag-icon flag-icon-{{((app()->getLocale()=='ar')?'eg':'gb')}}"></i>
                            <span class="selected-language"></span>
                        </a>
                        <div aria-labelledby="dropdown-flag" class="dropdown-menu">
                            <a href="{{route(request()->route()->getName(),['lang'=>'en'])}}" class="dropdown-item"><i class="flag-icon flag-icon-gb"></i> {{__('English')}}</a>
                            <a href="{{route(request()->route()->getName(),['lang'=>'ar'])}}" class="dropdown-item"><i class="flag-icon flag-icon-eg"></i> {{__('Arabic')}}</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" class="nav-link nav-link-label">
                            <i class="ficon ft-mic" onclick="startRecording(this);"></i>
                        </a>
                    </li>

                    <li class="dropdown dropdown-user nav-item">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                            <span class="avatar avatar-online">
                                <img src="{{asset('assets/system')}}/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i>
                            </span>
                            <span class="user-name">{{auth()->user()->firstname}} {{auth()->user()->lastname}}</span></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{route('panel.merchant.user.update-info')}}" class="dropdown-item">
                                <i class="ft-user"></i> {{__('Edit Profile')}}
                            </a>

                            <a href="{{route('panel.merchant.user.change-password')}}" class="dropdown-item">
                                <i class="ft-user"></i> {{__('Change password')}}
                            </a>

                            @if(merchantcan(['panel.merchant.edit','panel.merchant.update']))
                                <div class="dropdown-divider"></div>
                                <a href="{{route('panel.merchant.edit')}}" class="dropdown-item">
                                    <i class="ft-mail"></i> {{__('Edit merchant')}}
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a href="{{route('panel.merchant.logout')}}" class="dropdown-item">
                                <i class="ft-power"></i> {{__('Logout')}}
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- ////////////////////////////////////////////////////////////////////////////-->


<!-- Horizontal navigation-->
<div role="navigation" data-menu="menu-wrapper" class="header-navbar navbar navbar-horizontal navbar-fixed navbar-light navbar-without-dd-arrow navbar-bordered navbar-shadow menu-border">
    <!-- Horizontal menu content-->
    <div data-menu="menu-container" class="navbar-container main-menu-content container center-layout">
        <!-- include ../../../includes/mixins-->
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="nav navbar-nav">
            @include('merchant._menus')
        </ul>
    </div>
    <!-- /horizontal menu content-->
</div>
<!-- Horizontal navigation-->

<div class="app-content container center-layout mt-2">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="app-content content container-fluid">
                <div class="content-wrapper">

                    @if($errors->any())
                        <div class="card">
                            <div class="alert alert-danger">
                                {{__('Some fields are invalid please fix them')}}
                            </div>
                        </div>
                    @elseif(Session::has('status'))
                        <div class="card">
                            <div class="alert alert-{{Session::get('status')}}">
                                {{ Session::get('msg') }}
                            </div>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </div>

        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<footer class="footer footer-static footer-light navbar-shadow">
    <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
        <span class="float-md-right d-xs-block d-md-inline-block">Hand-crafted & Made with <i class="ft-heart pink"></i></span></p>
</footer>

<!-- BEGIN VENDOR JS-->
<script src="{{asset('assets/system/vendors/js/vendors.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/system/vendors/js/ui/jquery.sticky.js')}}"></script>
<script src="{{asset('assets/system/vendors/js/extensions/toastr.min.js')}}" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->

<!-- BEGIN PAGE VENDOR JS-->

<!-- END PAGE VENDOR JS-->

<!-- BEGIN STACK JS-->
<script src="{{asset('assets/system/js/core/app-menu.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/system/js/core/app.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/system/js/jquery.form.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/merchant/node.js')}}" type="text/javascript"></script>
<!-- END STACK JS-->
<!-- BEGIN PAGE LEVEL JS-->

<!-- END PAGE LEVEL JS-->

<!-- Audio Message -->
<script src="{{asset('assets/merchant/vendors/audio/recordmp3.js')}}"></script>
<script src="{{asset('assets/merchant/vendors/audio/js/mp3Worker.js')}}"></script>
<script src="{{asset('assets/merchant/vendors/audio/audio-msg.js')}}"></script>
<script>
    function uploadAudio(mp3Data){
        var reader = new FileReader();
        reader.onload = function(event){
            var fd = new FormData();
            fd.append('data', event.target.result);
            fd.append('type', 'audio-msg');
            $.ajax({
                type: 'POST',
                url: '{{route('panel.merchant.post')}}',
                data: fd,
                processData: false,
                contentType: false,
                dataType: "json",
            }).done(function(data) {
                if(data.status === true)
                    toastr.success(data.msg, '{{__('Success !')}}', {"closeButton": true});
                else if(data.status === false)
                    toastr.error(data.msg, '{{__('Error !')}}', {"closeButton": true});
            });
        };
        reader.readAsDataURL(mp3Data);
    }
</script>
<!-- End Audio Message -->
<!-- Custom footer -->
@yield('footer')
<!-- Custom footer -->
</body>
</html>