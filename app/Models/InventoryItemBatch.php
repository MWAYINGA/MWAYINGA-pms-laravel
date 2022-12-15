<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItemBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'batch_no',
        'expire_date',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
