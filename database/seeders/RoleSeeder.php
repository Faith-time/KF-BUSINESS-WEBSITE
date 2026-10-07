<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser les rôles et permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les rôles
        $administrateur = Role::create(['name' => 'administrateur', 'guard_name' => 'web']);
        $comptable = Role::create(['name' => 'comptable', 'guard_name' => 'web']);
        $investisseur = Role::create(['name' => 'investisseur', 'guard_name' => 'web']);
        $promoteur = Role::create(['name' => 'promoteur', 'guard_name' => 'web']);

        // Permissions pour les administrateurs
        $adminPermissions = [
            'view-dashboard',
            'manage-projets',
            'manage-utilisateurs',
            'manage-roles',
            'manage-paiements',
            'manage-dividendes',
            'manage-kyc',
            'manage-documents',
            'manage-pages',
            'view-audit-log',
            'export-donnees',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Permissions pour les comptables
        $comptablePermissions = [
            'view-dashboard',
            'view-paiements',
            'confirm-paiements',
            'reject-paiements',
            'view-souscriptions',
            'view-investisseurs',
            'view-documents',
            'export-donnees',
        ];

        foreach ($comptablePermissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Permissions pour les investisseurs
        $investisseurPermissions = [
            'view-projets',
            'create-souscriptions',
            'view-souscriptions',
            'create-paiements',
            'view-paiements',
            'view-dividendes',
            'view-documents',
        ];

        foreach ($investisseurPermissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Permissions pour les promoteurs
        $promoteurPermissions = [
            'manage-projets-own',
            'view-projets-own',
            'upload-documents',
        ];

        foreach ($promoteurPermissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Assigner les permissions aux rôles
        $administrateur->syncPermissions(Permission::all());

        $comptable->syncPermissions($comptablePermissions);

        $investisseur->syncPermissions($investisseurPermissions);

        $promoteur->syncPermissions($promoteurPermissions);

        $this->command->info('Rôles et permissions créés avec succès !');
    }
}
