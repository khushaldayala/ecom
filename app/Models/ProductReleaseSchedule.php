<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReleaseSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','title','release_date'];
}
