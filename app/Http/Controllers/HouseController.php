<?php

namespace App\Http\Controllers;

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

class HouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas'])->only('index', 'edit');
        $this->middleware(['can:estructura'])->only('create');
    }

    public function table(Request $request) {
        
        $type = Person::CASAD;

        $people = DB::table('people')
                    ->whereNull('people.deleted_at')
                    ->where('people.type', $type)
                    ->selectRaw('people.*, date_format(people.created_at, "%d/%m/%Y %H:%i:%s") as f_created_at')
                    ->get();

        foreach ( $people as $person ) {
            $person->name = Person::getFullName($person); 
            $person->f_training = ( $person->training ) ? 'SI' : 'NO';
        }

        $people = Tools::setOrder($people);

        return DataTables::of($people)
            ->addColumn('actions', 'admin.houses._actions')
            ->addColumn('image', 'admin.people._image')
            ->rawColumns([ 'actions', 'image' ])
            ->make(true);
    }

    public function index()
    {
        return view('admin.houses.index', [
            'title' => 'Casa Día D' 
        ]);
    }

    public function create() {

        $view = Person::getCreateViewByType(Person::CASAD);
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
        $person->type = Person::CASAD;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guarda la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);

        Log::saver(1, $person->id);

        return redirect(route('houses.create'));
    }

    public function storeByAdmin(Request $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::CASAD;
        $person->save();
        $person->setName();
        $person->setAddress();

        // Guardar la imagen
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);

        Log::saver(1, $person->id);

        return redirect(route('houses.create'));
    }

    public function edit($id)
    {

        $person = DB::table('people')
            ->where('id', $id)
            ->where('type', Person::CASAD)
            ->whereNull('deleted_at')
            ->first();

        if ( !isset($person) )
            return abort(404);

        $suburbs = Suburb::getByZipCode($person->zip_code);
        $zip_codes = Suburb::getZipCodes();
        $sections = Section::getActiveSections();

        $view = Person::getEditViewByType($person->type);

        return view($view, [
            'person' => $person,
            'sections' => $sections,
            'suburbs' => $suburbs,
            'zip_codes' => $zip_codes,
        ]);
    }

    public function update(Request $request, $id) {

        $person = Person::findOrFail($id);
        $person->fill($request->all());
        $person->save();
        $person->setName();
        $person->setAddress();

        PeopleController::saveImage($request, $person);

        Log::saver(2, $person->id);

        $redirect = Person::getIndexRouteByType($person->type);
        
        return redirect($redirect);
    }

    public function destroy($id) {

        $person = Person::find($id);
        $person->delete();

        Log::saver(3, $person->id);

        return response()->json(array(
            'success' => true,
            'message' => 'La casa ha sido eliminada correctamente'
        ));

    }

    public function getSectionInfo(Request $request) {
        
        $r_section = $request->get('section');
        $section = Section::getBySection($r_section);
        
        return view('admin.houses._section-info', [ 
            'section' => $section
        ]);
     
    }
   

}
