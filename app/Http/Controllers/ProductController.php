<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Discount;
use App\Models\CartMenu;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $menus = Menu::all();

        if (request()->is('api/*')) {
            return response()->json($menus);
        }

        return view('product', compact('menus'));
    }

    public function create()
    {
        $category = Category::all();

        return view('addproduct', compact('category'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        if ($request->hasFile('img')) {
            $uploadedImage = $request->file('img');
            $imageName = $uploadedImage->getClientOriginalName();
            $imagePath = $uploadedImage->storeAs('public/img', $imageName);
            $data['img'] = 'img/' . $imageName;
        }

        Menu::create($data);

        return redirect(route('product'))->with('success', 'Product Sukses Dibuat !');
    }

    public function show($id)
    {
        $menu = Menu::find($id);
        $discount = Discount::all();

        if (request()->is('api/*')) {
            return response()->json([
                'menu' => $menu,
                'discount' => $discount,
            ]);
        }

        return view('showproduct', compact('menu', 'discount'));
    }


    public function edit($id)
    {
        $menu = Menu::find($id);
        $category = Category::all();
        return view('editproduct', compact('menu', 'category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        $menuData = $request->only(['name', 'price', 'img', 'description', 'category_id']);

        if ($request->hasFile('img')) {
            $uploadedImage = $request->file('img');
            $imageName = $uploadedImage->getClientOriginalName();
            $imagePath = $uploadedImage->storeAs('public/img', $imageName);
            $menuData['img'] = 'img/' . $imageName;
        }

        Menu::where('id', $id)->update($menuData);

        return redirect(route('product'))->with('success', 'Product Sukses Diupdate !');
    }

    public function destroy($id)
    {
        CartMenu::where('menu_id', $id)->delete();

        Menu::destroy($id);

        return redirect(route('product'))->with('success', 'Product Berhasil Dihapus !');
    }
}
