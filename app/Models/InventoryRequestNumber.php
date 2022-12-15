<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequestNumber extends Model
{
    use HasFactory;
    protected $fillable =[
        'request',
        'source',
        'value',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
