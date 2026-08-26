<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MobilizerCoordinatorController;
use App\Http\Controllers\SectionCoordinatorController;
use App\Http\Controllers\ZoneCoordinatorController;
use App\Http\Controllers\RegionCoordinatorController;
use App\Http\Controllers\DirectorController;
use App\Http\Requests\PersonRequest;

use App\Models\SuburbSection;
use App\Models\Region;
use App\Models\Log;
use App\Models\Block;
use App\Models\Person;
use App\Models\PersonSection;
use App\Models\Section;
use App\Models\Suburb;
use App\Models\User;
use App\Tools\Tools;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeopleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSectionsOptions(Request $request) {
        $person_id = $request->get('person_id');
        $sections = PersonSection::getSectionsArray($person_id);

        return view('admin.people._sections-options', ['sections' => $sections]);
    }

    public static function saveImage(Request $request, Person &$person) {
        if ( $request->hasFile('image') ) {
            $file = $request->image;
            $new_name = Tools::saveImage($file, 'people/' . $person->id . '/');
            $person->image = '/people/' . $person->id . '/' . $new_name;
            $person->save();
        }
        else if ( $request->has('has_image') && $request->get('has_image') == 0 ) {
            $person->image = null;
            $person->save();
        }
    }

    public static function saveINE(Request $request, Person &$person) {

        if ( $request->hasFile('ine_image') ) {
            $file = $request->ine_image;
            $new_name = Tools::saveImage($file, 'people/' . $person->id . '/');
            $person->ine_image = '/people/' . $person->id . '/' . $new_name;
            $person->save();
        }
        else if ( $request->has('has_ine_image') && $request->get('has_ine_image') == 0 ) {
            $person->ine_image = null;
            $person->save();
        }

    }

    public static function saveSections($person_id, $sections) {

        // Inactivar las que no están en el array
        PersonSection::where('person_id', $person_id)
            ->whereNotIn('section', $sections)
            ->delete();

        foreach ( $sections as $section ) {

            $person_section = PersonSection::where('person_id', $person_id)
                ->where('section', $section)
                ->first();

            if ( !isset($person_section) ) {
                $person_section = new PersonSection();
                $person_section->person_id = $person_id;
                $person_section->section = $section;
                $person_section->save();
            }

        }


    }

    public function table(Request $request) {

        $type = $request->get('type');

        $sections = ( $request->has('sections') ) ? array_filter(explode(',', $request->get('sections'))) : [];
        
        $person_id = ( $request->has('person_id') ) ? $request->get('person_id') : 0;
    
        $people = Person::getPeople($type, $sections, $person_id);

        return DataTables::of($people)
            ->addColumn('actions', 'admin.people._actions')
            ->addColumn('image', 'admin.people._image')
            ->rawColumns([ 'actions', 'image'])
            //->rawColumns([ 'actions' ])
            ->make(true);
    }

    public static function create($type) {
        $people = Person::getPeopleByType($type); 
        $view = Person::getCreateViewByType($type);
        $suburbs = Suburb::all();
        $all_sections = [];
        $zip_codes = Suburb::getZipCodes();
        
        return view($view, [ 
            'people' => $people,
            'all_sections' => $all_sections,
            'suburbs' => $suburbs,
            'zip_codes' => $zip_codes
        ]);
    }

    public static function store(PersonRequest $request, $type)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->type = $type;
        $person->save();

        $sections = $request->get('sections');
        PeopleController::saveSections($person->id, $sections);

        mkdir('people/' . $person->id, 0777, true);
        PeopleController::saveImage($request, $person);

        $redirect = Person::getIndexRouteByType($type);
        
        return redirect($redirect);
    }

    public function edit($id)
    {
        $person = Person::findOrFail($id);
        $all_sections = PersonSection::getSectionsArray($person->person_id);
        $sections = PersonSection::getSectionsArray($person->id);
        $suburbs = Suburb::all();
        $zip_codes = Suburb::getZipCodes();

        $people = Person::getPeopleByType($person->type); 
        $view = Person::getEditViewByType($person->type);

        return view($view, [
            'person' => $person,
            'all_sections' => $all_sections,
            'sections' => $sections,
            'people' => $people,
            'suburbs' => $suburbs,
            'zip_codes' => $zip_codes
        ]);
    }

    /*
    public function update(PersonRequest $request, $id) {

        $person = Person::findOrFail($id);
        $person->fill($request->all());
        $person->save();

        $sections = $request->get('sections');
        PeopleController::saveSections($person->id, $sections);

        PeopleController::saveImage($request, $person);

        $redirect = Person::getIndexRouteByType($person->type);
        
        return redirect($redirect);
    }
    */

    public function getSectionInfo(Request $request) {
        
        $id = $request->get('id');
        $person = Person::find($id);
        $r_section = $request->get('section');
        $section = Section::getBySection($r_section);
        
        $people = Person::where('type', Person::COORD_SECCION)
            ->where('section', $section->section)
            ->get();

        $microregions = Block::getMicroregionsBySection($section->section);

        return view('admin.people._section-info', [ 
            'section' => $section,
            'people' => $people,
            'microregions' => $microregions
        ]);

    }

    public function getBlocksOptions(Request $request) {
        $section = $request->get('section');
        $blocks = Block::getBySection($section);

        return view('admin.people._blocks-options', [
            'blocks' => $blocks
        ]);
    }

    public function getSuburbsOptions(Request $request) {
        $zip_code = $request->get('zip_code');
        $suburbs = Suburb::getByZipCode($zip_code);

        return view('admin.people._suburbs-options', [
            'suburbs' => $suburbs
        ]);
    }

    public function editModal(Request $request) {
        $person_id = $request->get('person_id');
        $person = Person::find($person_id);

        return view('admin.people._edit', [
            'person' => $person
        ]);
    }

    public function getForm(Request $request) {
        
        $type = $request->get('type');
        $person_id = $request->get('person_id');
        $person = Person::find($person_id);

        switch ( $type ) {
            case Person::DIRECTOR:
            case Person::ENLACE:
            case Person::PEDROENTUCASA:
            case Person::FISCALIZADOR:
                $regions = Region::getRegions();

                return view('admin.people._director-form', [
                    'regions' => $regions,
                    'person' => $person
                ]);

                break;

            case Person::COORD_REGIONAL:
                $regions = Region::getRegions();

                return view('admin.people._r-coord-form', [
                    'regions' => $regions,
                    'person' => $person
                ]);

                break;

            case Person::COORD_ZONA:
                $regions = Region::getRegions();

                foreach ( $regions as $region ) {
                    $zones = Section::getZonesByRegion($region->region);
                    $region->zones = implode(', ', $zones);
                }

                return view('admin.people._z-coord-form', [
                    'regions' => $regions,
                    'person' => $person
                ]);

                break;

            case Person::COORD_SECCION:

                $sections = Section::getActiveSections();

                return view('admin.people._s-coord-form', [
                    'sections' => $sections,
                    'person' => $person
                ]);

                break;

            case Person::MOVILIZADOR:
                $sections = Section::getActiveSections();

                return view('admin.people._mob-form', [
                    'sections' => $sections,
                    'person' => $person
                ]);

                break;
            
        }

    }

    public function update(Request $request) {
        
        $id = $request->get('id');
        $person = Person::find($id);
        $person->fill($request->all());
        $person->type = $request->get('type');
        $person->save();
       
        // Se guarda el log de la actualización de nivel
        Log::saver(Log::CHANGE_LEVEL, $person->id);

        switch ( $person->type ) {
            case Person::DIRECTOR:
                DirectorController::updateDirector($person);
                DirectorController::updateStructure($person);
                break;
        
            case Person::ENLACE:
                DirectorController::updateStructure($person);
                break;

            case Person::COORD_REGIONAL:
                RegionCoordinatorController::updateCoordinator($person);
                RegionCoordinatorController::updateStructure($person);
                break;

            case Person::COORD_ZONA:
                ZoneCoordinatorController::updateCoordinator($person);
                ZoneCoordinatorController::updateStructure($person);
                break;

            case Person::COORD_SECCION:
                SectionCoordinatorController::updateCoordinator($person);
                SectionCoordinatorController::updateStructure($person);
                break;

            case Person::MOVILIZADOR:
                MobilizerController::updateMobilizer($person);
                MobilizerController::updateStructure($person);
                break;
        }

        return response()->json(array(
            'success' => true,
            'message' => 'Actualización realizada correctamente.'
        ));

    }

    public function mobilizersToPromoted() {

        $people = Person::whereIn('type', [ Person::MOVILIZADOR ] )
                        ->select('id', 'type', 'name')
                        ->get();

        foreach ( $people as $p ) {
            $promoted = Person::whereIn('type', [ Person::PROMOVIDO ] )
                            ->where('person_id', $p->id)
                            ->count();
            
            if ( $promoted == 0 ) {
                $p->type = Person::PROMOVIDO;
                $p->save();
                dd($p);
            }

            
        }

    }

    function updateSectionBySuburb() {
        $people = Person::whereIn('type', [ Person::PROMOVIDO ] )
                        ->whereNull('deleted_at')
                        ->whereNull('ine')
                        ->whereNotNull('vote')
                        ->whereNotNull('mobile')
                        ->whereNotNull('mobilizer')
                        ->get();

        $total = count($people);
        $first = 0;
        $last = $total;

        for ( $i = $first; $i < $last; $i++ ) {
            
            $person = $people[$i];
            $suburb = Suburb::find($person->suburb_id);

            if ( isset($suburb) ) {

                $suburb_section = SuburbSection::where('suburb', $suburb->name)->first();

                if ( isset($suburb_section) ) {

                    $section = Section::getBySection($suburb_section->section);

                    if ( isset($section) ) {

                        if ( $person->section != $section->section ) {
                            $person->section = $section->section;
                            $person->zone = $section->zone;
                            $person->region = $section->region;
                            $person->save();
                        }
                        
                    }

                }
            }

        }

    }


}
