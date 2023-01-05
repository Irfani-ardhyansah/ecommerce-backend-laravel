<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;

class ProfileController extends Controller
{
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

    public function index($id)
    {
        try {
            $response = User::with('detail')->find($id);

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

    public function edit(UserRequest $request, $id)
    {
        try {
            $user = User::find($id)->update([
                'email'     => $request->email,
            ]);

            UserDetail::where('user_id', $id)->first()->update([
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
