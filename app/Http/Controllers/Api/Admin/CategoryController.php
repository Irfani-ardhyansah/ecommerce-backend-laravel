<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Image;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::limit(4)->get();

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
            if($request->has('image')) {
                $file   = $request->file('image');
                $image  = Image::upload($file, 'categories', $request->name);
            } 

            $response = Category::create([
                'name'          => $request->name,
                'description'   => $request->description,
                'image'         => isset($image) ? $image : null
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

            if($request->has('image')) {
                $file   = $request->file('image');
                $image  = Image::update($file, 'categories', $request->name, $category->image, $id);
            } 

            $category->update([
                'name'          => $request->name,
                'description'   => $request->description,
                'image'         => isset($image) ? $image : $category->image
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
            if (file_exists(public_path().$category->image)) {
                $resp = Image::delete($category->image);
                if(!$resp) {
                    return response()->json([
                        'status' => 500,
                        'data'   => $e->getMessage()
                    ]);
                }
            }
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
