<?php

namespace App\Http\Controllers;

use App\Imports\SupportsImport;
use App\Models\Person;
use App\Models\PersonSection;
use App\Models\Support;
use App\Models\SupportCatalog;
use App\Tools\Tools;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:beneficiados']);
    }
    
    public function index(Request $request)
    { 
        return view('admin.support.deliver.index', [
        ]);
    }

    public function table(Request $request) {      
        $types = Support::get();

        return DataTables::of($types)
            ->addColumn('image', 'admin.people._image')
            ->rawColumns([ 'image' ])
            ->make(true);

        //return response()->json(['data' => $types]);   
    }
    

    public function create() {
        
        $catalogs = SupportCatalog::get();
        
        return view('admin.support.deliver.create', [ 
            'catalogs' => $catalogs,
        ]);
    }

    public function store(Request $request)
    {
        $item = new Support();
        $item->name = $request->get('name');
        $item->slug = Tools::convertToSlug($item->name);
        $item->uuid = (string) Str::orderedUuid();
        $item->cost = $request->get('cost');
        $item->beneficiary_father_last_name = $request->get('beneficiary_father_last_name');
        $item->beneficiary_mother_last_name = $request->get('beneficiary_mother_last_name');
        $item->beneficiary_first_name = $request->get('beneficiary_first_name');
        $item->beneficiary_quantity = $request->has('beneficiary_quantity') ? $request->get('beneficiary_quantity') : 1;
        $item->save();  
        
        mkdir('img/supports/' . $item->id, 0777, true);
        self::saveImage($request, $item);

        return redirect()->route('support.delive.create')
            ->with('message', 'Apoyo registrado correctamente');
    }

    public static function saveImage(Request $request, Support &$item) {

        if ( $request->hasFile('image') ) {
            $file = $request->image;
            $new_name = Tools::saveImage($file, 'img/supports/' . $item->id . '/');
            $item->image = '/img/supports/' . $item->id . '/' . $new_name;
            $item->save();
        }
        else if ( $request->has('has_image') && $request->get('has_image') == 0 ) {
            $item->image = null;
            $item->save();
        }

    }

    public function fullStore(Request $request)
    {
        $sCatalog = SupportCatalog::find($request->support_catalog_id);

        $item = new Support();
        $item->name = $sCatalog->name;
        $item->slug = Tools::convertToSlug($item->name);
        $item->uuid = (string) Str::orderedUuid();
        $item->support_catalog_id = $sCatalog->id;
        $item->support_catalog_name = $sCatalog->name;
        $item->dependence_id = $sCatalog->dependence_id;
        $item->dependence_name = $sCatalog->dependence_name;
        $item->support_type_id = $sCatalog->support_type_id;
        $item->support_type_name = $sCatalog->support_type_name;
        $item->cost = $sCatalog->cost;
        $item->beneficiary_father_last_name = $request->get('beneficiary_father_last_name');
        $item->beneficiary_mother_last_name = $request->get('beneficiary_mother_last_name');
        $item->beneficiary_first_name = $request->get('beneficiary_first_name');
        $item->beneficiary_street = $request->get('beneficiary_street');
        $item->beneficiary_street_number = $request->get('beneficiary_street_number');
        $item->beneficiary_colony = $request->get('beneficiary_colony');
        $item->beneficiary_zipcode = $request->get('beneficiary_zipcode');
        $item->beneficiary_phone = $request->get('beneficiary_phone');
        $item->beneficiary_gender = $request->get('beneficiary_gender');
        $item->beneficiary_curp = $request->get('beneficiary_curp');
        $item->beneficiary_age = $request->get('beneficiary_age');
        $item->beneficiary_quantity = $request->has('beneficiary_quantity')? $request->get('beneficiary_quantity') : 1;
        $item->geo_lat = doubleval(trim($request->get('geo_lat')));
        $item->geo_lng = doubleval(trim($request->get('geo_lng')));
        $item->save();        

        return redirect()->route('support.delive.create')
        ->with('message', 'Apoyo registrado correctamente!');
    }

    
    public function edit($id)
    {
        $coordinator = Person::findOrFail($id);
        $all_sections = PersonSection::getSectionsArray($coordinator->person_id);
        $sections = PersonSection::getSectionsArray($coordinator->id);
        $persons = Person::where('type', Person::COORD_REGIONAL)->get();

        return view('admin.zone-coordinators.edit', [
            'coordinator' => $coordinator,
            'all_sections' => $all_sections,
            'sections' => $sections,
            'persons' => $persons
        ]);
    }

    public function update(Request $request, $id) {

       

        return redirect('/admin/coordinadores-zona');
    }


   

    public function destroy($id)
    {
        /*
        $user = User::findOrFail($id);
        $user->delete();
        */
        return response()->json(array(
            'success' => true,
            'message' => 'El usuario ha sido eliminado correctamente'
        ));
    }


    public function upload(Request $request){              

        return view('admin.support.upload.index', [
        ]);
    }

    public function uploadStore(Request $request){
        Log::info('----- SupportController - saved ------');
        ini_set('max_execution_time', 5000);
        ini_set('memory_limit', '8192M');
        Log::info('Fecha de carga: ' .  date('Y-m-d H:i:s') );

        $import = new SupportsImport();        
        
        if ($request->hasFile('supports')) {
            $objFile = $request->file('supports');
            Log::info('Se va a hacer la carga');
            Excel::import($import, $objFile);
        }else{
            Log::info('No trai supports');
        }
                        

        return redirect()->route('support.upload.index')
        ->with('message', 'Archivo cargado correctamente!');
    }



    public function map(Request $request){                  
        $applications = Support::get();
        
        $markers = [];
        
        foreach ( $applications as $application ) {
            $new_marker = [
                'lat' => (float)$application->geo_lat, 
                'lng' => (float)$application->geo_lng, 
                'title' => $application->name
            ];
            
            $markers[] = $new_marker;
        }
          
        return view('admin.applications.map', compact('markers'));        
    }

}
