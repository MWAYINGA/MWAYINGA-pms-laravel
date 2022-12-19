<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryPaymentCategory extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'description',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
