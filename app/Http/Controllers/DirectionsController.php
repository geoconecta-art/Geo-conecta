<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Subdirections;
use App\Models\Plan;
use Yajra\DataTables\Facades\DataTables;

class DirectionsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only('index');
        $this->middleware(['can:areas']);
    }

    public function index() {
        return view('admin.directions.index');
    }

    // Obtiene las direcciones para el datatable
    public function getAreas(){
        $auxAreas = Area::with(['subareas', 'planeaciones'])->whereNull('deleted_at')->get();
        $areas = [];

        foreach ($auxAreas as $key => $area) {
            $areas[] = [
                'index' => $key + 1,
                'id' => $area->id,
                'name' => $area->name,
                'clave' => $area->area_key,
                'subareas' => $area->subareas->count(),
                'planeaciones' => $area->planeaciones->count(),
            ];
        }

        return DataTables::of($areas)
            ->addColumn('actions', 'admin.directions._actions')
            ->rawColumns(['actions'])
            ->make(true);
    }

    // Abre el modal para crear una dirección
    public function openModal(){
        return view('admin.directions._create_direction');
    }

    // Crea una dirección
    public function create(Request $request){
        $request->validate([
            'name' => 'required|string|min:1',
            'direction_key' => 'required|string|min:1'
        ]);

        $name = $request->get('name');
        $area_key = $request->get('direction_key');
        
        if($name != "" && !is_null($name)){
            Area::create([
                'name' => $name,
                'area_key' => $area_key,
            ]);

            return response()->json([
                'success' => true,
            ]);
        }
    }
    
    // Envía la información de la dirección a editar
    public function edit($id){
        $area = Area::find($id);

        return view('admin.directions._edit_direction', [
            'direction' => $area,
        ]);
    }

    // Actualiza la información de la dirección
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required|string|min:1',
            'direction_key' => 'required|string|min:1',
        ]);

        $name = $request->get('name');
        $area_key = $request->get('direction_key');

        if ($name != "" && !is_null($name)) {
            $area = Area::find($id);
            $area->update([
                'name' => $name,
                'area_key' => $area_key,
            ]);

            return response()->json([
                'success' => true
            ]);
        }
    }

    // Envía a la vista de confirmación de eliminación 
    public function confirmDelete($id){
        $area = Area::find($id);

        return view('admin.directions._deleting_warning', [
            'area' => $area,
        ]);
    }

    // Elimina la dirección seleccionada
    public function delete($id){
        $direccion = Area::find($id);
        $name = $direccion->name;

        $countSubdirectionsDeleted = Subdirections::where('id_area', $id)->whereNull('deleted_at')->delete();
        $countPlanDeleted = Plan::where('id_area', $id)->whereNull('deleted_at')->delete();
        $direccion->delete();

        return response()->json([
            'success' => true,
            'name' => $name,
            'subdirections' => $countSubdirectionsDeleted,
            'plans' => $countPlanDeleted,
        ]);
    }
}
