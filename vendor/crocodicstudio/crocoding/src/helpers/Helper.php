<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;


if(!function_exists('requestIs')) {
    function requestIs($path) {
        $request = app('request');
        $is = $request->is($path);
        return $is;
    }
}

if(!function_exists('request')) {
    function request() {
        return app('request');
    }
}


if(!function_exists('session')) {
    function session() {
        return app('request')->session();
    }
}

if(!function_exists('csrf_token')) {
    function csrf_token($path=null) {
        return app('request')->session()->get('_token');
    }
}


if(!function_exists('currentRouteAction')) {
    function currentRouteAction() {

//        dd(app("app")->router->getRoutes());
        $request = app('request');
        $verbs = 'GET|POST|PUT|DELETE|PATCH';
        $routeToRegex = function ($string) use ($verbs) {
            $string = preg_replace("/^({$verbs})/", '', $string);
            $string = preg_replace('/\{\w+\}/', '\w+', $string);
            $string = preg_replace('/\{(\w+):(.+?)\}/', '\2', $string);

            return '#^'.$string.'$#';
        };
        $routeToMethod = function ($string) use ($verbs) {
            return preg_replace("/^({$verbs}).+$/", '\1', $string);
        };

        $routes = [];

        foreach (app("app")->router->getRoutes() as $routeName => $route) {
            $regex = $routeToRegex($routeName);
            $method = $routeToMethod($routeName);
            $routes[$regex] = compact('route', 'method');
        }

        uksort($routes, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        $method = $request->getMethod();
        $path = rtrim($request->getPathInfo(), '/');
        $foundRoute = null;

        foreach ($routes as $regex => $details) {
            $regex = str_replace('[','',$regex);
            $regex = str_replace(']','',$regex);
            if (true == preg_match($regex, $path) && $method == $details['method']) {
                $foundRoute = $details['route'];
                break;
            }
        }


        return $foundRoute['action']['uses'];
    }
}

if(!function_exists('adminPath')) {
    function adminPath($path=null) {
        return url(config('crocoding.ADMIN_PATH').'/'.$path);
    }
}

if(!function_exists('appName')) {
    function appName() {
        $s = getSession('appname');

        return ($s)?$s:config('crocoding.appname');
    }
}
if(!function_exists('requestUrl')) {
    function requestUrl() {
        $request = app('request');
        return $request->url();
    }
}
if(!function_exists('requestFullUrl')) {
    function requestFullUrl() {
        $request = app('request');
        return $request->fullUrl();
    }
}

if(!function_exists('back')) {
    function back() {
        $back =  app("request")->session()->get('_previous')['url'];
        return $back;
    }
}

if(!function_exists('g')) {
    function g($name) {
        $request = app('request');
        return $request->get($name);
    }
}

if(!function_exists('requestServer')) {
    function requestServer($name) {
        $request = app('request');
        return $request->server($name);
    }
}
if(!function_exists('requestHeader')) {
    function requestHeader($name) {
        $request = app('request');
        return $request->header($name);
    }
}

if(!function_exists('requestAll')) {
    function requestAll() {
        $request = app('request');
        return $request->all();
    }
}

if(!function_exists('getSegment')) {
    function getSegment($index) {
        $request = app('request');
        return $request->segment($index);
    }
}

if (! function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool    $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if(!function_exists('getSession')) {
    function getSession($name) {
        return app("request")->session()->get($name);
    }
}

if(!function_exists('setSession')) {
    function setSession($name,$value) {
        return app("request")->session()->put($name,$value);
    }
}

if(!function_exists('hasFile')) {
    function hasFile($name) {
        $request = app('request');
        return $request->hasFile($name);
    }
}


if(!function_exists('getFile')) {
    function getFile($name) {
        $request = app('request');
        return $request->file($name);
    }
}
if (! function_exists('str_slug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @return string
     */
    function str_slug($title, $separator = '-')
    {
        return Str::slug($title, $separator);
    }
}

if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_path($path = '')
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
//        return app()->make('path.public').($path ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}

if (! function_exists('action')) {
    /**
     * Generate the URL to a controller action.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    function action($name, $parameters = [], $absolute = true)
    {
        return app('url')->action($name, $parameters, $absolute);
    }
}

if (! function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return app('request')->old($key, $default);
    }
}

if (! function_exists('str_random')) {
    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int  $length
     * @return string
     *
     * @throws \RuntimeException
     */
    function str_random($length = 16)
    {
        return Str::random($length);
    }
}

if(!function_exists('now')) {
    function now() {
        return date('Y-m-d H:i:s');
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app('path').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if(!function_exists('extract_unit')) {
    /*
    Credits: Bit Repository
    URL: http://www.bitrepository.com/extract-content-between-two-delimiters-with-php.html
    */
    function extract_unit($string, $start, $end)
    {
        $pos = stripos($string, $start);
        $str = substr($string, $pos);
        $str_two = substr($str, strlen($start));
        $second_pos = stripos($str_two, $end);
        $str_three = substr($str_two, 0, $second_pos);
        $unit = trim($str_three); // remove whitespaces
        return $unit;
    }
}

if(!function_exists('min_var_export')) {
    function min_var_export($input) {
        if(is_array($input)) {
            $buffer = [];
            foreach($input as $key => $value)
                $buffer[] = var_export($key, true)."=>".min_var_export($value);
            return "[".implode(",",$buffer)."]";
        } else
            return var_export($input, true);
    }
}

if (! function_exists('ends_with')) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function ends_with($haystack, $needles)
    {
        return Str::endsWith($haystack, $needles);
    }
}
