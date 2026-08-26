<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Http\Requests\PromotedRequest;
use App\Models\Block;
use App\Models\Corporation;
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
use Illuminate\Support\Facades\Auth;
use Svg\Tag\Rect;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas']);
    }

    public function index() {
        $regions = Region::getRegions();
        
        return view('admin.sections.index', [
            'regions' => $regions
        ]);
    }

    public function getSections(Request $request) {

        $region = $request->get('region');
        //$section = $request->get('section');
        $type = $request->get('type'); // Tipo de persona

        if ( $type == Person::MOVILIZADOR ) {
            return self::mobilizerInfo($region, 0);
        } else if ( $type == Person::PROMOVIDO ) {
            return self::promotedInfo($region, 0);
        }

    }

    public function mobilizerInfo($region, $section) {
        
        if ( $region == 0 ) {
            $sections = Section::getActiveSections();

            $zones = 40;

            $z_coord_limit = 80;
            
            $z_coord = Person::where('type', Person::COORD_ZONA)->count();

        } else {

            $sections = Section::getActiveSectionsByRegion($region);
            
            $zones = count(Section::getZonesByRegion($region));
            
            $z_coord_limit = $zones * 2;
            
            $z_coord = Person::where('type', Person::COORD_ZONA)->where('region', $region)->count();
        }

        $mob_limit = 0;
        $mob = 0;

        $s_coord_limit = count($sections) * 2;
        $s_coord = 0;

        foreach ( $sections as $section ) {
            $section->mob = Section::getMobilizersBySection($section->section);
            
            $mob_limit += $section->mob_limit;
            $mob += $section->mob;

            $section->section_coords = Section::getCoordsBySection($section->section);

            $s_coord += $section->section_coords;

            $section->zone_coords = '';
        }

        $limit = round($mob_limit * ( 1.20 ));

        return view('admin.sections._mobilizer', [
            'sections' => $sections,
            'mob_limit' => $mob_limit,
            'mob' => $mob,
            'limit' => $limit,
            's_coord_limit' => $s_coord_limit,
            's_coord' => $s_coord,
            'z_coord_limit' => $z_coord_limit,
            'z_coord' => $z_coord,
            'zones' => $zones
        ]);
    }

    public function promotedInfo($region, $section) {
        
        if ( $region == 0 ) {
            $sections = Section::getNotGraySections();

        } else {
            $sections = Section::getActiveSectionsByRegion($region);
        }

        $goal = 0;
        $prom = 0;
        $vp_prom = 0;
        $va_prom = 0;
        $vc_prom = 0;

        foreach ( $sections as $section ) {
            $section->mob = Section::getMobilizersBySection($section->section);
            $section->prom = Section::getPromotedBySection($section->section);
            
            $section->vp_prom = Section::getPromotedByVote($section->section, 'VP');
            $section->vc_prom = Section::getPromotedByVote($section->section, 'VC');
            $section->va_prom = Section::getPromotedByVote($section->section, 'VA');

            $goal += $section->goal;
            $prom += $section->prom;
            $vp_prom += $section->vp_prom;
            $va_prom += $section->va_prom;
            $vc_prom += $section->vc_prom;
        }

        return view('admin.sections._promoted', [
            'sections' => $sections,
            'goal' => $goal,
            'prom' => $prom,
            'vp_prom' => $vp_prom,
            'va_prom' => $va_prom,
            'vc_prom' => $vc_prom,
        ]);
    }

    public function getOptions(Request $request) {

        $region = $request->get('region');
        $sections = Section::getActiveSectionsByRegion($region);

        return view('admin.sections._options', [
            'sections' => $sections
        ]);
    }

   
   


}
