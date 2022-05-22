<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProductValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'availiable_quantity' => 'integer'
        ]);
        return $next($request);
    }
}
