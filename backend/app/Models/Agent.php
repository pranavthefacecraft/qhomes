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
}
