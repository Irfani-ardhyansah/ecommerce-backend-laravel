<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartController extends Controller
{
    public function index()
    {
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
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
            $response   = Cart::where('user_id', $user_id)
                ->with(['product', 'user'])
                ->paginate(10);

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
            if (!JWTAuth::parseToken()->authenticate()) {
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
            $product_id = $request->product_id;
            
            // check cart 
            $checkCart  = Cart::where([
                                    'user_id'       => $user_id,
                                    'product_id'    => $product_id
                                ])
                                ->first();

            $product    = Product::with('discount')->findOrFail($request->product_id);
            $price      = $product->price;
            if($product->discount) {
                $price = $product->discount->price;
            }

            if(!$checkCart) {
                $response = $this->handleStore($user_id, $product, $price, $request);
            } else {
                $response = $this->handleUpdate($product, $price, $request, $checkCart);
            }

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

    private function handleStore($user_id, $product, $price, $request) 
    {
        $response   = Cart::create([
            'user_id'       => $user_id,
            'product_id'    => $request->product_id,
            'qty'           => $request->qty,
            'price'         => $price,
            'total_price'   => ($request->qty * $price),
            'is_discount'   => isset($product->discount) ? 1 : null
        ]);

        return $response;
    }

    private function handleUpdate($product, $price, $request, $cart)
    {
        $cart->update([
            'qty'           => $request->qty,
            'price'         => $price,
            'total_price'   => ($request->qty * $price),
            'is_discount'   => isset($product->discount) ? 1 : null
        ]);

        return $cart;
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
