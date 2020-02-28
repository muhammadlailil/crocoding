<?php


namespace crocodicstudio\crocoding\controllers;


use crocodicstudio\crocoding\helpers\Crocoding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class PrivilegesController extends CBController
{
    public function cbInit()
    {
        $this->module_name = "Privilege";
        $this->table = 'cms_privileges';
        $this->primary_key = 'id';
        $this->title_field = "name";
        $this->button_import = false;
        $this->button_export = false;
        $this->button_action_style = 'button_icon';
        $this->button_detail = true;
        $this->button_filter = false;
        $this->button_bulk_action = true;

        $this->col = [];
        $this->col[] = ["label" => "ID", "name" => "id"];
        $this->col[] = ["label" => "Name", "name" => "name"];
        $this->col[] = [
            "label" => "Superadmin",
            "name" => "is_superadmin",
            'callback_php' => '($row->is_superadmin)?"<span class=\"badge badge-success\">Superadmin</span>":"<span class=\"label label-default\">Standard</span>"',
        ];

        $this->form = [];
        $this->form[] = ["label" => "Name", "name" => "name", 'required' => true];
        $this->form[] = ["label" => "Is Superadmin", "name" => "is_superadmin", 'required' => true];
        $this->form[] = ["label" => "Theme Color", "name" => "theme_color", 'required' => true];

        $this->alert[] = [
            'message' => "You can use the helper <code>crocoding::getMyPrivilegeId()</code> to get current user login privilege id, or <code>crocoding::getMyPrivilegeName()</code> to get current user login privilege name",
            'type' => 'info',
        ];
    }



    public function getAdd()
    {
        $this->cbLoader();

        if (! Crocoding::isCreate() && $this->global_privilege == false) {
            Crocoding::insertLog('Try add the data data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $id = 0;
        $data['page_title'] = "Add Data";
        $data['moduls'] = DB::table("cms_moduls")->where('is_protected', 0)->whereNull('deleted_at')->select("cms_moduls.*", DB::raw("(select is_visible from cms_privileges_roles where id_cms_moduls = cms_moduls.id and id_cms_privileges = '$id') as is_visible"), DB::raw("(select is_create from cms_privileges_roles where id_cms_moduls  = cms_moduls.id and id_cms_privileges = '$id') as is_create"), DB::raw("(select is_read from cms_privileges_roles where id_cms_moduls    = cms_moduls.id and id_cms_privileges = '$id') as is_read"), DB::raw("(select is_edit from cms_privileges_roles where id_cms_moduls    = cms_moduls.id and id_cms_privileges = '$id') as is_edit"), DB::raw("(select is_delete from cms_privileges_roles where id_cms_moduls  = cms_moduls.id and id_cms_privileges = '$id') as is_delete"))->orderby("name", "asc")->get();
        $data['page_menu'] = currentRouteAction();

        return view('crocoding::privileges', $data);
    }

    public function postAddSave()
    {
        $this->cbLoader();

        if (! Crocoding::isCreate() && $this->global_privilege == false) {
            Crocoding::insertLog('Try add the data '.g($this->title_field).' data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $this->validation($request);
        $this->input_assignment($request);

        $this->arr[$this->primary_key] = DB::table($this->table)->max($this->primary_key) + 1;

        DB::table($this->table)->insert($this->arr);
        $id = $this->arr[$this->primary_key];

        //set theme
        session()->put('theme_color', $this->arr['theme_color']);

        $priv = Request::input("privileges");
        if ($priv) {
            foreach ($priv as $id_modul => $data) {
                $arrs = [];
                $arrs['id'] = DB::table('cms_privileges_roles')->max('id') + 1;
                $arrs['is_visible'] = @$data['is_visible'] ?: 0;
                $arrs['is_create'] = @$data['is_create'] ?: 0;
                $arrs['is_read'] = @$data['is_read'] ?: 0;
                $arrs['is_edit'] = @$data['is_edit'] ?: 0;
                $arrs['is_delete'] = @$data['is_delete'] ?: 0;
                $arrs['id_cms_privileges'] = $id;
                $arrs['id_cms_moduls'] = $id_modul;
                DB::table("cms_privileges_roles")->insert($arrs);

                $module = DB::table('cms_moduls')->where('id', $id_modul)->first();
            }
        }

        //Refresh Session Roles
        $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', Crocoding::myPrivilegeId())->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();
        session()->put('admin_privileges_roles', $roles);

        Crocoding::redirect(Crocoding::mainpath(), 'The data has been added !', 'success');
    }

    public function getEdit($id)
    {
        $this->cbLoader();

        $row = DB::table($this->table)->where("id", $id)->first();

        if (! Crocoding::isRead() && $this->global_privilege == false) {
            Crocoding::insertLog('Try edit the data '.$row->{$this->title_field}.' at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $page_title = 'Edit Privilege '.$row->name;

        $moduls = DB::table("cms_moduls")->where('is_protected', 0)->select("cms_moduls.*")->orderby("name", "asc")->get();
        $page_menu = currentRouteAction();

        return view('crocoding::privileges', compact('row', 'page_title', 'moduls', 'page_menu'));
    }

    public function postEditSave($id)
    {
        $this->cbLoader();

        $row = Crocoding::first($this->table, $id);

        if (! Crocoding::isUpdate() && $this->global_privilege == false) {
            Crocoding::insertLog('Try edit the data '.$row->{$this->title_field}.' at '. Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $this->validation($id);
        $this->input_assignment($id);

        DB::table($this->table)->where($this->primary_key, $id)->update($this->arr);

        $priv = Request::input("privileges");

        // This solves issue #1074
        DB::table("cms_privileges_roles")->where("id_cms_privileges", $id)->delete();

        if ($priv) {

            foreach ($priv as $id_modul => $data) {
                //Check Menu
                $module = DB::table('cms_moduls')->where('id', $id_modul)->first();
                $currentPermission = DB::table('cms_privileges_roles')->where('id_cms_moduls', $id_modul)->where('id_cms_privileges', $id)->first();

                if ($currentPermission) {
                    $arrs = [];
                    $arrs['is_visible'] = @$data['is_visible'] ?: 0;
                    $arrs['is_create'] = @$data['is_create'] ?: 0;
                    $arrs['is_read'] = @$data['is_read'] ?: 0;
                    $arrs['is_edit'] = @$data['is_edit'] ?: 0;
                    $arrs['is_delete'] = @$data['is_delete'] ?: 0;
                    DB::table('cms_privileges_roles')->where('id', $currentPermission->id)->update($arrs);
                } else {
                    $arrs = [];
                    $arrs['id'] = DB::table('cms_privileges_roles')->max('id') + 1;
                    $arrs['is_visible'] = @$data['is_visible'] ?: 0;
                    $arrs['is_create'] = @$data['is_create'] ?: 0;
                    $arrs['is_read'] = @$data['is_read'] ?: 0;
                    $arrs['is_edit'] = @$data['is_edit'] ?: 0;
                    $arrs['is_delete'] = @$data['is_delete'] ?: 0;
                    $arrs['id_cms_privileges'] = $id;
                    $arrs['id_cms_moduls'] = $id_modul;
                    DB::table("cms_privileges_roles")->insert($arrs);
                }
            }
        }

        //Refresh Session Roles
        if ($id == Crocoding::myPrivilegeId()) {
            $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', Crocoding::myPrivilegeId())->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();
            session()->put('admin_privileges_roles', $roles);

            session()->put('theme_color', $this->arr['theme_color']);
        }

        Crocoding::redirect(Crocoding::mainpath(), 'The data has been updated !', 'success');
    }

    public function getDelete($id)
    {
        $this->cbLoader();

        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! Crocoding::isDelete() && $this->global_privilege == false) {
            Crocoding::insertLog('Try delete the '.$row->{$this->title_field}.' data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        DB::table($this->table)->where($this->primary_key, $id)->delete();
        DB::table("cms_privileges_roles")->where("id_cms_privileges", $row->id)->delete();

        Crocoding::redirect(Crocoding::mainpath(), 'Delete the data success !', 'success');
    }



}
