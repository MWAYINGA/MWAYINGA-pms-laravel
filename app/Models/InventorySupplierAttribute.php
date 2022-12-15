<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySupplierAttribute extends Model
{
    use HasFactory;

    protected $fillable =[
        'supplier',
        'type',
        'value',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
