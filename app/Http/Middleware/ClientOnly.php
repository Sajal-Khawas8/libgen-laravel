<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role !== 1) {
            // return str_starts_with(parse_url(url()->previous())["path"], "/admin") ?
            //     redirect()->back() :
            //     redirect('/admin');
            return redirect('/admin')->with('error','You are not allowed to access this page');
        }
        return $next($request);
    }
}
