<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Subdirections;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SubdirectionsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only('index');
        $this->middleware(['can:subareas']);
    }
    
    public function index() {
        return view('admin.subdirections.index');
    }

    // Obtiene las subdirecciones según el id del área
    public function updateTable(){
        $user = Auth::user();
        $auxSubareas = Subdirections::getSubByArea( $user->id_area );
        $subareas = [];

        foreach ($auxSubareas as $key => $sub) {
            $subareas[] = [
                'index' => ( $key + 1),
                'id' => $sub->id,
                'name' => $sub->name,
                'area' => $sub->area->name,
                'plans' => count( $sub->planeaciones ),
            ];
        }

        return DataTables::of($subareas)
                ->addColumn('actions', 'admin.subdirections._actions')
                ->rawColumns(['actions'])
                ->make(true);
    }

    // Abre el modal para crear una nueva subcategoría
    public function openModal(){
        $user = Auth::user();
        $areas = [];

        if( $user->hasRole("Super Administrador") )
            $areas = Area::whereNull('deleted_at')->get();
        else
            $areas = Area::whereNull('deleted_at')->where('_id', $user->id_area)->get();

        return view('admin.subdirections._create_sub',[
            'areas' => $areas,
        ]);
    }

    // Crea una nueva subcategoría
    public function create(Request $request){
        $request->validate([
            'id_area' => 'required|string|min:1',
            'name' => 'required|string|min:1',
        ]);

        $name = $request->get('name');
        $id_area = $request->get('id_area');

        if($name != "" && $id_area != ""){
            $area = Area::find($id_area);

            $subarea = new Subdirections([
                'name' => $name,
                'id_area' => $area->id
            ]);

            $area->subareas()->save($subarea);

            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function edit($id){
        $user = Auth::user();
        $areas = [];

        if( $user->hasRole("Super Administrador") )
            $areas = Area::whereNull('deleted_at')->get();
        else
            $areas = Area::whereNull('deleted_at')->where('_id', $user->id_area)->get();

        $subarea = Subdirections::find($id);

        return view('admin.subdirections._edit_sub', [
            'areas' => $areas,
            'subdirection' => $subarea,
        ]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'id_area' => 'required|string|min:1',
            'name' => 'required|string|min:1',
        ]);

        $name = $request->get('name');
        $id_direction = $request->get('id_area');

        if ($name != "" && !is_null($name) && $id_direction != "") {
            $area = Area::find($id_direction);

            $subarea = Subdirections::find($id);
            $subarea->update([
                'name' => $name,
                'id_area' => $id_direction,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Elemento actualizado con éxito',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'El nombre no puede estar vacío',
        ]);
    }

    public function confirmDelete($id){
        $subarea = Subdirections::find($id);

        return view('admin.subdirections._deleting_warning', [
            'subarea' => $subarea,
        ]);
    }

    public function delete($id){
        $subdirection = Subdirections::find($id);
        $nameSubdirection = $subdirection->name;

        $countPlanDeleted = Plan::where('id_subarea', $id)->whereNull('deleted_at')->delete();
        
        $subdirection->delete();

        return response()->json([
            'success' => true,
            'name' => $nameSubdirection,
            'plans' => $countPlanDeleted,
        ]);
    }
}
