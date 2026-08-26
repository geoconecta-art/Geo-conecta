<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Maklad\Permission\Models\Role;
// use Maklad\Permission\Models\Permission;
use App\Models\User;
use CuongNX\MongoPermission\Models\Permission;
use CuongNX\MongoPermission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {

        //Roles list
        $superadministrador = Role::create(['name' => 'Super Administrador']);  //Administra todo el sitio
        $administrador = Role::create(['name' => 'Administrador']);     //Administra una sola dirección
        // $subdirector = Role::create(['name' => 'Subdirector']);
        $capturista = Role::create(['name' => 'Capturista']);       //Captura los datos de una dirección y subdirección especifica

        // Permission list
        Permission::create(['name' => 'estadisticas']);
        Permission::create(['name' => 'usuarios']);
        Permission::create(['name' => 'colonias']);
        Permission::create(['name' => 'areas']);
        Permission::create(['name' => 'subareas']);
        Permission::create(['name' => 'inventarios']);
        Permission::create(['name' => 'registros']);
        Permission::create(['name' => 'mapa']);
        Permission::create(['name' => 'crear mapa']);

        $permissions = [
            'estadisticas',
            'usuarios',
            'areas',
            'subareas',
            'inventarios',
            'registros',
            'mapa',
            'crear mapa',
        ];

        foreach ($permissions as $permissionName) {
            $superadministrador->givePermissionTo($permissionName);
        }

        $permissions = [
            'estadisticas',
            'subareas',
            'inventarios',
            'registros',
            'mapa',
            'crear mapa',
        ];

        foreach ($permissions as $permissionName) {
            $administrador->givePermissionTo($permissionName);
        }

        $permissions = [
            'inventarios',
            'registros',
            'mapa,'
        ];

        foreach ($permissions as $permissionName) {
            $capturista->givePermissionTo($permissionName);
        }


        // $superadministrador->givePermissionTo([
        //     'estadisticas',
        //     'usuarios',
        //     'areas',
        //     'subareas',
        //     'inventarios',
        //     'registros',
        //     'mapa',
        //     'crear mapa',
        // ]);

        // $administrador->givePermissionTo([
        //     'estadisticas',
        //     'subareas',
        //     'inventarios',
        //     'registros',
        //     'mapa',
        //     'crear mapa',
        // ]);

        // // $subdirector->givePermissionTo([
        // //     'estadisticas',
        // //     'inventarios',
        // //     'registros',
        // //     'mapa',
        // // ]);

        // $capturista->givePermissionTo([
        //     'inventarios',
        //     'registros',
        //     'mapa,'
        // ]);
        
        $user = User::first();//find('67351aa89e74b4b19d0682d2');
        $user->assignRole('Super Administrador');
    }
}