<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDispensingOrder extends Model
{
    use HasFactory;
    protected $fillable =[
        'pos_order',
        'item',
        'units',
        'quantity',
        'equivalent_quantity',
        'quantifying_store',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
