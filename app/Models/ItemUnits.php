<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUnits extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'uuid',
        'date_created',
        'created_by'
    ];

    public $timestamps = false;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function creator(){
        return $this->belongsToMany(User::class,'item_units','created_by','');
    }
    public function changed_by(){
        return $this->belongsTo(User::class,'item_units','changed_by');
    }
    public function voided_by(){
        return $this->belongsTo(User::class,'item_units','voided_by');
    }

    /**
     * Get the items that owns the unit of measures
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'units', 'unit_id');
    }

}
