<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'no_order',
        'status',
        'payment_type',
        'atas_nama',
        'no_telpon',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    
    
}
