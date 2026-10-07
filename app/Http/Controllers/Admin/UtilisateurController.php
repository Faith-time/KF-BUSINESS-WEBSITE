<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = User::query()
            ->with('roles')
            ->withCount(['souscriptions', 'paiements'])
            ->latest('created_at')
            ->paginate(20);

        return Inertia::render('Admin/Utilisateurs/Index', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    public function create()
    {
        $roles = Role::all();

        return Inertia::render('Admin/Utilisateurs/Create', [
            'roles' => $roles,
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'name' => $validated['prenom'] . ' ' . $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles($validated['roles']);

        return redirect()->route('admin.utilisateurs.show', $user)
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $utilisateur)
    {
        $utilisateur->load('roles', 'permissions');

        return Inertia::render('Admin/Utilisateurs/Show', [
            'utilisateur' => $utilisateur,
            'roles' => $utilisateur->getRoleNames(),
            'permissions' => $utilisateur->getPermissionNames(),
        ]);
    }

    public function edit(User $utilisateur)
    {
        $roles = Role::all();

        return Inertia::render('Admin/Utilisateurs/Edit', [
            'utilisateur' => $utilisateur,
            'roles' => $roles,
            'userRoles' => $utilisateur->getRoleNames(),
        ]);
    }

    public function update(User $utilisateur)
    {
        $validated = request()->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $utilisateur->id,
            'telephone' => 'required|string|max:20',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
        ]);

        $utilisateur->update([
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'name' => $validated['prenom'] . ' ' . $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
        ]);

        $utilisateur->syncRoles($validated['roles']);

        return back()->with('success', 'Utilisateur mis à jour.');
    }

    public function changePassword(User $utilisateur)
    {
        $validated = request()->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $utilisateur->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Mot de passe changé.');
    }

    public function toggleRole(User $utilisateur)
    {
        $validated = request()->validate([
            'role' => 'required|exists:roles,name',
        ]);

        if ($utilisateur->hasRole($validated['role'])) {
            $utilisateur->removeRole($validated['role']);
            $action = 'retiré';
        } else {
            $utilisateur->assignRole($validated['role']);
            $action = 'attribué';
        }

        return back()->with('success', 'Rôle ' . $action . '.');
    }

    public function grantPermission(User $utilisateur)
    {
        $validated = request()->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $utilisateur->givePermissionTo($validated['permission']);

        return back()->with('success', 'Permission accordée.');
    }

    public function revokePermission(User $utilisateur)
    {
        $validated = request()->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $utilisateur->revokePermissionTo($validated['permission']);

        return back()->with('success', 'Permission révoquée.');
    }

    public function deactivate(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $utilisateur->update(['email_verified_at' => null]);

        return back()->with('success', 'Utilisateur désactivé.');
    }

    public function activate(User $utilisateur)
    {
        $utilisateur->update(['email_verified_at' => now()]);

        return back()->with('success', 'Utilisateur activé.');
    }

    public function destroy(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Vérifier qu'il n'y a pas de données importantes associées
        if ($utilisateur->souscriptions()->exists()) {
            return back()->with('error', 'Impossible de supprimer : cet utilisateur a des souscriptions.');
        }

        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    // Gestion des Rôles
    public function roles()
    {
        $roles = Role::withCount('users', 'permissions')->paginate(20);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function createRole()
    {
        $permissions = Permission::all();

        return Inertia::render('Admin/Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    public function storeRole()
    {
        $validated = request()->validate([
            'name' => 'required|string|unique:roles|max:255',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle créé.');
    }

    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->getPermissionNames();

        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function updateRole(Role $role)
    {
        $validated = request()->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id . '|max:255',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return back()->with('success', 'Rôle mis à jour.');
    }

    public function deleteRole(Role $role)
    {
        if ($role->users()->exists()) {
            return back()->with('error', 'Impossible de supprimer un rôle assigné à des utilisateurs.');
        }

        $role->delete();

        return back()->with('success', 'Rôle supprimé.');
    }
}
