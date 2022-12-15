<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStockOnHandByBatch extends Model
{
    use HasFactory;
    protected $fillable=[
        'store',
        'batch',
        'quantity',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
