<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySaleQuoteLine extends Model
{
    use HasFactory;
    protected $fillable =[
        'quote',
        'item',
        'units',
        'quantity',
        'payment_category',
        'price_type',
        'quoted_amount',
        'payable_amount',
        'status',
        'uuid'
    ];
    public $timestamps = false;
}
