<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();

        if (request()->is('api/*')) {
            return response()->json($discounts);
        }

        return view('discount', compact('discounts'));
    }

    public function create()
    {
        return view('adddiscount');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'percentage' => 'required',
        ]);

        Discount::create($data);

        return redirect(route('discount'))->with('success', 'Discount Sukses Dibuat !');
    }

    public function edit($id)
    {
        $discount = Discount::find($id);
        return view('editdiscount', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'percentage' => 'required',
        ]);

        $data = $request->only(['name', 'nominal']);

        Discount::where('id', $id)->update($data);

        return redirect(route('discount'))->with('success', 'Discount Sukses Diupdate !');
    }

    public function destroy($id)
    {
        Discount::destroy($id);

        return redirect(route('discount'))->with('success', 'Discount Berhasil Dihapus !');
    }
}
