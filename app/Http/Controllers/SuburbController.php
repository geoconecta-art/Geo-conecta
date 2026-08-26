<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Section;
use App\Models\Suburb;
use App\Models\SuburbSection;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuburbController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ( $request->ajax() ) {

            $items = Suburb::whereNull('deleted_at')->get();

            foreach ( $items as $item ) {
                $sections = SuburbSection::getSectionsArray($item->id);
                $item->sections = implode(', ', $sections);

                if ( count($sections) > 0 ) {
                    $item->df = Section::getDF($sections[0]);
                    $item->dl = Section::getDL($sections[0]);
                }
            }

            return DataTables::of($items)
                ->addColumn('actions', 'admin.suburbs._actions')
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admin.suburbs.index');
    }

    public function create() {
        $sections = Section::all();

        return view('admin.suburbs.create', [
            'item' => new Suburb(),
            'sections' => $sections
        ]);
    }

    public function store(Request $request)
    {
        $item = new Suburb();
        $item->fill($request->all());
        $item->save();

        $sections = $request->get('sections');
        self::saveSections($item->id, $sections);

        if ( count($sections) > 0 ) {
            $item->df = Section::getDF($sections[0]);
            $item->dl = Section::getDL($sections[0]);
            $item->save();
        }

        return redirect()->route('suburbs.index');
    }

    public function edit($id)
    {
        $item = Suburb::findOrFail($id);
        
        $all_sections = Section::all();
        $sections = SuburbSection::getSectionsArray($item->id);

        return view('admin.suburbs.edit', [
            'item' => $item,
            'all_sections' => $all_sections,
            'sections' => $sections
        ]);
    }

    public function update(Request $request, $id) {
        $item = Suburb::findOrFail($id);
        $item->fill($request->all());
        $item->save();

        $sections = $request->get('sections');
        self::saveSections($item->id, $sections);

        if ( count($sections) > 0 ) {
            $item->df = Section::getDF($sections[0]);
            $item->dl = Section::getDL($sections[0]);
            $item->save();
        }

        return redirect()->route('suburbs.index');
    }

    public function destroy($id)
    {
        $item = Suburb::findOrFail($id);
        $item->delete();

        return response()->json(array(
            'success' => true,
            'message' => 'La colonia ha sido eliminada correctamente'
        ));
    }

    public static function saveSections($suburb_id, $sections) {

        // Inactivar las que no están en el array
        SuburbSection::where('suburb_id', $suburb_id)
            ->whereNotIn('section', $sections)
            ->delete();

        foreach ( $sections as $section ) {

            $suburb_section = SuburbSection::where('suburb_id', $suburb_id)
                ->where('section', $section)
                ->first();

            if ( !isset($suburb_section) ) {
                $suburb_section = new SuburbSection();
                $suburb_section->suburb_id = $suburb_id;
                $suburb_section->section = $section;
                $suburb_section->save();
            }

        }

    }

    public function updateSectionsByCSV() {
        $path = 'csv/sections.csv';
        $row = 0;

        if ( ( $gestor = fopen($path, 'r') ) !== false) {
            while ( ( $columns = fgetcsv($gestor, 30000, ',') ) !== false ) {
                if ( $row > 0 ) {
                    $section = new Section();
                    $section->section = trim($columns[0]);
                    $section->df = trim($columns[1]);
                    $section->dl = trim($columns[2]);
                    $section->region = trim($columns[3]);
                    $section->zone = ( trim($columns[4]) == '' ) ? null : trim($columns[4]);
                    $section->dependence = trim($columns[5]);
                    $section->ln = trim($columns[6]);
                    $section->goal = trim($columns[7]);
                    $section->mob_limit = trim($columns[8]);
                    $section->active = ( $section->mob_limit == 0 ) ? 0 : 1;
                    $section->save();
                }

                $row++;
            }

            fclose($gestor);
        }

        return response()->json(array(
            'success' => true,
            'message' => $row - 1 . ' secciones fueron actualizadas'
        ));
    }

    public function updateBlocksByCSV() {
        $path = 'csv/blocks.csv';
        $row = 0;

        if ( ( $gestor = fopen($path, 'r') ) !== false) {
            while ( ( $columns = fgetcsv($gestor, 30000, ',') ) !== false ) {
                if ( $row > 0 ) {
                    $block = new Block();
                    $block->block = trim($columns[4]);
                    $block->section = trim($columns[3]);
                    $block->nl = trim($columns[5]);
                    $block->mr = trim($columns[6]);
                    $block->save();
                }

                $row++;
            }

            fclose($gestor);
        }

        return response()->json(array(
            'success' => true,
            'message' => $row - 1 . ' manzanas fueron actualizadas'
        ));
    }

    public function updateSuburbsByCSV() {
        $path = 'csv/colonias.csv';
        $row = 0;

        if ( ( $gestor = fopen($path, 'r') ) !== false) {
            while ( ( $columns = fgetcsv($gestor, 30000, ',') ) !== false ) {
                if ( $row > 1 ) {

                    $zip_code = trim($columns[0]);
                    $name = trim($columns[5]);

                    $suburb = Suburb::where('name', $name)->first();

                    if ( isset($suburb) ) {
                        $suburb->zip_code = $zip_code;
                        $suburb->save();
                    }
                    else {
                        $suburb = new Suburb();
                        $suburb->name = $name;
                        $suburb->zip_code = $zip_code;
                        $suburb->save();
                    }
                }
                $row++;
            }
            fclose($gestor);
        }

        return response()->json(array(
            'success' => true,
            'message' => $row - 1 . ' colonias fueron actualizadas'
        ));
    }

    public function updateSectionSuburbsByCSV() {
        $path = 'csv/suburbs.csv';
        $row = 0;

        if ( ( $gestor = fopen($path, 'r') ) !== false) {
            while ( ( $columns = fgetcsv($gestor, 30000, ',') ) !== false ) {
                if ( $row > 0 ) {

                    $suburb_name = trim($columns[6]);
                    $section = trim($columns[0]);

                    if ( $suburb_name !== '' and $section !== '' ) {
                        
                        $suburb_section = SuburbSection::where('suburb', $suburb_name)
                            ->where('section', $section)
                            ->first();
    
                        if ( !isset($suburb_section) ) {
                            $suburb_section = new SuburbSection();
                            $suburb_section->suburb = $suburb_name;
                            $suburb_section->section = $section;
                            $suburb_section->save();
                        }
                    }


                    

                    
                }

                $row++;
            }

            fclose($gestor);
        }

        return response()->json(array(
            'success' => true,
            'message' => $row - 1 . ' colonias fueron actualizadas'
        ));
    }

}
