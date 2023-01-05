<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        try {
            $response = Cart::with(['product', 'user'])->paginate(10);

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

    public function store(CartRequest $request)
    {
        try {
            $response = Cart::create([
                'user_id'       => $request->user_id,
                'product_id'    => $request->product_id,
                'qty'           => $request->qty,
                'price'         => $request->price,
                'total_price'   => ($request->qty * $request->price)
            ]);

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

    public function update(Request $request, $id)
    {
        try {
            $cart = Cart::with(['product', 'user'])->find($id);
            $cart->update([
                'qty'           => $request->qty,
                'price'         => $request->price,
                'total_price'   => ($request->qty * $request->price)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $response = Cart::find($id);
            $response->delete();

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
