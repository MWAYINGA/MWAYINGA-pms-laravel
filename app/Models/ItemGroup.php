<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'uuid',
        'date_created',
        'created_by'
    ];

    public $timestamps = false;
}
