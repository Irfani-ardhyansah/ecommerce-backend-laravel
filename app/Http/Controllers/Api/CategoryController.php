<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::get();

            return response()->json([
                'status' => 200,
                'data'   => $categories
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 500,
                'data'   => $e->getMessage()
            ]);
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $response = Category::create([
                'name'          => $request->name,
                'description'   => $request->description
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

    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = Category::find($id);
            $category->update([
                'name'          => $request->name,
                'description'   => $request->description
            ]);

            return response()->json([
                'status' => 200,
                'data'   => $category
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
            $category = Category::find($id);
            $category->delete();

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
}
