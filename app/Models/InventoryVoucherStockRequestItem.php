<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryVoucherStockRequestItem extends Model
{
    use HasFactory;
    protected $fillable =[
        'voucher',
        'request_item',
        'batch',
        'quantity',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
