<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function vchair()
    {
        $users = User::where('level', 'user')->get();
        return view('chair', compact('users'));
    }

    public function vuser()
    {
        $users = User::whereIn('level', ['cashier'])->get();
        return view('user', compact('users'));
    }

    public function vcreateuser()
    {

        return view('adduser');
    }

    public function vcreatechair()
    {

        return view('addchair');
    }

    public function rmuser($id)
    {
        User::destroy($id);

        return redirect(route('user'))->with('success', 'User Berhasil Dihapus !');
    }

    public function rmchair($id)
    {
        Order::whereHas('cart', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->delete();

        Cart::where('user_id', $id)->delete();
        User::destroy($id);

        return redirect(route('chair'))->with('success', 'Kursi Berhasil Dihapus !');
    }
}
