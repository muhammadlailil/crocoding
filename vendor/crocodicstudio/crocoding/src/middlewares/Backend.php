<?php
namespace crocodicstudio\crocoding\middlewares;

use Closure;
use crocodicstudio\crocoding\helpers\Crocoding;

class Backend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin_path = config('crocoding.ADMIN_PATH') ?: 'admin';

        if (Crocoding::myId() == '') {
            $url = url($admin_path.'/login');

            return redirect($url)->with('message', 'You are not logged in !');
        }
        if (Crocoding::isLocked()) {
            $url = url($admin_path.'/lock-screen');

            return redirect($url);
        }

        return $next($request);
    }
}
