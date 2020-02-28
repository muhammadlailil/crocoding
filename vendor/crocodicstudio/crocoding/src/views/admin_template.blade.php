<!DOCTYPE html>
<html>
<head>
    @include('crocoding::css')
    <style type="text/css">
        @if(isset($style_css))
            {!! $style_css !!}
        @endif
    </style>
    @if(isset($load_css))
        @foreach($load_css as $css)
            <link href="{{$css}}" rel="stylesheet" type="text/css"/>
        @endforeach
    @endif

    @stack('head')
</head>
<body class="h-100">
<div class="preload">
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Main Sidebar -->
    @include('crocoding::sidebar')
    <!-- End Main Sidebar -->

        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
            <!-- MAIN HEADER -->
        @include('crocoding::header')
        <!-- //END -->
            @if(getSession('message')!=null)
                <div class="alert alert-dismissible fade show mb-0 alert-{{getSession('message_type')}}" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    @if(getSession('message_type')=='success')
                        <i class="fa fa-check mx-2"></i>
                    @else
                        <i class="fa fa-info mx-2"></i>
                    @endif
                    <span>{{getSession('message')}}</span>
                </div>
        @endif

        <!-- KONTEN -->
            <div class="main-content-container container-fluid px-4">
                <!-- Page Header -->
                @if($page_title)
                    <?php
                    $module = \crocodicstudio\crocoding\helpers\Crocoding::getCurrentModule();
                    $method = \crocodicstudio\crocoding\helpers\Crocoding::getCurrentMethod();
                    ?>
                    <div class="page-header row no-gutters py-4">
                        @if($method=='getIndex')
                            <div class="col-12 col-sm-6 text-center text-sm-left mb-0">
                                @else
                                    <div class="col-12 col-sm-12 text-center text-sm-left mb-0">
                                        @endif
                                        <span class="pageIconLeft">
                           <i class="{{(isset($module->icon))?$module->icon:'fa fa-home'}}"></i>
                        </span>
                                        <span class="text-uppercase page-subtitle" style="margin-left: 2px"> Dashboard</span>
                                        <h3 class="page-title">{!! $page_title !!}</h3>
                                    </div>

                                    @if($module)
                                        @if($method=='getIndex')
                                            <div class="col-12 col-sm-6 text-right">
                                                @include('crocoding::button_action')
                                            </div>
                                        @endif
                                    @endif


                            </div>
                    @endif
                    <!-- End Page Header -->
                        <div class="row">
                            @yield('content')
                        </div>
                    </div>
                    <!-- END -->
                @include('crocoding::footer')
                <!-- / .main-navbar -->
        </main>
    </div>
</div>

@include('crocoding::admin_template_plugins')

<!-- load js -->
@if(isset($load_js))
    @foreach($load_js as $js)
        <script src="{{$js}}"></script>
    @endforeach
@endif
<script type="text/javascript">
    var site_url = "{{url('/')}}";
    @if(isset($script_js))
        {!! $script_js !!}
    @endif
</script>

@stack('bottom')
</body>
</html>
