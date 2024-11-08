<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartMenu extends Model
{
    use HasFactory;
    protected $fillable = ['cart_id', 'menu_id', 'discount_id', 'notes', 'quantity', 'subtotal'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
