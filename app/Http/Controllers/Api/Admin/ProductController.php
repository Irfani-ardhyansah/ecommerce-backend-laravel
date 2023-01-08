<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductDiscount;
use Image;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('discount')->get();

            return response()->json([
                'status' => 200,
                'data'   => $products
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function store(ProductRequest $request )
    {
        try {
            if($request->has('image')) {
                $file   = $request->file('image');
                $image  = Image::upload($file, 'products', $request->name);
            } 

            $response = Product::create([
                'category_id'   => $request->category_id,
                'name'          => $request->name,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock,
                'status'        => 1,
                'image'         => isset($image) ? $image : null
            ]);

            return response()->json([
                'status' => 200,
                'data'   => $response
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::with('discount')->find($id);

            if($request->has('image')) {
                $file   = $request->file('image');
                $image  = Image::upload($file, 'products', $request->name, $product->image);
            } 

            $product->update([
                'category_id'   => $request->category_id,
                'name'          => $request->name,
                'description'   => $request->description,
                'price'         => $request->price,
                'stock'         => $request->stock,
                'status'        => 1,
                'image'         => isset($image) ? $image : $product->image
            ]);

            return response()->json([
                'status' => 200,
                'data'   => $product
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);

            if (file_exists($product->image)) {
                $resp = Image::delete($product->image);
                if(!$resp) {
                    return response()->json([
                        'status' => 500,
                        'data'   => $e->getMessage()
                    ]);
                }
            }

            $product->delete();

            return response()->json([
                'status' => 200,
                'data'   => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function setDiscount(Request $request, $id)
    {
        try {
            // validation
            $request->validate([
                'status'    => 'required|boolean',
                'price'     => 'required|integer',
            ]);

            $check = Product::select('id')->with('discount')->find($id);
            // check if product exists
            if($check) {
                // check if already had discount data
                if($check->discount) {
                    $response = ProductDiscount::find($check->discount->id);
                    // check if status 1, then update data
                    if($request->status == 1) {
                        $response->update([
                            'price'     => $request->price,
                            'start_at'  => $request->start_at,
                            'end_at'    => $request->end_at
                        ]);
                    // check if status 0, then delete data
                    } else if($request->status == 0) {
                        $response->delete();
                    }
                } else {
                    // create product discount
                    $response = ProductDiscount::create([
                        'product_id'    => $id,
                        'price'         => $request->price,
                        'start_at'      => $request->start_at,
                        'end_at'        => $request->end_at
                    ]);
                }

                return response()->json([
                    'status' => 200,
                    'data'   => $response
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'data'   => 'Product id does not exists!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function setStatus($id)
    {
        try {
            $product = Product::with('discount')->find($id);

            if($product->status == 1) {
                $product->update(['status' => 0]);
            } else {
                $product->update(['status' => 1]);
            }
            return response()->json([
                'status' => 200,
                'data'   => $product
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }
}
