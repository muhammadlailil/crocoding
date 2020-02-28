@extends("crocoding::admin_template")
@section("content")


    @push('bottom')
        <script>
            $(function () {
                $('.select2').SumoSelect({search:true,searchText:'Search ...'});

            })
            $(function () {
                $('select[name=table]').change(function () {
                    var v = $(this).val().replace(".", "_");
                    $.get("{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath('check-slug')}}/" + v, function (resp) {
                        if (resp.total == 0) {
                            $('input[name=path]').val(v);
                        } else {
                            v = v + resp.lastid;
                            $('input[name=path]').val(v);
                        }
                    })

                })
            })
        </script>
    @endpush


    <div class="col-sm-12">
        <form method="post" action="{{Route('ModulsControllerPostStep2')}}">
            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <strong style="margin-top: 4px;display: block;float: left;">
                        Module Information
                    </strong>
                    <div class="pull-right">
                        <div class="btn-group btn-group-sm btn-group-toggle d-flex my-auto mx-auto mx-sm-0">
                            @if($id)
                                <a class="btn btn-white active" href="{{Route('ModulsControllerGetStep1')}}?id={{$id}}">
                                    <i class='fa fa-info'></i> Step 1 - Module Information
                                </a>
                                <a class="btn btn-white" href="{{Route('ModulsControllerGetStep2',['id'=>$id])}}">
                                    <i class='fa fa-table'></i> Step 2 - Table Display
                                </a>
                                <a class="btn btn-white" href="{{Route('ModulsControllerGetStep3',['id'=>$id])}}">
                                    <i class='fa fa-plus-square'></i> Step 3 - Form Display
                                </a>
                                <a class="btn btn-white" href="{{Route('ModulsControllerGetStep4',['id'=>$id])}}">
                                    <i class='fa fa-wrench'></i> Step 4 - Configuration
                                </a>
                            @else
                                <a class="btn btn-white active" href="javascript:;">
                                    <i class='fa fa-info'></i> Step 1 - Module Information
                                </a>
                                <a class="btn btn-white" href="javascript:;">
                                    <i class='fa fa-table'></i> Step 2 - Table Display
                                </a>
                                <a class="btn btn-white" href="javascript:;">
                                    <i class='fa fa-plus-square'></i> Step 3 - Form Display
                                </a>
                                <a class="btn btn-white" href="javascript:;">
                                    <i class='fa fa-wrench'></i> Step 4 - Configuration
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body row cardBodyForm">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label>
                                Judul
                                <span class='text-danger'>*</span>
                            </label>
                            <select name="table" id="table" required class="select2 form-control" value="{{$row->table_name}}">
                                <option value="">** Please select a Table</option>
                                @foreach($tables_list as $table)

                                    <option {{($table == $row->table_name)?"selected":""}} value="{{$table}}">{{$table}}</option>

                                @endforeach
                            </select>
                            <div class="help-block">
                                Do not use cms_* as prefix on your tables name
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">Module Name <span class='text-danger'>*</span></label>
                            <input type="text" class="form-control" required name="name" value="{{$row->name}}">
                        </div>

                        <div class="form-group">
                            <label for="">Icon <span class='text-danger'>*</span></label>
                            <select name="icon" id="icon" required class="select2 form-control">
                                @foreach($fontawesome as $f)
                                    <option {{($row->icon == 'fa fa-'.$f)?"selected":""}} value="fa fa-{{$f}}">{{$f}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Module Slug <span class='text-danger'>*</span></label>
                            <input type="text" class="form-control" required name="path" value="{{$row->path}}">
                            <div class="help-block">Please alpha numeric only, without space instead _ and or special character</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="alert-form">
                            <img  src="{{asset('vendor/crocoding/assets/img/info.png')}}" class="img-info-form">
                            Mohon lengkapi form yang sudah di sediakan untuk dapat melanjutkan proses !
                        </div>
                        <input checked type='checkbox' name='create_menu' value='1'/> <small style="font-size: 14px"> Also create menu for this module</small>
                        <a href='javascript:;' title='If you check this, we will create the menu for this module'>(?)</a>
                        <br><br>

                        <button name="submit" type="submit" class="btn btn-primary" value="save">Save</button>
                        <a  class="btn btn-white">Cancel</a>
                    </div>
                    <br><br><br>
                </div>

            </div>
        </form>
    </div>


@endsection
