<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryOrderPayInstallment extends Model
{
    use HasFactory;
    protected $fillable =[
        'installment_no',
        'soql_no',
        'paid_amount',
        'receipt',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
