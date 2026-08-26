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

class RegionCoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas']);
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
            $sections = Section::getByRegion($person->region);
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
        $people = Person::getPeopleFullName(Person::DIRECTOR); 
        $sections = Section::all();

        return view('admin.region-coordinators.index', [ 
            'people' => $people,
            'sections' => $sections,
            'title' => 'Coordinador Regional' 
        ]);
    }

    public function create() {

        $people = Person::getPeopleByType(Person::COORD_REGIONAL); 
        $view = Person::getCreateViewByType(Person::COORD_REGIONAL);
        $zip_codes = Suburb::getZipCodes();
        $regions = Region::getRegions();

        foreach ( $regions as $region ) {
            $director = Person::where('type', Person::DIRECTOR)->where('region', $region->region)->first();
            $region->director = ( isset($director) ) ? $director->name : '';
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
        $person->type = Person::COORD_REGIONAL;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guardar su director
        $director = Person::where('type', Person::DIRECTOR)->where('region', $person->region)->first();
        if ( isset($director) ) {
            $person->person_id = $director->id;
        }

        // Guarda sus secciones
        $sections = Section::getByRegion($person->region);
        PeopleController::saveSections($person->id, $sections);

        // Guardar la iamgen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(1, $person->id);

        // Actualiza el campo r_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);

        $redirect = Person::getIndexRouteByType(Person::COORD_REGIONAL);
        
        return redirect($redirect);

        //return PeopleController::store($request, Person::COORD_REGIONAL);
    }

    public function storeByAdmin(Request $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::COORD_REGIONAL;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guardar su director
        $director = Person::where('type', Person::DIRECTOR)->where('region', $person->region)->first();
        if ( isset($director) ) {
            $person->person_id = $director->id;
        }

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(1, $person->id);

        //Actualiza el campo r_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);

        $redirect = Person::getIndexRouteByType(Person::COORD_REGIONAL);
        
        return redirect($redirect);
    }

    public function edit($id)
    {
        $person = Person::findOrFail($id);
        //$sections = Section::where('active', 1)->get();
        //$p_sections = PersonSection::getSectionsArray($person->id);
        $suburbs = Suburb::getByZipCode($person->zip_code);
        $zip_codes = Suburb::getZipCodes();
        //$people = Person::getPeopleByType($person->type); 
        $view = Person::getEditViewByType($person->type);
        $regions = Region::getRegions();

        foreach ( $regions as $region ) {
            $director = Person::where('type', Person::DIRECTOR)->where('region', $region->region)->first();
            $region->director = ( isset($director) ) ? $director->name : '';
        }

        return view($view, [
            'person' => $person,
            //'sections' => $sections,
            //'p_sections' => $p_sections,
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

        //Actualiza el campo r_coordinator en los registros de la tabla 'people'
        self::updateCoordinator($person);

        // Actualiza la estructura
        self::updateStructure($person);

        $redirect = Person::getIndexRouteByType($person->type);
        
        return redirect($redirect);
    }

    public static function updateCoordinator($coord) {
        $name = Person::getName($coord->id);

        DB::table('people')
            ->where('type', '>', Person::COORD_REGIONAL)
            ->where('region', $coord->region)
            ->update([ 'r_coordinator' => $name ]);
    }

    public static function updateStructure($person) {
        $person->director = Person::getDirectorName($person->region);
        $person->dependence = Person::getDependence($person->region);
        $person->save();
    }

}
