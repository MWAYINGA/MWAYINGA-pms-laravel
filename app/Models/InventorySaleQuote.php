<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySaleQuote extends Model
{
    use HasFactory;
    protected $fillable =[
        'customer',
        'total_quote',
        'payable_amount',
        'status',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
