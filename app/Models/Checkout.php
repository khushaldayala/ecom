<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function checkoutProducts()
    {
        return $this->hasMany(CheckoutProducts::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
