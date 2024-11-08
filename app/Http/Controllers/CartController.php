<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\CartMenu;
use App\Models\Discount;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function vaddcart()
    {
        $menus = Menu::all();

        return view('addcart', compact('menus'));
    }

    public function vcart()
    {
        $carts = Cart::all();
        return response()->json(['carts' => $carts], 200);
    }

    public function show($id)
    {
        $cart = Cart::with(['user', 'cartMenus.menu', 'cartMenus.discount'])->findOrFail($id);
        return response()->json(['cart' => $cart], 200);
    }

    public function getCart(Request $request)
    {
        $user = $request->user();

        $cart = Cart::where('user_id', $user->id)->latest()->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();
        }

        return response()->json(['id' => $cart->id]);
    }


    public function store(Request $request, $cartId)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'discount_id' => 'nullable|exists:discounts,id', // Discount can be nullable
        ]);

        $menu = Menu::findOrFail($request->input('menu_id'));
        $quantity = (int) $request->input('quantity');
        $subtotal = (float) $menu->price * $quantity;

        $cart = Cart::findOrFail($cartId);

        $discount = null;
        $discountAmount = 0;

        // Check discount if provided
        if ($request->filled('discount_id')) {
            $discount = Discount::find($request->input('discount_id'));
            if ($discount) {
                $discountAmount = $subtotal * ($discount->percentage / 100);
                $subtotal -= $discountAmount; // Apply discount
            }
        }

        $existingCartMenu = CartMenu::where('cart_id', $cart->id)
            ->where('menu_id', $menu->id)
            ->where('notes', $request->input('notes'))
            ->where('discount_id', $discount ? $discount->id : null)
            ->first();

        if ($existingCartMenu) {
            $existingCartMenu->quantity += $quantity;
            $existingCartMenu->subtotal += $subtotal;
            $existingCartMenu->save();
        } else {
            $existingCartMenu = CartMenu::create([
                'cart_id' => $cart->id,
                'menu_id' => $menu->id,
                'quantity' => $quantity,
                'notes' => $request->input('notes'),
                'subtotal' => $subtotal,
                'discount_id' => $discount ? $discount->id : null, // Assign discount ID if applicable
            ]);
        }

        $cart->update(['total_amount' => $cart->total_amount + $subtotal]);

        return response()->json(['cart_menu' => $existingCartMenu, 'cart' => $cart], 201);
    }

    public function rmcart(Request $request, $cartId, $cartMenuId)
    {

        $cart = Cart::findOrFail($cartId);
        $cartMenu = CartMenu::findOrFail($cartMenuId);

        $cart->update(['total_amount' => $cart->total_amount - $cartMenu->subtotal]);

        $cartMenu->delete();

        return response()->json(['message' => 'Item removed from the cart', 'cart' => $cart], 200);
    }

    //ADMIN

    public function postcart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'discount_id' => 'nullable|exists:discounts,id', // Discount can be nullable
        ]);

        $user = auth()->user();
        $cart = $user->carts()->latest()->first() ?? $user->carts()->create(['total_amount' => 0]);

        $menu = Menu::findOrFail($request->input('menu_id'));
        $quantity = $request->input('quantity');

        // Ensure price is numeric
        $subtotal = (float)$menu->price * (int)$quantity;

        // Initialize discount variable
        $discount = null;

        // Check discount if provided
        if ($request->filled('discount_id')) {
            $discount = Discount::find($request->input('discount_id'));
            if ($discount) {
                $discountAmount = $subtotal * ($discount->percentage / 100);
                $subtotal -= $discountAmount; // Apply discount
            }
        }

        // Check if the same menu item with the same notes and discount already exists in the cart
        $existingCartMenu = CartMenu::where('cart_id', $cart->id)
            ->where('menu_id', $menu->id)
            ->where('notes', $request->input('notes'))
            ->where('discount_id', $discount ? $discount->id : null)
            ->first();

        if ($existingCartMenu) {
            // Update existing item in the cart
            $existingCartMenu->quantity += $quantity;
            $existingCartMenu->subtotal += $subtotal;
            $existingCartMenu->save();
        } else {
            // Create new cart menu entry
            CartMenu::create([
                'cart_id' => $cart->id,
                'menu_id' => $menu->id,
                'quantity' => $quantity,
                'notes' => $request->input('notes'),
                'subtotal' => $subtotal,
                'discount_id' => $discount ? $discount->id : null, // Assign discount ID if applicable
            ]);
        }

        // Update the total amount in the cart
        $cart->update(['total_amount' => $cart->total_amount + $subtotal]);

        return redirect(route('addorder'));
    }


    public function removecart(Request $request, $cartMenuId)
    {
        $user = auth()->user();
        $cart = $user->carts()->latest()->first();

        $cartMenu = CartMenu::findOrFail($cartMenuId);

        // Calculate the subtotal of the item being removed
        $subtotal = $cartMenu->subtotal;

        $discountId = $cartMenu->discount_id;

        $cartMenu->delete();

        $cart->update(['total_amount' => $cart->total_amount - $subtotal]);


        return redirect()->route('addorder');
    }
}
