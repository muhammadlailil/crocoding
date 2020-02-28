@extends("crocoding::admin_template")
@section("content")

    <div class="col-sm-12">
        <form method="post" action="{{Route('ModulsControllerPostStepFinish')}}">
            <input type="hidden" name="id" value='{{$id}}'>
            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <strong style="margin-top: 4px;display: block;float: left;">
                        Configuration
                    </strong>
                    <div class="pull-right">
                        <div class="btn-group btn-group-sm btn-group-toggle d-flex my-auto mx-auto mx-sm-0">
                            @if($id)
                                <a class="btn btn-white" href="{{Route('ModulsControllerGetStep1')}}?id={{$id}}">
                                    <i class='fa fa-info'></i> Step 1 - Module Information
                                </a>
                                <a class="btn btn-white" href="{{Route('ModulsControllerGetStep2',['id'=>$id])}}">
                                    <i class='fa fa-table'></i> Step 2 - Table Display
                                </a>
                                <a class="btn btn-white" href="{{Route('ModulsControllerGetStep3',['id'=>$id])}}">
                                    <i class='fa fa-plus-square'></i> Step 3 - Form Display
                                </a>
                                <a class="btn btn-white active" href="{{Route('ModulsControllerGetStep4',['id'=>$id])}}">
                                    <i class='fa fa-wrench'></i> Step 4 - Configuration
                                </a>
                            @else
                                <a class="btn btn-white" href="javascript:;">
                                    <i class='fa fa-info'></i> Step 1 - Module Information
                                </a>
                                <a class="btn btn-white" href="javascript:;">
                                    <i class='fa fa-table'></i> Step 2 - Table Display
                                </a>
                                <a class="btn btn-white" href="javascript:;">
                                    <i class='fa fa-plus-square'></i> Step 3 - Form Display
                                </a>
                                <a class="btn btn-white active" href="javascript:;">
                                    <i class='fa fa-wrench'></i> Step 4 - Configuration
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body cardBodyForm" style="padding-bottom: 0px !important;">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Title Field Candidate</label>
                                <input type="text" name="title_field" value="{{$cb_title_field}}" class='form-control'>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="form-group">
                                <label>Limit Data</label>
                                <input type="number" name="limit" value="{{$cb_limit}}" class='form-control'>
                            </div>
                        </div>

                        <div class="col-sm-7">
                            <div class="form-group">
                                <label>Order By</label>
                                <?php
                                if (is_array($cb_orderby)) {
                                    $orderby = [];
                                    foreach ($cb_orderby as $k => $v) {
                                        $orderby[] = $k.','.$v;
                                    }
                                    $orderby = implode(";", $orderby);
                                } else {
                                    $orderby = $cb_orderby;
                                }
                                ?>
                                <input type="text" name="orderby" value="{{$orderby}}" class='form-control'>
                                <div class="help-block">E.g : column_name,desc</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Global Privilege</label><br>
                                        <label class='radio-inline'>
                                            <input type='radio' name='global_privilege' {{($cb_global_privilege)?"checked":""}} value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_global_privilege)?"checked":""}} type='radio' name='global_privilege' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Table Action</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_table_action)?"checked":""}} type='radio' name='button_table_action' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_table_action)?"checked":""}} type='radio' name='button_table_action' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Bulk Action Button</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_bulk_action)?"checked":""}} type='radio' name='button_bulk_action' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_bulk_action)?"checked":""}} type='radio' name='button_bulk_action' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Button Action Style</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_action_style=='button_icon')?"checked":""}} type='radio' name='button_action_style'
                                                   value='button_icon'/> Icon
                                        </label>
{{--                                        <label class='radio-inline'>--}}
{{--                                            <input {{($cb_button_action_style=='button_icon_text')?"checked":""}} type='radio' name='button_action_style'--}}
{{--                                                   value='button_icon_text'/> Icon & Text--}}
{{--                                        </label>--}}
{{--                                        <label class='radio-inline'>--}}
{{--                                            <input {{($cb_button_action_style=='button_text')?"checked":""}} type='radio' name='button_action_style'--}}
{{--                                                   value='button_text'/> Button Text--}}
{{--                                        </label>--}}
{{--                                        <label class='radio-inline'>--}}
{{--                                            <input {{($cb_button_action_style=='button_dropdown')?"checked":""}} type='radio' name='button_action_style'--}}
{{--                                                   value='button_dropdown'/> Dropdown--}}
{{--                                        </label>--}}
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Add</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_add)?"checked":""}} type='radio' name='button_add' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_add)?"checked":""}} type='radio' name='button_add' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Edit</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_edit)?"checked":""}} type='radio' name='button_edit' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_edit)?"checked":""}} type='radio' name='button_edit' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Delete</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_delete)?"checked":""}} type='radio' name='button_delete' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_delete)?"checked":""}} type='radio' name='button_delete' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Detail</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_detail)?"checked":""}} type='radio' name='button_detail' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_detail)?"checked":""}} type='radio' name='button_detail' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>


                            </div>


                        </div>

                        <div class="col-sm-4">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Show Data</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_show)?"checked":""}} type='radio' name='button_show' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_show)?"checked":""}} type='radio' name='button_show' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Filter & Sorting</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_filter)?"checked":""}} type='radio' name='button_filter' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_filter)?"checked":""}} type='radio' name='button_filter' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Import</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_import)?"checked":""}} type='radio' name='button_import' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_import)?"checked":""}} type='radio' name='button_import' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Show Button Export</label><br>
                                        <label class='radio-inline'>
                                            <input {{($cb_button_export)?"checked":""}} type='radio' name='button_export' value='true'/> TRUE
                                        </label>
                                        <label class='radio-inline'>
                                            <input {{(!$cb_button_export)?"checked":""}} type='radio' name='button_export' value='false'/> FALSE
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <div align="right">
                        <button type="button" onclick="location.href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath('step3').'/'.$id}}'" class="btn btn-default">&laquo; Back</button>
                        <input autocomplate="off" type="submit" class="btn btn-primary" value="Save Module">
                    </div>
                </div>

            </div>
        </form>
    </div>


@endsection
