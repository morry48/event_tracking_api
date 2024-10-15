<?php

namespace App\Features\Shipment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    // Primary UUID
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'internal_reference_name',
        'user_id'
    ];

    public function shipmentEvents(): HasMany
    {
        return $this->hasMany(ShipmentEvent::class, 'shipment_id', 'id');
    }
}