<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header("Authorization");
        $auth = true;

        if (!$token){
            $auth = false;
        }

        $user = User::query()->where("token", $token)->first();

        if (!$user){
            $auth =false;
        } else{
            $request = $request->merge(["current_user"=> $user]);
        }
        // dd($request);
        if($auth){

            return $next($request);
        } else {
            return response()->json(
                [
                    "errors"=>[
                        "message"=>[
                            "unauthorized"
                        ]
                    ]
                ]
            )->setStatusCode(401);
        }

        
    }
}
