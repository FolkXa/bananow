<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Menu::get();
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return $menu;
    }

    public function showMenuById(string $id)
    {
        return Menu::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:1'],
            'price' => ['required', 'integer', 'min:1'],
            'category' => ['required', 'string'],
            'description' => ['required', 'string', 'min:1'],
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);


        // Store image in 'public/images' folder


        // Search what category has the same name as selected
        $category = Category::where('name', $request->get('category'))->first();

        $menu = new Menu();

        $menu->name = $request->get('name');
        $menu->price = $request->get('price');
        $menu->category_id = $category->id;
        $menu->description = $request->get('description');
        $menu->status = 'available';

        if($request->file('image') != null )
        {
            $imagePath = $request->file('image')->store('foodImages', 'public');
            $menu->imgPath = $imagePath;
        }

        $menu->save();
        $menu->refresh();

        return $menu;
    }
}
