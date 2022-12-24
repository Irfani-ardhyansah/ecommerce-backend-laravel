<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        try{

        } catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }
}
