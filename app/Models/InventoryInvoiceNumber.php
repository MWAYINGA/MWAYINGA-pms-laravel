<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class InventoryInvoiceNumber extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'invoice',
        'source',
        'value',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
