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
        'sale_price',
        'price_per_month',
        'price_per_day',
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
        if ($this->status === 'for_rent') {
            $prices = [];
            if ($this->price_per_month) {
                $prices[] = number_format($this->price_per_month, 2) . ' ' . $this->currency . '/month';
            }
            if ($this->price_per_day) {
                $prices[] = number_format($this->price_per_day, 2) . ' ' . $this->currency . '/day';
            }
            return implode(' | ', $prices) ?: 'Price not set';
        } else {
            return $this->sale_price ? number_format($this->sale_price, 2) . ' ' . $this->currency : 'Price not set';
        }
    }

    public function getDisplayPriceAttribute(): string
    {
        if ($this->status === 'for_rent') {
            return $this->price_per_month ? number_format($this->price_per_month, 2) . ' ' . $this->currency . '/mo' : 'Contact for price';
        } else {
            return $this->sale_price ? number_format($this->sale_price, 2) . ' ' . $this->currency : 'Contact for price';
        }
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
