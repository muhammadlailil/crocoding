@extends('crocoding::admin_template')
@section('content')

    <div class="col-sm-12">
        <div class="card card-small mb-4">
            <?php
            $action = (@$row) ? \crocodicstudio\crocoding\helpers\Crocoding::mainpath("edit-save/$row->id") : \crocodicstudio\crocoding\helpers\Crocoding::mainpath("add-save");
            $return_url = (isset($return_url)) ? $return_url : g('return_url');
            ?>
            <form enctype="multipart/form-data" class="form-horizontal" action='{{$action}}' method="POST">
                {{--                <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                <input type='hidden' name='return_url' value='{{ @$return_url }}'/>
                <input type='hidden' name='ref_mainpath' value='{{ \crocodicstudio\crocoding\helpers\Crocoding::mainpath() }}'/>
                <input type='hidden' name='ref_parameter' value='{{urldecode(http_build_query(@$_GET))}}'/>
                @if($hide_form)
                    <input type="hidden" name="hide_form" value='{!! serialize($hide_form) !!}'>
                @endif

                <div class="card-header border-bottom">
                    @if(g('return_url'))
                        <a title="Return" class="icBack" href="{{g("return_url")}}">
                            <i class="fa fa-long-arrow-alt-left"></i>
                            &nbsp; Back To List {{\crocodicstudio\crocoding\helpers\Crocoding::getCurrentModule()->name}}
                        </a>
                    @else
                        <a title="Main Module" class="icBack" href="{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath()}}">
                            <i class="fa fa-long-arrow-alt-left"></i>
                            &nbsp; Back To List {{\crocodicstudio\crocoding\helpers\Crocoding::getCurrentModule()->name}}
                        </a>
                    @endif

                </div>
                <div class="card-body row cardBodyForm">

                    @if($command == 'detail')
                        @include("crocoding::default.form_detail")
                    @else
                        <div class="col-sm-8">
                            @include("crocoding::default.form_body")
                        </div>
                    @endif

                    <div class="col-sm-4">


                        @if(\crocodicstudio\crocoding\helpers\Crocoding::getCurrentMethod() != 'getDetail')
                            <div class="alert-form">
                                <img src="{{asset('vendor/crocoding/assets/img/info.png')}}" class="img-info-form">
                                Mohon lengkapi form yang sudah di sediakan untuk dapat melanjutkan proses !
                            </div>
                        @endif

                        @if(\crocodicstudio\crocoding\helpers\Crocoding::isCreate() || \crocodicstudio\crocoding\helpers\Crocoding::isUpdate())


                            @if(\crocodicstudio\crocoding\helpers\Crocoding::isCreate() && $button_addmore==TRUE && $command == 'add')
                                <button name="submit" type="submit" class="btn btn-primary" value="Save & Add More">Save & Add More</button>
                            @endif

                            @if($button_save && $command != 'detail')
                                <button name="submit" type="submit" class="btn btn-primary" value="Save">Save</button>
                            @endif

                        @endif




                        @if($button_cancel && \crocodicstudio\crocoding\helpers\Crocoding::getCurrentMethod() != 'getDetail')
                            @if(g('return_url'))
                                <a href='{{g("return_url")}}' class='btn btn-white'>Cancel</a>
                            @else
                                <a href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath("?".http_build_query(@$_GET)) }}' class='btn btn-white'>Cancel</a>
                            @endif
                        @endif
                    </div>
                    <br><br><br>
                </div>
            </form>
        </div>
    </div>

@endsection
