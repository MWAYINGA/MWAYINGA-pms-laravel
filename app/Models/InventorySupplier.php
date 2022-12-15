<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'description',
        'uuid',
        'created_by'
    ];
    public $timestamps = false;
}
