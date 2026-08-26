<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Lote;
use App\Models\Map;
use App\Models\Subdirections;
use App\Models\Plan;
use App\Models\PlanRegister;
use App\Tools\Tools;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MapController extends Controller
{
    protected $utils;

    public function __construct(){
        $this->utils = new UtilsController();
        $this->middleware('auth')->only('index');
        $this->middleware(['can:mapa']);
    }

    // Lleva a la pantalla index del mapa
    public function index(Request $request){
        $user = Auth::user();
        $areas = $lotes = [];

        if( Lote::count() == 0 )
            $this->utils->saveLotes();

        $lotes = Lote::paginate(1000);
        // $lotes_poly = $this->utils->getCatastroInfo();

        if ( $user->hasRole("Super Administrador") ){
            $areas = Area::with(['subareas.planeaciones', 'mapas'])->whereNull('deleted_at')->get();
        } else {
            $areas = Area::with(['subareas.planeaciones', 'mapas'])->where('_id', $user->id_area)->whereNull('deleted_at')->get();
        }

        return view('admin.map.index',[
            'areas' => $areas,
            'lotes' => $lotes,
            // 'lotes_poly' => $lotes_poly,
            'tab' => $request->has('page') ? 'tab-catastro' : '',
        ]);
    }

    // Obtiene la información de las claves catastrales
    public function getCatastroData(Request $request){
        $lotes = Lote::paginate(1000);

        return response()->json(
            view( 'admin.map._catastro_list', compact('lotes'))->render()
        );
    }

    // Busca dentro de las claves catastrales 
    public function searchLote( Request $request ){
        $clave = $request->input('query');
        $avoiding_lotes = $request->input('avoidingLotes', []);
        $lotes = Lote::query();

        if( !empty($clave) )
            $lotes->where('clave', 'like', "%$clave%");

        // if( !empty($avoiding_lotes) )
        //     $lotes->whereNotIn('clave', $avoiding_lotes);

        $lotes = $lotes->paginate(1000);

        return response()->json(
            view( 'admin.map._catastro_list', compact('lotes', 'avoiding_lotes'))->render()
        );
    }

    public function getLotePolygon(Request $request){
        $clave = $request->input('clave');
        $lote_coors = $this->utils->getCatastroInfoByLote($clave);

        return response()->json([
            'coors' => $lote_coors,
        ]);
    }

    // Revisa que la información esté dentro del polígono seleccionado
    public function rowsByLote(Request $request){
        $clave = $request->input('clave');

        $rows = PlanRegister::with(['planeacion'])->whereNull('deleted_at')->get();

        return response()->json([
            $rows
        ]);
    }

    // Obtiene la información de un invetario
    public function getInventoryInfo( $id_plan ){
        $plan = Plan::find($id_plan);
        $rows = PlanRegister::where('id_plan', $id_plan)->whereNull('deleted_at')->get();

        $fieldKeys = $this->utils->getFieldKeysFromAttr($plan->attributes, $plan, "gen");

        return response()->json(array(
            'rows' => $rows,
            'plan' => $plan,
            'geometry' => $plan->geometry,
            'fieldKeys' => $fieldKeys,
        ));
    }

    // Obtiene los ID's asociados a un mapa
    public function getInventoriesId( $map ){
        $map = Map::find( $map );
        $plan_ids = $map->plans;

        return $plan_ids;
    }

    // Exporta en formato PDF la información del mapa
    public function exportMap( $map ){
        $mapa = Map::find($map);
        $plan_ids = $mapa->plans;
        $info_rows = "";
        $plans = [];

        foreach ($plan_ids as $plan_id) {
            $plan = Plan::find($plan_id);
            $plans[] = $plan;
            $registers = PlanRegister::where('id_plan', $plan_id)->whereNull('deleted_at')->get();
            $info_rows .= $plan->geometry == "Point" ? $this->utils->createPointInfoMap( $registers, $plan->color ) : $this->utils->createLinesInfoMap( $registers, $plan->color );
        }

        $gm_image = $this->utils->getImageFromGoogleMaps( $info_rows, "19.563921797552112", "-99.28347607035111", "12", "4096x4096");
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
        $area_name = $plan->subarea->area->name;
        $area_key = $plan->subarea->area->area_key;

        $data = [
            'gm_image' => $gm_image,
            'mapa' => $mapa,
            'area_key' => $area_key,
            'plans' => $plans,
            'date' => $date,
            'area_name' => $area_name,
        ];

        $pdf = Pdf::loadView('admin.registros.pdf.map.map_pdf', $data);

        return $pdf->setPaper('a4', 'landscape')
                   ->stream('Inventario - ' . $plan->name .  '.pdf');
    }

    // Dependiendo del tipo se muestra el modal para crear o editar
    public function showModal(Request $request){
        $type = $request->get('type');

        if( $type == "crear" ){
            return self::showCreateModal($request);
        } else if( $type == "editar" ){
            return self::showEditModal($request);
        }

        
    }

    // Muestra el modal para crear un mapa
    private function showCreateModal( Request $request ){
        $plan_ids = $request->get('plan_ids');
        $plans = [];

        foreach ($plan_ids as $plan_id) {
            $plans[] = Plan::find($plan_id);
        }

        return view('admin.map._create',[
            'plans' => $plans,
        ]);
    }

    // Crea un nuevo mapa
    public function createMap( Request $request ){
        $request->validate([
            'name_map' => 'required|string|min:1',
            'id_plans' => 'required|array|min:1',
        ]);

        $name = $request->get('name_map');
        $id_plans = $request->get('id_plans');
        $user = Auth::user();
        $area = null;

        if( $user->id_area ){
            $area = Area::find( $user->id_area );
        } else {
            $plan = Plan::find( reset($id_plans) );
            $area = Area::find( $plan->id_area );
        }

        $system_plans = count( Map::whereNull('deleted_at')->get() ) + 1;
        $system_key = UtilsController::createMapKey( $system_plans );
        $area_plans = count( Map::where('id_area', $area->id)->whereNull('deleted_at')->get() ) + 1;
        $area_key = UtilsController::createMapKey( $area_plans );

        $map = new Map([
            'name' => $name,
            'id_area' => $user->id_area,
            'id_user' => $user->id,
            'system_key' => $system_key,
            'area_key' => $area_key,
            'map_key' => $area->area_key . '-' . $system_key . '-' . $area_key,
            'plans' => $id_plans,
        ]);

        if( $area )
            $area->mapas()->save( $map );
        else
            $map->save();
    }

    // Editar mapa
    private function showEditModal( Request $request ){
        $id_map = $request->get('id_map');
        $mapa = Map::find( $id_map );
        $plans = [];

        foreach ($mapa->plans as $id_plan) {
            $plans[] = Plan::find($id_plan);
        }

        return view('admin.map._edit', [
            'map' => $mapa,
            'plans' => $plans,
        ]);
    }

    // Actualioza los datos del mapa
    public function editMap($map, Request $request){
        $request->validate([
            'name_map' => 'required|string|min:1',
            'id_plans' => 'required|array|min:1',
        ]);

        $name = $request->get('name_map');
        $id_plans = $request->get('id_plans');
        
        $mapa = Map::find( $map );
        $mapa->name = $name;
        $mapa->plans = $id_plans;

        $editions = $mapa->editions ?? [];
        $editions[] = [
            'id_user' => auth()->user()->id,
            'updated_at' => Carbon::now()->toISOString(),
        ];

        $mapa->editions = $editions;
        $mapa->save();
    }

    // Genera la URL para compartir el mapa
    public function shareMap( $id ){
        $url = route('public.view-map', $id);

        return response()->json(
            ['url' => $url]
        );
    }

}