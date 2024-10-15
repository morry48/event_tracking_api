<?php

namespace App\Features\Shipment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentEvent extends Model
{
    use HasFactory;

    // primary UUID
    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'shipment_id',
        'event_type',
        'estimated_started_at',
        'actual_started_at',
        'estimated_completion_at',
        'actual_completion_at'
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class, 'shipment_id', 'id');
    }
}