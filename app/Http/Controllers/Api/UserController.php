<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserDetail;
use Auth;
use Validator;
use Carbon\Carbon;  
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function auth(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            if (!$token = auth()->attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            return response()->json([
                'status'    => 200,
                'data'      => [
                    'user'              => Auth::user(),
                    'token'             => $token,
                    'token_created_at'  => Carbon::now()
                ]
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status'    => 200,
                'data'      => null
            ], 201);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function register(UserRequest $request)
    {
        try {
            $user = User::create([
                'email'     => $request->email,
                'role'      => 'customer',
                'password'  => bcrypt($request->password)
            ]);

            UserDetail::create([
                'user_id'   => $user->id,
                'name'      => $request->name,
                'address'   => $request->address,
                'phone'     => $request->phone,
            ]);

            return response()->json([
                'status' => 200,
                'data'   => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }
}
