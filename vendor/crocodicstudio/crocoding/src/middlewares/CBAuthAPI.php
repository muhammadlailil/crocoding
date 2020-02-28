<?php
namespace crocodicstudio\crocoding\middlewares;


use Closure;
use crocodicstudio\crocoding\helpers\Crocoding;

class CBAuthAPI
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


        Crocoding::authAPI();

        return $next($request);
    }
}
