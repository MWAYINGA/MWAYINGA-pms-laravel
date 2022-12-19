<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySaleOrderByQuoteLine extends Model
{
    use HasFactory;
    protected $fillable =[
        'sale_order_quote',
        'quote_line',
        'paid_amount',
        'debt_amount',
        'uuid'
    ];
    public $timestamps = false;
}
