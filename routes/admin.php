<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\UserComponent;
use App\Livewire\Admin\RoleComponent;
use App\Livewire\Admin\PermissionComponent;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('users', UserComponent::class)->name('admin.users')->middleware('can:ver usuarios');
    Route::get('roles', RoleComponent::class)->name('admin.roles')->middleware('can:ver roles');
    Route::get('permissions', PermissionComponent::class)->name('admin.permissions')->middleware('can:ver permisos');
});
