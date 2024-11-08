<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();

        if (request()->is('api/*')) {
            return response()->json($expenses);
        }

        return view('expense', compact('expenses'));    }

    public function create()
    {
        return view('addexpense');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'nominal' => 'required',
        ]);

        Expense::create($data);

        return redirect(route('expense'))->with('success', 'Expense Sukses Dibuat !');
    }

    public function edit($id)
    {
        $expense = Expense::find($id);
        return view('editexpense', compact('expense'));

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'nominal' => 'required',
        ]);

        $data = $request->only(['name', 'nominal']);

        Expense::where('id', $id)->update($data);

        return redirect(route('expense'))->with('success', 'Expense Sukses Diupdate !');
    }

    public function destroy($id)
    {
        Expense::destroy($id);

        return redirect(route('expense'))->with('success', 'Expense Berhasil Dihapus !');

    }
}
