<?php

namespace App\Http\Controllers;

use App\Models\User;
use CuongNX\MongoPermission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request) {
        $credentials = array_merge( $request->only('email', 'password'), ['deleted_at' => null] );

        // Auth::attempt(['email' => $email, 'password' => $password, 'deleted_at' => null])
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if( Auth::user()->hasRole('Super Administrador') || Auth::user()->hasRole('Administrador') ){
                return redirect( route('plans.index') );//->intended('/admin');
            } else if( Auth::user()->hasRole('Capturista') ) {
                return redirect( route('register.index') );
            }

            return redirect( route('register.index') );//->intended('/admin');
        }

        return back()->withErrors([
            'credentials' => 'Estas credenciales no coinciden con nuestras registros.',
        ]);
    }

    public function migrarRoles(){
        // Traer todos los roles antiguos
        $oldRoles = DB::connection('mongodb')->table('roles')->get();

        foreach ($oldRoles as $oldRole) {
            // Crear o actualizar rol en cuongnx
            $role = Role::firstOrCreate(
                [
                    'name' => $oldRole->name, 
                    'guard_name' => $oldRole->guard_name
                ]
            );

            // Asignar usuarios
            if (!empty($oldRole->user_ids)) {
                foreach ($oldRole->user_ids as $userId) {
                    $user = User::find($userId);
                    if ($user && !$user->hasRole($role->name)) {
                        $user->assignRole($role->name);
                    }
                }
            }
        }
    }

    public static function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin');
    }
}
