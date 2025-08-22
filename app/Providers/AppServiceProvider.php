<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        App::setLocale('es');

        try {
            // Registrar todos los permisos para el Gate
            Permission::all()->map(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });

            // Otorgar todos los permisos al rol de Super Admin
            Gate::before(function ($user, $ability) {
                return $user->hasRole('Super Admin') ? true : null;
            });
        } catch (\Exception $e) {
            // Registrar el error o simplemente ignorarlo si ocurre durante las migraciones
        }

        Blade::directive('canany', function ($permissions) {
            $permissions = array_map('trim', explode(',', $permissions));
            $expression = "auth()->check() && (";
            foreach ($permissions as $index => $permission) {
                $expression .= "auth()->user()->can({$permission})";
                if ($index < count($permissions) - 1) {
                    $expression .= " || ";
                }
            }
            $expression .= ")";
            return "<?php if ({$expression}): ?>";
        });

        Blade::directive('endcanany', function () {
            return "<?php endif; ?>";
        });
    }
}
