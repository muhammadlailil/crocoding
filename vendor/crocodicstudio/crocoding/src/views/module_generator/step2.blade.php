@extends("crocoding::admin_template")
@section("content")

    @push('head')
        <style>
            .table-display tbody tr td {
                position: relative;
            }

            .sub {
                position: absolute;
                top: inherit;
                left: inherit;
                padding: 0 0 0 0;
                list-style-type: none;
                height: 180px;
                overflow: auto;
                z-index: 1;
            }

            .sub li {
                padding: 5px;
                background: #eae9e8;
                cursor: pointer;
                display: block;
                width: 180px;
            }

            .sub li:hover {
                background: #ECF0F5;
            }

            .btn-drag {
                cursor: move;
            }
            .table tr td button, .table tr td a{
                padding: 2px 6px 4px !important
            }
            .table tr td button, .table tr td a i{
                font-size: 9px !important;
            }
            .form-control{
                padding: 6px 16px !important;
            }
        </style>
    @endpush
    @push('bottom')
        <script>
            var columns = {!! json_encode($columns) !!};
            var tables = {!! json_encode($table_list) !!};

            function ucwords(str) {
                return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                    return $1.toUpperCase();
                });
            }

            function showNameSuggest(t) {
                t = $(t);

                t.next("ul").remove();
                var list = '';
                $.each(columns, function (i, obj) {
                    list += "<li>" + obj + "</li>";
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showNameSuggestLike(t) {
                t = $(t);

                var v = t.val();
                t.next("ul").remove();
                if (!v) return false;

                var list = '';
                $.each(columns, function (i, obj) {
                    if (obj.includes(v.toLowerCase())) {
                        list += "<li>" + obj + "</li>";
                    }
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showColumnSuggest(t) {
                t = $(t);
                t.next("ul").remove();

                var list = '';
                $.each(columns, function (i, obj) {
                    obj = obj.replace('id_', '');
                    obj = ucwords(obj.replace('_', ' '));
                    list += "<li>" + obj + "</li>";
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showColumnSuggestLike(t) {
                t = $(t);
                var v = t.val();

                t.next("ul").remove();
                if (!v) return false;

                var list = '';
                $.each(columns, function (i, obj) {

                    if (obj.includes(v.toLowerCase())) {
                        obj = obj.replace('id_', '');
                        obj = ucwords(obj.replace('_', ' '));

                        list += "<li>" + obj + "</li>";
                    }
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showTable(t) {
                t = $(t);
                t.next("ul").remove();
                var list = '';
                $.each(tables, function (i, obj) {
                    list += "<li>" + obj + "</li>";
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showTableLike(t) {
                t = $(t);
                var v = t.val();

                t.next("ul").remove();
                if (!v) return false;

                var list = '';
                $.each(tables, function (i, obj) {
                    if (obj.includes(v.toLowerCase())) {
                        list += "<li>" + obj + "</li>";
                    }
                });

                t.after("<ul class='sub'>" + list + "</ul>");
            }

            function showTableFieldLike(t) {
                t = $(t);
                var table = t.parent().parent().find('.join_table').val();
                var v = t.val();

                t.next("ul").remove();

                if (!table) return false;
                if (!v) return false;

                t.after("<ul class='sub'><li><i class='fa fa-spin fa-spinner'></i> Loading...</li></ul>");

                $.get("{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath('table-columns')}}/" + table, function (response) {
                    t.next("ul").remove();
                    var list = '';
                    $.each(response, function (i, obj) {
                        if (obj.includes(v.toLowerCase())) {
                            list += "<li>" + obj + "</li>";
                        }
                    });
                    t.after("<ul class='sub'>" + list + "</ul>");
                });
            }

            function showTableField(t) {
                t = $(t);
                var table = t.parent().parent().find('.join_table').val();
                var v = t.val();

                if (!table) return false;

                t.after("<ul class='sub'><li><i class='fa fa-spin fa-spinner'></i> Loading...</li></ul>");

                $.get("{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath('table-columns')}}/" + table, function (response) {
                    t.next("ul").remove();
                    var list = '';
                    $.each(response, function (i, obj) {
                        list += "<li>" + obj + "</li>";
                    });
                    t.after("<ul class='sub'>" + list + "</ul>");
                });
            }

            $(function () {


                $(document).on('click', '.btn-plus', function () {
                    var tr_parent = $(this).parent().parent('tr');
                    var clone = $('#tr-sample').clone();
                    clone.removeAttr('id');
                    tr_parent.after(clone);
                    $('.table-display tr').not('#tr-sample').show();
                })

                //init row
                $('.btn-plus').last().click();

                $(document).mouseup(function (e) {
                    var container = $(".sub");
                    if (!container.is(e.target)
                        && container.has(e.target).length === 0) {
                        container.hide();
                    }
                });

                $(document).on('click', '.sub li', function () {
                    var v = $(this).text();
                    $(this).parent('ul').prev('input[type=text]').val(v);
                    $(this).parent('ul').remove();
                })

                $(document).on('click', '.table-display .btn-delete', function () {
                    $(this).parent().parent().remove();
                })

                $(document).on('click', '.table-display .btn-up', function () {
                    var tr = $(this).parent().parent();
                    var trPrev = tr.prev('tr');
                    if (trPrev.length != 0) {

                        tr.prev('tr').before(tr.clone());
                        tr.remove();
                    }
                })

                $(document).on('click', '.table-display .btn-down', function () {
                    var tr = $(this).parent().parent();
                    var trPrev = tr.next('tr');
                    if (trPrev.length != 0) {

                        tr.next('tr').after(tr.clone());
                        tr.remove();
                    }
                })

                $(document).on('change', '.is_image', function () {
                    var tr = $(this).parent().parent();
                    if ($(this).val() == 1) {
                        tr.find('.is_download').val(0);
                    }
                })

                $(document).on('change', '.is_download', function () {
                    var tr = $(this).parent().parent();
                    if ($(this).val() == 1) {
                        tr.find('.is_image').val(0);
                    }
                })

            })
        </script>
    @endpush


    <div class="col-sm-12">
        <form method="post" action="{{Route('ModulsControllerPostStep3')}}">
            <input autocomplate="off" type="hidden" name="_token" value="{{csrf_token()}}">
            <input autocomplate="off" type="hidden" name="id" value="{{$id}}">
            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <strong style="margin-top: 4px;display: block;float: left;">
                        Table Display
                    </strong>
                    <div class="pull-right">
                        <div class="btn-group btn-group-sm d-flex my-auto mx-auto mx-sm-0">
                            @if($id)
                                <a class="btn btn-white" href="{{Route('ModulsControllerGetStep1')}}?id={{$id}}">
                                    <i class='fa fa-info'></i> Step 1 - Module Information
                                </a>
                                <a class="btn btn-white active" href="{{Route('ModulsControllerGetStep2',['id'=>$id])}}">
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
                <div class="card-body row cardBodyForm" style="padding-bottom: 0px !important;">
                    <div class="alert alert-info" style="margin: 0 10px 20px;width: 100%">
                        <strong>Warning</strong>
                        <small>Make sure that your column format are normally, unless using this Tool maybe make your current configuration broken, because this Tool will replace your configuration.</small>
                    </div>

                    <table class="table-display table table-striped">
                        <thead>
                        <tr>
                            <th>Column</th>
                            <th>Name</th>
                            <th colspan='2'>Join (Optional)</th>
                            <th>CallbackPHP</th>
                            <th width="90px">Width (px)</th>
                            <th width='80px'>Image</th>
                            <th width='80px'>Download</th>
                            <th width="130px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($cb_col)
                            @foreach($cb_col as $c)
                                <tr>
                                    <td><input autocomplete="off" value='{{$c["label"]}}' type='text' name='column[]' onclick='showColumnSuggest(this)'
                                               onKeyUp='showColumnSuggestLike(this)' placeholder='Column Name' class='column form-control notfocus' value=''/></td>
                                    <td><input autocomplete="off" value='{{$c["name"]}}' type='text' name='name[]' onclick='showNameSuggest(this)' onKeyUp='showNameSuggestLike(this)'
                                               placeholder='Field Name' class='name form-control notfocus' value=''/></td>
                                    <td><input autocomplete="off" value='{{ @explode(",",$c["join"])[0] }}' type='text' name='join_table[]' onclick='showTable(this)'
                                               onKeyUp='showTableLike(this)' placeholder='Table Name' class='join_table form-control notfocus' value=''/></td>
                                    <td><input autocomplete="off" value='{{ @explode(",",$c["join"])[1] }}' type='text' name='join_field[]' onclick='showTableField(this)'
                                               onKeyUp='showTableFieldLike(this)' placeholder='Field Name Shown' class='join_field form-control notfocus' value=''/>
                                    </td>
                                    <td><input autocomplete="off" type='text' name='callbackphp[]' class='form-control callbackphp notfocus' value='{{$c["callback_php"]}}'
                                               placeholder="Optional"/></td>
                                    <td><input autocomplete="off" value='{{$c["width"]?:0}}' type='number' name='width[]' class='form-control'/></td>
                                    <td>
                                        <select class='custom-select custom-select-sm is_image' name='is_image[]'>
                                            <option {{ (!$c['image'])?"selected":""}} value='0'>N</option>
                                            <option {{ ($c['image'])?"selected":""}} value='1'>Y</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class='custom-select custom-select-sm is_download' name='is_download[]'>
                                            <option {{ (!(isset($c['download'])))?"selected":""}} value='0'>N</option>
                                            <option {{ ($c['download'])?"selected":""}} value='1'>Y</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-info btn-plus"><i class='fa fa-plus'></i></a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-delete"><i class='fa fa-trash'></i></a>
                                        <a href="javascript:void(0)" class="btn btn-success btn-up"><i class='fa fa-arrow-up'></i></a>
                                        <a href="javascript:void(0)" class="btn btn-success btn-down"><i class='fa fa-arrow-down'></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        <tr id="tr-sample" style="display:none">
                            <td><input autocomplate="off" type='text' name='column[]' onclick='showColumnSuggest(this)' onKeyUp='showColumnSuggestLike(this)' placeholder='Column Name'
                                       class='column form-control notfocus' value=''/></td>
                            <td><input autocomplate="off" type='text' name='name[]' onclick='showNameSuggest(this)' onKeyUp='showNameSuggestLike(this)' placeholder='Field Name'
                                       class='name form-control notfocus' value=''/></td>
                            <td><input autocomplate="off" type='text' name='join_table[]' onclick='showTable(this)' onKeyUp='showTableLike(this)' placeholder='Table Name'
                                       class='join_table form-control notfocus' value=''/></td>
                            <td><input autocomplate="off" type='text' name='join_field[]' onclick='showTableField(this)' onKeyUp='showTableFieldLike(this)'
                                       placeholder='Field Name Shown' class='join_field form-control notfocus' value=''/></td>
                            <td><input autocomplate="off" type='text' name='callbackphp[]' class='form-control callbackphp notfocus' value='' placeholder="Optional"/></td>
                            <td><input autocomplate="off" type='number' name='width[]' value='0' class='form-control'/></td>
                            <td>
                                <select class='custom-select custom-select-sm is_image' name='is_image[]'>
                                    <option value='0'>N</option>
                                    <option value='1'>Y</option>
                                </select>
                            </td>
                            <td>
                                <select class='custom-select custom-select-sm is_download' name='is_download[]'>
                                    <option value='0'>N</option>
                                    <option value='1'>Y</option>
                                </select>
                            </td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-info btn-plus"><i class='fa fa-plus'></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-delete"><i class='fa fa-trash'></i></a>
                                <a href="javascript:void(0)" class="btn btn-success btn-up"><i class='fa fa-arrow-up'></i></a>
                                <a href="javascript:void(0)" class="btn btn-success btn-down"><i class='fa fa-arrow-down'></i></a>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                </div>

                <div class="card-footer">
                    <div align="right">
                        <button type="button" onclick="location.href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath('step1').'?id='.$id}}'" class="btn btn-default">&laquo; Back</button>
                        <input autocomplate="off" type="submit" class="btn btn-primary" value="Step 3 &raquo;">
                    </div>
                </div>

            </div>
        </form>
    </div>


@endsection
