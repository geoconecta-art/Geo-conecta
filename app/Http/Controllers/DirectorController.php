<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Models\Log;
use App\Models\Person;
use App\Models\Region;
use App\Models\Section;
use App\Models\Suburb;
use App\Tools\Tools;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas']);
    }

    public function table(Request $request) { 
        $type = $request->get('type');

        $people = DB::table('people')
                    ->whereNull('people.deleted_at')
                    ->where('people.type', $type);

        $people = $people
            ->selectRaw('people.*, date_format(people.created_at, "%d/%m/%Y %H:%i:%s") as f_created_at')
            ->get();

        $people = Tools::setOrder($people);

        foreach ( $people as $person ) {
            $person->name = Person::getFullName($person); 

            // Obtener las secciones
            $sections = Section::getByRegion($person->region);
            $person->sections = implode(', ', $sections);

            $link = Person::where('person_id', $person->id)
                        ->where('type', Person::ENLACE)
                        ->first();

            $person->link_name = isset($link) ? Person::getFullName($link) : '';
            $person->link_phone = isset($phone) ? $link->phone : '';
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
        return view('admin.directors.index');
    }

    public function create() {

        $regions = Region::getRegions();
        
        return view('admin.directors.create', [
            'regions' => $regions
        ]);
    }

    public function store(PersonRequest $request)
    {
        $director = new Person();
        $director->fill($request->all());
        $director->type = Person::DIRECTOR;
        $director->save();
        $director->setName();

        mkdir('people/' . $director->id, 0777, true);
        PeopleController::saveImage($request, $director);
        
        $link = new Person();
        $link->first_name = $request->get('link_first_name');
        $link->last_name_1 = $request->get('link_last_name_1');
        $link->last_name_2 = $request->get('link_last_name_2');

        $link->phone = $request->get('link_phone');
        $link->type = Person::ENLACE;
        $link->person_id = $director->id;
        $link->save();
        $link->setName();

        Log::saver(1, $director->id);
        Log::saver(1, $link->id);

        //Actualiza el campo director en los registros de la tabla 'people'
        self::updateDirector($director);

        // Actualiza la estructura
        self::updateStructure($director);
        self::updateStructure($link);

        return redirect()->route('directors.index');
    }

    public function storeByAdmin(Request $request)
    {
        $director = new Person();
        $director->fill($request->all());
        $director->type = Person::DIRECTOR;
        $director->save();
        $director->setName();

        mkdir('people/' . $director->id, 0777, true);
        PeopleController::saveImage($request, $director);
        
        $link = new Person();
        $link->first_name = $request->get('link_first_name');
        $link->last_name_1 = $request->get('link_last_name_1');
        $link->last_name_2 = $request->get('link_last_name_2');

        $link->phone = $request->get('link_phone');
        $link->type = Person::ENLACE;
        $link->person_id = $director->id;
        $link->save();
        $link->setName();

        Log::saver(1, $director->id);
        Log::saver(1, $link->id);

        //Actualiza el campo director en los registros de la tabla 'people'
        self::updateDirector($director);

        // Actualiza la estructura
        self::updateStructure($director);
        self::updateStructure($link);

        return redirect()->route('directors.index');
    }

    public function edit($id)
    {
        $director = Person::findOrFail($id);

        $link = Person::where('person_id', $director->id)
                    ->where('type', Person::ENLACE)
                    ->first();
        
        $director->link_id = $link->id;
        $director->link_first_name = $link->first_name;
        $director->link_last_name_1 = $link->last_name_1;
        $director->link_last_name_2 = $link->last_name_2;
        $director->link_phone = $link->phone;

        //$suburbs = Suburb::all();
        $regions = Region::getRegions();

        return view('admin.directors.edit', [
            'person' => $director,
            'regions' => $regions
            //'suburbs' => $suburbs
        ]);
    }

    public function update(PersonRequest $request, $id) {

        $director = Person::findOrFail($id);
        $director->fill($request->all());
        $director->save();
        $director->setName();

        PeopleController::saveImage($request, $director);

        $link_id = $request->get('link_id');
        $link = Person::findOrFail($link_id);
        $link->first_name = $request->get('link_first_name');
        $link->last_name_1 = $request->get('link_last_name_1');
        $link->last_name_2 = $request->get('link_last_name_2');
        $link->save();
        $link->setName();

        Log::saver(2, $director->id);
        Log::saver(2, $link->id);

        //Actualiza el campo director en los registros de la tabla 'people'
        self::updateDirector($director);

        // Actualiza la estructura
        self::updateStructure($director);
        self::updateStructure($link);

        return redirect()->route('directors.index');

    }

    public function destroy($id)
    {
        
        return response()->json(array(
            'success' => true,
            'message' => 'El director ha sido eliminado correctamente'
        ));
    }

    public static function updateDirector($director) {

        $name = Person::getName($director->id);

        DB::table('people')
            ->where('type', '!=', Person::DIRECTOR)
            ->where('region', $director->region)
            ->update([ 'director' => $name ]);
    }

    public static function updateStructure($person) {
        $person->director = Person::getDirectorName($person->region);
        $person->dependence = Person::getDependence($person->region);
        $person->save();
    }

}
