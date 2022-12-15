<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvTransactionVoucherRequestItem extends Model
{
    use HasFactory;
    protected $fillable =[
        'transaction',
        'voucher_item',
        'created_by',
        'uuid'
    ];
    public $timestamps = false;
}
