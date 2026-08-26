<?php

namespace App\Http\Controllers;

// MODELOS

use App\Exports\InventaryExport;
use App\Imports\DataImportCSV;
use App\Models\Area;
use App\Models\Subdirections;
use App\Models\Plan;
use App\Models\PlanRegister;

// IMPORTS
use App\Imports\RegisterImport;
use App\Models\Colonia;
use App\Models\Lote;
use App\Tools\Tools;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlanRegisterController extends Controller
{
    protected $utils;

    public function __construct(){
        $this->middleware('auth')->only('index');
        $this->middleware(['can:registros']);
        $this->utils = new UtilsController();
    }

    // Lleva a la vista principal de los inventarios
    public function index() {
        $user = Auth::user();
        $directions = $subdirections = $plans = [];

        if( $user->hasRole("Super Administrador") ){
            $directions = Area::whereNull('deleted_at')->get();
            $subdirections = Subdirections::whereNull('deleted_at')->get();
            $plans = Plan::whereNull('deleted_at')->get();
        } else {
            $directions = Area::whereNull('deleted_at')->where('_id', $user->id_area)->get();
            $subdirections = Subdirections::whereNull('deleted_at')->where('id_area', $user->id_area)->get();
            $plans = Plan::whereNull('deleted_at')->where('id_area', $user->id_area)->get();
        }

        return view('admin.registros.index', [
            'directions' => $directions,
            'subdirections' => $subdirections,
            'plans' => $plans,
        ]);
    }

    // Regresa la información de los registros, filtrada por area y subarea
    public function filterInfo( Request $request ){

        $user = Auth::user();

        $id_area = $request->get('id_area') ?? $user->id_area;
        $id_subarea = $request->get('id_subarea');

        $inventariesAux = $inventaries = [];

        if( $id_area && $id_subarea ){
            $inventariesAux = Plan::where('id_area', $id_area)->where('id_subarea', $id_subarea)->whereNull('deleted_at')->orderBy('id_area')->get();
        } else if($id_area && $id_subarea == null) {
            $inventariesAux = Plan::where('id_area', $id_area)->whereNull('deleted_at')->orderBy('id_area')->get();
        } else if( $id_area == null && $id_area ){
            $inventariesAux = Plan::where('id_subarea', $id_subarea)->whereNull('deleted_at')->orderBy('id_area')->get();
        } else if( $id_area == null && $id_subarea == null ){
            $inventariesAux = Plan::whereNull('deleted_at')->orderBy('id_area')->get();
        }

        foreach ($inventariesAux as $key => $inventary) {
            $inventaries[$key]['index'] = $key + 1;
            $inventaries[$key]['id'] = $inventary->id;
            $inventaries[$key]['area_name'] = $inventary->subarea->area->name;
            $inventaries[$key]['subarea_name'] = $inventary->subarea->name;
            $inventaries[$key]['name'] = $inventary->name;
        }

        return DataTables::of($inventaries)
            ->addColumn('actions', 'admin.registros._actions')
            ->rawColumns([ 'actions', 'image'])
            ->make(true);
    }

    // Lleva a la vista principal del inventario
    public function inventoryIndex($id) {
        $plan = Plan::find($id);
        $registers = PlanRegister::where('id_plan', $id)->whereNull('deleted_at')->get();
        $fieldKeys = $this->utils->getFieldKeysFromAttr( $plan->attributes, $plan, "gen" );
        
        return view('admin.registros.invetory_index', [
            'plan' => $plan,
            'rows' => $registers,
            'keys' => $fieldKeys,
        ]);
    }

    // Lleva a la vista del formulario para agregar datos
    public function inventoryFormIndex($id){
        $plan = Plan::find($id);
        $suburbs = Colonia::whereNull('deleted_at')->get();
        $lotes = Lote::paginate(1000);
        $cps = [];

        foreach ($suburbs as $suburb) {
            $cps[] = $suburb->cp;
        }

        $cps = array_unique( $cps );

        return view('admin.registros.register_plan', [
            'plan' => $plan,
            'lotes' => $lotes,
            'suburbs' => $suburbs,
            'cps' => $cps,
            'edit' => false,
        ]);
    }

    // Se encarga de realizar la validación de prueba de los datos para la vista previa
    public function validation($id, Request $request){
        $plan = Plan::find($id);
        $rules = $this->utils->createPlanRules( $plan->attributes, $plan, "gen" );// array_merge( $attrRules, $addrRules);
        $values = $request->all();

        $rulesToRemove = array_filter( $values, function( $value, $key ){
            return str_contains($key, "_hidden") && $value == 1;
        }, ARRAY_FILTER_USE_BOTH);

        $rulesToRemove = array_map( function($key){
            return str_replace("_hidden", "", $key);
        }, array_keys($rulesToRemove) );

        $rulesToRemove = array_flip( $rulesToRemove );

        $rules = array_diff_key( $rules, $rulesToRemove);

        $validator = $this->utils->validation( $rules, $values );
        
        return $validator;
    }

    // Almacena un registro de un plan
    public function inventoryStore($id, Request $request) {
        $validator = self::validation($id, $request);
        $status = $message = null;
        
        if( !$validator->fails() ){
            $plan = Plan::find($id);
            $values = $request->all();

            $attrs = $plan->attributes;
            $catas = $plan->catastral ?? [];
            $addr = $plan->address ?? [];
            $geo = $plan->georeference;

            $attrValues = self::getRequestValues($values, $attrs, "gen");
            $cataValues = self::getRequestValues($values, $catas, "catastral");
            $addAttrValues = self::getRequestValues($values, $addr,  "address");
            $geoAttrValues['_Inicial'] = self::getRequestValues($values, $geo['_Inicial'], "georeference_Inicial");
            $geoAttrValues['_Final'] = $plan->geometry == 'LineString' ? self::getRequestValues($values, $geo['_Final'], "georeference_Final") : null;
            $geoAttrValues = array_filter( $geoAttrValues, function( $value ){
                return $value != null;
            });

            $plan->attributes = $attrs;
            $plan->catastral = $catas;
            $plan->address = $addr;
            $plan->georeference = $geo;
            $plan->save();

            $planRegister = new PlanRegister();
            $planRegister->id_plan = $plan->id;
            $planRegister->id_subarea = $plan->id_subarea;
            $planRegister->id_area = $plan->id_area;

            $planRegister->georeference = $geoAttrValues;
            $planRegister->attributes = $attrValues;
            $planRegister->address = $addAttrValues;
            $planRegister->catastral = $cataValues;
            
            $planRegister->save();
            self::updateInventoryPlanFields($id);

            self::storeFiles($request, $plan, $planRegister);

            $status = 200;
            $message = 'Registro almacenado corectamente.';
        } else {
            $status = 422;
            $message = $validator->errors();
        }
        
        return response()->json([
            "message" => $message,
        ], $status);
    }

    // Actualiza los campos para evitar que sean editables, incluyendo los atributos de address
    private function updateInventoryPlanFields($id){
        $plan = Plan::find($id);
        $attributes = [];

        foreach ($plan['attributes'] as $key => $attr) {
            if( $attr != "address" && $attr != 'georeference_Inicial' && $attr != 'georeference_Final' && $attr != 'catastral' ){
                $attr['editable'] = false;
            } else if( $attr == "address" ){
                $address = $plan[$attr];
                $address['editable'] = false;
                $plan[$attr] = $address;
            }
            $attributes[] = $attr;
        }

        $plan->attributes = $attributes;
        $plan->save();
    }

    // Obtiene los valores del formulario. Si el sufijo se encuentra dentro del nombre, significa que es parte de la petición
    private function getRequestValues($attrVals, &$attributes, $sufix){
        $values = $attrAux = [];

        foreach ($attributes as $key => $attribute) {
            if( is_array( $attribute ) ){
                if( !in_array( $attribute['type'], ['file', 'image', 'button'] ) ){
                    $attrKey = $this->utils->createFieldKey( $attribute, $key, $sufix );

                    if( isset($attrVals[$attrKey]) ){
                        $values[$attrKey] = $attrVals[$attrKey];
                        $attribute["editable"] = false;
                    } else {
                        $values[$attrKey] = null;
                    }
                }
            } else {
                if( $sufix == "address" ){
                    $attribute = false;
                }
            }
            $attrAux[$key] = $attribute;
        }

        $attributes = $attrAux;
        
        return $values;
    }

    // Almacena las imagenes en el servidor
    private function storeFiles(Request $request, $plan, $row, $edit = false){
        $files = $request->allFiles();
        $files = array_filter($files, function($file){
            return $file->getClientOriginalExtension();
        });

        $plan_name = str_replace(" ", "_", $plan->name);
        $attrs = $plan->attributes;
        $attrVals = $row->attributes;
        
        foreach( $files as $key => $file){
            $path = 'registros/inventarios/' . $plan_name . '/' . $row->id . '/files/' . $key . '/' ;
            $fileName = $file->getClientOriginalName();

            // if( $edit ){
            //     $deleting_file_path = str_replace( "storage/", "",  $attrVals[ $key ] ?? "" );

            //     if( Storage::disk('public')->exists( $deleting_file_path ) ){
            //         Storage::disk('public')->delete( $deleting_file_path );
            //     }
            // }

            if( !Storage::disk('public')->exists($path) ){
                Storage::disk('public')->makeDirectory($path, 0777, true);
            }

            $parts = explode( "_", $key );
            $gen_pos = array_search( "gen", $parts);
            $fileAttrIndex = intval( $parts[ $gen_pos - 1 ] );

            if( $fileAttrIndex ){
                $file->storeAs($path, $fileName, 'public');
                $attrVals[$key] = 'storage/' . $path . $fileName;
                $attrs[ $fileAttrIndex ]['editable'] = false;
            }
        }

        $plan->attributes = $attrs;
        $plan->save();

        $row->attributes = $attrVals;
        $row->save();
    }

    // Obtiene los valores para editar un registro
    public function editInventoryData($id, $row){
        $plan = Plan::find($id);
        $planRegister = PlanRegister::find($row);
        $suburbs = Colonia::whereNull('deleted_at')->get();
        $lotes = Lote::paginate(1000);
        $cps = [];

        foreach ($suburbs as $suburb) {
            $cps[] = $suburb->cp;
        }

        $cps = array_unique( $cps );

        return view('admin.registros._edit_row', [
            'plan' => $plan,
            'row' => $planRegister,
            'lotes' => $lotes,
            'suburbs' => $suburbs,
            'cps' => $cps,
            'edit' => false,
        ]);
    }

    // Actualiza los campos del registro
    public function updateInventoryData($id, $row, Request $request){
        $validator = self::validation($id, $request);
        $status = $message = null;
        
        if( !$validator->fails() ){
            $plan = Plan::find($id);
            $values = $request->all();

            $attrs = $plan->attributes;
            $addr = $plan->address ?? [];
            $geo = $plan->georeference;
            $catas = $plan->catastral ?? [];

            $attrValues = self::getRequestValues($values, $attrs, "gen");
            $addAttrValues = self::getRequestValues($values, $addr,  "address");
            $cataValues = self::getRequestValues($values, $catas, "catastral");
            $geoAttrValues['_Inicial'] = self::getRequestValues($values, $geo['_Inicial'], "georeference_Inicial");
            $geoAttrValues['_Final'] = $plan->geometry == 'LineString' ? self::getRequestValues($values, $geo['_Final'], "georeference_Final") : null;
            $geoAttrValues = array_filter( $geoAttrValues, function( $value ){
                return $value != null;
            });

            $plan->attributes = $attrs;
            $plan->catastral = $catas;
            $plan->address = $addr;
            $plan->georeference = $geo;
            $plan->save();

            $planRegister = PlanRegister::find($row);

            $attrToKeep = $planRegister->attributes;
            $attrToKeep = array_diff_key( $attrToKeep , $attrValues );
            $attrValues = array_merge( $attrValues, $attrToKeep );
            
            $planRegister->attributes = $attrValues;
            $planRegister->catastral = $cataValues;
            $planRegister->georeference = $geoAttrValues;
            $planRegister->address = $addAttrValues;
            $planRegister->save();

            self::storeFiles($request, $plan, $planRegister, true);

            $status = 200;
            $message = 'Registro modificado correctamente.';
        } else {
            $status = 422;
            $message = $validator->errors();
        }
        
        return response()->json([
            "message" => $message,
        ], $status);
    }

    // Define el tipo de modal que se va a mostrar para cargar el archivo
    public function showImportRowsFromFileModal( $id, $type ){
        $title = $files = $typeF = null;
        $plan = Plan::find($id);

        switch ($type) {
            case 'excel':
                $title = strtoupper( $type );
                $files = '.xlsx,.xls';
                $typeF = 1;
                break;
            
            case 'csv' :
                $title = strtoupper( $type ) . ' / Texto';
                $files = '.csv,.txt';
                $typeF = 2;
                break;
        }

        $fieldNames = $this->utils->getNamesPlanFields( $plan->attributes, $plan, "", false );

        return view('admin.registros._import_rows', [
            'plan' => $plan,
            'title' => $title,
            'files' => $files,
            'typeF' => $typeF,
            'route' => 'register.inventory.import-save',
            'fieldNames' => $fieldNames,
        ]);
    }

    // Obtiene los nombres de los encabezados del archivo exportado exportado
    public function getHeadersFromFile(Request $request, $id){
        $validator = Validator::make(
            $request->all(),
            [ 'import_excel_plan' => 'required|file|mimes:csv,txt', ]
        );

        if( !$validator->fails() ){
            $file_path = $request->file('import_excel_plan')->getRealPath();
            $content = $this->utils->encodeFileContent( $file_path );

            $import = new DataImportCSV();
            Excel::import( $import, $content );
            $headers = $import->getHeaders();

            $plan = Plan::select('geometry')->find($id);
            $georeferences = $plan->geometry == "Point" ? ['Latitud', 'Longitud'] : ['Latitud Inicial', 'Longitud Inicial', 'Latitud Final', 'Longitud Final'];
            
            return view('admin.registros._headers_select', [
                'headers' => $headers,
                'geometry' => $plan->geometry,
                'georeferences' => $georeferences,
            ]);
        }
    }

    // Importa la información del archivo a la base de datos. Permite el ingreso de cualquier archivo CSV
    public function saveImportRowsFromFile(Request $request, $id, $type,){

        $validator = Validator::make(
            $request->all(),
            [ 'import_excel_plan' => $type == 1 ? 'required|file|mimes:xlsx,xlx' :  'required|file|mimes:csv,txt', ]
        );

        $status = 200;
        $message = null;

        if( !$validator->fails() ){
            // Codificar la información del archivo para evitar errores en los acentos
            $file_path = $request->file('import_excel_plan')->getRealPath();
            $content = $this->utils->encodeFileContent( $file_path );

            $plan = Plan::find($id);
            $import = new DataImportCSV();
            Excel::import( $import, $content );

            // Separar encabezados de coordenadas
            $coor_headers = $this->utils->getCoorsHeaderPositions( $request, $plan->geometry );
            if( empty( $coor_headers['_Inicial'] ) && empty( $coor_headers['_Final'] ) )
                $coor_headers = $this->utils->getCoorsHeaderPositionsFromCSV( $import->getHeaders() );
            $headers = $this->utils->splitHeaders( $import->getHeaders(), $coor_headers['_Inicial'] );
            $headers = $this->utils->splitHeaders( $headers, $coor_headers['_Final'] );
            
            // Separar encabezados de dirección y actualizar registros en caso
            $add_headers = $this->utils->getAddressHeaderPositionsFromCSV( $import->getHeaders() );     //Encabezados de dirección
            $headers = $this->utils->splitHeaders( $headers, $add_headers );

            // Separar encabezados de clave catastral
            $cat_headers = $this->utils->getCatastralHeaderPositionFromCSV( $import->getHeaders() );
            $headers = $this->utils->splitHeaders( $headers, $cat_headers );

            // Agregar los campos de dirección al inventario
            if( $this->utils->thereAreAddressFields($add_headers, $plan) )
                $this->utils->addAddressField( $plan->id, -1, false );

            if( !empty( $cat_headers ) && !in_array( "catastral", $plan->attributes ) )
                $this->utils->addCatastralField($plan->id, -1, false);
            
            $add_headers = $this->utils->formatHeaders( $plan->id, $add_headers, "address" );
            $cat_headers = $this->utils->formatHeaders( $plan->id, $cat_headers, "catastral" );
            
            // Actualizar los campos del inventario y actualizar registros
            $planFields = $this->utils->getNameAttrFields($plan->attributes, "");
            $extraFields = array_diff( $headers, $planFields );
            $this->utils->addExtraFieldsToPlan( $extraFields, $plan->id );
            $plan_headers = $this->utils->formatPlanHeaders( $plan->id, $headers );
    
            // Obtener nuevamente el inventario, con todos los cambios realizados
            $plan = Plan::find($id);

            self::createRowsFromCSVFile( $import, $plan, $plan_headers, $add_headers, $coor_headers, $cat_headers );

            $status = 200;
            $message = 'Registro modificado correctamente.';

        } else {
            $status = 422;
            $message = $validator->errors();
        }

        return response()->json([
            "message" => $message,
        ], $status);
    }

    // Crea los registros y los almacena en la base de datos
    private function createRowsFromCSVFile($import, $plan, $attr_headers, $add_headers, $geo_headers, $cat_headers ){
        $data = $import->getData();

        for ($i=1; $i < count($data) ; $i++) { 
            $row = new PlanRegister();

            $row->id_plan = $plan->id;
            $row->id_subarea = $plan->id_subarea;
            $row->id_area = $plan->id_area;

            $row->attributes = self::getValuesFromRowCSV( $attr_headers, $data[$i] );
            $row->address = self::getValuesFromRowCSV( $add_headers, $data[$i] );
            $row->catastral = self::getValuesFromRowCSV( $cat_headers, $data[$i] );
            
            $georeference = [];
            $georeference['_Inicial'] = self::getValuesFromRowCSV( $geo_headers['_Inicial'], $data[$i] );
            if( $plan->geometry == "LineString" ){
                $georeference['_Final'] = self::getValuesFromRowCSV( $geo_headers['_Final'], $data[$i] );
            }
            $row->georeference = $georeference;
            $row->save();
        }
    }

    // Obtiene los valores de un array de atributos de un registro
    private function getValuesFromRowCSV( $headers, $row){
        $values = [];

        foreach ($headers as $key => $head) {
            if( $head !== false && $head !== null ){
                $values[ $key ] = $row[ $head ];
            } else {
                $values[ $key ] = '';
            }
        }

        return $values;

    }

    // Exporta los datos de los registros en formato Excel
    public function exportRegisterExcel($id){
        $plan = Plan::find( $id );
        $date = Carbon::now()->format('Y-m-d');
        $date = str_replace("-", "_", $date);

        $headers = $this->utils->getNamesPlanFields( $plan->attributes, $plan, "", true );
        
        $numCols = count($headers);
        
        return Excel::download(new InventaryExport($id, $headers, $numCols), $plan->name .'_'.$date.'.xlsx');
    }

    // Abre el modal de exportación en PDF
    public function openPdfExportModal( $id, Request $request ){
        $plan = Plan::find( $id );
        $fieldNames = $this->utils->getNamesPlanFields( $plan->attributes, $plan, "", $plan->geometry == "LineString" );
        $fieldKeys = $this->utils->getFieldKeysFromAttr( $plan->attributes, $plan, "gen" );
        $fieldKeys = $this->utils->flatte_array( $fieldKeys );
        
        $fields = array_combine( $fieldKeys, $fieldNames );

        return view('admin.registros._export_pdf_modal',[
            'plan' => $plan,
            'fieldNames' => $fields,
        ]);
    }

    // Determina el tipo de exportación si será detallada o en formato de lista
    public function exportRegisterPdf($id, Request $request){
        $type = $request->get('type');
        $extraRule = $type == 1 ? 'min:1|max:10' : 'min:0';

        $validator = Validator::make($request->all(), [
            'export_pdf_field_check' => 'array|' . $extraRule,
            'export_pdf_field_check*' => 'string|'
        ]);

        if( !$validator->fails() ){
            $headers = $request->get('export_pdf_field_check');

            if( !$headers && $type != 1){
                $plan = Plan::find($id);
                $keys = $this->utils->getFieldKeysFromAttr( $plan->attributes, $plan, "gen" );
                $keys = $this->utils->flatte_array( $keys );
                $names = $this->utils->getNamesPlanFields( $plan->attributes, $plan, "", $plan->geometry == "LineString");
    
                $headers = array_combine( $keys, $names );
            }

            $urlName = $type == 1 ? 'register.inventory.export-list-pdf' : 'register.inventory.export-details-pdf';
            $url = route($urlName, [
                'id' => $id,
                'headers' => json_encode($headers)
            ]);

            return response()->json([ 'url' => $url ]);
        }
    }

    // Exporta la información de los registros en formato PDF
    public function exportDetailsRegisterPdf( $id, Request $request ){
        $plan = Plan::find($id);
        $registers = PlanRegister::where('id_plan', $id)->whereNull('deleted_at')->get();
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
        $headers = $request->get('headers');
        $headers = json_decode($headers, true);

        $data = [
            'plan' => $plan,
            'rows' => $registers,
            'date' => $date,
            'headers' => $headers,
        ];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        $pdf = Pdf::loadView('admin.registros.pdf.details.inventory_pdf', $data);

        return $pdf->setPaper('a4', 'portrait')->stream('Inventario - ' . $plan->name .  '.pdf');
    }

    // Exporta la información de los registros en formato PDF
    public function exportListRegisterPdf( $id, Request $request ){
        $plan = Plan::find($id);
        $registers = PlanRegister::where('id_plan', $id)->whereNull('deleted_at')->get();
        // $fieldKeys = $this->utils->getFieldKeysFromAttr( $plan->attributes, $plan, "gen" );
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
        $headers = $request->get('headers');
        $headers = json_decode($headers, true);

        $data = [
            'plan' => $plan,
            'rows' => $registers,
            // 'keys' => $fieldKeys,
            'date' => $date,
            'headers' => $headers,
        ];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        $pdf = Pdf::loadView('admin.registros.pdf.list.inventory_pdf', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Inventario - ' . $plan->name .  '.pdf');
    }

    // Exporta la información de los registros en formato PDF pero colocados en un mapa
    // public function exportMapRegisterPdf( $id ){
    //     $plan = Plan::find($id);
    //     $registers = PlanRegister::where('id_plan', $id)->whereNull('deleted_at')->get();

    //     $fieldKeys = $this->utils->getFieldKeysFromAttr( $plan->attributes, $plan, "gen" ); //self::orderFieldKeys($fieldKeys, $plan);
    //     $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
    //     $area_name = $plan->subarea->area->name;

    //     $info_rows = $plan->geometry == "Point" ? $this->utils->createPointInfoMap( $registers, $plan->color ) : $this->utils->createLinesInfoMap( $registers, $plan->color );        
    //     $gm_image = $this->utils->getImageFromGoogleMaps( $info_rows, "19.563921797552112", "-99.28347607035111", "12", "1024x1024");

    //     $data = [
    //         'plan' => $plan,
    //         'rows' => $registers,
    //         'keys' => $fieldKeys,
    //         'date' => $date,
    //         'gm_image' => $gm_image,
    //         'area_name' => $area_name,
    //     ];

    //     ini_set('memory_limit', '-1');
    //     ini_set('max_execution_time', '1800');

    //     $pdf = Pdf::loadView('admin.registros.pdf.map.map_pdf', $data);

    //     return $pdf->setPaper('a4', 'landscape')
    //                ->setOption('margin-top', 5)
    //                ->setOption('margin-left', 5)
    //                ->setOption('margin-right', 5)
    //                ->setOption('margin-bottom', 5)
    //                ->stream('Inventario - ' . $plan->name .  '.pdf');
    // }

    // Exporta la información en formato KML
    public function exportKML($id_plan){
        $plan = Plan::find($id_plan);
        $rows = PlanRegister::where('id_plan', $id_plan)->whereNull('deleted_at')->get();

        $dom = new \DOMDocument("1.0", "UTF-8");
        $dom->formatOutput = true;

        $kmlFile = $dom->createElement("kml");
        $kmlFile->setAttribute("xmlns", "http://www.opengis.net/kml/2.2");
        $kmlFile->setAttribute("xmlns:gx", "http://www.google.com/kml/ext/2.2");
        $kmlFile->setAttribute("xmlns:kml", "http://www.opengis.net/kml/2.2");
        $kmlFile->setAttribute("xmlns:atom", "http://www.w3.org/2005/Atom");
        $dom->appendChild( $kmlFile );
        
        $document = $dom->createElement('Document');
        $kmlFile->appendChild($document);

        foreach ($rows as $key => $row) {

            $placemark = $dom->createElement('Placemark');
            $document->appendChild($placemark);

            $name = $dom->createElement("name", $plan->name);
            $placemark->appendChild($name);

            $description = $dom->createElement("description", $plan->name);
            $placemark->appendChild($description);

            $coorsAux = $this->utils->getCoorsFromRow($row, $plan->geometry);

            $geometryElement = $dom->createElement($plan->geometry);
            $placemark->appendChild($geometryElement);

            if ( $plan->geometry == 'LineString' ){
                $coors = $coorsAux[0][0] . ',' . $coorsAux[0][1] . ',0 ' . $coorsAux[1][0] . ',' . $coorsAux[1][1] . ',0 ' ;
            } else {
                $coors = implode(",", $coorsAux) . ',0';
            }

            $coordinates = $dom->createElement('coordinates', $coors);
            $geometryElement->appendChild($coordinates);

            $childNode = null;
            $childNode = $this->utils->getKMLProperties($row, $row->attributes, $plan->attributes, $plan, 'gen', $dom, $childNode);
            $placemark->appendChild( $childNode );
        }

        $kmlContent = $dom->saveXML();
        $fileName = $plan->name . '.kml';

        return response( $kmlContent, 200 )
            ->header('Content-Type', 'application/vnd.google-earth.kml+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    // Exporta la información en formato GeoJSON
    public function exportGeoJSON($id_plan){
        $plan = Plan::find($id_plan);
        $rows = PlanRegister::where('id_plan', $id_plan)->whereNull('deleted_at')->get()->toArray();

        $jsonContent = [
            'type' => "FeatureCollection",
            'features' => array_map( function($row) use($plan){
                return [
                    'type' => 'Feature',
                    'properties' => [
                        $this->utils->getGeoJSONProperties( $row, $row['attributes'], $plan->attributes, $plan, "gen" ),
                    ],
                    'geometry' => [
                        'type' => $plan->geometry,
                        'coordinates' => $this->utils->getCoorsFromRow( $row, $plan->geometry ),
                    ],
                ];
            }, $rows ),
        ];

        $jsonString = json_encode( $jsonContent );

        return response()->stream(
            function() use ($jsonString) {
                echo $jsonString;
            }, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $plan->name . '.geojson"',
            ]
        );
    }

    // Manda la advertencia de confirmación
    public function deleteRowWarning($id_plan, $id_row){
        $row = PlanRegister::find($id_row);
        $plan = Plan::find($id_plan);

        $planNames = $this->utils->getNamesPlanFields( $plan->attributes, $plan, "", true );
        $planKeys = $this->utils->getFieldKeysFromAttr( $plan->attributes, $plan, "gen" );

        $result = [];
        foreach ($planKeys as $planKey) {
            if( is_array( $planKey ) ){
                $result = array_merge( $result, $planKey );
            } else{
                $result[] = $planKey;
            }
        }

        $fields = array_combine( $result, $planNames );
        
        return view('admin.registros._deleting_warning', [
            'row' => $row,
            'plan' => $plan,
            'fields' => $fields,
        ]);
    }

    // Elimina el registro de la base de datos
    public function deleteRow($id_plan, $id_row){
        $row = PlanRegister::find($id_row);
        $plan = Plan::find($id_plan);
        
        $name = $plan['name'];
        $row->delete();

        return response()->json([
            'success' => true,
            'name' => $name,
        ]);
    }

}

// public function viewMapRegisterPdf( $id ){
//     $plan = Plan::find($id);
//     $registers = PlanRegister::where('id_plan', $id)->whereNull('deleted_at')->get();

//     $fieldKeys = $this->utils->getFieldKeysFromAttr( $plan->attributes, $plan, "gen" ); //self::orderFieldKeys($fieldKeys, $plan);
//     $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
//     $gm_image = "";
//     $area_name = $plan->subarea->area->name;

//     if( $plan->geometry == "Point" ){
//         $markers = $this->utils->createPointInfoMap( $registers, $plan->color );
//         $gm_image = $this->utils->getImageFromGoogleMaps( $markers, "19.543454812522057", "-99.23502275276154", "14", "900x900");
//     } else if( $plan->geometry == "LineString" ) {
//         $lines = $this->utils->createLinesInfoMap( $registers, $plan->color );
//         $gm_image = $this->utils->getImageFromGoogleMaps( $lines, "19.543454812522057", "-99.23502275276154", "13", "800x800");
//     }

//     $data = [
//         'plan' => $plan,
//         'rows' => $registers,
//         'keys' => $fieldKeys,
//         'date' => $date,
//         'gm_image' => $gm_image,
//         'area_name' => $area_name,
//     ];

//     return view('admin.registros.pdf._map_pdf', $data);
// }

// $index = array_search( "georeference_Inicial", $plan->attributes );
// array_splice( $fieldKeys, $index, 0, $fieldKeys['georeference_Inicial'] );
// unset( $fieldKeys['georeference_Inicial'] );

// if( $plan->geometry == "LineString" ){
//     $index = array_search( "georeference_Final", $plan->attributes );
//     array_splice( $fieldKeys, $index, 0, $fieldKeys['georeference_Final'] );
//     unset( $fieldKeys['georeference_Final'] );
// }

// if( array_search( "address", $plan->attributes ) ){
//     $index = array_search( "address", $plan->attributes );
//     array_splice( $fieldKeys, $index, 0, $fieldKeys['address']);
//     unset( $fieldKeys['address'] );
// }

// $fields = array_combine($fieldKeys, $fieldNames);