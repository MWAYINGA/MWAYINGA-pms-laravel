<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItemPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'price_type',
        'item',
        'price',
        'date_created',
        'created_by'
    ];

    public $timestamps = false;
}
