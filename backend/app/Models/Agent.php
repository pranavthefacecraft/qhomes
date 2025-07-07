<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_name',
        'display_name',
        'code',
        'image',
        'nric',
        'email',
        'mobile',
        'address',
        'designation',
        'upline',
        'sponsor',
        'branch',
        'payee_nric',
        'payee_nric_type',
        'bank',
        'bank_account_no',
        'ren_code',
        'ren_license',
        'ren_expired_date',
        'join_date',
        'resign_date',
        'leaderboard',
        'active',
        'remark',
        'created_by',
        'last_modified_by',
        'last_modified_date',
    ];

    protected $casts = [
        'join_date' => 'date',
        'resign_date' => 'date',
        'ren_expired_date' => 'date',
        'last_modified_date' => 'datetime',
    ];

    /**
     * Get the user associated with the agent.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Check if agent has a user account.
     */
    public function hasUserAccount(): bool
    {
        return $this->user()->exists();
    }

    /**
     * Get the agent's display name or agent name as fallback.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->attributes['display_name'] ?: $this->agent_name;
    }
}
