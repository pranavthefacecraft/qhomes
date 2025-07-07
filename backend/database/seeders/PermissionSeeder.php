<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            [
                'name' => 'dashboard.view',
                'display_name' => 'View Dashboard',
                'category' => 'dashboard',
                'description' => 'Access to the main dashboard'
            ],
            
            // Properties
            [
                'name' => 'properties.view',
                'display_name' => 'View Properties',
                'category' => 'properties',
                'description' => 'View property listings'
            ],
            [
                'name' => 'properties.create',
                'display_name' => 'Create Properties',
                'category' => 'properties',
                'description' => 'Add new properties'
            ],
            [
                'name' => 'properties.edit',
                'display_name' => 'Edit Properties',
                'category' => 'properties',
                'description' => 'Modify existing properties'
            ],
            [
                'name' => 'properties.delete',
                'display_name' => 'Delete Properties',
                'category' => 'properties',
                'description' => 'Remove properties'
            ],
            
            // Agents (Super Admin Only)
            [
                'name' => 'agents.view',
                'display_name' => 'View Agents',
                'category' => 'agents',
                'description' => 'View agent list and details'
            ],
            [
                'name' => 'agents.create',
                'display_name' => 'Create Agents',
                'category' => 'agents',
                'description' => 'Add new agents'
            ],
            [
                'name' => 'agents.edit',
                'display_name' => 'Edit Agents',
                'category' => 'agents',
                'description' => 'Modify agent information'
            ],
            [
                'name' => 'agents.delete',
                'display_name' => 'Delete Agents',
                'category' => 'agents',
                'description' => 'Remove agents'
            ],
            
            // Users & Permissions
            [
                'name' => 'users.view',
                'display_name' => 'View Users',
                'category' => 'users',
                'description' => 'View user accounts'
            ],
            [
                'name' => 'permissions.manage',
                'display_name' => 'Manage Permissions',
                'category' => 'users',
                'description' => 'Assign permissions to users'
            ],
            
            // Reports & Analytics
            [
                'name' => 'analytics.view',
                'display_name' => 'View Analytics',
                'category' => 'analytics',
                'description' => 'Access analytics and reports'
            ],
            [
                'name' => 'inquiries.view',
                'display_name' => 'View Inquiries',
                'category' => 'inquiries',
                'description' => 'View customer inquiries'
            ],
            [
                'name' => 'inquiries.manage',
                'display_name' => 'Manage Inquiries',
                'category' => 'inquiries',
                'description' => 'Respond to and manage inquiries'
            ],
            
            // Listings
            [
                'name' => 'listings.view',
                'display_name' => 'View Listings',
                'category' => 'listings',
                'description' => 'View property listings'
            ],
            [
                'name' => 'listings.manage',
                'display_name' => 'Manage Listings',
                'category' => 'listings',
                'description' => 'Manage property listings'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully.');
    }
}
