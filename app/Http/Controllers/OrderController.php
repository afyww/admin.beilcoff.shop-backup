<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use App\Models\Histoy;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['cart.user', 'cart.cartMenus.menu'])->get();
        $statuses = [];
    
        foreach ($orders as $order) {
            try {
                // Check if the order has already been marked as 'settlement' through cash payment
                if ($order->status === 'settlement' && $order->payment_type === 'cash') {
                    // Add to statuses without further processing
                    $statuses[$order->no_order] = (object) [
                        'status' => $order->status,
                        'bg_color' => 'text-white text-center bg-green-500 w-fit rounded-xl'
                    ];
                    continue; // Skip further processing for this order
                }
    
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = true;
    
                $status = \Midtrans\Transaction::status($order->no_order);
    
                $order->update([
                    'status' => $status->transaction_status,
                    'payment_type' => $status->payment_type ?? null,
                ]);
    
                if ($status->transaction_status === 'expire') {
                    $order->delete();
                    continue;
                }
    
                $statuses[$order->no_order] = (object) [
                    'status' => $status->transaction_status,
                    'bg_color' => $status->transaction_status === 'settlement' ? 'text-white text-center bg-green-500 w-fit rounded-xl' : 'text-white text-center bg-red-500 w-fit rounded-xl'
                ];
            } catch (\Exception $e) {
                $statuses[$order->no_order] = (object) [
                    'status' => 'Error: ' . $e->getMessage(),
                    'bg_color' => 'bg-red-500 w-fit text-white text-center rounded-xl'
                ];
    
            }
        }
    
        if (request()->is('api/*')) {
            return response()->json(['orders' => $orders, 'statuses' => $statuses]);
        }
    
        return view('order', compact('orders', 'statuses'));
    }
    


    public function checkOrder()
    {
        $newOrdersCount = Order::where('status', 'settlement')->count();

        return response()->json(['newOrdersCount' => $newOrdersCount]);
    }


    public function show($id)
    {
        $order = Order::with(['cart.user', 'cart.cartMenus.menu'])->findOrFail($id);
        return response()->json(['order' => $order], 200);
    }

    public function placeOrder(Request $request, $cartId)
    {
        $request->validate([
            'no_telpon' => 'required|string|max:15', // Adjust the validation rules as necessary
            'atas_nama' => 'required|string|max:255',
        ]);
    
        $cart = Cart::where('id', $cartId)
            ->where('user_id', auth()->id())
            ->with(['user', 'cartMenus.menu'])
            ->first();
    
        if (!$cart) {
            return response()->json(['error' => 'Invalid cart or unauthorized access.'], 403);
        }
    
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    
        $user = $cart->user;
    
        $orderId = substr(Uuid::uuid4()->toString(), 0, 5);
    
        $params = [
            'item_details' => [
                [
                    'id' => $cartId,
                    'price' => $cart->total_amount,
                    'quantity' => 1,
                    'name' => 'Order for cart ' . $cartId,
                ]
            ],
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $cart->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $request->no_telpon,
            ],
            'expiry' => [
                'start_time' => date("Y-m-d H:i:s T"), // Start time in format 'Y-m-d H:i:s T'
                'unit' => 'minutes',
                'duration' => 15 // Duration in minutes (adjust as needed)
            ],
        ];
    
        $snapToken = \Midtrans\Snap::getSnapToken($params);
    
        $order = new Order();
        $order->cart_id = $cartId;
        $order->no_order = $orderId;
        $order->atas_nama = $request->atas_nama;
        $order->no_telpon = $request->no_telpon;
        $order->status = 'pending'; // Initial status
        $order->save();
    
        $newCart = new Cart();
        $newCart->user_id = $user->id;
        $newCart->save();
    
        return response()->json(['snapToken' => $snapToken, 'message' => 'Order placed successfully.'], 200);
    }
    
    public function placeCash(Request $request, $cartId)
    {
        $request->validate([
            'no_telpon' => 'required|string|max:15', // Adjust the validation rules as necessary
            'atas_nama' => 'required|string|max:255',
        ]);
    
        $cart = Cart::where('id', $cartId)
            ->where('user_id', auth()->id())
            ->with(['user', 'cartMenus.menu'])
            ->first();
    
        if (!$cart) {
            return response()->json(['error' => 'Invalid cart or unauthorized access.'], 403);
        }
    
        $orderId = substr(Uuid::uuid4()->toString(), 0, 5);
    
        $order = new Order();
        $order->status = 'settlement';
        $order->payment_type = 'cash';
        $order->cart_id = $cartId;
        $order->no_order = $orderId;
        $order->atas_nama = $request->atas_nama;
        $order->no_telpon = $request->no_telpon;
        $order->save();
    
        $newCart = new Cart();
        $newCart->user_id = $cart->user_id;
        $newCart->save();
    
        return response()->json(['message' => 'Order placed successfully.'], 200);
    }
    
    //ADMIN

    public function archive($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            if (request()->is('api/*')) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            return redirect()->back()->with('error', 'Order not found');
        }

        // Retrieve the authenticated user
        $user = auth()->user();

        // Retrieve or create a settlement
        $settlement = $user->settlements()->latest()->first();

        if (!$settlement) {
            // Create a new settlement if none exists
            $settlement = new Settlement();
            $settlement->user_id = $user->id;
            $settlement->start_time = now(); // Set as needed
            $settlement->start_amount = 0; // Initialize as needed
            $settlement->total_amount = 0; // Initialize as needed
            $settlement->expected = 0; // Initialize as needed
            $settlement->save();
        }

        DB::transaction(function () use ($order, $settlement) {
            $history = new Histoy();
            $history->id = $order->id; // Assuming you want to keep the same ID
            $history->no_order = $order->no_order;
            $history->kursi = $order->cart->user->name;
            $history->name = $order->atas_nama;
            $orderDetails = '';
            foreach ($order->cart->cartMenus as $cartMenu) {
                $orderDetails .= $cartMenu->menu->name . ' - ' . $cartMenu->quantity . ' - ' . $cartMenu->notes . ' - ';
            }
            $history->order = $orderDetails;
            $history->total_amount = $order->cart->total_amount;
            $history->status = $order->status;
            $history->payment_type = $order->payment_type;
            $history->settlement_id = $settlement->id; // Set the settlement_id

            $history->save();

            // Update the expected amount in the related settlement
            $totalHistoyAmount = $settlement->histoys()->sum('total_amount');
            $settlement->expected = $totalHistoyAmount + $settlement->start_amount;
            $settlement->save();

            // Delete related cart menus
            foreach ($order->cart->cartMenus as $cartMenu) {
                $cartMenu->delete();
            }

            // Delete related cart
            $order->cart->delete();

            // Delete the order
            $order->delete();
        });

        if (request()->is('api/*')) {
            return response()->json(['success' => 'Order archived successfully']);
        }

        return redirect()->back()->with('success', 'Order archived successfully');
    }

    public function create()
    {
        $user = auth()->user();

        $cart = $user->carts()->latest()->first();

        if (!$cart) {
            $cart = $user->carts()->create([]);
        }

        return view('addorder', compact('cart'));
    }

    public function adminOrder(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'no_telpon' => 'required|string|max:15', // Adjust the validation rules as necessary
            'atas_nama' => 'required|string|max:255',
        ]);

        $cart = $user->carts()->with('user', 'cartMenus.menu')->latest()->first();

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = substr(Uuid::uuid4()->toString(), 0, 5);

        $params = [
            'items_details' => [
                'name' => $user->name,
            ],
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $cart->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        $order = new Order();
        $order->cart_id = $cart->id;
        $order->no_order = $orderId;
        $order->atas_nama = $request->atas_nama;
        $order->no_telpon = $request->no_telpon;
        $order->save();

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $newCart = new Cart();
        $newCart->user_id = $user->id;
        $newCart->save();

        return view('checkout', compact('snapToken', 'order'));
    }

    public function cashpayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::find($orderId);

        if ($order) {
            // Update the order status to paid (for cash payment)
            $order->status = 'settlement';
            $order->payment_type = 'cash';
            $order->save();

            // Create a new cart for the user
            $newCart = new Cart();
            $newCart->user_id = $order->cart->user_id;
            $newCart->save();

            return redirect()->route('order')->with('success', 'Cash payment successful!');
        }

        return redirect()->route('order')->with('error', 'Cash payment failed!');
    }
    
    public function destroy($id)
    {
        Order::destroy($id);

        return redirect(route('order'))->with('success', 'Order Berhasil Dihapus !');
    }
}
