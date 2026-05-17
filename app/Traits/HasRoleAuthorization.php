<?php

namespace App\Traits;

trait HasRoleAuthorization
{
    /**
     * Authorize user against a specific role or array of roles.
     *
     * @param string|array $roles
     * @return void
     */
    protected function authorizeRole(string|array $roles): void
    {
        abort_unless(auth()->user()->hasAnyRole($roles), 403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    /**
     * Authorize user against a specific permission.
     *
     * @param string|array $permissions
     * @return void
     */
    protected function authorizePermission(string|array $permissions): void
    {
        abort_unless(auth()->user()->hasAnyPermission($permissions), 403, 'Aksi tidak diizinkan.');
    }
}
