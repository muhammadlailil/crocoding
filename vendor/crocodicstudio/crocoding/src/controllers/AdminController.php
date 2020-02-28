<?php

namespace crocodicstudio\crocoding\controllers;
use crocodicstudio\crocoding\helpers\Crocoding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class AdminController extends CBController
{
    public function getIndex()
    {
        $data = [];
        $data['page_title'] = 'Dashboard';

        return view('crocoding::home', $data);
    }

    public function getLockscreen()
    {

        if (! Crocoding::myId()) {
            session()->flush();

            return redirect()->route('getLogin')->with('message', 'Your session was expired, please login again !');
        }

        session()->put('admin_lock', 1);

        return view('crocoding::lockscreen');
    }
    public function postUnlockScreen()
    {
        $id = Crocoding::myId();
        $password = g('password');
        $users = DB::table(config('crocoding.USER_TABLE'))->where('id', $id)->first();

        if (Hash::check($password, $users->password)) {
            session()->put('admin_lock', 0);

            return redirect(Crocoding::adminPath());
        } else {
            echo "<script>alert('Sorry your password is wrong !');history.go(-1);</script>";
        }
    }

    public function getLogin()
    {
        if (Crocoding::myId()) {
            return redirect(Crocoding::adminPath());
        }
        $data['page_title'] = 'Login';
        return view('crocoding::login',$data);
    }

    public function postLogin()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:'.config('crocoding.USER_TABLE'),
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return redirect(back())->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $email = g("email");
        $password = g("password");
        $users = DB::table(config('crocoding.USER_TABLE'))->where("email", $email)->first();

        if (Hash::check($password, $users->password)) {
            $priv = DB::table("cms_privileges")->where("id", $users->id_cms_privileges)->first();

            $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', $users->id_cms_privileges)->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();

            $photo = ($users->photo) ? asset($users->photo) : asset('vendor/crocoding/asstes/img/avatar.jpg');
            $logo = (Crocoding::getSetting('logo'))?asset(Crocoding::getSetting('logo')):asset('vendor/crocoding/assets/img/img_login_logo.png');
            setSession('admin_id', $users->id);
            setSession('admin_is_superadmin', $priv->is_superadmin);
            setSession('admin_name', $users->name);
            setSession('admin_photo', $photo);
            setSession('admin_privileges_roles', $roles);
            setSession("admin_privileges", $users->id_cms_privileges);
            setSession('admin_privileges_name', $priv->name);
            setSession('admin_lock', 0);
            setSession('theme_color', $priv->theme_color);
            setSession("appname", Crocoding::getSetting('appname'));
            setSession("applogo", $logo);

            Crocoding::insertLog($users->email.' login with IP Address '.requestServer('REMOTE_ADDR'));


            return redirect(Crocoding::adminPath());
        } else {
            return redirect()->route('getLogin')->with('message', 'Sorry your password is wrong !');
        }
    }


    public function getForgot()
    {
        if (Crocoding::myId()) {
            return redirect(Crocoding::adminPath());
        }

        return view('crocoding::forgot');
    }

    public function postForgot()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:'.config('crocoding.USER_TABLE'),
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();

            return redirect(back())->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $rand_string = str_random(5);
        $password = Hash::make($rand_string);

        DB::table(config('crocoding.USER_TABLE'))->where('email', g('email'))->update(['password' => $password]);

        $appname = Crocoding::getSetting('appname');
        $user = Crocoding::first(config('crocoding.USER_TABLE'), ['email' => g('email')]);
        $user->password = $rand_string;
        Crocoding::sendEmail(['to' => $user->email, 'data' => $user, 'template' => 'forgot_password_backend']);

        Crocoding::insertLog(trans("crocoding.log_forgot", ['email' => g('email'), 'ip' => Request::server('REMOTE_ADDR')]));

        return redirect()->route('getLogin')->with('message', 'We have sent new password to your email, check inbox or spambox !');
    }

    public function getLogout()
    {

        $me = Crocoding::me();
        Crocoding::insertLog($me->email. ' logout');

        session()->flush();

        return redirect()->route('getLogin')->with('message', 'Thank You, See You Later !');
    }



}
