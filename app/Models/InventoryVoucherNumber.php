<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryVoucherNumber extends Model
{
    use HasFactory;
    protected $fillable =[
        'voucher',
        'source',
        'value',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
