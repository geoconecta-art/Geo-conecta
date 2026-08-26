<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Models\Block;
use App\Models\Log;
use App\Models\Person;
use App\Models\PersonSection;
use App\Models\Region;
use App\Models\Section;
use App\Models\Suburb;
use App\Models\User;
use App\Tools\Tools;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MobilizerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas'])->only('index', 'edit');
        $this->middleware(['can:estructura'])->only('create');
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

        foreach ( $people as $person ) {
            $person->name = Person::getFullName($person); 
        }

        $people = Tools::setOrder($people);

        return DataTables::of($people)
            ->addColumn('actions', 'admin.people._actions')
            ->addColumn('image', 'admin.people._image')
            //->addColumn('light', 'admin.mobilizers._status')
            ->rawColumns([ 'actions', 'image' ])
            ->make(true);
    }

    public function index()
    {
        $people = Person::getPeopleFullName(Person::COORD_SECCION);
        $sections = Section::all();

        return view('admin.mobilizers.index', [
            'people' => $people,
            'sections' => $sections,
            'title' => 'Promotor' 
        ]);
    }

    public function create() {

        $view = Person::getCreateViewByType(Person::MOVILIZADOR);
        $zip_codes = Suburb::getZipCodes();
        $sections = Section::getActiveSections();

        return view($view, [ 
            'zip_codes' => $zip_codes,
            'sections' => $sections
        ]);

    }

    public function store(PersonRequest $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::MOVILIZADOR;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guarda su sección
        $section = $request->get('section');
        $sections = [ $section ];
        PeopleController::saveSections($person->id, $sections);

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(1, $person->id);

        //Actualiza el campo mobilizer en los registros de la tabla 'people'
        self::updateMobilizer($person);

        // Actualiza la estructura
        self::updateStructure($person);

        return redirect(route('mobilizers.create'));
    }

    public function storeByAdmin(Request $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::MOVILIZADOR;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guarda su sección
        $section = $request->get('section');
        $sections = [ $section ];
        PeopleController::saveSections($person->id, $sections);

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(1, $person->id);

        //Actualiza el campo mobilizer en los registros de la tabla 'people'
        self::updateMobilizer($person);

        // Actualiza la estructura
        self::updateStructure($person);

        return redirect(route('mobilizers.create'));
    }

    public function edit($id)
    {

        $person = DB::table('people')
            ->where('id', $id)
            ->where('type', Person::MOVILIZADOR)
            ->whereNull('deleted_at')
            ->first();

        if ( !isset($person) )
            return abort(404);

        $suburbs = Suburb::getByZipCode($person->zip_code);
        $zip_codes = Suburb::getZipCodes();
        $sections = Section::getActiveSections();

        $view = Person::getEditViewByType($person->type);

        $promoted = Person::where('type', Person::PROMOVIDO)->where('person_id', $person->id)->count();
        $edit = ( $promoted == 0 );

        return view($view, [
            'person' => $person,
            'sections' => $sections,
            'suburbs' => $suburbs,
            'zip_codes' => $zip_codes,
            'edit' => $edit
        ]);
    }

    public function update(Request $request, $id) {

        $person = Person::findOrFail($id);
        $person->fill($request->all());
        $person->save();
        $person->setName();
        $person->setAddress();

        PeopleController::saveImage($request, $person);
        PeopleController::saveINE($request, $person);

        Log::saver(2, $person->id);

        //Actualiza el campo mobilizer en los registros de la tabla 'people'
        self::updateMobilizer($person);

        // Actualiza la estructura
        self::updateStructure($person);

        $redirect = Person::getIndexRouteByType($person->type);
        
        return redirect($redirect);
    }

    public function validateBySection(Request $request) {

        if ( Auth::user()->hasRole('Administrador') ) {
            return response()->json(array(
                'success' => true,
                'message' => 'Usuario Administrador'
            ));
        }
        
        $r_section = $request->get('section');
        $section = Section::getBySection($r_section);

        $mob_number = Person::where('section', $r_section)->where('type', Person::MOVILIZADOR)->count();

        if ( $mob_number < $section->mob_limit ) {
            return response()->json(array(
                'success' => true,
            ));
        }

        return response()->json(array(
            'success' => false,
            'error' => 'No es posible registrar el promotor. El número de promotores excede la lista nominal de la sección' 
        ));
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

        $people = Person::where('type', Person::COORD_SECCION)
            ->where('section', $section->section)
            ->get();

        $microregions = Block::getMicroregionsBySection($section->section);

        return view('admin.mobilizers._section-info', [ 
            'section' => $section,
            'people' => $people,
            'microregions' => $microregions
        ]);
     
    }

    public static function updateStructure($person) {
        $s_coordinator = Person::find($person->person_id);
        $person->s_coordinator = isset($s_coordinator) ? Person::getName($s_coordinator->id) : '';
        $person->z_coordinator = Person::getZoneCoordName($person->region, $person->zone);
        $person->r_coordinator = Person::getRegionCoordName($person->region);
        $person->director = Person::getDirectorName($person->region);
        $person->dependence = Person::getDependence($person->region);
        $person->save();
    }

    public function destroy($id) {

        $person = Person::find($id);

        $promoted = Person::where('type', Person::PROMOVIDO)->where('person_id', $person->id)->count();

        if ( $promoted == 0 and $person->delete() ) {

            Log::saver(3, $person->id);

            return response()->json(array(
                'success' => true,
                'message' => 'El Promotor ha sido eliminado correctamente'
            ));
        }

        return response()->json(array(
            'success' => false,
            'error' => 'No es posible eliminar al Promotor ya que cuenta con Promovidos registrados'
        ));
    }

    public static function updateMobilizer($mob) {
        
        $name = Person::getName($mob->id);

        // Actualizar promovidos
        DB::table('people')
            ->where('type', Person::PROMOVIDO)
            ->where('person_id', $mob->id)
            ->update([ 'mobilizer' => $name ]);

    }



}
