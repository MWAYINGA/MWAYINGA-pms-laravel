<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    use HasFactory;
    protected $fillable =[
        'source_store',
        'destination_store',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
