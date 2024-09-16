<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Throw_;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\UserLoginResource;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if(User::where("email",$data["email"])->count() == 1){
            throw new HttpResponseException(response(
                [
                "errors"=> [
                        "email"=>[
                            "       email already exist"
                                ]
                            ]
                ],400));
        };
        // dd($data);
        $user = new User($data);
        // dd($data);
        $user-> password = Hash::make($data["password"]);
        $user->save();

        // return new UserResource($user);
        $resp = new UserResource($user,   ["gori"=>"kingkong"] );
        // $resp->additional =;
        return $resp->response()->setStatusCode(201);
        // return (new UserResource($user))->response()->setStatusCode(201);
    }
    
    public function login(UserLoginRequest $request): UserLoginResource
    {
        $data = $request->validated();
        // dd(request(["email"]), $data);
        // dd(Auth::attempt(($data)));
        $user = User::query()->where("email",$data["email"])->first();
        if(
            !$user || !(Hash::check($data["password"], $user->password))
            ){
            throw new HttpResponseException(response(
                [
                "errors"=> [
                        "message"=>[
                            "wrong email or password"
                                ]
                            ]
                ],401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();


        return new UserLoginResource($user);
    }

    public function get(Request $request): UserResource
    {

        // $is_user = $request::has('current_user');
        // dd($is_user);

        $data = $request["current_user"];
        if (!$data){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                            ]
                        ]
            ],400));
        }
        // dd($request["current_usser"]);

        return new UserResource($data);
    }

    public function patch(UserUpdateRequest $request): UserResource
    {
        $curent_user = $request->get("current_user");
        // request();
        // dd(request(["current_user"]));
        if (!$curent_user){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                            ]
                        ]
            ],400));
        }
        $data = $request->validated();
        // dd($data);
        
        // $olduser = User::query()->find($curent_user->id);
        // dd($olduser);
        $curent_user->update($data);
        $newuser = User::query()->find($curent_user->id);
        // dd($newuser);
        return new UserResource(($newuser));
    }

    public function logout(Request $request): JsonResponse {
        $curent_user = $request->get("current_user");
        if (!$curent_user){
            throw new HttpResponseException(response([
                "errors"=> [
                    "message"=>[
                        "invalid user"
                            ]
                        ]
            ],400));
        }

        return response()->json(["data"=>true]);
    }


// todo
// check availabel email
// upload avatar

}
