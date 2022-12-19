<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySaleOrderByQuote extends Model
{
    use HasFactory;
    protected $fillable =[
        'dated_sale_id',
        'sale_quote',
        'payment_category',
        'payable_amount',
        'payment_methods',
        'paid_amount',
        'debt_amount',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
