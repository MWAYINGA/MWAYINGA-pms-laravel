<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice',
        'batch',
        'units',
        'quantity',
        'batch_quantity',
        'unit_price',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
