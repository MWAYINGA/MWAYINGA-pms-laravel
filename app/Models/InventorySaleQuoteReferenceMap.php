<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySaleQuoteReferenceMap extends Model
{
    use HasFactory;
    protected $fillable =[
        'quote_line',
        'reference_value',
        'reference_type'
    ];
    public $timestamps = false;
}
