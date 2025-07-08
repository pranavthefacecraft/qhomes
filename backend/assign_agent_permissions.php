<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Find the agent user
$user = \App\Models\User::find(4);

if ($user) {
    // Assign basic permissions: dashboard.view, properties.view, listings.view
    $permissions = [1, 2, 15];
    $user->syncPermissions($permissions);
    
    echo "Permissions assigned to {$user->name}: " . $user->permissions->pluck('name')->implode(', ') . "\n";
} else {
    echo "User not found!\n";
}
