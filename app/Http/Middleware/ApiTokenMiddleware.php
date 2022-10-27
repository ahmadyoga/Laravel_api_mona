<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ApiTokenMiddleware
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
        //cek api token pada user
        //jika tidak terdaftar, kembalikan pesan error
        //jika berhasil maka lanjutkan
        if(User::where('api_token', $request->api_token)->count()<=0){
            return response()->json([
                'massage' => 'API TOKEN tidak terdaftar'
            ]);
        }

        return $next($request);
    }
}
