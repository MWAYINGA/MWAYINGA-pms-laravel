<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'name',
        'uuid',
        'date_created',
        'created_by',
        'group',
        'category',
        'units',
        'sku',
        'strength',
        'description'
    ];

    public $timestamps = false;
}
