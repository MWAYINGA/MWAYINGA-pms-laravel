<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryVoucher extends Model
{
    use HasFactory;
    protected $fillable =[
        'store',
        'reference_source',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
