<?php

namespace App\Http\Controllers;

use App\Exports\QuickBoothsExport;
use App\Exports\BoothsExport;
use App\Exports\HouseImageExport;
use App\Exports\HouseExport;
use App\Exports\MobilizerExport;
use App\Exports\PeopleExport;
use App\Exports\PromotedExport;
use App\Exports\SecCoordExport;
use App\Exports\ZeroAttendanceExport;
use App\Exports\ZoneCoordExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;
use App\Models\Log;
use App\Models\Booth;
use App\Models\Person;
use App\Models\Application;
use App\Models\PersonSection;
use App\Models\Region;
use App\Models\Section;
use App\Tools\Tools;

use App\Models\Corporation;
use App\Models\CorporationPerson;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estructura']);
    }

    public function index() {
        return view('admin.reports.index');
    }

    public function create(Request $request) {
        $type = $request->get('type');

        switch ( $type ) {

            case 1:
                $regions = Region::getRegions();

                return view('admin.reports._region-modal', [
                    'regions' => $regions,
                ]);
                break;

            case 2:
                return view('admin.reports._attendance-modal');
                break;

            case 3:
                $sections = Section::getActiveSections();

                return view('admin.reports._section-modal', [
                    'title' => 'Reporte Promotores por Sección',
                    'type' => 3,
                    'sections' => $sections,
                ]);
                break;
            
            case 4: // PROMOVIDOS POR SECCION
                $sections = Section::getActiveSections();

                return view('admin.reports._section-modal', [
                    'title' => 'Reporte Promovidos por Sección',
                    'type' => 4,
                    'sections' => $sections,
                ]);
                break;
        
            case 5: // PROMOVIDOS POR PROMOTOR
                $sections = Section::getActiveSections();

                return view('admin.reports._section-modal', [
                    'title' => 'Reporte Promovidos por Promotor',
                    'type' => 5,
                    'sections' => $sections,
                ]);
                break;

            case 6: //PROMOTORES POR CORPORACIÓN
                $corporations = Corporation::getCorporations();

                return view('admin.reports._corporation-modal', [
                    'title' => 'Reporte por Corporación',
                    'type' => 6,
                    'corporations' => $corporations,
                ]);

                break;
            case 7: //PROMOVIDOS POR CORPORACIÓN
                $corporations = Corporation::getCorporations();

                return view('admin.reports._corporation-modal', [
                    'title' => 'Reporte Promovidos por Corporación',
                    'type' => 7,
                    'corporations' => $corporations,
                ]);
                break;
            
            case 8: //PROMOVIDOS POR PROMOTOR CORPORACIÓN
                $corporations = Corporation::getCorporations();
    
                return view('admin.reports._corporation-modal', [
                    'title' => 'Reporte Promovidos por Promotor por Corporación',
                    'type' => 8,
                    'corporations' => $corporations,
                ]);
                break;
            
            case 9:
                $sections = Section::getActiveSections();
                return view('admin.reports._section-modal', [
                    'title' => 'Reporte Coordinadores y Promotores por Sección',
                    'type' => 9,
                    'sections' => $sections,
                ]);
                break;
        }
    }

    public function generate(Request $request) {
        $type = $request->get('type');

        switch ( $type ) {

            case 1:
                $region = $request->get('region');
                return self::getRegionView($region);
                break;

            case 2:
                $day = $request->get('day');
                $weeks = $request->get('weeks');

                return self::getAttendanceView($day, $weeks);
                break;

            case 3:
                $section = $request->get('section');
                return self::getMobBySectionView($section);
                break;

            case 4:
                $section = $request->get('section');
                return self::getPromBySectionView($section);
                break;
            
            case 5:
                $section = $request->get('section');
                return self::getPromByMobView($section);
                break;

            case 6: //PROMOTORES POR CORPORACIÓN
                $corporation = $request->get('corporation');
                if($corporation == '-1'){
                    return self::getCorporationsRView();
                } else {
                    return self::getMobByCorpView($corporation);
                }
                break;
            case 7: //PROMOVIDOS POR CORPORACIÓN
                $corporation = $request->get('corporation');
                if($corporation == '-1'){
                    return self::getCorporationPromView();
                }  else{
                    return self::getPromByCorporationView($corporation);
                }
            
                break;
            
            case 8: //PROMOVIDOS POR PROMOTOR POR CORPORACIÓN
                $corporation = $request->get('corporation');
                if($corporation == '-1'){
                    return self::getPromotedByAllMobByAllCorpView();
                }  else{
                    return self::getPromotedByMobByCorpView($corporation);
                }
                
                break;

            case 9:
                $section = $request->get('section');
                return self::getCoorAndMobBySectionView($section);
                break;

            case 10:
                return self::getCapturistsView();
        }
    }

    public function generatePDF(Request $request) {
        $type = $request->get('type');

        switch ( $type ) {

            case 1:
                $region = $request->get('region');
                return self::region($region);
                break;

            case 2: // PROMOTORES POR SECCION
                $section = $request->get('section');
                return self::mobilizersBySectionPDF($section);
                break;

            case 3: // PROMOVIDOS POR SECCION
                $section = $request->get('section');
                $r_type = $request->get('r_type');
                $booth = ( $request->has('booth') ) ? $request->get('booth'): '0';
                return self::promotedBySectionPDF($section, $r_type, $booth);
                break;
          
            case 4: // PROMOVIDOS POR PROMOTOR
                $section = $request->get('section');
                return self::promotedByMobilizerPDF($section);
                break;

            case 6: //PROMOTORES POR CORPORACIÓN
                $corporation = $request->get('corporation');
                if($corporation == '-1'){
                    return self::mobilizerByAllCorporationsPDF();
                }  else{
                    return self::mobilizerByCorporationPDF($corporation);
                }
                break;
            
            case 7: //PROMOVIDOS POR CORPORACIÓN
                $corporation = $request->get('corporation');
                if($corporation == '-1'){
                    return self::promotedByAllCorporationPDF();
                }  else{
                    return self::promotedByCorporationPDF($corporation);
                }
                break;

            case 8: //PROMOVIDOS POR PROMOTOR POR CORPORACIÓN
                $corporation = $request->get('corporation');
                if($corporation == '-1'){
                    return self::getPromotedByAllMobByAllCorpPDF();
                }  else{
                    return self::getPromotedByMobByCorpPDF($corporation);
                }
                    
                break;
            case 9:
                $section = $request->get('section');
                return self::coorAndMobsBySectionPDF($section);
                break;
        }

    }

    public function getRegionView($r) {
        $region = Region::getByRegion($r);
        $zones = count(Section::getZonesByRegion($r));
        $sections = Section::getActiveSectionsByRegion($r);
    
        $new_r = $r;
        if ( $r == '8.1' ) $new_r = '8';
        if ( $r == '9.1' ) $new_r = '9';
        if ( $r == '13.1' ) $new_r = '13';

        $r_coord = Person::where('type', Person::COORD_REGIONAL)
            ->where('region', $new_r)
            ->first();

        $z_coord_limit = $zones * 2;
        $s_coord_limit = count($sections) * 2;
        $mob_limit = 0;
        $goal = 0;

        foreach ( $sections as $section ) {

            $s_mobs = Person::where('section', $section->section)->where('type', Person::MOVILIZADOR)->get();
            $section->mob = count($s_mobs);
            $section->active_mob = 0;

            foreach ( $s_mobs as $s_mob ) {
                $mob_prom = Person::calculatePromotedNumber($s_mob->id);
                if ( $mob_prom > 0 ) {
                    $section->active_mob++;
                }
            }

            $section->prom = Section::getPromotedBySection($section->section);
            $section->vp_prom = Section::getPromotedByVote($section->section, 'VP');
            $section->vc_prom = Section::getPromotedByVote($section->section, 'VC');
            $section->va_prom = Section::getPromotedByVote($section->section, 'VA');

            $mob_limit += $section->mob_limit;
            $goal += $section->goal;
        }

        $z_coords = Person::getPeopleByRegion(Person::COORD_ZONA, $r);
        $s_coords = Person::getPeopleByRegion(Person::COORD_SECCION, $r);
        $mobs = Person::getPeopleByRegion(Person::MOVILIZADOR, $r);

        $z_coord = count($z_coords);
        $s_coord = count($s_coords);
        $mob = count($mobs);
        $prom = Person::where('type', Person::PROMOVIDO)->where('region', $r)->count();
        $vp_prom = Person::calculatePromotedByRegion($r, 'VP');
        $va_prom = Person::calculatePromotedByRegion($r, 'VA');
        $vc_prom = Person::calculatePromotedByRegion($r, 'VC');
        $vae_prom = Person::calculatePromotedByRegion($r, 'VAE');

        $prom_1 = count(Person::getPromotedByDate('2024-04-29 00:00:00', '2024-05-05 23:59:59', $r));
        $prom_2 = count(Person::getPromotedByDate('2024-05-06 00:00:00', '2024-05-12 23:59:59', $r));
        $prom_3 = count(Person::getPromotedByDate('2024-05-13 00:00:00', '2024-05-19 23:59:59', $r));
        $prom_4 = count(Person::getPromotedByDate('2024-05-20 00:00:00', '2024-05-26 23:59:59', $r));

        return view('admin.reports._region', [
            'region' => $region,
            'zones' => $zones,
            'sections' => $sections,
            'mob_limit' => $mob_limit,
            'mob' => $mob,
            's_coord_limit' => $s_coord_limit,
            's_coord' => $s_coord,
            'z_coord_limit' => $z_coord_limit,
            'z_coord' => $z_coord,
            'goal' => $goal,
            'prom' => $prom,
            'prom_1' => $prom_1,
            'prom_2' => $prom_2,
            'prom_3' => $prom_3,
            'prom_4' => $prom_4,
            'z_coords' => $z_coords,
            's_coords' => $s_coords,
            'mobs' => $mobs,
            'r_coord' => $r_coord,
            'vp_prom' => $vp_prom,
            'va_prom' => $va_prom,
            'vc_prom' => $vc_prom,
            'vae_prom' => $vae_prom,

        ]);
    }

    public function promotedModal(Request $request) {
        
        $person_id = $request->get('person_id');
        $promoted = [];
        
        if ( $request->has('type') ){
            
            $type = $request->get('type');

            switch ($type) {
                case 5:
                    $promoted = Person::getPromotedByCorporationPerson($person_id);
                    break;
                case 6:
                    $corporation_id = $person_id;
                    $promoted = Person::getPromotedByCorporationPersonNull($corporation_id);
                    break;

                case 7:
                    $corporation_id = $person_id;
                    $promoted = Person::getPromotedByCorporation($corporation_id);
                    break;
                
                case 8:
                    $promoted = Person::getPromotedByPromoter($person_id, 'VA');
                    break;
                
                case 9: 
                    $promoted = Person::getPromotedByPromoter($person_id, 'VP');
                    break;
            }
        } else {
            $promoted = Person::getPromotedByPerson($person_id);
        }

        return view('admin.reports._promoted-list-modal', [
            'promoted' => $promoted
        ]);
    }

    public function getAttendanceView($day, $weeks) {
        
        // Obtiene las fechas
        $dates = Region::getDatesByDay($day);
        
        // Obtiene las regiones
        $regions = Region::where('date', 'like', '%' . $dates[0] . '%')
            ->pluck('region')
            ->toArray();

        $people = Region::getPeopleByRegions($regions, 0);

        $f_dates = [];

        foreach (  $dates as $i => $date ) {
            
            $week = $i + 1;

            if ( in_array($week, $weeks)  ) { // Si la semana fue seleccionada
                
                $initial_date = $date . ' 00:00:00';
                $final_date = date('Y-m-d', strtotime('+6 days', strtotime($date)));
                $final_date = $final_date . ' 23:59:59';

                // Obtener asistentes en el rango de la semana
                $assistants_by_region = Person::getAssistantsByRegions($initial_date, $final_date, $regions)->toArray();

                $assistants_by_date[] = $assistants_by_region;

                $assistants[] = count($assistants_by_region);
                
                $f_dates[] = Tools::formatYmdToDmy($date);

                $person_id[] = array_column($assistants_by_region, 'person_id');
            }
        }

        // Encontrar las coincidencias
        $coincidences = call_user_func_array('array_intersect', $person_id);

        $all_assistants = array_unique(array_merge(...$person_id));
        $all_people = Person::whereIn('region', $regions)
                        ->whereIn('type', [ Person::MOVILIZADOR, Person::COORD_ZONA, Person::COORD_SECCION, Person::COORD_REGIONAL ] )
                        ->pluck('id')
                        ->toArray();

        $inactives = array_diff($all_people, $all_assistants);
        
        return view('admin.reports._attendance', [
            'day' => $day,
            'dates' => $dates,
            'f_dates' => $f_dates,
            'regions' => $regions,
            'people' => $people,
            'assistants' => $assistants,
            'coincidences' => count($coincidences),
            'inactives' => count($inactives),
            'actives' => count(array_unique($all_assistants)),
            'weeks' => $weeks,
        ]);
    }

    public function region($r) {

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $region = Region::getByRegion($r);
        $region->r_coord = Person::where('type', Person::COORD_REGIONAL)
            ->where('region', $r)
            ->first();

        $z_coords = Person::where('type', Person::COORD_ZONA)->where('region', $r)->get();
        $s_coords = Person::where('type', Person::COORD_SECCION)->where('region', $r)->get();
        $mobs = Person::where('type', Person::MOVILIZADOR)->where('region', $r)->get();

        $region->z_coord = count($z_coords);
        $region->s_coord = count($s_coords);
        $region->mob = count($mobs);
        $region->prom = Person::where('type', Person::PROMOVIDO)->where('region', $r)->count();

        $r_sections = Section::getByRegion($r);
        $region->sections_str = implode(', ', $r_sections);

        $region->zones = DB::table('sections')
            ->where('region', $r)
            ->select('zone')
            ->groupBy('zone')
            ->get();

        foreach ( $region->zones as $zone ) {
            
            $z_sections = Section::getByZone($r, $zone->zone);

            $zone->sections_str = implode(', ', $z_sections);

            $zone->coords = Person::where('type', Person::COORD_ZONA)
                ->where('zone', $zone->zone)
                ->where('region', $r)
                ->get();

            $zone->sections = DB::table('sections')
                ->where('region', $r)
                ->where('zone', $zone->zone)
                ->get();

            $zone->prom = Person::where('type', Person::PROMOVIDO)
                ->where('zone', $zone->zone)
                ->where('region', $r)
                ->count();


            foreach ( $zone->sections as $section ) {
            
                $section->coords = Person::where('type', Person::COORD_SECCION)
                    ->where('section', $section->section)
                    ->get();


                $section->mobs = Person::where('type', Person::MOVILIZADOR)
                    ->where('section', $section->section)
                    ->get();

                $section->prom = Person::where('type', Person::PROMOVIDO)
                    ->where('section', $section->section)
                    ->count();

            }
        }

        $sections = Section::getActiveSectionsByRegion($r);
    
        $region->z_coord_limit = count($region->zones) * 2;
        $region->s_coord_limit = count($sections) * 2;
        $region->mob_limit = 0;
        $region->goal = 0;

        foreach ( $sections as $section ) {
            $region->mob_limit += $section->mob_limit;
            $region->goal += $section->goal;
        }

        $data = [
            'region' => $region,
            'date' => $date,
            'title' => ''
        ];

        $pdf = Pdf::loadView('admin.reports.region-pdf', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Región' . $r .  '.pdf');
    }

    /**********************************************************************************
     * PROMOTORES POR SECCION
     **********************************************************************************/

    public function getMobBySectionView($s) {
        $section = Section::getBySection($s);
        $people = Person::where('section', $s)
            ->where('type', Person::MOVILIZADOR)
            ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'address', 'phone', 'section', 'block', 'region')
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();
        //echo($people);
        $total_promoted = 0;
        $totalPerWeek = [0,0,0,0,0];

        foreach ( $people as $p ) {
            $p->promotedPerWeek = Person::getPersonsPromotedPerWeeks($p);
            $p->promoted = array_sum($p->promotedPerWeek); //Person::calculatePromotedNumber($p->id);
            $total_promoted += $p->promoted;

            for ($i = 0; $i < count($p->promotedPerWeek); $i++) {
                $totalPerWeek[$i] += $p->promotedPerWeek[$i];
            }

            $p->VA = count(Person::getPromotedByPromoter($p->id, 'VA'));
            $p->VP = count(Person::getPromotedByPromoter($p->id, 'VP'));
        }

        $people->promotedTotal = $total_promoted;
        $people->promotedTotalPerWeek = $totalPerWeek;

        $coords = Person::getSecCoords($s);

        return view('admin.reports._mob-by-section', [
            'section' => $section,
            'people' => $people,
            'coords' => $coords
        ]);
    }

    public function mobilizersBySectionPDF($s) {

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $section = Section::getBySection($s);

        $promoted = 0;

        $people = Person::where('section', $s)
            ->where('type', Person::MOVILIZADOR)
            ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'address', 'phone', 'section', 'block', 'region')
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        $total_promoted = 0;
        $totalPerWeek = [0,0,0,0,0];

        foreach ( $people as $p ) {
            $p->promotedPerWeek = Person::getPersonsPromotedPerWeeks($p);
            $p->promoted = array_sum($p->promotedPerWeek); //Person::calculatePromotedNumber($p->id);
            $total_promoted += $p->promoted;

            for ($i = 0; $i < count($p->promotedPerWeek); $i++) {
                $totalPerWeek[$i] += $p->promotedPerWeek[$i];
            }
        }

        $people->promotedTotal = $total_promoted;
        $people->promotedTotalPerWeek = $totalPerWeek;

        $coords = Person::getSecCoords($s);  

        $data = [
            'section' => $section,
            'date' => $date,
            'title' => 'PROMOTORES POR SECCION',
            'people' => $people,
            'promoted' => $total_promoted,
            'coords' => $coords
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.mob-by-section', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Sección' . $s .  '.pdf');
    }

    /**********************************************************************************
     * PROMOVIDOS POR SECCION
     **********************************************************************************/

    public function getPromBySectionView($s) {
        $section = Section::getBySection($s);
        
        $people = Person::where('section', $s)
            ->where('type', Person::PROMOVIDO)
            ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'address', 'phone', 'mobile', 'section', 'block', 'vote')
            ->orderBy('address')
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        $coords = Person::getSecCoords($s);  

        $booths = Booth::getBySection($s);

        return view('admin.reports._prom-by-section', [
            'section' => $section,
            'people' => $people,
            'coords' => $coords,
            'booths' => $booths
        ]);
    }

    public function promotedBySectionPDF($s, $r_type, $booth_id) {

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $section = Section::getBySection($s);

        $coords = Person::getSecCoords($s); 
        
        // CASA D
        if ( $r_type == 1 ) {

            $people = Person::where('section', $s)
                ->where('type', Person::PROMOVIDO)
                ->select('id', 'full_name', 'address', 'phone', 'mobile', 
                    'section', 'block', 'vote', 'mobilizer')
                ->orderBy('address')
                ->orderBy('last_name_1')
                ->orderBy('last_name_2')
                ->orderBy('first_name')
                ->get();


            $data = [
                'section' => $section,
                'date' => $date,
                'title' => 'PROMOVIDOS POR SECCION',
                'coords' => $coords,
                'people' =>  $people,
            ];

            $pdf = Pdf::loadView('admin.reports.pdf.prom-by-section', $data);
            //return $pdf->setPaper('a4', 'landscape')->download('Sección ' . $s .  '.pdf'); 
            
            $pdf->setPaper('a4', 'landscape');
            $pdf_output = $pdf->output();

            return Tools::downloadZip($pdf_output, 'CASAD-' . $s .  '.pdf', 'CASAD-' . $s);
        }

        $booths = Booth::getBySection($s);
        
        foreach ( $booths as $b ) {
            
            $b->type_name = Booth::getType($b->type);

            if ( $booth_id == 0 || $b->id == $booth_id ) {
                $b->people = Person::where('section', $s)
                                ->where('type', Person::PROMOVIDO)
                                ->whereRaw('last_name_1 REGEXP "^[' . $b->letters . ']"')
                                ->select('id', 'full_name', 'address', 'phone', 'mobile', 
                                    'section', 'block', 'vote', 'mobilizer', 'corporation')
                                ->orderBy('last_name_1')
                                ->orderBy('last_name_2')
                                ->orderBy('first_name')
                                ->get();
            } else {
                $b->people = [];
            }
            
        }

        $data = [
            'section' => $section,
            'date' => $date,
            'title' => 'PROMOVIDOS POR SECCION',
            'coords' => $coords,
            'booths' =>  $booths,
        ];

        // RC
        $pdf = Pdf::loadView('admin.reports.pdf.prom-by-section-rc', $data);
        //return $pdf->setPaper('a4', 'landscape')->download('RC - Sección ' . $s .  '.pdf');
        
        $pdf->setPaper('a4', 'landscape');
        $pdf_output = $pdf->output();

        return Tools::downloadZip($pdf_output, 'RC-' . $s .  '.pdf', 'RC-' . $s);
        
    }

    /**********************************************************************************
     * PROMOVIDOS POR PROMOTOR
     **********************************************************************************/

     public function getPromByMobView($s) {

        $section = Section::getBySection($s);

        $people = Person::where('section', $s)
            ->where('type', Person::MOVILIZADOR)
            ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'address', 'phone', 'section', 'block', 'region')
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        foreach ( $people as $p ) {
            $p->va_prom = Person::getPromotedByPromoter($p->id, 'VA');
            $p->vp_prom = Person::getPromotedByPromoter($p->id, 'VP');
        }

        $coords = Person::getSecCoords($s);

        return view('admin.reports._prom-by-mob', [
            'section' => $section,
            'people' => $people,
            'coords' => $coords
        ]);
    }

    public function promotedByMobilizerPDF($s) {

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $section = Section::getBySection($s);

        $people = Person::where('section', $s)
            ->where('type', Person::MOVILIZADOR)
            ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'address', 'phone', 'section', 'block', 'region')
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        foreach ( $people as $p ) {
            $p->va_prom = Person::getPromotedByPromoter($p->id, 'VA');
            $p->vp_prom = Person::getPromotedByPromoter($p->id, 'VP');
        }

        $coords = Person::getSecCoords($s);

        foreach ( $coords as $coord ) {
            $coord->va_prom = Person::getPromotedByPromoter($coord->id, 'VA');
            $coord->vp_prom = Person::getPromotedByPromoter($coord->id, 'VP');
        }

        $data = [
            'section' => $section,
            'date' => $date,
            'title' => 'PROMOVIDOS POR PROMOTOR',
            'people' => $people,
            'coords' => $coords
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.prom-by-mob', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Promovidos por Promotor - Sección' . $s .  '.pdf');
    }
    
    public function directors() {

        $directors = Person::whereNull('deleted_at')
                    ->where('type', Person::DIRECTOR)
                    ->orderByRaw('CAST(region AS DECIMAL(10,2)) ASC')
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
    }

    // Hacerlo por region y zonas, no por personas
    /*
    public function directorReport($id) {

        $director = Person::find($id);
        $director->name = Person::getFullName($director);

        $link = Person::where('person_id', $director->id)
                    ->where('type', Person::ENLACE)
                    ->first();
            
        $director->link_name = Person::getFullName($link);
        $director->link_phone = $link->phone;

        $director->reg_coords = Person::where('region', $director->region)
                                    ->where('type', Person::COORD_REGIONAL)
                                    ->get();

        foreach ( $director->reg_coords as $reg_coord ) {

            $reg_coord->name = Person::getFullName($reg_coord);

            // Obtener las secciones
            $sections = Section::getByRegion($reg_coord->region);
            $reg_coord->sections = implode(', ', $sections);

            $reg_coord->zone_coords = Person::where('type', Person::COORD_ZONA)
                                    ->where('region', $reg_coord->region)
                                    ->get();

            foreach ( $reg_coord->zone_coords as $zone_coord ) {

                $zone_coord->name = Person::getFullName($zone_coord);

                // Obtener las secciones
                $sections = Section::getByZone($zone_coord->region, $zone_coord->zone);
                $person->sections = implode(', ', $sections);

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
    }
    */

    
    public function getPromotedNumber($person_id) {

        $promoted_number = 0;

        $mobilizers = Person::where('person_id', $person_id)
            ->where('type', Person::MOVILIZADOR)
            ->get();

        foreach ( $mobilizers as $mob ) {
            $promoted_number += Person::where('person_id', $mob->id)
                            ->where('type', Person::PROMOVIDO)
                            ->count();
        }

        return $promoted_number;
    }
    

    public function sectionReport(Request $request) {

        $type = $request->get('tipo');

        $sections = ( $request->has('secciones') ) ? array_filter(explode(',', $request->get('secciones'))) : [];
        $people = Person::getPeople($type, $sections, 0);

        $total = Person::getPeople($type, [], 0)->count();
        $total_promoted = 0;
        
        foreach ( $people as $p ) {
            $p->name = Person::getName($p->id);
            $p->address = Person::getAddress($p->id);
            $p->promoted_number = self::getPromotedNumber($p->id);
            $total_promoted += $p->promoted_number;
        }

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'people' => $people,
            'date' => $date,
            'title' => 'COORDINADORES SECCIONALES POR SECCION',
            'section' => $sections[0],
            'total' => $total,
            'total_promoted' => $total_promoted,
        ];

        $pdf = Pdf::loadView('admin.reports.section', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('seccion.pdf');
    }

    public function promotedReportBySection(Request $request) {

        $type = $request->get('tipo');
        $sections = ( $request->has('secciones') ) ? array_filter(explode(',', $request->get('secciones'))) : [];
        $people = Person::getPeople($type, $sections, 0);
        $total = Person::getPeople($type, [], 0)->count();
        
        foreach ( $people as $p ) {
            $p->name = Person::getName($p->id);
            $p->address = Person::getAddress($p->id);
        }

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $data = [
            'people' => $people,
            'date' => $date,
            'title' => 'PROMOVIDOS POR SECCION',
            'section' => $sections[0],
            'total' => $total,
            //'total_promoted' => $total_promoted,
        ];

        $pdf = Pdf::loadView('admin.reports.promoted-by-section', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('seccion.pdf');
    }

    public function regionReport(Request $request) {
        
        $type = $request->get('type');
        $region = $request->get('region');

        $region_name =  ( $region == 0 ) ? '' : ' - Región ' . $region;

        switch($type) {
            case 1:
                return Excel::download(new MobilizerExport($region), 'Promotor' . $region_name . '.xlsx');
                break;
            
            case 2:
                return Excel::download(new SecCoordExport($region), 'Coordinador Seccional' . $region_name . '.xlsx');
                break;

            case 3:
                return Excel::download(new ZoneCoordExport($region), 'Coordinador de Zona' . $region_name . '.xlsx');
                break;

            case 4:
                return Excel::download(new PromotedExport($region), 'Promovido' . $region_name . '.xlsx');
                break;
        }

        
    }

    public function peopleReport(Request $request) {
        
        $type = $request->get('type');
        

        switch($type) {
            case 1:
                $date = $request->get('date');
                return Excel::download(new PeopleExport($date), 'Personas ' . $date . '.xlsx');
                break;

            case 2:
                $day = $request->get('day');
                $weeks = $request->get('weeks');
                return Excel::download(new ZeroAttendanceExport($day, $weeks), 'Promotores inactivos.xlsx');
                break;


            case 3:
                $date = $request->get('date');
                $regions = $request->get('regions');
                $initial_date = $date . ' 00:00:00';

                return Excel::download(new ZeroAttendanceExport($initial_date, $final_date, $regions), 'Asistentes regiones.xlsx');
                break;
          
        }

        
    }

    public function housesReport(Request $request) {

        $type = $request->get('type');

        //dd($type);
        
        switch($type) {
            case 1:
                return Excel::download(new HouseExport(), 'Casas Día D.xlsx');
                break;

            case 2:
                return Excel::download(new HouseImageExport(), 'Casas Día D.xlsx');
                break;
        }
        
        
    }

    public function boothsReport(Request $request) {

        $type = $request->get('type');

        switch($type) {
            case 'electoral':
                return Excel::download(new BoothsExport(), 'Electoral.xlsx');
                break;

            case 'conteo-rapido':
                return Excel::download(new QuickBoothsExport(), 'Conteo rápido.xlsx');
                break;
        }
        
        
    }

    /*************************************
     * PROMOTORES POR CORPORACIÓN        *
     *************************************/

    //Método para generar la vista de reporte por corporación
    public function getMobByCorpView($c) {
        //Obtener información de la corporación
        $corporation = Corporation::find($c);

        //Promotores info
        $promotores = CorporationPerson::getCorporationPeople($c);
        $promotoresCount = count($promotores);

        //Info de promovidos por corporación
        $promotedCount = count(Person::getPromotedByCorporation($c));

        //Promovidos y promotores totales de todas las corporaciones
        $countVC = Person::getCountCorporativePromoted();
        $promotoresVC = count(CorporationPerson::getAllCorporationPeople());

        return view('admin.reports._mob-by-corporation', [
            'corporation' => $corporation,
            'promotoresCount' => $promotoresCount,
            'promotedCount' => $promotedCount,
            'promotores' => $promotores,
            'countVC' => $countVC,
            'promotoresVC' => $promotoresVC,
        ]);
    }
    
    public function mobilizerByCorporationPDF($corporation_id){
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $corporation = Corporation::getByCorporation($corporation_id);
        $promotores = CorporationPerson::getCorporationPeople($corporation_id);

        //Info de promovidos por corporación
        $promotedCount = count(Person::getPromotedByCorporation($corporation_id));

        $data = [
            'date' => $date,
            'title' => 'PROMOTORES POR CORPORACIÓN',
            'corporation' => $corporation,
            'promotores' => $promotores,
            'promotedCount' => $promotedCount,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.mob-by-corporation', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Corporación - ' . $corporation->name .  '.pdf');
    }


    /*****************************************
     * PROMOTORES DE TODAS LAS CORPORACIONES *
     *****************************************/

     //Método que se encarga de generar toda la información para 
    //crear la vista de reporte para todas las corporaciones
    public function getCorporationsRView(){
        $countVC = Person::getCountCorporativePromoted();
        $promotoresVC = count(CorporationPerson::getAllCorporationPeople());
        $corporations = Corporation::getCorporations();

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        //Recorrer todas las corporaciones para extraer la información de todos los promotores.
        foreach($corporations as $corporation){
            $corporation->mobs = CorporationPerson::getCorporationPeople($corporation->id);
        }

        return view('admin.reports._mob-by-all-corporations', [
            'promotedCount' => $countVC,
            'promotoresVC' => $promotoresVC,
            'corporations' => $corporations,
        ]);
    }

    public function mobilizerByAllCorporationsPDF(){
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
        $corporations = Corporation::getCorporations();

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        //Recorrer todas las corporaciones para extraer la información de todos los promotores.
        foreach($corporations as $corporation){
            $corporation->mobs = CorporationPerson::getCorporationPeople($corporation->id);
        }

        $data = [
            'date' => $date,
            'title' => 'PROMOTORES POR CORPORACIONES',
            'corporations' => $corporations,
        ];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        $pdf = Pdf::loadView('admin.reports.pdf.mob-by-all-corps', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Corporación - *.pdf');
    }


    /***************************************
     * PROMOVIDOS POR CORPORACIÓN          *
     ***************************************/

    //POR CORPORACIÓN
    public function getPromByCorporationView($corporationID){
        $corporation = Corporation::getByCorporation($corporationID);
        $proms = Person::getPromotedByCorporation($corporationID);

        return view('admin.reports._prom-by-corp',[
            'corporation' => $corporation,
            'proms' => $proms,
        ]);

    }

    //POR TODAS LAS CORPORACIONES
    public function getCorporationPromView(){
        $corporations = Corporation::getCorporations();
        $proms = [];

        foreach ($corporations as $corporation) {
            $corporation->proms = Person::getPromotedByCorporation($corporation->id);
        }

        return view('admin.reports._prom-by-all-corps',[
            'corporations' => $corporations,
        ]);

    }

    //PDF POR CORPORACIÓN
    public function promotedByCorporationPDF($corporationID) {
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $corporation = Corporation::getByCorporation($corporationID);
        $proms = Person::getPromotedByCorporation($corporationID);

        $data = [
            'date' => $date,
            'title' => 'PROMOVIDOS POR CORPORACIÓN',
            'corporation' => $corporation,
            'proms' => $proms,
        ];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        $pdf = Pdf::loadView('admin.reports.pdf.prom-by-corporation', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Corporación - ' . $corporation->name .  '.pdf');
    }

    //PDF de TODAS LAS CORPORACIONES
    public function promotedByAllCorporationPDF(){
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $corporations = Corporation::getCorporations();
        $proms = [];

        foreach ($corporations as $corporation) {
            $corporation->proms = Person::getPromotedByCorporation($corporation->id);
        }

        $data = [
            'date' => $date,
            'title' => 'PROMOVIDOS POR CORPORACIÓN',
            'corporations' => $corporations,
        ];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        $pdf = Pdf::loadView('admin.reports.pdf.prom-by-all-corps', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Corporación - ' . $corporation->name .  '.pdf');
    }

    /*******************************************
     * PROMOVIDOS POR PROMOTOR POR CORPORACIÓN *
     *******************************************/

     //Promovidos por promotor por corporación
     public function getPromotedByMobByCorpView($corporationID){
        //Extraer la corporación de la que serán extraídos los promovidos
        $corporation = Corporation::getByCorporation($corporationID);
        //Extraer los promotores de la corporación
        $mobs = CorporationPerson::getCorporationPeople($corporationID);

        foreach($mobs as $mob){
            $mob->proms = Person::getPromotedByCorporationPerson($mob->id);
        }

        $noProm = Person::getPromotedByCorporationPersonNull($corporation->id);

        return view('admin.reports._prom-by-mob-by-corp',[
            'title' => 'Reporte Promovovidos por Promotor por Corporación',
            'corporation' => $corporation,
            'mobilizers' => $mobs,
            'noProm' => $noProm,
        ]);
     }

     public function getPromotedByMobByCorpPDF($corporationID){
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
        //Extraer la corporación de la que serán extraídos los promovidos
        $corporation = Corporation::getByCorporation($corporationID);
        //Extraer los promotores de la corporación
        $mobs = CorporationPerson::getCorporationPeople($corporationID);

        foreach($mobs as $mob){
            $mob->proms = Person::getPromotedByCorporationPerson($mob->id);
        }

        $noProm = Person::getPromotedByCorporationPersonNull($corporation->id);

        $data = [
            'date' => $date,
            'title' => 'PROMOVIDOS POR PROMOTOR POR CORPORACIÓN',
            'corporation' => $corporation,
            'mobilizers' => $mobs,
            'noProm' => $noProm,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.prom-by-mob-by-corp', $data);

        //return $pdf->setPaper('a4', 'landscape')->stream('Corporación - ' . $corporation->name .  '.pdf');
        $pdf->setPaper('a4', 'landscape');
        $pdf_output = $pdf->output();

        return Tools::downloadZip($pdf_output, $corporation->name .  '.pdf', $corporation->name);
    }

    //------------------------------------------------------------------------------------------------------

    public function getPromotedByAllMobByAllCorpView(){
        $corporations = Corporation::getCorporations();
        $noProms = [];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        foreach ($corporations as $corp) {
            $corp->mobs = CorporationPerson::getCorporationPeople($corp->id);
            foreach ($corp->mobs as $mob) {
                $mob->proms = Person::getPromotedByCorporationPerson($mob->id);
            }
            $noProms [] = Person::getPromotedByCorporationPersonNull($corp->id);
        }

        return view('admin.reports._prom-by-mob-by-all-corps',[
            'title' => 'Promovidos por Promotor por Corporación',
            'corporations' => $corporations,
            'noProms' => $noProms,
        ]);
    }

    //REPORTE PDF DE TODOS LOS PROMOVIDOS POR PROMOTOR POR CORPORACIÓN
    public function getPromotedByAllMobByAllCorpPDF(){
        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');
        $corporations = Corporation::getCorporations();
        $noProms = [];

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '1800');

        foreach ($corporations as $corp) {
            $corp->mobs = CorporationPerson::getCorporationPeople($corp->id);
            foreach ($corp->mobs as $mob) {
                $mob->proms = Person::getPromotedByCorporationPerson($mob->id);
            }
            $noProms [] = Person::getPromotedByCorporationPersonNull($corp->id);
        }

        $data = [
            'date' => $date,
            'title' => 'PROMOVIDOS POR POR PROMOTOR POR CORPORACIÓN',
            'corporations' => $corporations,
            'noProms' => $noProms,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.prom-by-mob-by-all-corps', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Corporaciones - *.pdf');
    }

    /******************************************
     * COORDINADORES Y PROMOTORES POR SECCIÓN *
     ******************************************/

    //Método que se encarga de obtener toda la información necesaria para 
    //cargar en la vista del reporte Coordinadores y promotores por sección.
    public function getCoorAndMobBySectionView($s) {
        
        $section = Section::getBySection($s);

        //Coordinador regional
        $new_r = $section->region;
        if ( $section->region == '8.1' ) $new_r = '8';
        if ( $section->region == '9.1' ) $new_r = '9';
        if ( $section->region == '13.1' ) $new_r = '13';

        $coord_Reg = Person::getRegionalCoorBySeccion($new_r);

        $people = Person::where('section', $s)
            ->where('type', Person::MOVILIZADOR)
            ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'address', 'phone', 'section', 'block', 'region')
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();
        //echo($people);
        $total_promoted = 0;

        foreach ( $people as $p ) {
            $p->promoted = count(Person::getPromotedByPerson($p->id));
            $total_promoted += $p->promoted;
        }

        $people->promotedTotal = $total_promoted;

        $coords = Person::getSecCoords($s);

        $aux = 0;
        foreach ($coords as $coord ) {
            $coord->promoted = count(Person::getPromotedByCoord($coord->id));
            $aux += $coord->promoted;
        }
        $coords->promotedTotal = $aux;

        $coordsZ = Person::getSecCoordZone($section);
        $aux = 0;
        foreach ($coordsZ as $coordz ) {
            $coordz->promoted = count(Person::getPromotedByCoord($coordz->id));
            $aux += $coordz->promoted;
        }

        $coordsZ->promotedTotal = $aux;

        return view('admin.reports._coor-mob-by-sec', [
            'section' => $section,
            'people' => $people,
            'coords' => $coords,
            'coord_Reg' => $coord_Reg,
            'coordsZ' => $coordsZ,
        ]);
    }

     public function coorAndMobsBySectionPDF($s) {

        $date = date('d') . '-' . Tools::getShortMonthName(date('m')) . '-' . date('Y');

        $section = Section::getBySection($s);

        //Coordinador regional
        $new_r = $section->region;
        if ( $section->region == '8.1' ) $new_r = '8';
        if ( $section->region == '9.1' ) $new_r = '9';
        if ( $section->region == '13.1' ) $new_r = '13';

        $coord_Reg = Person::getRegionalCoorBySeccion($new_r);

        $people = Person::where('section', $s)
            ->where('type', Person::MOVILIZADOR)
            ->select('id', 'first_name', 'last_name_1', 'last_name_2', 'address', 'phone', 'section', 'block', 'region')
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        $total_promoted = 0;

        foreach ( $people as $p ) {
            $p->promoted = count(Person::getPromotedByPerson($p->id));
            $total_promoted += $p->promoted;
        }

        $people->promotedTotal = $total_promoted;

        $coords = Person::getSecCoords($s);

        $aux = 0;
        foreach ($coords as $coord ) {
            $coord->promoted = count(Person::getPromotedByCoord($coord->id));
            $aux += $coord->promoted;
        }
        $coords->promotedTotal = $aux;

        $coordsZ = Person::getSecCoordZone($section);
        $aux = 0;
        foreach ($coordsZ as $coordz ) {
            $coordz->promoted = count(Person::getPromotedByCoord($coordz->id));
            $aux += $coordz->promoted;
        }

        $coordsZ->promotedTotal = $aux;

        $data = [
            'section' => $section,
            'date' => $date,
            'title' => 'COORDINADORES Y PROMOTORES POR SECCION',
            'people' => $people,
            'coords' => $coords,
            'coord_Reg' => $coord_Reg,
            'coordsZ' => $coordsZ,
        ];

        $pdf = Pdf::loadView('admin.reports.pdf.coor-and-mob-by-sec', $data);

        return $pdf->setPaper('a4', 'landscape')->stream('Sección' . $s .  '.pdf');
    }

    /**********************************************************************************
     * CAPTURISTAS
     **********************************************************************************/

    public function getCapturistsView() {
        
        $users = User::where('name', 'like', '%capturista%')->get();

        foreach ( $users as $u )
        {
            $u->promoted = Log::calculatePeopleByType($u->id, Person::PROMOVIDO);
            $u->mob = Log::calculatePeopleByType($u->id, Person::MOVILIZADOR);
            $u->sec_coord = Log::calculatePeopleByType($u->id, Person::COORD_SECCION);
            $u->zone_coord = Log::calculatePeopleByType($u->id, Person::COORD_ZONA);
            $u->reg_coor = Log::calculatePeopleByType($u->id, Person::COORD_REGIONAL);
            $u->peter = Log::calculatePeopleByType($u->id, Person::PEDROENTUCASA);
            $u->inspector = Log::calculatePeopleByType($u->id, Person::FISCALIZADOR);
            $u->house = Log::calculatePeopleByType($u->id, Person::CASAD);
        }        

        return view('admin.reports._capturists', [
            'users' => $users,
        ]);
    }

   
}