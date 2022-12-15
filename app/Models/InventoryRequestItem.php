<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequestItem extends Model
{
    use HasFactory;
    protected $fillable =[
        'item',
        'request',
        'units',
        'quantity',
        'equivalent_quantity',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
