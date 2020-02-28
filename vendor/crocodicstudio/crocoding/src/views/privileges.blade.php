@extends('crocoding::admin_template')

@section('content')

    <div style="width:750px;margin:0 auto ">


        @if(\crocodicstudio\crocoding\helpers\Crocoding::getCurrentMethod() != 'getProfile')
            <p style="margin-bottom: 10px"><a href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath()}}'>Back To List Data {{\crocodicstudio\crocoding\helpers\Crocoding::getCurrentModule()->name}}</a></p>
    @endif



    <!-- Box -->
        <div class="card">
            <div class="card-header border-bottom">
                <h6 class="card-title" style="margin-bottom: 0px">{{ $page_title }}</h6>
            </div>
            <form method='post' action='{{ (@$row->id)?route("PrivilegesControllerPostEditSave",['id'=>$row->id])."":route("PrivilegesControllerPostAddSave") }}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="card-body  ">
                    <div class="alert alert-info">
                        <strong>Note:</strong> To show the menu you have to create a menu at Menu Management
                    </div>
                    <div class='form-group'>
                        <label>Privilege Name</label>
                        <input type='text' class='form-control' name='name' required value='{{ @$row->name }}'/>
                        <div class="text-danger">{{ (isset($errors))?$errors->first('name'):'' }}</div>
                    </div>

                    <div class='form-group'>
                        <label>Set as Superadmin</label>
                        <div id='set_as_superadmin' class='radio'>
                            <label><input required {{ (@$row->is_superadmin==1)?'checked':'' }} type='radio' name='is_superadmin'
                                          value='1'/> Yes</label> &nbsp;&nbsp;
                            <label><input {{ (@$row->is_superadmin==0)?'checked':'' }} type='radio' name='is_superadmin'
                                          value='0'/> No</label>
                        </div>
                        <div class="text-danger">{{ (isset($errors))?$errors->first('is_superadmin'):'' }}</div>
                    </div>


                    <div id='privileges_configuration' class='form-group'>
                        <label>Privileges Configuration</label>
                        @push('bottom')
                            <script>
                                $(function () {
                                    $("#is_visible").click(function () {
                                        var is_ch = $(this).prop('checked');
                                        console.log('is checked create ' + is_ch);
                                        $(".is_visible").prop("checked", is_ch);
                                        console.log('Create all');
                                    })
                                    $("#is_create").click(function () {
                                        var is_ch = $(this).prop('checked');
                                        console.log('is checked create ' + is_ch);
                                        $(".is_create").prop("checked", is_ch);
                                        console.log('Create all');
                                    })
                                    $("#is_read").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_read").prop("checked", is_ch);
                                    })
                                    $("#is_edit").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_edit").prop("checked", is_ch);
                                    })
                                    $("#is_delete").click(function () {
                                        var is_ch = $(this).is(':checked');
                                        $(".is_delete").prop("checked", is_ch);
                                    })
                                    $(".select_horizontal").click(function () {
                                        var p = $(this).parents('tr');
                                        var is_ch = $(this).is(':checked');
                                        p.find("input[type=checkbox]").prop("checked", is_ch);
                                    })
                                })
                            </script>
                        @endpush
                        <table class='table table-striped table-hover table-bordered'>
                            <thead>
                            <tr class='active'>
                                <th width='3%'>No</th>
                                <th width='60%'>Module's Name</th>
                                <th>&nbsp;</th>
                                <th>View</th>
                                <th>Create</th>
                                <th>Read</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            <tr class='info'>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_visible'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_create'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_read'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_edit'/></td>
                                <td align="center"><input title='Check all vertical' type='checkbox' id='is_delete'/></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1;?>
                            @foreach($moduls as $modul)
                                <?php
                                $roles = DB::table('cms_privileges_roles')->where('id_cms_moduls', $modul->id)->where('id_cms_privileges', $row->id)->first();
                                ?>
                                <tr>
                                    <td><?php echo $no++;?></td>
                                    <td>{{$modul->name}}</td>
                                    <td class='info' align="center"><input type='checkbox' title='Check All Horizontal'
                                                                           <?=($roles->is_create && $roles->is_read && $roles->is_edit && $roles->is_delete) ? "checked" : ""?> class='select_horizontal'/>
                                    </td>
                                    <td class='active' align="center"><input type='checkbox' class='is_visible' name='privileges[<?=$modul->id?>][is_visible]'
                                                                             <?=@$roles->is_visible ? "checked" : ""?> value='1'/></td>
                                    <td class='warning' align="center"><input type='checkbox' class='is_create' name='privileges[<?=$modul->id?>][is_create]'
                                                                              <?=@$roles->is_create ? "checked" : ""?> value='1'/></td>
                                    <td class='info' align="center"><input type='checkbox' class='is_read' name='privileges[<?=$modul->id?>][is_read]'
                                                                           <?=@$roles->is_read ? "checked" : ""?> value='1'/></td>
                                    <td class='success' align="center"><input type='checkbox' class='is_edit' name='privileges[<?=$modul->id?>][is_edit]'
                                                                              <?=@$roles->is_edit ? "checked" : ""?> value='1'/></td>
                                    <td class='danger' align="center"><input type='checkbox' class='is_delete' name='privileges[<?=$modul->id?>][is_delete]'
                                                                             <?=@$roles->is_delete ? "checked" : ""?> value='1'/></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>

                </div><!-- /.box-body -->
                <div class="card-footer" align="right">
                    <button type='button' onclick="location.href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath()}}'"
                            class='btn btn-default'>Cancel</button>
                    <button type='submit' class='btn btn-primary'><i class='fa fa-save'></i> Save</button>
                </div><!-- /.box-footer-->
        </div><!-- /.box -->

            <br><br><br>
    </div><!-- /.row -->
@endsection
