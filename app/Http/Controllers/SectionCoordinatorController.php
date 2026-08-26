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

class SectionCoordinatorController extends Controller
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
            $person->name = Person::getFullName($person); //$person->last_name_1 . ' ' . $person->last_name_2 . ' ' . $person->first_name;
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
        $people = Person::getPeopleFullName(Person::COORD_ZONA);
        $sections = Section::all();

        return view('admin.section-coordinators.index', [
            'people' => $people,
            'sections' => $sections,
            'title' => 'Coordinador Seccional' 
        ]);
    }
    
    public function create() {
        
        $view = Person::getCreateViewByType(Person::COORD_SECCION);
        $zip_codes = Suburb::getZipCodes();
        $sections = Section::getActiveSections();
        
        return view($view, [ 
            'zip_codes' => $zip_codes,
            'sections' => $sections,
        ]);
        
    }

    public function store(PersonRequest $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::COORD_SECCION;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Obtiene la sección
        $r_section = $request->get('section');
        $section = Section::getBySection($r_section);

        // Guardar su coordinador de zona
        $coord = Person::where('type', Person::COORD_ZONA)
            ->where('region', $section->region)
            ->where('zone', $section->zone)
            ->first();

        if ( isset($coord) ) {
            $person->person_id = $coord->id;
        }

        // Guarda su sección
        $sections = [ $r_section ];
        PeopleController::saveSections($person->id, $sections);

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(1, $person->id);

        //Actualiza el campo s_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);

        //$redirect = Person::getIndexRouteByType(Person::COORD_SECCION);
        //return redirect($redirect);
        //return PeopleController::store($request, Person::COORD_SECCION);

        return redirect(route('s-coordinators.create'));
    }

    public function storeByAdmin(Request $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::COORD_SECCION;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Obtiene la sección
        $r_section = $request->get('section');
        $section = Section::getBySection($r_section);

        // Guardar su coordinador de zona
        $coord = Person::where('type', Person::COORD_ZONA)
            ->where('region', $section->region)
            ->where('zone', $section->zone)
            ->first();

        if ( isset($coord) ) {
            $person->person_id = $coord->id;
        }

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(1, $person->id);

        //Actualiza el campo s_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);

        return redirect(route('s-coordinators.create'));
    }

    public function edit($id)
    {
        //$person = Person::findOrFail($id);
        
        $person = DB::table('people')
            ->where('id', $id)
            ->where('type', Person::COORD_SECCION)
            ->whereNull('deleted_at')
            ->first();

        if ( !isset($person) )
            return abort(404);
        
        
        //$all_sections = PersonSection::getSectionsArray($person->person_id);
        //$sections = PersonSection::getSectionsArray($person->id);
        $suburbs = Suburb::getByZipCode($person->zip_code);
        $zip_codes = Suburb::getZipCodes();
        $sections = Section::getActiveSections();

        //$people = Person::getPeopleByType($person->type); 
        $view = Person::getEditViewByType($person->type);

        return view($view, [
            'person' => $person,
            //'all_sections' => $all_sections,
            'sections' => $sections,
            //'people' => $people,
            'suburbs' => $suburbs,
            'zip_codes' => $zip_codes
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

        //Actualiza el campo s_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);

        $redirect = Person::getIndexRouteByType($person->type);
        
        return redirect($redirect);
    }

    public function getSectionInfo(Request $request) {
        
        $r_section = $request->get('section');

        $section = Section::getBySection($r_section);
        $region = Region::getByRegion($section->region);
        
        $section->dependence = $region->dependence;

        $director = Person::where('type', Person::DIRECTOR)->where('region', $section->region)->first();
        $section->director = ( isset($director) ) ? $director->name : '';

        $r_coord = Person::where('type', Person::COORD_REGIONAL)->where('region', $section->region)->first();
        $section->r_coord = ( isset($r_coord) ) ? $r_coord->name : '';
        
        $z_coord = Person::where('type', Person::COORD_ZONA)
            ->where('region', $section->region)
            ->where('zone', $section->zone)
            ->first();

        $section->z_coord = ( isset($z_coord) ) ? $z_coord->name : '';
        
        return view('admin.section-coordinators._section-info', [ 
            'section' => $section
        ]);
     
    }

    public static function updateCoordinator($coord) {
        
        $name = Person::getName($coord->id);

        $people = DB::table('people')
            ->where('type', Person::MOVILIZADOR)
            ->where('person_id', $coord->id)
            ->get();

        foreach ( $people as $person ) {
            
            $person->s_coordinator = $name;
            $person->save();

            // Actualizar promovidos
            DB::table('people')
                ->where('type', Person::PROMOVIDO)
                ->where('person_id', $person->id)
                ->update([ 's_coordinator' => $name ]);

        }
            
    }

    public static function updateStructure($person) {
        $person->z_coordinator = Person::getZoneCoordName($person->region, $person->zone);
        $person->r_coordinator = Person::getRegionCoordName($person->region);
        $person->director = Person::getDirectorName($person->region);
        $person->dependence = Person::getDependence($person->region);
        $person->save();
    }

}
