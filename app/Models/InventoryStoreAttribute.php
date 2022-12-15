<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStoreAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'uuid',
        'type',
        'store',
        'value',
        'date_created',
        'created_by',
    ];

    public $timestamps = false;
}
