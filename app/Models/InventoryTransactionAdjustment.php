<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionAdjustment extends Model
{
    use HasFactory;
    protected $fillable =[
        'transaction',
        'adjustment_batch',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
