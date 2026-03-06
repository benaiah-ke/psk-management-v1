<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Members
            'view members', 'create members', 'edit members', 'delete members',
            'approve applications', 'reject applications',
            // Finance
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
            'record payments', 'void invoices',
            'view budgets', 'create budgets', 'edit budgets',
            'manage cost centers',
            // Events
            'view events', 'create events', 'edit events', 'delete events',
            'manage registrations', 'check in attendees',
            'manage sponsors',
            // CPD
            'view cpd activities', 'verify cpd activities', 'manage cpd categories',
            // Communication
            'send communications', 'manage email templates',
            // Tickets
            'view all tickets', 'assign tickets', 'respond to tickets',
            // Organization
            'manage branches', 'manage committees',
            // Community
            'manage posts', 'moderate comments',
            // Settings
            'manage settings', 'view reports', 'view audit logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Super Admin gets everything via Gate::before
        Role::create(['name' => 'Super Admin']);

        Role::create(['name' => 'Admin'])->givePermissionTo([
            'view members', 'create members', 'edit members', 'approve applications', 'reject applications',
            'view invoices', 'create invoices', 'edit invoices', 'record payments',
            'view events', 'create events', 'edit events', 'manage registrations', 'check in attendees', 'manage sponsors',
            'view cpd activities', 'verify cpd activities', 'manage cpd categories',
            'send communications', 'manage email templates',
            'view all tickets', 'assign tickets', 'respond to tickets',
            'manage branches', 'manage committees',
            'manage posts', 'moderate comments',
            'view reports', 'view audit logs',
        ]);

        Role::create(['name' => 'Finance'])->givePermissionTo([
            'view members',
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
            'record payments', 'void invoices',
            'view budgets', 'create budgets', 'edit budgets',
            'manage cost centers',
            'view reports',
        ]);

        Role::create(['name' => 'Branch Admin'])->givePermissionTo([
            'view members',
            'view events', 'create events', 'edit events', 'manage registrations', 'check in attendees',
            'send communications',
            'manage posts', 'moderate comments',
        ]);

        Role::create(['name' => 'Committee Admin'])->givePermissionTo([
            'view members',
            'view events', 'create events', 'edit events', 'manage registrations',
            'manage posts', 'moderate comments',
        ]);

        Role::create(['name' => 'Member']);
    }
}
