<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'status',
        'price',
        'currency',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'square_feet',
        'lot_size',
        'year_built',
        'features',
        'description',
        'key_features',
        'images',
        'virtual_tour_link',
        'agent_name',
        'agent_phone',
        'agent_email',
        'availability_date',
        'property_id',
        'is_featured',
        'is_active',
        'views'
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'availability_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'bathrooms' => 'decimal:1'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    public function getTypeDisplayAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->type));
    }

    public function getStatusDisplayAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }
}
