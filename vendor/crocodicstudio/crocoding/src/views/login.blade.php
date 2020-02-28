<!DOCTYPE html>
<html style="height: 100%">
@include('crocoding::css')
<body class="h-100 loginPageBody">
<div class="container-fluid">
    <div class="row">
        <?php
            $logo = \crocodicstudio\crocoding\helpers\Crocoding::getSetting('logo');
            $appName =\crocodicstudio\crocoding\helpers\Crocoding::getSetting('appname');
        ?>

        <main class="main-content col">
            <div class="main-content-container container-fluid px-4 my-auto h-100">
                <div class="row no-gutters h-100">
                    <div class="col-lg-4 col-md-5 auth-form mx-auto my-auto">
                        <div class="card">
                            <div class="card-body">
                                <img class="auth-form__logo d-table mx-auto mb-3" title="{{$appName}}" src="{{($logo)?asset($logo):asset('vendor/crocoding//assets/img/img_login_logo.png')}}">

                                @if(getSession('message')!=null)
                                <div class="alert alert-dismissible fade show mb-0 alert-warning" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <span style="font-size: 11px;font-weight: normal">{{getSession('message')}}</span>
                                </div>
                                @endif
                                <br>
                                <form action="{{route('postLogin')}}" method="POST">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input autocomplete="off" type="email" name="email" class="form-control" id="exampleInputEmail1" required  placeholder="Enter email">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input autocomplete="off" type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                    </div>

                                    <button type="submit" class="btn btn-pill btn-accent d-table mx-auto btnLoginLogin">Login</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('crocoding::admin_template_plugins')
<style>
    .loginPageBody{
        background:  {{ \crocodicstudio\crocoding\helpers\Crocoding::getSetting("login_background_color")?:'#dddddd'}} url('{{ \crocodicstudio\crocoding\helpers\Crocoding::getSetting("login_background_image")?asset(\crocodicstudio\crocoding\helpers\Crocoding::getSetting("login_background_image")):asset('vendor/crocoding/assets/img/bg_auth_log_in.png') }}');;
    }
</style>
</body>
</html>
