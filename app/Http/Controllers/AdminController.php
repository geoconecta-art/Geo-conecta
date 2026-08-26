<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;

use App\Models\Person;
use App\Models\Application;
use App\Models\PersonSection;
use App\Models\Region;
use App\Models\Section;
use App\Tools\Tools;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware(['can:estadisticas'])->only('dashboard');
        //$this->middleware(['can:inteligencia'])->only('home', 'profile', 'search');
        //$this->middleware(['can:mapa'])->only('maps');
        $this->middleware(['can:estructura'])->only('directorReport', 'directorsReport');
    }

    // DEPRECATED
    /*
    public function dashboard() {
         
        $directors = Person::where('type', Person::DIRECTOR)->count();
        $r_coords = Person::where('type', Person::COORD_REGIONAL)->count();
        $z_coords = Person::where('type', Person::COORD_ZONA)->count();
        $s_coords = Person::where('type', Person::COORD_SECCION)->count();
        $mobilizers = Person::where('type', Person::MOVILIZADOR)->count();

        $total = $directors + $r_coords + $z_coords + $s_coords + $mobilizers;

        if ( $total > 0 ) {
            $p_directors = round(($directors / $total) * 100, 2);
            $p_r_coords = round(($r_coords / $total) * 100, 2);
            $p_z_coords =  round(($z_coords / $total) * 100, 2);
            $p_s_coords =  round(($s_coords / $total) * 100, 2);
            $p_mobilizers =  round(($mobilizers / $total) * 100, 2);
        } else {
            $p_directors = 0;
            $p_r_coords = 0;
            $p_z_coords = 0;
            $p_s_coords = 0;
            $p_mobilizers = 0;
        }


        return view('admin.dashboard', [
            'directors' => $directors,
            'r_coords' => $r_coords,
            'z_coords' => $z_coords,
            's_coords' => $s_coords,
            'mobilizers' => $mobilizers,
            'p_directors' => $p_directors,
            'p_r_coords' => $p_r_coords,
            'p_z_coords' => $p_z_coords,
            'p_s_coords' => $p_s_coords,
            'p_mobilizers' => $p_mobilizers
        ]);
        
    }
    */
    
    /*
    public function home() {

        return view('admin.intelligence.home');
    }
    */

    /*
    public function profile($id) {
        $application = Application::find($id);

        return view('admin.intelligence.profile', [ 
            'item' => $application 
        ]);
    }
    */

    /*
    public function search(Request $request) {
        $search_words = $request->get('search_words');
        $applicaton = Application::where(DB::raw("CONCAT(name, ' ', last_name)"), 'LIKE', '%' . $search_words .'%')->first();

        if ( isset($applicaton) ) {

            return response()->json(array(
                'success' => true,
                'id' => $applicaton->id
            ));
        }

        return response()->json(array(
            'success' => false,
            'error' => 'No se encontraron coincidencias'
        ));
    }
    

    public function maps() {
        return view('admin.maps');
    }

    public function messajes() {
        return view('admin.messages');

    }
    */
    
    public function directorsReport() {

        $directors = Person::whereNull('deleted_at')
                    ->where('type', Person::DIRECTOR)
                    ->get();

        foreach ( $directors as $director ) {
            $director->name = Person::getFullName($director);

            $link = Person::where('person_id', $director->id)
                        ->where('type', Person::ENLACE)
                        ->first();
                
            $director->link_name = Person::getFullName($link);
            $director->link_phone = $link->phone;
        }
        
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'directors' => $directors,
            'date' => $date,
            'title' => 'DIRECTOR REGIONAL'
        ];

        $pdf = Pdf::loadView('admin.reports.directors', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('directores.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function directorReport($id) {

        $director = Person::find($id);
        $director->name = Person::getName($id);

        $link = Person::where('person_id', $director->id)
                    ->where('type', Person::ENLACE)
                    ->first();
            
        $director->link_name = Person::getName($link->id);
        $director->link_phone = $link->phone;

        $director->reg_coords = Person::where('person_id', $director->id)
                                    ->where('type', Person::COORD_REGIONAL)
                                    ->get();

        foreach ( $director->reg_coords as $reg_coord ) {

            $reg_coord->name = Person::getName($reg_coord->id);

            $sections = PersonSection::getSectionsArray($reg_coord->id);
            $reg_coord->sections = implode(', ', $sections);

            $reg_coord->zone_coords = Person::where('person_id', $reg_coord->id)
                                        ->where('type', Person::COORD_ZONA)
                                        ->get();

            foreach ( $reg_coord->zone_coords as $zone_coord ) {

                $zone_coord->name = Person::getName($zone_coord->id);

                $sections = PersonSection::getSectionsArray($zone_coord->id);
                $zone_coord->sections = implode(', ', $sections);

                $zone_coord->sec_coords = Person::where('person_id', $zone_coord->id)
                                    ->where('type', Person::COORD_SECCION)
                                    ->get();

                foreach ( $zone_coord->sec_coords as $sec_coord ) {

                    $sec_coord->name = Person::getName($sec_coord->id);

                    $sections = PersonSection::getSectionsArray($sec_coord->id);
                    $sec_coord->sections = implode(', ', $sections);

                    $sec_coord->mobs = Person::where('person_id', $sec_coord->id)
                                    ->where('type', Person::MOVILIZADOR)
                                    ->get();

                
                    foreach ( $sec_coord->mobs as $mob ) {

                        $mob->name = Person::getName($mob->id);
                        $sections = PersonSection::getSectionsArray($mob->id);
                        $mob->sections = implode(', ', $sections);

                        $mob->promoted = Person::where('person_id', $mob->id)
                            ->where('type', Person::PROMOVIDO)
                            ->get();

                            
                        foreach ( $mob->promoted as $promoted ) {
                            $promoted->name = Person::getName($promoted->id);

                        }

                    }
                    
                }

            }
            
        }

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'director' => $director,
            'date' => $date,
            'title' => ''
        ];

        $pdf = Pdf::loadView('admin.reports.director', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('director.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    /*
    public function directorReport($id) {

        $director = Person::find($id);

        $link = Person::where('person_id', $director->id)
                    ->where('type', Person::ENLACE)
                    ->first();
            
        $director->link_name = $link->name;
        $director->link_phone = $link->phone;

        $coordinators = Person::where('person_id', $director->id)
                        ->where('type', Person::COORD_REGIONAL)
                        ->get();

        foreach ( $coordinators as $coordinator ) {
            $sections = PersonSection::getSectionsArray($coordinator->id);
            $coordinator->sections = implode(', ', $sections);
        } 
        
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'director' => $director,
            'coordinators' => $coordinators,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.director', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('director.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }
    */
    

    public function structureDirectorReport($id) {

        $director = Person::find($id);
        $link = Person::where('person_id', $director->id)
                    ->where('type', Person::ENLACE)
                    ->first();
            
        $director->link_name = $link->name;
        $director->link_phone = $link->phone;

        $director->reg_coords = Person::where('person_id', $director->id)
                                    ->where('type', Person::COORD_REGIONAL)
                                    ->get();
        
        // Rowspan del director
        $director->rowspan = count($director->reg_coords);
        $dir_rowspan = 0;

        $zone_coords = 0;
        $sec_coords = 0;
        $mobs = 0;

        foreach ( $director->reg_coords as $reg_coord ) {
            
            $reg_coord->zone_coords = Person::where('person_id', $reg_coord->id)
                                        ->where('type', Person::COORD_ZONA)
                                        ->get();
            
            // Rowspan del coordinador regional
            $reg_coord->rowspan = count($reg_coord->zone_coords);
            $reg_coord_rowspan = 0;

            foreach ( $reg_coord->zone_coords as $zone_coord ) {

                $zone_coords++;

                $zone_coord->sec_coords = Person::where('person_id', $zone_coord->id)
                                    ->where('type', Person::COORD_SECCION)
                                    ->get();

                // Rowspan del coordinador zonal
                $zone_coord->rowspan = count($zone_coord->sec_coords);
                $zone_coord_rowspan = 0;

                foreach ( $zone_coord->sec_coords as $sec_coord ) {

                    $sec_coords++;

                    // Asignar la sección correcta
                    $person_section = PersonSection::where('person_id', $sec_coord->id)->first();
                    $sec_coord->section = $person_section->section;
                    
                    
                    $sec_coord->mobs = Person::where('person_id', $sec_coord->id)
                                    ->where('type', Person::MOVILIZADOR)
                                    ->get();

                    // Rowspan del coordinador seccional
                    $sec_coord->rowspan = count($sec_coord->mobs);

                    foreach ( $sec_coord->mobs as $mob ) {
                        $dir_rowspan++;
                        $reg_coord_rowspan++;
                        $zone_coord_rowspan++;

                        $mobs++;
                    }


                    if ( count($sec_coord->mobs) === 0 ) {
                        $dir_rowspan++;
                        $reg_coord_rowspan++;
                        $zone_coord_rowspan++;

                        $sec_coord->rowspan++;
                    }

                    
                }

                if ( count($zone_coord->sec_coords) === 0 ) {
                    $dir_rowspan++;
                    $reg_coord_rowspan++;
                }

                // Rowspan del coordinador zonal
                if ( $zone_coord_rowspan > $zone_coord->rowspan )
                    $zone_coord->rowspan = $zone_coord_rowspan;
               

            }

            

            // Rowspan del coordinador regional
            if ( $reg_coord->zone_coords === 0 ) {
                $dir_rowspan++;
                $reg_coord_rowspan++;
            }

            // Rowspan del coordinador regional
            if ( $reg_coord_rowspan > $reg_coord->rowspan )
                $reg_coord->rowspan = $reg_coord_rowspan;
            
        }

        // Rowspan del director
        if ( $dir_rowspan > $director->rowspan )
            $director->rowspan = $dir_rowspan;

        $first_reg_coord = isset($director->reg_coords[0]) ? $director->reg_coords[0]->name : '';
        $first_zone_coord = isset($director->reg_coords[0]->zone_coords[0]) ? $director->reg_coords[0]->zone_coords[0]->name : '';
        $first_sec_coord = isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]) ? $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->name : '';
        $first_mob = isset($director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]) ? $director->reg_coords[0]->zone_coords[0]->sec_coords[0]->mobs[0]->name : '';

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
        
        $data = [
            'director' => $director,
            'first_reg_coord' => $first_reg_coord,
            'first_zone_coord' => $first_zone_coord,
            'first_sec_coord' => $first_sec_coord,
            'first_mob' => $first_mob,
            'zone_coords' => $zone_coords,
            'sec_coords' => $sec_coords,
            'mobs' => $mobs,
            'date' => $date
        ];

        $pdf = Pdf::loadView('admin.reports.director', $data);

        return $pdf->setPaper('legal', 'landscape')->stream('director.pdf');
        //return $pdf->setPaper('legal', 'landscape')->download('reporte.pdf');
    }

    public function rCoordinatorsReport(Request $request) {

        $type = $request->get('tipo');

        $sections = ( $request->has('secciones') ) ? array_filter(explode(',', $request->get('secciones'))) : [];
        
        $director_id = ( $request->has('director') ) ? $request->get('director') : 0;
    
        $coordinators = Person::getPeople($type, $sections, $director_id);

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'coordinators' => $coordinators,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.region-coordinators', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('coordinadores-regionales.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }
    
    public function rCoordinatorReport($id) {

        $coordinator = Person::find($id);
        $coordinator->name = Person::getName($coordinator->id);
        $coordinator->address = Person::getAddress($coordinator->id);

        $z_coordinators = Person::where('person_id', $coordinator->id)
                        ->where('type', Person::COORD_ZONA)
                        ->get();
        
        foreach ( $z_coordinators as $z_coordinator ) {
            $z_coordinator->name = Person::getName($z_coordinator->id);
            $z_coordinator->address = Person::getAddress($z_coordinator->id);
            $sections = PersonSection::getSectionsArray($z_coordinator->id);
            $z_coordinator->sections = implode(', ', $sections);
        }
        
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'coordinator' => $coordinator,
            'z_coordinators' => $z_coordinators,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.region-coordinator', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('coordinador-regional.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function zCoordinatorsReport(Request $request) {

        $type = $request->get('tipo');

        $sections = ( $request->has('secciones') ) ? array_filter(explode(',', $request->get('secciones'))) : [];
        
        $coord_id = ( $request->has('coord_reg') ) ? $request->get('coord_reg') : 0;
    
        $coordinators = Person::getPeople($type, $sections, $coord_id);

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'coordinators' => $coordinators,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.zone-coordinators', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('coordinadores-zona.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function zCoordinatorReport($id) {

        $coordinator = Person::find($id);
        $coordinator->name = Person::getName($coordinator->id);
        $coordinator->address = Person::getAddress($coordinator->id);

        $s_coordinators = Person::where('person_id', $coordinator->id)
                        ->where('type', Person::COORD_SECCION)
                        ->get();

        foreach ( $s_coordinators as $s_coordinator ) {
            $s_coordinator->name = Person::getName($s_coordinator->id);
            $s_coordinator->address = Person::getAddress($s_coordinator->id);

            $sections = PersonSection::getSectionsArray($s_coordinator->id);
            $s_coordinator->sections = implode(', ', $sections);
        }
        
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'coordinator' => $coordinator,
            's_coordinators' => $s_coordinators,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.zone-coordinator', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('coordinador-zonal.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function sCoordinatorsReport(Request $request) {

        $type = $request->get('tipo');

        $sections = ( $request->has('secciones') ) ? array_filter(explode(',', $request->get('secciones'))) : [];
        
        $coord_id = ( $request->has('coord_zona') ) ? $request->get('coord_zona') : 0;
    
        $coordinators = Person::getPeople($type, $sections, $coord_id);

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'coordinators' => $coordinators,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.section-coordinators', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('coordinadores-seccion.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function sCoordinatorReport($id) {

        $coordinator = Person::find($id);
        $coordinator->name = Person::getName($coordinator->id);
        $coordinator->address = Person::getAddress($coordinator->id);

        $mobilizers = Person::where('person_id', $coordinator->id)
                        ->where('type', Person::MOVILIZADOR)
                        ->get();

        foreach ( $mobilizers as $mobilizer ) {
            $mobilizer->name = Person::getName($mobilizer->id);
            $mobilizer->address = Person::getAddress($mobilizer->id);

            $sections = PersonSection::getSectionsArray($mobilizer->id);
            $mobilizer->sections = implode(', ', $sections);
        }

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'coordinator' => $coordinator,
            'mobilizers' => $mobilizers,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.section-coordinator', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('coordinador-seccional.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function mobilizersReport(Request $request) {

        /*
        $mobilizers = Person::whereNull('deleted_at')
                    ->where('type', Person::MOVILIZADOR)
                    ->get();
        
        foreach ( $mobilizers as $mobilizer ) {

            $s_coordinator = Person::find($mobilizer->person_id);
            $mobilizer->s_coordinator = $s_coordinator->name;

            $z_coordinator = Person::find($s_coordinator->person_id);
            $mobilizer->z_coordinator = $z_coordinator->name;

            $r_coordinator = Person::find($z_coordinator->person_id);
            $mobilizer->r_coordinator = $r_coordinator->name;
            
            $director = Person::find($r_coordinator->person_id);
            $mobilizer->director = $director->name;

            $sections = PersonSection::getSectionsArray($mobilizer->id);
            $mobilizer->sections = implode(', ', $sections);
        }*/

        $type = $request->get('tipo');

        $sections = ( $request->has('secciones') ) ? array_filter(explode(',', $request->get('secciones'))) : [];
        
        $coord_id = ( $request->has('coord_sec') ) ? $request->get('coord_sec') : 0;
    
        $mobilizers = Person::getPeople($type, $sections, $coord_id);

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'mobilizers' => $mobilizers,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.mobilizers', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('mobilizadores.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function mobilizerReport($id) {

        $mobilizer = Person::find($id);
        $mobilizer->name = Person::getName($mobilizer->id);
        $mobilizer->address = Person::getAddress($mobilizer->id);

        $persons = Person::where('person_id', $mobilizer->id)
                        ->where('type', Person::PROMOVIDO)
                        ->get();

        foreach ( $persons as $person ) {
            $person->name = Person::getName($person->id);
            $person->address = Person::getAddress($person->id);

            $sections = PersonSection::getSectionsArray($person->id);
            $person->sections = implode(', ', $sections);
        }

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'mobilizer' => $mobilizer,
            'persons' => $persons,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.mobilizer', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('movilizador.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function promotedReport(Request $request) {

        $type = $request->get('tipo');

        $sections = ( $request->has('secciones') ) ? array_filter(explode(',', $request->get('secciones'))) : [];
        
        $person_id = ( $request->has('mov') ) ? $request->get('mov') : 0;
    
        $persons = Person::getPeople($type, $sections, $person_id);

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'persons' => $persons,
            'date' => $date,
        ];

        $pdf = Pdf::loadView('admin.reports.promoted', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('promovidos.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('directores.pdf');
    }

    public function downloadReport() {

        $people = DB::table('people')
            ->whereNull('deleted_at')
            ->where('type', Person::COORD_SECCION)
            ->get();

        $mobilizers = 0;

        foreach ($people as $person) {
            $person->mobilizers = Person::where('person_id', $person->id)->count();
            $mobilizers += $person->mobilizers;
            $person_section = PersonSection::where('person_id', $person->id)->first();
            $person->section = $person_section->section;
        }

        $data = [
            'section' => '251',
            'people' => $people,
            'mobilizers' => $mobilizers
        ];

        $pdf = Pdf::loadView('admin.reports.section-coordinators', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('reporte.pdf');
        //return $pdf->setPaper('a4', 'landscape')->download('reporte.pdf');
    }

    
}