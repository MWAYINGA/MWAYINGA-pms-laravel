<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;
    protected $fillable =[
        'batch',
        'store',
        'source',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
