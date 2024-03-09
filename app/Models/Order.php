<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderNotes()
    {
        return $this->hasMany(OrderNote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
