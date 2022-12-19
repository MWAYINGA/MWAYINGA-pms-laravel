<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPosOrder extends Model
{
    use HasFactory;
    protected $fillable =[
        'source',
        'item',
        'units',
        'quantity',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
