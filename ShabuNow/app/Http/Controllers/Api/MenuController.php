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

    public function availableMenu() {
        return Menu::where('status', 'available')->get();
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
        $request->validate([
            'name' => 'required|string|min:1|max:20',
            'imgPath' => 'nullable|string',
            'description' => 'nullable|string|min:1|max:30',
            'status' => 'required|in:available,outofstock',
            'price' => 'required|integer|min:1|max:1000',
        ]);

        $menu = Menu::find($id);
        if (!$menu) {
            return abort(400, 'invalid menu id');
        }

        $menu->name = $request->get('name');
        $menu->imgPath = $request->get('imgPath');
        $menu->description = $request->get('description');
        $menu->status = $request->get('status');
        $menu->price = $request->get('price');
        $menu->save();
        $menu->refresh();

        return $menu;
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
            'name' => 'required|string|min:1|max:20',
            'imgPath' => 'nullable|string',
            'description' => 'nullable|string|min:1|max:30',
            'status' => 'required|in:available,outofstock',
            'price' => 'required|integer|min:1|max:1000',
        ]);

        $name = $request->get('name');
        if (Menu::where('name', $name)) {
            return abort(400, 'repeated name');
        }
        $menu = new Menu();

        $menu->name = $name;
        $menu->imgPath = $request->get('imgPath');
        $menu->description = $request->get('description');
        $menu->status = $request->get('status');
        $menu->price = $request->get('price');
        $menu->save();
        $menu->refresh();

        return $menu;
    }
}
