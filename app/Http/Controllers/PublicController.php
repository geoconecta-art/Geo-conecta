<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    private $utils;

    public function __construct() {
        $this->utils = new UtilsController();
    }

    // Descarga un archivo cuya URL se encuentre en un archivo Excel exportado
    function downloadFileFromExcel(Request $request){
        $path = $request->get('file_path');
        $pathAux = str_replace("storage/", "", $path);
        
        if(Storage::disk('public')->exists( $pathAux ) ){
            $path = storage_path( $path );
            return response()->download($pathAux);
        } else {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }
    }

    // Abre un mapa en una vista publica
    public function openPublicMap($id){
        $map = Map::find($id);
        $id_plans = $map->plans;
        $planKeys = [];
        $plans = [];

        foreach ($id_plans as $id_plan) {
            $plan = Plan::with('registros')->find($id_plan);
            $planKeys[ $id_plan ] = self::getFisrtElement( $plan->attributes, 0 );
            $plans[] = $plan;
        }

        return view('public.map', [
            'map' => $map,
            'plans' => $plans,
            'planKeys' => $planKeys,
        ]);
    }

    private function getFisrtElement( $attributes, $index ){
        if( $attributes ){
            if( is_array( $attributes[0] ) ){
                if( !isset( $attributes[0]['deleted_at'] ) ){
                    return $this->utils->createFieldKey( $attributes[0], $index, "gen" );
                } else {
                    array_shift($attributes);
                    return self::getFisrtElement( $attributes, $index++ );
                }
            } else {
                array_shift($attributes);
                return self::getFisrtElement( $attributes, $index++ );
            }
            
        } else {
            return null;
        }
    }
}
