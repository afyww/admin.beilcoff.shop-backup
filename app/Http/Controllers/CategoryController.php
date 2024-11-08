<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::with(['menus'])->get();

        if (request()->is('api/*')) {
            return response()->json($category);
        }

        return view('category', compact('category'));
    }

    public function create()
    {
        return view('addcategory');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        Category::create($data);

        return redirect(route('category'))->with('success', 'Category Sukses Dibuat !');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('editcategory', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $data = $request->only(['name']);

        Category::where('id', $id)->update($data);

        return redirect(route('category'))->with('success', 'Category Sukses Diupdate !');
    }

    public function destroy($id)
    {
        Category::destroy($id);

        return redirect(route('category'))->with('success', 'Category Berhasil Dihapus !');
    }
}
