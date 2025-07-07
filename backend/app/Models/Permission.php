<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'category',
        'description',
    ];

    /**
     * Get users that have this permission
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }

    /**
     * Get permissions grouped by category
     */
    public static function getGroupedPermissions()
    {
        return self::orderBy('category')->orderBy('display_name')->get()->groupBy('category');
    }
}
