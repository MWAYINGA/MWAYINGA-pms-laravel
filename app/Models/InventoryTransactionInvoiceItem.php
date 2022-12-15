<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransactionInvoiceItem extends Model
{
    use HasFactory;
    protected $fillable =[
        'transaction',
        'invoice_item',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
