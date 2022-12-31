<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustmentNumber extends Model
{
    use HasFactory;
    protected $fillable =[
        'adjustment',
        'source',
        'value',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
