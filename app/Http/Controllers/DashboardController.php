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

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas'])->only('dashboard');
    }
    
    public function dashboard() {
        return view('admin.dashboard.dashboard');
    }

    public function getInfo(Request $request) {
        
        $date = $request->get('date');
        $initial_date = $date . ' 00:00:00';
        $final_date = $date . ' 23:59:59';

        $promoted = Person::getPromotedByDate($initial_date, $final_date);
        $total_prom = count($promoted);
        $prom = 0;

        $regions_in = [];

        $regions = Region::where('date', 'like', '%' . $date . '%')->get();
        self::setRegionsInfo($regions, $regions_in, $prom, $initial_date, $final_date);

        $people = Region::getPeopleByRegions($regions_in, 0);

        $assistants = Person::getAssistantsByDate($initial_date, $final_date);

        $r_coords = 0;
        $z_coords = 0;
        $s_coords = 0;
        $mob = 0;

        self::countAssistansByType($assistants, $r_coords, $z_coords, $s_coords, $mob);

        $assistants = count($assistants);

        return view('admin.dashboard._info', [
            'total_prom' => $total_prom,
            'regions' => $regions,
            'people' => $people,
            'assistants' => $assistants,
            'r_coords' => $r_coords,
            'z_coords' => $z_coords,
            's_coords' => $s_coords,
            'mob' => $mob,
            'prom' => $prom,
            'regions_in' => $regions_in,
       
        ]);

    }

    public function countAssistansByType($assistants, &$r_coords, &$z_coords, &$s_coords, &$mob) {
        foreach ( $assistants as $a ) {
            $person = Person::find($a->person_id);
            
            switch ( $person->type ) {
                case Person::COORD_REGIONAL:
                    $r_coords++;
                    break;
                case Person::COORD_ZONA:
                    $z_coords++;
                    break;
                case Person::COORD_SECCION:
                    $s_coords++;
                    break;
                case Person::MOVILIZADOR:
                    $mob++;
                    break;

            }
        }
    }

    public function setRegionsInfo(&$regions, &$regions_in, &$prom, $initial_date, $final_date) {
        foreach ( $regions as $region ) {
            $region->prom = Region::getPromotedByDate($region->region, $initial_date, $final_date);
            $prom += $region->prom;
            array_push($regions_in, $region->region);
        }
    }


    
}