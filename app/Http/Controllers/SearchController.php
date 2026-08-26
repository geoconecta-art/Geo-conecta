<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Map;
use App\Models\Person;
use App\Models\Plan;
use App\Models\PlanRegister;
use App\Models\Subdirections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware(['can:estructura']);
    }

    public function home() {
        return view('admin.search.home');
    }

    public function search(Request $request) {

        $inventaries = $maps = [];
        $user = Auth::user();

        $maps = Map::mapsByArea( $user->id_area );
       

        $maps = array_map(function($map){
            $area_key = explode( "-", $map['map_key'] );

            return [
                'id' => $map['_id'],
                'data' => ['category' => 'Mapa'],
                'value' => $area_key[0] . '-' . $map['system_key'] . "  |  " . $map['name'],
                'route' => "/admin/mapa/". $map['_id'] ."/exportar-pdf",
            ];
        }, $maps->toArray());

        $inventaries = Plan::inventoriesByArea( $user->id_area );
        $inventaries = array_map( function( $plan ){
            return [
                'id' => $plan['_id'],
                'data' => [ 'category' => 'Inventarios' ],
                'value' => $plan['name'],
                'route' => "/admin/inventarios/formulario/". $plan['_id'] ."/",
            ];
        }, $inventaries->toArray() );

        $planRegister = array_map( function($plan){
            return [
                'id' => $plan['id'],
                'data' => [ 'category' => 'Datos' ],
                'value' => $plan['value'],
                'route' => "/admin/datos/inventario/". $plan['id'] ."/",
            ];
        }, $inventaries );
        
        $data = array_merge( $inventaries, $planRegister, $maps );

        return response()->json($data);
    }

    public function profile($id) {
        $person = Person::find($id);

        $person->promoted = Person::where('person_id', $person->id)
            ->where('type', Person::PROMOVIDO)            
            ->get();
        
        $countPromotedWeeks = Person::getPersonsPromotedPerWeeks($person);

        return view('admin.search.profile', [ 
            'person' => $person,
            'countPromotedWeeks'  => $countPromotedWeeks
        ]);
    }

    public function getId(Request $request) {

        $name = $request->get('name');

        $info = explode(' - ', $name);

        $person = Person::where('ine', $info[0])
            ->where('type', '!=', Person::PROMOVIDO)            
            ->first();
        
        return response()->json(array(
            'success' => true,
            'id' => $person->id
        ));
    }
}

// 
// $areas = Area::whereNull('deleted_at')
        //             ->orderBy('name')
        //             ->get()
        //             ->map(function( $area ){
        //                 return [ 
        //                     'id' => $area->id,
        //                     'data' => 'Dirección',
        //                     'value' => $area->name 
        //                 ];
        //             })
        //             ->toArray();

        // $subareas = Subdirections::whereNull('deleted_at')
        //             ->orderBy('name')
        //             ->get()
        //             ->map(function( $subarea ){
        //                 return [
        //                     'id' => $subarea->id,
        //                     'data' => 'Subdirección',
        //                     'value' => $subarea->name 
        //                 ];
        //             })
        //             ->toArray();