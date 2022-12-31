<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustmentFactor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'category',
        'type',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
