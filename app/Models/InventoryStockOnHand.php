<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStockOnHand extends Model
{
    use HasFactory;
    protected $fillable=[
        'store',
        'item',
        'quantity',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
