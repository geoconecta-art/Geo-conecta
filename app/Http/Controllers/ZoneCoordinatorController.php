<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Models\Log;
use App\Models\Person;
use App\Models\PersonSection;
use App\Models\Region;
use App\Models\Section;
use App\Models\Suburb;
use App\Tools\Tools;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ZoneCoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas'])->only('index', 'edit', 'create');
        //$this->middleware(['can:estructura'])->only('create');
    }

    public function table(Request $request) { 
        $type = $request->get('type');

        $sections = ( $request->has('sections') ) ? array_filter(explode(',', $request->get('sections'))) : [];
        
        $person_id = ( $request->has('person_id') ) ? $request->get('person_id') : 0;
    
        $people = DB::table('people')
                    ->whereNull('people.deleted_at')
                    ->where('people.type', $type);

        if ( $person_id != 0 )
            $people = $people->where('people.person_id', $person_id);

        if ( count($sections) > 0 )
            $people = $people->whereIn('people.section', $sections);
        
        $people = $people
            ->selectRaw('people.*, date_format(people.created_at, "%d/%m/%Y %H:%i:%s") as f_created_at')
            ->get();

        $people = Tools::setOrder($people);

        foreach ( $people as $person ) {
            $person->name = Person::getFullName($person); 

            // Obtener las secciones
            $sections = Section::getByZone($person->region, $person->zone);
            $person->sections = implode(', ', $sections);
        }

        $people = Tools::setOrder($people);

        return DataTables::of($people)
            ->addColumn('actions', 'admin.people._actions')
            ->addColumn('image', 'admin.people._image')
            ->rawColumns([ 'actions', 'image'])
            ->make(true);
    }
    
    public function index()
    {
        $people = Person::getPeopleFullName(Person::COORD_REGIONAL);
        $sections = Section::all();

        return view('admin.zone-coordinators.index', [
            'people' => $people,
            'sections' => $sections,
            'title' => 'Coordinador de Zona' 
        ]);
    }

    public function create() {

        $people = Person::getPeopleByType(Person::COORD_ZONA); 
        $view = Person::getCreateViewByType(Person::COORD_ZONA);
        $zip_codes = Suburb::getZipCodes();
        $regions = Region::getRegions();

        foreach ( $regions as $region ) {
            $director = Person::where('type', Person::DIRECTOR)->where('region', $region->region)->first();
            $region->director = ( isset($director) ) ? $director->name : '';

            $r_coord = Person::where('type', Person::COORD_REGIONAL)->where('region', $region->region)->first();
            $region->r_coord = ( isset($r_coord) ) ? $r_coord->name : '';
            
            $zones = Section::getZonesByRegion($region->region);
            $region->zones = implode(', ', $zones);
        }

        return view($view, [ 
            'people' => $people,
            'zip_codes' => $zip_codes,
            'regions' => $regions
        ]);

    }

    public function store(PersonRequest $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::COORD_ZONA;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guardar su coordinador regional
        $coord = Person::where('type', Person::COORD_REGIONAL)->where('region', $person->region)->first();
        if ( isset($coord) ) {
            $person->person_id = $coord->id;
        }

        // Guarda sus secciones, según región y zona
        $sections = Section::getByZone($person->region, $person->zone);
        PeopleController::saveSections($person->id, $sections);

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        // Guardar el movimiento
        Log::saver(1, $person->id);

        // Actualiza el campo z_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);
        
        return redirect(route('z-coordinators.create'));
        //$redirect = Person::getIndexRouteByType(Person::COORD_ZONA);
        //return redirect($redirect);
        //return PeopleController::store($request, Person::COORD_ZONA);
    }

    public function storeByAdmin(Request $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::COORD_ZONA;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guardar su coordinador regional
        $coord = Person::where('type', Person::COORD_REGIONAL)->where('region', $person->region)->first();
        if ( isset($coord) ) {
            $person->person_id = $coord->id;
        }

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        // Guardar el movimiento
        Log::saver(1, $person->id);

        // Actualiza el campo z_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);
        
        return redirect(route('z-coordinators.create'));
    }

    public function edit($id)
    {
        //$person = Person::findOrFail($id);
        $person = DB::table('people')
            ->where('id', $id)
            ->where('type', Person::COORD_ZONA)
            ->whereNull('deleted_at')
            ->first();

        if ( !isset($person) )
            return abort(404);

        //$all_sections = PersonSection::getSectionsArray($person->person_id);
        //$sections = PersonSection::getSectionsArray($person->id);
        $suburbs = Suburb::getByZipCode($person->zip_code);
        $zip_codes = Suburb::getZipCodes();

        //$people = Person::getPeopleByType($person->type); 
        $view = Person::getEditViewByType($person->type);

        $regions = Region::getRegions();

        foreach ( $regions as $region ) {
            $director = Person::where('type', Person::DIRECTOR)->where('region', $region->region)->first();
            $region->director = ( isset($director) ) ? $director->name : '';

            $r_coord = Person::where('type', Person::COORD_REGIONAL)->where('region', $region->region)->first();
            $region->r_coord = ( isset($r_coord) ) ? $r_coord->name : '';
            
            $zones = Section::getZonesByRegion($region->region);
            $region->zones = implode(',', $zones);
        }

        return view($view, [
            'person' => $person,
            //'all_sections' => $all_sections,
            //'sections' => $sections,
            //'people' => $people,
            'suburbs' => $suburbs,
            'zip_codes' => $zip_codes,
            'regions' => $regions 
        ]);
    }

    public function update(PersonRequest $request, $id) {

        $person = Person::findOrFail($id);
        $person->fill($request->all());
        $person->save();
        $person->setName();
        $person->setAddress();

        //$sections = $request->get('sections');
        //PeopleController::saveSections($person->id, $sections);

        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(2, $person->id);

        //Actualiza el campo z_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        //Actualiza el campo z_coordinator en los registros de la tabla 'people'
        self::updateStructure($person);

        $redirect = Person::getIndexRouteByType($person->type);
        
        return redirect($redirect);
    }

    public static function updateCoordinator($coord) {
        $name = Person::getName($coord->id);

        DB::table('people')
            ->where('type', '>', Person::COORD_ZONA)
            ->where('region', $coord->region)
            ->where('zone', $coord->zone)
            ->update([ 'z_coordinator' => $name ]);
    }

    public static function updateStructure($person) {
        $person->r_coordinator = Person::getRegionCoordName($person->region);
        $person->director = Person::getDirectorName($person->region);
        $person->dependence = Person::getDependence($person->region);
        $person->save();
    }


}
