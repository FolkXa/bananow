<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $categoryName = $request->get('name');

            $existingCategory = Category::where('name', $categoryName)->first();

            if ($existingCategory) {
                return response()->json(['message' => 'Category with this name already exists'], 400);
            }

            $category = new Category();
            $category->name = $categoryName;
            $category->save();

            return response()->json(['message' => 'Category stored successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store category'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $newName = $request->get('name');

            if (empty($newName)) {
                return response()->json(['message' => 'Name field cannot be empty'], 400);
            }

            $category = Category::find($id);

            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            // Check if the new name already exists for another category
            $existingCategory = Category::where('name', $newName)->where('id', '!=', $id)->first();

            if ($existingCategory) {
                return response()->json(['message' => 'Category with this name already exists'], 400);
            }

            $category->name = $newName;
            $category->save();

            return response()->json(['message' => 'Category updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update category'], 500);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
