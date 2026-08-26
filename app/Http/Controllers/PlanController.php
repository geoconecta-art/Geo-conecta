<?php

namespace App\Http\Controllers;

use App\Imports\DataImportCSV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// MODELOS
use App\Models\Area;
use App\Models\Colonia;
use App\Models\Lote;
use App\Models\Map;
use App\Models\Subdirections;
use App\Models\Plan;
use App\Models\PlanRegister;
use App\Models\TypeDependencies;
use Carbon\Carbon;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    protected $utils;

    public function __construct() {
        $this->utils = new UtilsController();
        $this->middleware('auth')->only('index');
        $this->middleware(['can:inventarios']);
    }

    // Envía a la vista con la tabla 
    public function index() {
        $user = Auth::user();

        return view('admin.plans.index');
    }

    public function updateTable(){
        $user = Auth::user();
        $auxPlans = Plan::inventoriesByArea( $user->id_area );
        $plans = [];

        foreach ($auxPlans as $key => $plan) {
            $plans[] = [
                'index' => ($key + 1),
                'id' => $plan->id,
                'name' => $plan->name,
                'area' => $plan->area->name,
                'subarea' => $plan->subarea->name,
                'type_plan' => $plan->type_plan ?? '',
                'created_at' => $plan->created_at->format("Y-m-d"),
                'rows' => count( $plan->registros ),
            ];
        }

        return DataTables::of($plans)
                ->addColumn('actions', 'admin.plans._actions')
                ->rawColumns(['actions'])
                ->make(true);
    }

    // Abre el modal para crear una nueva planeación
    public function openModal() {
        $user = Auth::user();
        $typeDependecies = $subareas = [];

        if( $user->hasRole("Super Administrador") ){
            $typeDependecies = TypeDependencies::whereNull('deleted_at')->with('dependencies')->get();
            $subareas = Subdirections::with('area')->whereNull('deleted_at')->get();
        } else {
            $area = Area::find( $user->id_area );
            $typeDependecies = TypeDependencies::where('_id', $area->id_type)->with('dependencies')->get();
            $subareas = Subdirections::whereNull('deleted_at')->where('id_dependency', $area->id)->get();
        }
        $type_plans = ["Programa", "Proyecto", "Acción", "Trámite", "Incidente", "Otros"];

        return view('admin.plans._create_plan',[
            'typeDependecies' => $typeDependecies,
            'subareas' => $subareas,
            'type_plans' => $type_plans,
        ]);
    }

    // Abre el modal para crear un inventario planeación desde un archivo CSV
    public function openModalCSV() {
        $user = Auth::user();
        $typeDependecies = $subareas = [];

        if( $user->hasRole("Super Administrador") ){
            $typeDependecies = TypeDependencies::whereNull('deleted_at')->with('dependencies')->get();
            $subareas = Subdirections::with('area')->whereNull('deleted_at')->get();
        } else {
            $area = Area::find( $user->id_area );
            $typeDependecies = TypeDependencies::where('_id', $area->id_type)->with('dependencies')->get();
            $subareas = Subdirections::whereNull('deleted_at')->where('id_dependency', $area->id)->get();
        }
        $type_plans = ["Programa", "Proyecto", "Acción", "Trámite", "Incidente", "Otros"];

        return view('admin.plans._create_from_csv',[
            'typeDependecies' => $typeDependecies,
            'subareas' => $subareas,
            'type_plans' => $type_plans,
        ]);
    }

    // Extrae los encabezados del archivo CSV para el posterior uso
    public function getHeadersFromCSV( Request $request ){
        $validator = Validator::make(
            $request->all(),
            [ 
                'create_plan_from_csv_input' => 'required|file|mimes:csv,txt',
                'geometry' => 'required|string|min:1',
        ]);

        if( !$validator->fails() ){
            $geometry = $request->get('geometry');
            $file_path = $request->file('create_plan_from_csv_input')->getRealPath();
            $content = $this->utils->encodeFileContent( $file_path );

            $import = new DataImportCSV();
            Excel::import( $import, $content );
            $headers = $import->getHeaders();

            $georeferences = $geometry == "Point" ? ['Latitud', 'Longitud'] : ['Latitud Inicial', 'Longitud Inicial', 'Latitud Final', 'Longitud Final'];
            
            return view('admin.registros._headers_select', [
                'headers' => $headers,
                'geometry' => $geometry,
                'georeferences' => $georeferences,
            ]);
        }
    }

    // Crea un formulario a partir de un archivo CSV
    public function createPlanFromCSV( Request $request ){
        $request->validate([
            'name' => 'required|string|min:1',
            'id_area' => 'required|string|min:5',
            'id_subarea' => 'required|string|min:5',
            'type_plan' => 'required|string|min:5',
            'geometry' => 'required|string|min:1',
            'plan_color' => 'required|string|min:1',
            'create_plan_from_csv_input' => 'required|file|mimes:csv',
        ]);

        $id_plan = $this->utils->createInventory( $request );

        return response()->json([
            'route' => route('register.inventory.import-save', ['id' => $id_plan, 'type' => 2]),
        ]);
    }

    // Crea la planeación y la almacena en la base de datos
    public function create(Request $request){
        $request->validate([
            'name' => 'required|string|min:1',
            'id_area' => 'required|string|min:5',
            'id_subarea' => 'required|string|min:5',
            'type_plan' => 'required|string|min:5',
            'geometry' => 'required|string|min:1',
            'plan_color' => 'required|string|min:1',
        ]);

        $id_plan = $this->utils->createInventory( $request );

        $success = false;
        $redirect = "El nombre no puede estar vacío";

        if( $id_plan ){
            $success = true;
            $redirect = route('plans.form.create', ['id' => $id_plan]);
        }

        return response()->json([
            'success' => $success,
            'redirect' => $redirect,
        ]);
    }

    // Agrega los campos de dirección
    public function addAddressField($id, Request $request){
        $index = $request->get('index');
        $this->utils->addAddressField( $id, $index, true );

        $rows = PlanRegister::where('id_plan',$id)->get();
        $plan = Plan::find($id);

        if( !empty( $rows ) ){
            foreach ($rows as $key => $row) {
                $addrKeys = $this->utils->createFieldKeys( $plan->address, "address" );
                $addrVals = array_fill_keys( $addrKeys, null );
                $row->address = $addrVals;
                $row->save();
            }
        }
    }

    // Agrega el campo para la clave catastral
    public function addCatastralField($id, Request $request){
        $index = $request->get('index');
        $this->utils->addCatastralField($id, $index, false);

        return true;
    }

    //Lleva a la vista de creación del formulario
    public function createForm($id){
        $plan = Plan::find($id);
        $suburbs = Colonia::whereNull('deleted_at')->get();
        $cps = [];

        foreach ($suburbs as $suburb) {
            $cps[] = $suburb->cp;
        }

        $cps = array_unique( $cps );

        return view('admin.plans.plan_form',[
            'plan' => $plan,
            'suburbs' => $suburbs,
            'cps' => $cps,
            'edit' => true,
            'index' => -1,
        ]);
    }

    //Extrae los valores para mostrarlos en el modal de nombre
    public function getInfoEdit($id){
        $user = Auth::user();
        $typeDependecies = $subareas = [];

        if( $user->hasRole("Super Administrador") ){
            $typeDependecies = TypeDependencies::whereNull('deleted_at')->with('dependencies')->get();
            $subareas = Subdirections::with('area')->whereNull('deleted_at')->get();
        } else {
            $area = Area::find( $user->id_area );
            $typeDependecies = TypeDependencies::where('_id', $area->id_type)->with('dependencies')->get();
            $subareas = Subdirections::whereNull('deleted_at')->where('id_dependency', $area->id)->get();
        }

        $plan = Plan::with(['subarea.area'])->find($id);
        $type_plans = ["Programa", "Proyecto", "Acción", "Trámite", "Incidente", "Otros"];
        
        return view('admin.plans._edit_plan', [
            'typeDependencies' => $typeDependecies,
            'subareas' => $subareas,
            'plan' => $plan,
            'type_plans' => $type_plans,
        ]);
    }

    // Actualiza los valores como nombre y subarea
    public function updateBasicInfo(Request $request, $id){

        $request->validate([
            'id_area' => 'required|string|min:1',
            'id_subarea' => 'required|string|min:1',
            'name' => 'required|string|min:1',
            'type_plan' => 'required|string|min:5',
            'plan_color' => 'required|string|min:1',
        ]);

        $plan_info = $request->all();
        $plan_info['color'] = $plan_info['plan_color'];
        unset( $plan_info['plan_color'] );

        $plan = Plan::find( $id );
        $plan->update( $plan_info );

        return response()->json([ 'success' => true, ]);
    }

    // Abre el modal para confirmar si se elimina la planeación
    public function confirmDelete($id){
        $plan = Plan::find($id);

        return view('admin.plans._deleting_warning', [
            'plan' => $plan,
        ]);
    }

    // Elimina la planeación seleccionada
    public function deleteForm($id) {
        $plan = Plan::find($id);
        $name = $plan->name;
        $maps = Map::where('plans', $id)->get();
        $updatedMaps = 0;
        $deletedMaps = 0;

        foreach ($maps as $map) {
            $plans = $map->plans;            
            unset( $plans[ array_search( $id, $plans ) ] );

            if( empty( $plans ) ){
                $map->delete();
                $deletedMaps++;
            } else {
                $map->plans = $plans;
                $map->save();
                $updatedMaps++;
            }
        }

        $deletedRows = PlanRegister::where('id_plan', $id)->delete();

        $plan->delete();

        return response()->json([
            'success' => true,
            'name' => $name,
            'deletedRows' => $deletedRows,
            'updatedMaps' => $updatedMaps,
            'deletedMaps' => $deletedMaps,
        ]);
    }

    //Agrega un nuevo campo al formulario
    public function createFormField(Request $request, $id) {
        $nameField = $request->get('name_field');
        $typeData = $request->get('type_data');
        $options = $request->get('options');
        $size = $request->get('size');
        $index = $request->get('index');
        
        if( $nameField && $typeData ){
            $newField = [
                'title' => $nameField,
                'type' => $typeData,// == 'image' ? 'file' : $typeData,
                'size' => $size,
                'editable' => true,
            ];

            if (in_array($typeData, ["radio", "checkbox", "select"]) && $options) {
                $newField['options'] = explode("\n", $options);
            }

            if( $typeData == 'image' ){
                $newField['type_file'] = $typeData == 'image' ? 'image/*' : '';
            }

            $plan = Plan::find($id);
            $attributes = $plan->attributes ?? [];

            $index = $index >= 0 
                ? ( ($index + 1) == count($attributes) 
                    ? $index 
                    : $index + 1 ) 
                : ( $plan->geometry == 'LineString' 
                    ? count( $attributes ) - 2 
                    : count( $attributes ) - 1 );
            
            array_splice( $attributes, $index, 0, [$newField] );
            self::updatePlanRegister( $attributes, $plan->id, $index );

            $plan->attributes = $attributes; // Actualiza attributes
            $plan->save(); // Guarda el modelo

            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al intentar crear el elemento.',
        ]);
    }
    
    // Actualiza los datos de los registros
    private function updatePlanRegister ($newAttr, $id_plan, $index = 0){

        $rows = PlanRegister::where('id_plan',$id_plan)->get();
        $plan = Plan::find($id_plan);

        if( count( $rows ) ){
            $newKeys = $this->utils->createFieldKeys( $newAttr, "gen" );
            $oldKeys = $this->utils->createFieldKeys( $plan->attributes, "gen" );

            $lastIndex = (count($newKeys) - 1) == $index;

            foreach ($rows as $key => $row) {
                // $oldKeys = array_keys( $row->attributes );
                $newValues = [];
                $preVal = null;

                if( $lastIndex ){
                    $newValues = $row->attributes;
                    $newValues[ last( $newKeys ) ] = null;
                } else {

                    foreach ($newKeys as $key => $newKey) {
                        if( $key < $index ){
                            $newValues[ $newKey ] = $row->attributes[ $newKey ];
                        } else if( $key >= $index ) {
                            $newValues[ $newKey ] = $preVal;
                            $preVal = $row->attributes[ $oldKeys[ $key ] ?? $oldKeys[ $key - 1] ];
                        }
                    }

                }

                $row->attributes = $newValues;
                $row->save();
            }
        }
    }

    // Actualiza la información de un campo seleccionado
    public function updateFormField(Request $request, $id, $index){
        $nameField = $request->get('name_field');
        $typeData = $request->get('type_data');
        $options = $request->get('options');
        $size = $request->get('size');

        if( $nameField && $typeData ){
            $newField = [
                'title' => $nameField,
                'type' => $typeData,// == 'image' ? 'file' : $typeData,
                'size' => $size,
                $attributes[$index]['updated_at'] = Carbon::now()->toISOString(),
            ];

            if (in_array($typeData, ["radio", "checkbox", "select"]) && $options) {
                $newField['options'] = explode("\n", $options);
            }

            if( $typeData == 'image' ){
                $newField['type_file'] = $typeData == 'image' ? 'image/*' : '';
            }
            
            $plan = Plan::find($id);
            $attributes = $plan->attributes;
            $oldAttribute = $attributes[$index];
            $newField['editable'] = $oldAttribute['editable'];
            self::updateFieldRegister($plan->id, $attributes[$index], $newField, $index);

            $attributes[$index] = $newField; // Agrega el nuevo campo
            $plan->attributes = $attributes; // Actualiza attributes
            $plan->save(); // Guarda el modelo
    
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al intentar editar el elemento.',
        ]);
    }

    // AActualiza los registros cuando solo se cambia el nombre de un campo
    private function updateFieldRegister($id_plan, $oldField, $newField, $index ){
        $rows = PlanRegister::where('id_plan', $id_plan)->get();
        $oldName = $this->utils->createFieldKey($oldField, $index, "gen");
        $newName = $this->utils->createFieldKey($newField, $index, "gen");

        foreach ($rows as $row) {
            if( is_array( $row->attributes ) ){
                $oldValues = $row->attributes;
                $oldVal = $oldValues[$oldName];
                unset( $oldValues[$oldName] );
                $oldValues[$newName] = null;

                $oldValues[$newName] = $oldVal;

                $row->attributes = $oldValues;
                $row->save();
            }
        }
    }

    // Actualiza el orden de los campos del formulario
    public function updateFormFieldOrder(Request $request, $id){
        $plan = Plan::find($id);
        $rows = PlanRegister::where('id_plan', $id)->get();

        $newOrder = $request->get('fields');
        $oldAttrs = $plan->attributes;
        $newAttrs = $newKeys = $oldKeys = [];

        foreach ($newOrder as $key => $index) {
            $newAttrs[$key] = $oldAttrs[$index]; 

            if( is_array($oldAttrs[ $index ]) ){
                $oldKeys[] = $this->utils->createFieldKey( $oldAttrs[$index], $index, "gen" );
                $newKeys[] = $this->utils->createFieldKey( $oldAttrs[$index], $key, "gen" );
            }
        }

        foreach ($rows as $key => $row) {
            $oldValues = $row->attributes;
            $newValues = [];
            
            foreach ($newKeys as $index => $newKey) {

                if( is_array($oldValues) ){
                    $newValues[ $newKey] = $oldValues[ $oldKeys[ $index ] ] ?? null;
                }
            }

            $row->attributes = $newValues;
            $row->save();
        }

        $plan->attributes = $newAttrs;
        $plan->save();

        return response()->json([
            'success' => true,
        ]);

    }

    // Elimina un campo del formulario
    public function deleteFormField($id, $index){
        $plan = Plan::find($id);
        $attributes = $plan->attributes;
        $attr = $attributes[$index];
     
        // Si el elemento es un arreglo se agrega el elemento 'deleted_at', pero si es una cadena de texto, se selimina de los attributos
        if( is_array( $attr ) ){
            $attributes[$index]['deleted_at'] = Carbon::now()->toISOString();
        } else {
            unset($attributes[$index]);
        }

        $plan->attributes = $attributes;
        $plan->save();

        return response()->json([
            'success' => true,
        ]);
    }

    // Retorna a la vista de la vista previa del formulario
    public function preview($id){
        $plan = Plan::find($id);
        $suburbs = Colonia::whereNull('deleted_at')->get();
        $cps = [];

        foreach ($suburbs as $suburb) {
            $cps[] = $suburb->cp;
        }

        $cps = array_unique( $cps );

        return view('admin.plans.preview', [
            'plan' => $plan,
            'cps' => $cps,
            'suburbs' => $suburbs,
            'edit' => false,
        ]);
    }

    // Realiza una validación de los datos en el formulario de vista previa (no almacena nada)
    public function previewValidateForm($id, Request $request) {
        $plan = Plan::find($id);
        $values = $request->all();
        $status = $message = null;
        $rules = $this->utils->createPlanRules( $plan->attributes, $plan, "gen" );
        $validator = $this->utils->validation( $rules, $values);

        if( !$validator->fails() ){
            $status = 200;
            $message = 'Los datos son correctos.';
        } else {
            $status = 422;
            $message = $validator->errors();
        }
        
        return response()->json([
            "message" => $message,
        ], $status);
    }

    // Realiza la validación de la clave catastral
    public function valitadateCatastralKey(Request $request){
        $request->validate([
            'catastralKey' => 'nullable|numeric'
        ]);

        $catastralKey = $request->get("catastralKey");

        if( $catastralKey )
            return $this->utils->valitadateCatastralKey( $request );
        else 
            return response('No se encontró una Clave Catastral para validar.', 422);
    }

}