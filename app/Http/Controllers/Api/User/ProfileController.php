<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Image;

class ProfileController extends Controller
{

    public function index()
    {
        try{
            if (! JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'data'    => null
                ], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired',
                'data'    => null
            ], 400);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalid',
                'data'    => null
            ], 400);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token absent',
                'data'    => null
            ], 400);
        }

        try {
            $user_id    = auth()->user()->id;
            $response   = User::with('detail')->find($user_id);

            return response()->json([
                'status' => 200,
                'data'   => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        try{
            if (! JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'data'    => null
                ], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired',
                'data'    => null
            ], 400);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalid',
                'data'    => null
            ], 400);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token absent',
                'data'    => null
            ], 400);
        }

        try {
            $user_id    = auth()->user()->id;
            $user       = User::find($user_id);
            $detail     = UserDetail::where('user_id', $user_id);

            if($request->email) {
                $user->update(['email' => $request->email]);
            }

            if($request->birthday) {
                $detail->update(['birthday' => $request->birthday]);
            }

            if($request->gender) {
                $detail->update(['gender' => $request->gender]);
            }

            if($request->name) {
                $detail->update(['name' => $request->name]);
            }

            if($request->phone) {
                $detail->update(['phone' => $request->phone]);
            }

            if($request->address) {
                $detail->update(['address' => $request->address]);
            }
            
            if($request->password) {
                $user->update(['password' => $request->password]);
            }

            if($request->has('image')) {
                $file   = $request->file('image');
                $image  = Image::update($file, 'profile', $user->email, $detail->first()->image, $user_id);
            }

            $response = User::with('detail')->find($user_id);

            return response()->json([
                'status' => 200,
                'data'   => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }
}
