<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'phone',
        'address',
        'comment',
        'cart_data',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'cart_data' => 'array',
        'total_amount' => 'decimal:2',
    ];
}
