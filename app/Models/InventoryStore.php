<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStore extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'uuid',
        'date_created',
        'created_by',
        'description'
    ];

    public $timestamps = false;
}
