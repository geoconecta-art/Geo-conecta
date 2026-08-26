<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Area;
use App\Models\Subdirections;
use App\Models\TypeDependencies;
use App\Models\User;
use CuongNX\MongoPermission\Models\Role;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\ObjectId;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->only('index');
        $this->middleware(['can:usuarios']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $users = User::whereNull('deleted_at')->get();

            foreach ($users as $key => $user) {
                $user->index = $key + 1;
                $user->role = $user->getRoleNames();
                $area_name = $user->id_area ? Area::find($user->id_area)->name : "";
                $user->area = $area_name;
            }

            return DataTables::of($users)
                ->addColumn('actions', 'admin.users._actions')
                ->addColumn('Imagen', 'admin.users._image')
                ->rawColumns(['actions', 'Imagen'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create() {
        $roles = Role::orderBy('created_at')->get();
        $typeDependencies = TypeDependencies::whereNull('deleted_at')->with('dependencies')->get();
        $subareas = Subdirections::whereNull('deleted')->get();

        return view('admin.users.create', [
            'user' => new User(),
            'roles' => $roles,
            'typeDependencies' => $typeDependencies,
            'subareas' => $subareas,
        ]);
    }

    public function store(UserRequest $request)
    {
        $item = new User();
        $item->name = $request->get('name');
        $item->email = $request->get('email');
        $item->password = Hash::make($request->get('password'));

        // Manejo de imagen
        if ($request->hasFile('image')) {
            $file = $request->image;
            $name = $file->getClientOriginalName();
            $date = date('YmdHis');
            $n_explode = explode('.', $name);
            $n_explode[0] .= '_' . $date;
            $new_name = implode('.', $n_explode);
            $file->move(public_path('img/users/'), $new_name);
            $item->image = '/img/users/' . $new_name;
        } elseif ($request->get('has_image') == 0) {
            $item->image = null;
        }
        $item->save();

        // Roles y asignaciones
        $role_id = $request->get('role_id');
        $role = Role::find($role_id);
        $item->assignRole($role->name);

        $admin_rol = Role::find('692633d000d9c35e55033092');
        if ($role_id != $admin_rol->id) {
            $item->id_area = $request->get('area_id');
            $item->id_subarea = $request->get('subarea_id');
        }

        // Guardamos todo junto
        $item->save();

        return redirect()->route('users.index');

    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $typeDependencies = TypeDependencies::whereNull('deleted_at')->with('dependencies')->get();
        // $areas = Area::whereNull('deleted_at')->get();
        $subareas = Subdirections::whereNull('deleted')->get();
        $user->role_id = $user->role_ids[0];

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'typeDependencies' => $typeDependencies,
            'subareas' => $subareas
        ]);
    }

    public function update(UserRequest $request, User $user) {

        // $user = User::findOrFail($user->id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        $pass = $request->get('password');
        if (!is_null($pass))
            $user->password = Hash::make($request->get('password'));

        if ( $request->hasFile('image') ) {
            $file = $request->image;
            $name = $file->getClientOriginalName();
            $date = date('YmdHis');
            $n_explode = explode('.', $name);
            $n_explode[0] = $n_explode[0] . '_' . $date;
            $new_name = implode('.', $n_explode);

            $file->move(public_path() . '/img/users/', $new_name);
            //$file->move('img/users/', $new_name);   //Prod

            $user->image = '/img/users/' . $new_name;
        }
        else if ( $request->get('has_image') == 0 )
            $user->image = null;

        $user->save();

        $role_id = $user->role_ids[0];
        $new_role_id = $request->get('role_id');

        if ($role_id != $new_role_id) {
            $user->role_ids = [ $new_role_id ];
        }

        if( $role_id != "692633d000d9c35e55033092"){
            $user->id_area = $request->get('area_id');
        }
        $user->save();

        return redirect('/admin/usuarios');
    }

    // Elimina un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json(array(
            'success' => true,
            'message' => 'El usuario ha sido eliminado correctamente'
        ));
    }

}
