<?php

namespace App\Http\Middleware;

use Closure;

class CheckExpert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ajax = \Request::ajax(); //check request is ajax, => return true or false

        if(!auth()->check()){
            if($ajax){
                return response()->json(['error'=> __('messages.not_auth')], 403);
            }else {
                return redirect('/')->with('flash_message', __('messages.not_auth'));
            }
        }else if( !auth()->user()->checkExpert()  ) {
            if($ajax){
                return response()->json(['error'=> __('messages.not_auth')], 403);
            }else{
                return redirect('/')->with('flash_message', __('messages.not_auth'));
            }
        }

        return $next($request);
    }
}
