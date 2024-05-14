<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\hasOne;
use App\Models\ItemUnits;

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
    
    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uoms(): BelongsTo
    {
        return $this->belongsTo(ItemUnits::class, 'units', 'unit_id');
    }
}
