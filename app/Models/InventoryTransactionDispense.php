<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionDispense extends Model
{
    use HasFactory;
    protected $fillable =[
        'transaction',
        'dispense_order',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
