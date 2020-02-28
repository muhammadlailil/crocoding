<?php
use crocodicstudio\crocoding\helpers\Crocoding;
$namespace = '\crocodicstudio\crocoding\controllers';

$app->router->get("tes",function (){
    return view('crocoding::home');
});
$app->router->get('app[/{id}/{name}]',function ($id='x',$name='y'){
    echo "ds".$id,$name;
});

$app->router->group([
    'middleware' => ['\crocodicstudio\crocoding\middlewares\Backend'],
    'prefix' => config('crocoding.ADMIN_PATH'),
    'namespace' => $namespace,
],function () use ($namespace,$app){
    /* DO NOT EDIT THESE BELLOW LINES */
    if (requestIs(config('crocoding.ADMIN_PATH'))) {
        $menus = DB::table('cms_menus')->where('is_dashboard', 1)->first();
        if (! $menus) {
            Crocoding::routeController('/', 'AdminController',$app->router, $namespace = '\crocodicstudio\crocoding\controllers');
        }
    }

    Crocoding::routeController('api_generator', 'ApiCustomController', $app->router,$namespace = '\crocodicstudio\crocoding\controllers');

    try {

        $master_controller = glob(__DIR__.'/controllers/*.php');
        foreach ($master_controller as &$m) {
            $m = str_replace('.php', '', basename($m));
        }

        $moduls = DB::table('cms_moduls')->whereIn('controller', $master_controller)->get();

        foreach ($moduls as $v) {
            if (@$v->path && @$v->controller) {
                Crocoding::routeController($v->path, $v->controller,$app->router, $namespace = '\crocodicstudio\crocoding\controllers');
            }
        }
    } catch (Exception $e) {

    }
//
//    Crocoding::routeController('/','AdminController',$app->router,$namespace);
//    Crocoding::routeController('/privileges','PrivilegesController',$app->router,$namespace);
});

$app->router->group(['middleware' => [],'prefix' => config('crocoding.ADMIN_PATH'), 'namespace' => $namespace], function () use ($app) {
    $app->router->post('unlock-screen', ['uses' => 'AdminController@postUnlockScreen', 'as' => 'postUnlockScreen']);
    $app->router->get('lock-screen', ['uses' => 'AdminController@getLockscreen', 'as' => 'getLockScreen']);
    $app->router->post('forgot', ['uses' => 'AdminController@postForgot', 'as' => 'postForgot']);
    $app->router->get('forgot', ['uses' => 'AdminController@getForgot', 'as' => 'getForgot']);
    $app->router->post('register', ['uses' => 'AdminController@postRegister', 'as' => 'postRegister']);
    $app->router->get('register', ['uses' => 'AdminController@getRegister', 'as' => 'getRegister']);
    $app->router->get('logout', ['uses' => 'AdminController@getLogout', 'as' => 'getLogout']);
    $app->router->post('login', ['uses' => 'AdminController@postLogin', 'as' => 'postLogin']);
    $app->router->get('login', ['uses' => 'AdminController@getLogin', 'as' => 'getLogin']);
});


$namespaceAdmin = 'App\Http\Controllers';
$app->router->group([
    'middleware' => ['\crocodicstudio\crocoding\middlewares\Backend'],
    'prefix' => config('crocoding.ADMIN_PATH'),
    'namespace' => $namespaceAdmin,
],function () use ($namespaceAdmin,$app){

    if (requestIs(config('crocoding.ADMIN_PATH'))) {
        $menus = DB::table('cms_menus')->where('is_dashboard', 1)->first();
        if ($menus) {
            if ($menus->type == 'Statistic') {
               $app->router->get('/', '\crocodicstudio\crocoding\controllers\StatisticBuilderController@getDashboard');
            } elseif ($menus->type == 'Module') {
                $module = Crocoding::first('cms_moduls', ['path' => $menus->path]);
               $app->router->get('/', $module->controller.'@getIndex');
            } elseif ($menus->type == 'Route') {
                $action = str_replace("Controller", "Controller@", $menus->path);
                $action = str_replace(['Get', 'Post'], ['get', 'post'], $action);
               $app->router->get('/', $action);
            } elseif ($menus->type == 'Controller & Method') {
               $app->router->get('/', $menus->path);
            } elseif ($menus->type == 'URL') {
                redirect($menus->path);
            }
        }
    }

    try {
        $moduls = DB::table('cms_moduls')->where('path', '!=', '')->where('controller', '!=', '')->where('is_protected', 0)->get();
        foreach ($moduls as $v) {
            Crocoding::routeController($v->path, "Admin\\".$v->controller,$app->router,$namespaceAdmin);
        }
    } catch (Exception $e) {

    }
});



/* ROUTER FOR API GENERATOR */
$namespace = '\crocodicstudio\crocoding\controllers';

$app->router->group(['middleware' => ['\crocodicstudio\crocoding\middlewares\CBAuthAPI'], 'namespace' => 'App\Http\Controllers\Api'], function () use ($app) {
    //Router for custom api defeault

    $dir = scandir(base_path("app/Http/Controllers/Api"));
    foreach ($dir as $v) {
        $v = str_replace('.php', '', $v);
        $names = array_filter(preg_split('/(?=[A-Z])/', str_replace('Controller', '', $v)));
        $names = strtolower(implode('_', $names));

        if (substr($names, 0, 4) == 'api_') {
            $names = str_replace('api_', '', $names);
            $app->router->get('api/'.$names, $v.'@execute_api');
            $app->router->post('api/'.$names, $v.'@execute_api');
            $app->router->put('api/'.$names, $v.'@execute_api');
            $app->router->patch('api/'.$names, $v.'@execute_api');
            $app->router->delete('api/'.$names, $v.'@execute_api');
        }
    }
});
