<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'imgPath' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string|min:1|max:30',
            'status' => 'required|in:available,outofstock',
            'price' => 'required|integer|min:1|max:1000',
        ]);

        $menu = Menu::find($id);
        if (!$menu) {
            return abort(400, 'invalid menu id');
        }

        $menu->name = $request->get('name');
        $menu->description = $request->get('description');
        $menu->status = $request->get('status');
        $menu->price = $request->get('price');

        // Check if an image file is provided
        if ($request->hasFile('imgPath')) {
            // delete file
            if ($menu->imgPath) {
                $filePath = 'public' . $menu->imgPath;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }

            // Upload the image
            $image = $request->file('imgPath');
            $imageName = time().'.'.$image->extension();

            // Store the image in the 'menu_images' directory in the public disk
            $path = 'images/menus';
            $fullPath = '/'.$path.'/'.$imageName;
            $image->move(public_path($path), $imageName);

            // Save the image path to the database
            $menu->imgPath = $fullPath;
        }
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
            'imgPath' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string|min:1|max:30',
            'status' => 'required|in:available,outofstock',
            'price' => 'required|integer|min:1|max:1000',
        ]);

        $name = $request->get('name');
        $menu = new Menu();

        $menu->name = $name;
        $menu->description = $request->get('description');
        $menu->status = $request->get('status');
        $menu->price = $request->get('price');

        // Check if an image file is provided
        if ($request->hasFile('imgPath')) {
            // Upload the image
            $image = $request->file('imgPath');
            $imageName = time().'.'.$image->extension();

            // Store the image in the 'menu_images' directory in the public disk
            $path = 'images/menus';
            $fullPath = '/'.$path.'/'.$imageName;
            $image->move(public_path($path), $imageName);

            // Save the image path to the database
            $menu->imgPath = $fullPath;
        }

        $menu->save();
        $menu->refresh();

        return $menu;
    }
}
