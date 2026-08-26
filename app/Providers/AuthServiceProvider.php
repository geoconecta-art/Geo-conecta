<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use CuongNX\MongoPermission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
public function boot()
    {
        $this->registerPolicies();

        // Registrar permisos dinámicamente (Con seguro contra fallos)
        try {
            foreach (Permission::all() as $permission) {
                Gate::define($permission->name, function (User $user) use ($permission) {
                    return $user->hasPermissionTo($permission->name);
                });
            }
        } catch (\Exception $e) {
            // Si la base de datos no existe o no hay permisos aún, ignorar el error 
            // para permitir que Laravel arranque y podamos correr las migraciones.
        }
    }
}
