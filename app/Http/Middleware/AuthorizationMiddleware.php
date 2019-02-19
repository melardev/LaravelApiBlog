<?php

namespace App\Http\Middleware;


class AuthorizationMiddleware
{
    public function handle(Request $request, Closure $next, string $role) {
        //$route = $request->route('id');
        //dd($request->route()->parameters());
        if (!$request->user()->hasRole($role))
            return redirect()->route('home')
                //->withErrors(__('auth.not_authorized'))
                             ->withErrors('Permission Denied');

        return $next($request);
    }
}