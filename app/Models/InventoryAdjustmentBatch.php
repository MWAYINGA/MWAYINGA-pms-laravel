<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustmentBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'adjustment',
        'factor',
        'batch',
        'quantity',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
