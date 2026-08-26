<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Subdirections;
use App\Models\Plan;
use App\Models\PlanRegister;
use App\Models\User;
use App\Tools\Tools;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class StatsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only('index');
        $this->middleware(['can:estadisticas']);
    }
    
    // Lleva a la vista principal de las estadísticas
    public function index(){
        $user = Auth::user();
        $areas = [];

        if( $user->hasRole('Super Administrador') )
            $areas = Area::whereNull('deleted_at')->get();
        else
            $areas = Area::whereNull('deleted_at')->where('_id', $user->id_area)->get();

        return view('admin.stats.index',[
            'areas' => $areas,
        ]);
    }

    /**
     * Obtiene la información de las estadísticas de la respectiva dirección o de todas las direcciones
     * 
     * @param int  $id_direction representa la id de la dirección 
     * 
     * @return response Retorna una vista renderizada con la respectiva información de las estadísticas
     */
    public function getInventoryStats( $id_area ){
        $data = $id_area == -1 ? self::allAreaStats() : self::statsByArea( $id_area );

        return response()->json([
            'html' => view('admin.stats._info',$data[0])->render(),
            'subarea_inventaries' => $data[1],
            'id_area' => $id_area,
        ]);
    }

    private function allAreaStats(){
        $inventories = Plan::all()->whereNull('deleted_at');
        $areas = Area::all()->whereNull('deleted_at');
        $area_name = "Todas las Dependencias / Organismos";
        $one_direction = false;

        $data = self::stats( $inventories, $areas, $one_direction );
        $data[0]['area_name'] = $area_name;
        $data[0]['one_direction'] = $one_direction;

        return $data;
    }

    private function statsByArea( $id_area ){
        $area = Area::with(['subareas.planeaciones', 'planeaciones'])->find($id_area);
        $areas = $area->subareas;
        $inventories = $area->planeaciones;
        $area_name = $area->name;
        $one_direction = true;

        $data = self::stats( $inventories, $areas, $one_direction );
        $data[0]['area_name'] = $area_name;
        $data[0]['one_direction'] = $one_direction;

        return $data;
    }


    /**
     * Obtiene el conteo de los datos acerca de los inventarios
     * 
     * @param Array $inventaries es un arreglo que posee todos los inventarios de los que se hará el conteo
     * @param Array $areas es un arreglo que posee todas las áreas, o subareas, de las que se obtendrá la información
     * @param bool  $one_direction representa si se está trabajando con una sola dirección o no
     * 
     * @return Array Retorna un arreglo con toda la información de las estadísticas
     */
    private function stats( $inventaries, $areas, $one_direction){
        $data = [ [], [], ];

        // TARJETAS - GRÁFICA DE PASTEL
        $data[0]['inventaries'] = count($inventaries);
        $data[0]['inventaries_with_georreferencia'] = self::getCountInventory( $inventaries, "registrosGeo" );
        $data[0]['inventaries_no_georreferencia'] = self::getCountInventory( $inventaries, "registrosNoGeo" );
        $data[0]['inventaries_no_data'] = self::getCountInventory( $inventaries, "registrosNoData");

        // GRÁFICA DE BARRAS
        $area_info = [];

        foreach ($areas as $area) {
            // Etiquetas de las subidrecciones
            $area_info['labels'][] = $area->name;
            // Inventarios con georreferencia
            $area_info['inventaries_with_georreferencia'][] = self::getCountInventory( $area->planeaciones, "registrosGeo" );
            // Inventarios sin datos
            $area_info['inventaries_no_data'][] = self::getCountInventory( $area->planeaciones, "registrosNoData" );
            // Inventarios sin georreferencia
            $area_info['inventaries_no_georreferencia'][] = self::getCountInventory( $area->planeaciones, "registrosNoGeo" );
        }

        $data[1] = $area_info;

        // TABLA DE GEORREFERENCIAS FALTANTES
        $info_inventaries_no_georreferencia = [];

        foreach ($inventaries as $inventory) {
            $countRows = count( $inventory->registros );

            if( $countRows > 0 ){
                $countRowsNoGeo = $inventory->geometry == 'LineString' ? count($inventory->registrosNoGeoLines) : count($inventory->registrosNoGeoPoints);
                if( $countRowsNoGeo > 0 ){
                    $info_inventaries_no_georreferencia[] = [
                        'id' => $inventory->id,
                        'name' => $inventory->name,
                        'countRow' => $countRows,
                        'countRowNoGeo' => $countRowsNoGeo,
                        'area_name' => $inventory->area->name,
                        'subarea_name' => $inventory->subarea->name,
                    ];
                }
            }
        }

        if( !$one_direction ){
            $area_inventories = [];
            foreach ($areas as $area) {
                $area_inventory['area_name'] = $area->name;
                $area_inventory['inventories'] = count( $area->planeaciones );
                $area_inventory['inventories_with_georreferencia'] = self::getCountInventory( $area->planeaciones, "registrosGeo" );
                $area_inventory['inventories_no_georreferencia'] = self::getCountInventory( $area->planeaciones, "registrosNoGeo" );
                $area_inventory['inventories_no_data'] = self::getCountInventory( $area->planeaciones, "registrosNoData");

                $area_inventories[] = $area_inventory;
            }
            $data[0]['area_inventories'] = $area_inventories;
        }

        $data[0]['info_inventaries_no_georreferencia'] = $info_inventaries_no_georreferencia;

        return $data;
    }


    // Recibe un conjunto de inventarios y el nombre de un evento (método) que será disparado par obtener la información del inventario
    private function getCountInventory( $plans, $event ){
        $count = 0;

        foreach ($plans as $plan) {
            $method = $event . ( ($plan->geometry == 'Point') ? 'Points' : 'Lines');
            $rows = count($plan->registros);

            // El conteno se aumenta si la cantidad de registros es la misma que 
            if( str_contains($event, "registrosGeo") ){    
                $count += ( $rows >= 1 && count( $plan->$method ) == $rows ) ? 1 : 0;
            
            // El conteo se aumenta si la cantidad de rows es 0
            } else if( str_contains($event, "registrosNoData") ){
                $count += $rows == 0 ? 1 : 0;

            // El conteo se aumenta si los registros son mayores a 1
            } else if( str_contains($event, "registrosNoGeo") ){
                $count += count( $plan->$method ) >= 1 ? 1 : 0;
            }
        }

        return $count;
    }

    public function error(){
        return view('admin.stats._error_stats');
    }

    public function exportPdfStats( $id_area, Request $request ){
        $result = $id_area == -1 ? self::allAreaStats() : self::statsByArea( $id_area );
        $director = $id_area == -1 ? null : User::where('id_area', $id_area)->first();

        $donutImage = $request->get('donutImage');
        $barImage = $request->get('barImage');
        
        $date = date('d') . '-' . Tools::getShortMonthName( date('m')) . '-' . date('Y');

        $data['date'] = $date;
        $data['director'] = $director;
        $data['donutImage'] = $donutImage;
        $data['barImage'] = $barImage;
        $data = array_merge( $data, $result[0] );

        $pdf = Pdf::loadView('admin.stats.pdf.direction.index', $data);
        $pdf->setPaper('a4', 'landscape');
        $pdfContent = $pdf->output();

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Estadísticas'. $data['area_name'] .'.pdf"');
    }

}