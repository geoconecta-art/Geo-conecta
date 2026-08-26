<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\InfoMaps;
use App\Models\Map;
use App\Models\Plan;
use App\Models\PlanRegister;
use App\Models\TypeDependencies;
use App\Tools\Tools;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GeovisorController extends Controller
{
    private $utils;

    public function __construct() {
        $this->utils = new UtilsController();
        $this->middleware('auth')->only('index');
        $this->middleware(['can:mapa']);
    }

    public function index( Request $request ) {
        $user = Auth::user();
        $areas = [];

        if( $user->hasRole("Super Administrador") ){
            $areas = Area::with(['subareas.planeaciones', 'mapas'])->whereNull('deleted_at')->get();
        }  else {
            $areas = Area::with(['subareas.planeaciones', 'mapas'])->where('_id', $user->id_area)->whereNull('deleted_at')->get();
        }

        $weather = $this->utils->updateWeather();
        $date = now()->format('d-m-Y');
        $id_plan = $request->get('inventario', null);

        return view('admin.geovisor.map.index', [
            'areas' => $areas,
            'weather' => $weather,
            'date' => $date,
            'id_plan' => $id_plan,
        ]);
    }

    public function createMapIndex( Request $request ){
        $user = Auth::user();
        $typeDependencies = $areas = [];
        $zoom = $request->get('zoom');
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $id_plans_string = $request->get('id_plans');
        $id_plans = explode( ",", $id_plans_string );
        $plan = $map_key = null;

        $id_plans = array_filter( $id_plans, function( $id_plan ){
            return $id_plan != "";
        });

        if( !empty( $id_plans ) ){
            $id_plan = reset( $id_plans );
            $plan = Plan::find($id_plan);
            $area_plans = count( Map::where('id_area', $plan->id_area)->whereNull('deleted_at')->get() ) + 1;
            $area_key = UtilsController::createMapKey( $area_plans );
            $map_key = $this->utils->getInits( $plan->area->name ) . '-' . $area_key;
        }

        if( $user->hasRole("Super Administrador") ){
            $areas = Area::with(['subareas.planeaciones', 'mapas'])->whereNull('deleted_at')->get();
            $typeDependencies = TypeDependencies::all();
        }  else {
            $areas = Area::with(['subareas.planeaciones', 'mapas'])->where('_id', $user->id_area)->whereNull('deleted_at')->get();
            $typeDependencies = TypeDependencies::where('_id', $areas[0]->id_type)->get();
        }

        $weather = $this->utils->updateWeather();
        $date = now()->format('d-m-Y');

        return view('admin.geovisor.print.index', [
            'areas' => $areas,
            'typeDependencies' => $typeDependencies,
            'zoom' => $zoom,
            'lat' => $lat,
            'lng' => $lng,
            'id_plans' => $id_plans,
            'plan' => $plan,
            'map_key' => $map_key,
            'weather' => $weather,
            'date' => $date,
        ]);
    }

    // Obtiene los datos codificados para la impresión del mapa
    public function preLoadMap( Request $request ){
        $request->validate([
            "name_dependency" => 'required|string',
            "key_map" => 'required|string',
            "name_map" => 'required|string',
            'id_plans' => 'required',
        ]);

        $aux_markers = $request->get('visibleMarkers');
        $aux_lines = $request->get('visibleLines');
        $aux_city = $request->get('visibleCity');

        $markers = json_decode( $aux_markers, true );
        $lines = json_decode( $aux_lines, true );
        $city = json_decode( $aux_city, true );

        $info_rows = $this->utils->createMarkersInfoMap( $markers );
        $info_rows .= $this->utils->createLineStringInfoMap( $lines );
        // $info_rows .= $this->utils->createCityLineInfoMap( $city );

        $map_info = $request->all();
        unset( $map_info["visibleMarkers"] );
        unset( $map_info["visibleLines"] );
        unset( $map_info["visibleCity"] );
        unset( $map_info["_token"] );
        $map_info["info_rows"] = $info_rows;

        $info = new InfoMaps($map_info);
        $info->save();
        $id_info = $info->id;

        return response()->json([
            'id_info' => $id_info,
        ]);
    }

    // Abre la ventana de impresión del mapa
    public function printMapIndex(Request $request){
        $info = InfoMaps::find($request->get('id_info'));
        
        // $id_plans = json_decode( $request->get('id_plans') );
        $id_plans = $info->id_plans;
        // $dependency = $request->get('dependency');
        $dependency = $info->name_dependency;
        // $key_map = $request->get('key_map');
        $key_map = $info->key_map;
        // $map_name = $request->get('map_name');
        $map_name = $info->name_map;

        $zoom = $request->get('zoom');
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        // $typeMap = $request->get('typeMap');
        $typeMap = $info->typeMap;

        // $info_rows = $request->get('info_rows');
        $info_rows = $info->info_rows;

        $plans = [];

        foreach ($id_plans as $id_plan) {
            $plan = Plan::find($id_plan);
            $plans[] = $plan;
        }

        $gm_image = $this->utils->getImageFromGoogleMaps( $info_rows, $lat, $lng, $zoom, $typeMap, "1024x1024");

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'gm_image' => $gm_image,
            'dependency' => $dependency,
            'key_map' => $key_map,
            'map_name' => $map_name,
            'plans' => $plans,
            'date' => $date,
            'zoom' => $zoom,
            'lat' => $lat,
            'lng' => $lng,
            'typeMap' => $typeMap,
            'id_plans' => $id_plans,
        ];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        return view('admin.registros.pdf.map.map_pdf', $data);

        $pdf = Pdf::loadView('admin.registros.pdf.map.map_pdf', $data);

        return $pdf->setPaper('a4', 'landscape')
                   ->stream('Inventario - ' . $plan->name .  '.pdf');
    }


    // Abre la ventana de impresión del mapa
    public function printMap(Request $request){
        
        $id_plans = explode( ",", $request->get('id_plans') );
        $dependency = $request->get('dependency');
        $key_map = $request->get('key_map');
        $map_name = $request->get('name_map');
        
        $zoom = $request->get('zoom');
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $typeMap = $request->get('type_map');
        
        $plans = [];

        foreach ($id_plans as $id_plan) {
            if( $id_plan != null && $id_plans != '' ){
                $plan = Plan::find($id_plan);
                $plans[] = $plan;
            }
        }

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'dependency' => $dependency,
            'key_map' => $key_map,
            'map_name' => $map_name,
            'plans' => $plans,
            'date' => $date,
            'zoom' => $zoom,
            'lat' => $lat,
            'lng' => $lng,
            'typeMap' => $typeMap,
            'id_plans' => $id_plans,
        ];

        return view('admin.registros.pdf.map.map_pdf', $data);
    }
}
