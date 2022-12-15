<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryVoucherStockRequest extends Model
{
    use HasFactory;
    protected $fillable =[
        'voucher',
        'request',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
