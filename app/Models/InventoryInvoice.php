<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier',
        'created_by',
        'completed',
        'store',
        'uuid'
    ];
    public $timestamps = false;
}
