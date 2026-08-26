<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotedRequest;
use App\Models\CorporationPerson;
use App\Http\Requests\PersonRequest;
use App\Models\Block;
use App\Models\Corporation;
use App\Models\Log;
use App\Models\Person;
use App\Models\Region;
use App\Models\Section;
use App\Models\Suburb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

ini_set('max_execution_time', 300);

class PromotedController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas'])->only('index', 'edit');
        $this->middleware(['can:promovidos'])->only('create');
    }

    public function table(Request $request)
    {

        $type = $request->get('type');

        $regions = ($request->has('regions')) ? array_filter(explode(',', $request->get('regions'))) : [];

        $sections = ($request->has('sections')) ? array_filter(explode(',', $request->get('sections'))) : [];

        $person_id = ($request->has('person_id')) ? $request->get('person_id') : 0;

        $words = ($request->has('words')) ? $request->get('words') : '';

        $people = DB::table('people')
            ->whereNull('deleted_at')
            ->where('type', $type);

        if ($words != '') {
            $people = $people->where('name', 'like', '%' . $words . '%');
        }

        if ($person_id != 0) {
            $people = $people->where('people.person_id', $person_id);
        }

        if (count($regions) > 0) {
            $people = $people->whereIn('region', $regions);
        }

        if (count($sections) > 0) {
            $people = $people->whereIn('section', $sections);
        }

        if ($request->has('dates')) {
            $range = array_map('trim', explode('_', $request->get('dates')));
            $start_date = $range[0] . ' 00:00:00';
            $end_date = $range[1] . ' 23:59:59';

            $people = $people->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);
        }

        $people = $people
            ->select('id', 'name', 'mobile', 'address', 'region', 'zone', 'section', 'block', 'mr', 'vote', 'type', 'created_at', 'mobilizer')
            ->get();

        return DataTables::of($people)
            ->make(true);
    }

    public function index()
    {
        $people = Person::whereIn('type', [Person::COORD_REGIONAL, Person::COORD_ZONA, Person::COORD_SECCION, Person::MOVILIZADOR])
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        foreach ($people as $p) {
            $p->name = Person::getFullName($p);
        }

        $regions = Region::getRegions();

        $sections = Section::getActiveSections();

        return view('admin.promoted.index', [
            'people' => $people,
            'regions' => $regions,
            'sections' => $sections,
            'title' => 'Promovido',
        ]);
    }

    public function create()
    {

        $view = Person::getCreateViewByType(Person::PROMOVIDO);
        $zip_codes = Suburb::getZipCodes();

        return view($view, [
            'zip_codes' => $zip_codes,
        ]);
    }

    public function createWOC()
    {

        $view = 'admin.promoted.createWOC';
        $zip_codes = Suburb::getZipCodes();

        return view($view, [
            'zip_codes' => $zip_codes,
        ]);
    }

    public function store(PromotedRequest $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::PROMOVIDO;
        $person->save();
        $person->setName();
        $person->setAddress();
        $person->setCount();

        // Guarda su sección
        $section = $request->get('section');
        $sections = [$section];
        PeopleController::saveSections($person->id, $sections);

        // Guardar las imágenes
        mkdir('people/' . $person->id, 0777, true);
        //PeopleController::saveINE($request, $person);

        Log::saver(Log::STORE_PERSON, $person->id);

        // Actualiza la estructura
        self::updateStructure($person);

        if ($request->type == 'sc') {
            return redirect(route('promoted.createWOC'));
        } else {
            return redirect(route('promoted.create'));
        }

    }

    public function storeByAdmin(Request $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = Person::PROMOVIDO;
        $person->save();
        $person->setName();
        $person->setAddress();
        $person->setCount();

        // Guarda su sección
        $section = $request->get('section');
        $sections = [$section];
        PeopleController::saveSections($person->id, $sections);

        // Guardar las imágenes
        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveINE($request, $person);

        Log::saver(Log::STORE_PERSON, $person->id);

        // Actualiza la estructura
        self::updateStructure($person);

        if ($request->type == 'sc') {
            return redirect(route('promoted.createWOC'));
        } else {
            return redirect(route('promoted.create'));
        }

    }

    public function validateByBlock(Request $request)
    {

        $r_section = $request->get('section');
        $r_block = ($request->has('block')) ? $request->get('block') : null;

        if ($request->has('block')) {

            $r_block = $request->get('block');

            $block = Block::where('block', $r_block)->where('section', $r_section)->first();
            $promoted_number = Person::where('block', $r_block)
                ->where('section', $r_section)
                ->where('type', Person::PROMOVIDO)
                ->count();

            if ($promoted_number < $block->nl) {
                return response()->json(array(
                    'success' => true,
                ));
            }

            return response()->json(array(
                'success' => false,
                'error' => 'No es posible registrar el promovido. El número de promovidos excede la lista nominal de la manzana electoral',
            ));
        }

        return response()->json(array(
            'success' => true,
        ));

    }

    public function edit($id)
    {
        $person = DB::table('people')
            ->where('id', $id)
            ->where('type', Person::PROMOVIDO)
            ->whereNull('deleted_at')
            ->first();

        if (!isset($person)) {
            return abort(404);
        }

        $person = Person::findOrFail($id);
        $zip_codes = Suburb::getZipCodes();
        $view = Person::getEditViewByType($person->type);
        $suburbs = Suburb::getByZipCode($person->zip_code);

        return view($view, [
            'person' => $person,
            'zip_codes' => $zip_codes,
            'suburbs' => $suburbs,
        ]);
    }

    public function procesarLote(&$response, &$contador, $batch, $promoted)
    {
        //$contador = 0;

        foreach ($promoted as $person) {
            $ine = $person->ine;
            if ($ine) {
                foreach ($batch as $columns) {
                    $cve = trim($columns[1]);
                    if ($ine == $cve) {
                        $sectionNew = trim($columns[15]);
                        $sectionOld = trim($person->section);
                        if ($sectionNew !== $sectionOld) {
                            $section = Section::getBySection($sectionNew);
                            if ($section) {
                                $personOriginal = clone $person;
                                //$response[] = $personOriginal;

                                $personNew = Person::findOrFail($person->id);

                                // Modificar campos
                                $personNew->section = $section->section;
                                $personNew->region = $section->region;
                                $personNew->zone = $section->zone;
                                $personNew->dependence = $section->dependence;

                                $personNew->street = str_replace('√ë', 'Ñ', trim($columns[7]));
                                $personNew->ext_number = trim($columns[9]);
                                $personNew->int_number = trim($columns[8]);
                                $personNew->lote = trim($columns[16]);
                                $personNew->mza = trim($columns[17]);

                                $cpOld = trim($personOriginal->zip_code);
                                $cpNew = trim($columns[11]);

                                if ($cpNew !== $cpOld) {
                                    //$response[] = $personOriginal;
                                    $colonias = Suburb::getByZipCode($cpNew);
                                    //$response[] = $colonias;
                                    $coloniaLN = strtoupper(str_replace('√ë', 'Ñ', trim($columns[10])));
                                    //$response[] = "El CP es diferente, la colonia en LN es $coloniaLN";

                                    foreach ($colonias as $colonia) {
                                        $coloniaActual = strtoupper(trim($colonia->name));

                                        similar_text($coloniaLN, $coloniaActual, $percent);
                                        if ($percent >= 80 || $coloniaLN == $coloniaActual || strpos($coloniaLN, $coloniaActual) !== false || strpos($coloniaActual, $coloniaLN) !== false) {
                                            //$response[] = "hay una colonia coincidente";
                                            $personNew->zip_code = $cpNew;
                                            $personNew->suburb_id = $colonia->id;
                                            $personNew->suburb = str_replace('√ë', 'Ñ', trim($columns[10]));
                                        }
                                    }
                                    //$response[] = $personNew;

                                }

                                $personNew->save();
                                $personNew->setAddress();

                                //$response[] = $personNew;
                                //$response[] = $section;
                                $contador++;
                            }
                        }
                    }
                }
            }
        }
        //$response[] = "Contador en este lote: $contador";
    }

    public function procesarArchivoCSV($path, $callback, $batchSize = 100000)
    {
        if (($gestor = fopen($path, 'r')) !== false) {
            $header = fgetcsv($gestor, 30000, ','); // Leer el encabezado
            $batch = [];
            $row = 0;

            while (($columns = fgetcsv($gestor, 30000, ',')) !== false) {
                $batch[] = $columns;
                $row++;

                if ($row % $batchSize == 0) {
                    $callback($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $callback($batch);
            }

            fclose($gestor);
        }
    }

    public function updatePromotedSectionsSearch(Request $request)
    {

        $type = $request->get('type');

        $regions = ($request->has('regions')) ? array_filter(explode(',', $request->get('regions'))) : [];

        $sections = ($request->has('sections')) ? array_filter(explode(',', $request->get('sections'))) : [];

        $person_id = ($request->has('person_id')) ? $request->get('person_id') : 0;

        $words = ($request->has('words')) ? $request->get('words') : '';

        $path = 'csv/ln.csv';

        $response = [];

        $contador = 0;

        $people = DB::table('people')
            ->whereNull('deleted_at')
            ->where('type', $type);

        if ($words != '') {
            $people = $people->where('name', 'like', '%' . $words . '%');
        }

        if ($person_id != 0) {
            $people = $people->where('people.person_id', $person_id);
        }

        if (count($regions) > 0) {
            $people = $people->whereIn('region', $regions);
        }

        if (count($sections) > 0) {
            $people = $people->whereIn('section', $sections);
        }

        if ($request->has('dates')) {
            $range = array_map('trim', explode('_', $request->get('dates')));
            $start_date = $range[0] . ' 00:00:00';
            $end_date = $range[1] . ' 23:59:59';

            $people = $people->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);
        }

        $people = $people
            ->get();

        $this->procesarArchivoCSV($path, function ($csvBatch) use ($people, &$response, &$contador) {
            $this->procesarLote($response, $contador, $csvBatch, $people);
        });

        if ($contador > 0)
            return response()->json(['message' => 'Se editaron ' . $contador . ' registros']); //json_encode($response); 
        else   
            return response()->json(['message' => 'Todos los registros estan en orden']);
    }

    public function updatePromotedSections()
    {
        $people = Person::whereIn('type', [Person::COORD_REGIONAL, Person::COORD_ZONA, Person::COORD_SECCION, Person::MOVILIZADOR])
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        foreach ($people as $p) {
            $p->name = Person::getFullName($p);
        }

        $regions = Region::getRegions();

        $sections = Section::getActiveSections();

        return view('admin.promoted.updatePromotedSections', [
            'people' => $people,
            'regions' => $regions,
            'sections' => $sections,
            'title' => 'Actualizar Secciones de Promovido por ln',
        ]);
    }
    
    public function update(Request $request, $id) {
        $response = [];
       
        $person = Person::findOrFail($id);
        $person->fill($request->all());
        $person->save();
        $person->setName();
        $person->setAddress();
        $person->setCount();

        Log::saver(Log::UPDATE_PERSON, $person->id);

        // Actualiza la estructura
        self::updateStructure($person);

        $response[] = $person;

        return $response;
    }
     

    public function getSectionInfo(Request $request)
    {
        $r_section = $request->get('section');
        $section = Section::getBySection($r_section);
        $blocks = Block::getBySection($r_section);

        return response()->json(array(
            'section' => $section,
            'blocks' => $blocks,
        ));
    }

    public function getFormByVote(Request $request)
    {
        $vote = $request->get('vote');

        switch ($vote) {
            case 'VP':
            case 'VA':
                $types = [Person::COORD_REGIONAL, Person::COORD_ZONA, Person::COORD_SECCION, Person::MOVILIZADOR, Person::VOLUNTARIO];
                $people = Person::whereIn('type', $types)
                    ->orderBy('last_name_1')
                    ->orderBy('last_name_2')
                    ->orderBy('first_name')
                    ->get();

                foreach ($people as $p) {
                    $p->name = Person::getFullName($p);
                    //$p->promotedNumber = Person::calculatePromotedNumber($p->id);
                }

                $sections = Section::getActiveSections();

                return view('admin.promoted._vp-form', [
                    'people' => $people,
                    'sections' => $sections,
                ]);
                break;

            case 'VC':
            case 'VAE':
                $sections = Section::getActiveSections();
                $corporations = Corporation::all();

                return view('admin.promoted._vc-form', [
                    'corporations' => $corporations,
                    'sections' => $sections,
                ]);
                break;
        }

        return '';
    }

    public function getPromotedNumber(Request $request)
    {
        $id = $request->get('id');

        $person = Person::find($id);

        $promotedNumber = Person::calculatePromotedNumber($id);

        // Obtiene los promovidos cada semana
        $countPromotedWeeks = Person::getPersonsPromotedPerWeeks($person);

        return response()->json(array(
            'promotedNumber' => $promotedNumber,
            'week1' => $countPromotedWeeks[0],
            'week2' => $countPromotedWeeks[1],
            'week3' => $countPromotedWeeks[2],
            'week4' => $countPromotedWeeks[3],
            'week5' => $countPromotedWeeks[4],
        ));
    }

    public function getStructure(Request $request)
    {
        $sections = Section::getActiveSections();

        return view('admin.promoted._vc-structure', [
            'sections' => $sections,
        ]);
    }

    public function updateStructure($person)
    {

        if ( is_null($person->person_id) ) {

            if ( is_null($person->corporation_person_id) ) {

                $person->mobilizer = '';
                $person->s_coordinator = '';

            } else {

                $mobilizer = CorporationPerson::find($person->corporation_person_id);
                if ( isset($mobilizer) )
                    $person->mobilizer = $mobilizer->name;

            }
            

        } else {

            $mobilizer = Person::find($person->person_id);
            $person->mobilizer = Person::getFullName($mobilizer);

            $s_coordinator = Person::find($mobilizer->person_id);
            $person->s_coordinator = isset($s_coordinator) ? Person::getFullName($s_coordinator) : '';
        }

        $person->z_coordinator = Person::getZoneCoordName($person->region, $person->zone);
        $person->r_coordinator = Person::getRegionCoordName($person->region);
        $person->director = Person::getDirectorName($person->region);
        $person->dependence = Person::getDependence($person->region);

        if ( !is_null($person->corporation_id) ) {
            $corporation = Corporation::find($person->corporation_id);

            if ( isset($corporation) ) {
                $person->corporation = $corporation->short_name;
            }
        }

        $person->save();
    }


    public function updateStructureVC() {
        $people = Person::where('type', Person::PROMOVIDO)
            ->where('vote', 'VC')
            ->whereNotNull('corporation_person_id')
            ->where('mobilizer', '')
            ->get();    

        foreach ( $people as $p ) {
            $mobilizer = CorporationPerson::find($person->corporation_person_id);
            if ( isset($mobilizer) )
                $person->mobilizer = $mobilizer->name;
        }


    }

}
