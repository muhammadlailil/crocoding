<?php
namespace App\Http\Controllers\Admin;
use crocodicstudio\crocoding\controllers\CBController;
use crocodicstudio\crocoding\helpers\Crocoding;

class AdminCmsUsersController extends CBController
{
    public function cbInit() {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table               = 'cms_users';
        $this->primary_key         = 'id';
        $this->title_field         = "name";
        $this->button_action_style = 'button_icon';
        $this->button_import 	   = FALSE;
        $this->button_export 	   = FALSE;
        $this->button_filter = false;
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = array();
        $this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);
        $this->col[] = array("label"=>"Name","name"=>"name");
        $this->col[] = array("label"=>"Email","name"=>"email");
        $this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = array();
        $this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|min:3');
        $this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.Crocoding::getCurrentId());
        $this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'required'=>true,'validation'=>'required|image|max:1000','resize_width'=>90,'resize_height'=>90);
        $this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true);
        $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change");
        # END FORM DO NOT REMOVE THIS LINE

    }

    public function getProfile() {

        $this->button_addmore = FALSE;
        $this->button_cancel  = FALSE;
        $this->button_show    = FALSE;
        $this->button_add     = FALSE;
        $this->button_delete  = FALSE;
        $this->hide_form 	  = ['id_cms_privileges'];

        $data['page_title'] = 'Profile';
        $data['row']        = Crocoding::first('cms_users',Crocoding::myId());
        $this->cbView('crocoding::default.form',$data);
    }
}
