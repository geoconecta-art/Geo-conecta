<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\PersonSection;
use App\Models\SupportCatalog;
use App\Tools\Tools;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class SupportCatalogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:beneficiados']);
    }
    
    public function index(Request $request)
    {        
        return view('admin.support.catalog.index', [
        ]);
    }

    public function table(Request $request) {
        $types = SupportCatalog::get();
        return response()->json(['data' => $types]);        
    }
    

    public function create() {
        
        $persons = Person::where('type', Person::COORD_REGIONAL)->get();

        return view('admin.zone-coordinators.create', [ 
            'persons' => $persons,
            'sections' => []
        ]);
    }

    public function store(Request $request)
    {
        $item = new Person();
        $item->name = $request->get('name');
        $item->ine = $request->get('ine');
        $item->address = $request->get('address');
        $item->phone = $request->get('phone');
        $item->type = Person::COORD_ZONA;
        $item->person_id = $request->get('person_id');

        if ( $request->hasFile('image') ) {
            $file = $request->image;
            $new_name = Tools::saveImage($file, 'img/people/');
            $item->image = '/img/people/' . $new_name;
        }
        else if ( $request->get('has_image') == 0 )
            $item->image = null;
            
        $item->save();

        $sections = $request->get('sections');
        
        foreach ( $sections as $section ) {
            $person_section = new PersonSection();
            $person_section->person_id = $item->id;
            $person_section->section =  $section;
            $person_section->save();
        }

        return redirect('/admin/coordinadores-zona/');
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

        $item = Person::findOrFail($id);
        $item->name = $request->get('name');
        $item->ine = $request->get('ine');
        $item->phone = $request->get('phone');
        $item->person_id = $request->get('person_id');

        if ( $request->hasFile('image') ) {
            $file = $request->image;
            $new_name = Tools::saveImage($file, 'img/people/');
            $item->image = '/img/people/' . $new_name;
        }
        else if ( $request->get('has_image') == 0 )
            $item->image = null;

        $item->save();

        $sections = $request->get('sections');

        self::saveSections($item->id, $sections);

        return redirect('/admin/coordinadores-zona');
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

}
