<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
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
            $userId     = auth()->user()->id;
            $response   = Order::where('user_id', $userId)->with('details.product');

            if($request->search) {
                
            }

            if($request->category_id) {

            }

            if($request->date) {
                $response = $response->where('created_at', $request->date);
            }

            if($request->status) {
                $response = $response->where('status', $request->status);
            }

            $response   = $response->get();

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

    public function store(OrderRequest $request)
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
            DB::beginTransaction();
            $request        = $request->json()->all();
            $user           = auth()->user();
            $userId         = $user->id;
            $userDetailId   = $user->detail->id;
            $carbon         = Carbon::now();
            $date           = str_replace('-', '', $carbon->format('Y-m-d'));
            $time           = str_replace(':', '', $carbon->format('H:i:s'));
            


            $carts      = $request['carts'];
            $cartsId    = [];
            foreach($carts as $row) {
                array_push($cartsId, $row['id']);
            }

            Cart::whereIn('id', $cartsId)->delete();

            $order = [
                'user_id'           => $userId,
                'user_detail_id'    => $userDetailId,
                'invoice'           => $time.$date.strtotime($carbon),
                'status'            => 'process',
                'payment_total'     => $request['payment']['total_price'],
                'payment_method'    => $request['payment']['method'],
            ];

            $order = Order::create($order);

            $orerDetail = [];
            foreach($request['carts'] as $row) {
                $orderDetail[] = [
                    'order_id'      => $order->id,
                    'product_id'    => $row['id'],
                    'qty'           => $row['qty'],
                    'price'         => $row['price'],
                    'total_price'   => $row['total_price'],
                    'is_discount'   => isset($row['is_discount']) ? $row['is_discount'] : null
                ];
            }

            OrderDetail::insert($orderDetail);

            $response = Order::with('details')->find($order->id);

            DB::commit();
            return response()->json([
                'status' => 200,
                'data'   => $response
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }
}
