<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPriceType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'uuid',
        'date_created',
        'created_by'
    ];

    public $timestamps = false;
}
