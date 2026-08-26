<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotedRequest;
use App\Models\CorporationPerson;
use App\Models\Vote;
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

class BingoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function table(Request $request)
    {
        $regions = ($request->has('regions')) ? array_filter(explode(',', $request->get('regions'))) : [];

        $sections = ($request->has('sections')) ? array_filter(explode(',', $request->get('sections'))) : [];

        $person_id = ($request->has('person_id')) ? $request->get('person_id') : 0;

        $id = ($request->has('id')) ? $request->get('id') : 0;
        $last_name_1 = ($request->has('last_name_1')) ? $request->get('last_name_1') : '';
        $last_name_2 = ($request->has('last_name_2')) ? $request->get('last_name_2') : '';
        $first_name = ($request->has('first_name')) ? $request->get('first_name') : '';

        $people = DB::table('people')
            ->whereNull('deleted_at')
            ->where('type', Person::PROMOVIDO);

        if ( $id != 0 ) {
            $people = $people->where('id', $id);
        }

        if ( $last_name_1 != '' ) {
            $people = $people->where('last_name_1', 'like', '%' . $last_name_1 . '%');
        }

        if ( $last_name_2 != '' ) {
            $people = $people->where('last_name_2', 'like', '%' . $last_name_2 . '%');
        }

        if ( $first_name != '' ) {
            $people = $people->where('first_name', 'like', '%' . $first_name . '%');
        }

        if ( $person_id != 0 ) {
            $people = $people->where('people.person_id', $person_id);
        }

        if ( count($regions) > 0 ) {
            $people = $people->whereIn('region', $regions);
        }

        if ( count($sections) > 0 ) {
            $people = $people->whereIn('section', $sections);
        }

        if ( $request->has('dates') ) {
            $range = array_map('trim', explode('_', $request->get('dates')));
            $start_date = $range[0] . ' 00:00:00';
            $end_date = $range[1] . ' 23:59:59';

            $people = $people->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);
        }

        $people = $people
            ->select('id', 'last_name_1', 'last_name_2', 'first_name', 'name', 'mobile', 'address', 
                'region', 'zone', 'section', 'vote', 'type', 'created_at', 'mobilizer', 
                'voting_date')
            ->get();

        foreach ( $people as $p ) {
            $p->name = Person::getFullName($p);
        }

        return DataTables::of($people)
            ->make(true);
    }

    public function index()
    {
        $people = Person::whereIn('type', [ Person::COORD_REGIONAL, Person::COORD_ZONA, Person::COORD_SECCION, Person::MOVILIZADOR ])
            ->orderBy('last_name_1')
            ->orderBy('last_name_2')
            ->orderBy('first_name')
            ->get();

        foreach ($people as $p) {
            $p->name = Person::getFullName($p);
        }

        $regions = Region::getRegions();

        $sections = Section::getActiveSections();

        return view('admin.bingo.index', [
            'people' => $people,
            'regions' => $regions,
            'sections' => $sections,
            'title' => 'Bingo',
            'votes' => self::calculateVotes(),
        ]);
    }

    public function setVotingDate(Request $request) {

        $id = $request->get('id');
        $vote = $request->get('vote');
        
        $person = Person::find($id);

        if ( $vote == 1 ) {
            $person->voting_date = date('Y-m-d H:i:s');
            Log::saver(Log::SET_VOTING_DATE, $person->id);

        } else {
            $person->voting_date = null;
            Log::saver(Log::DELETE_VOTING_DATE, $person->id);

        }

        $person->save();

        return response()->json(array(
            'success' => true,
            'votes' => self::calculateVotes(),
            'message' => 'Registro actualizado',
        ));


    }

    public function calculateVotes() {
        $votes = Vote::sum('votes');
        
        $people = Person::whereNotNull('voting_date')->count();

        return $votes + $people;
    }

    public function votesTable(Request $request)
    {
        $votes = DB::table('votes')
            ->whereNull('deleted_at')
            ->selectRaw('*, date_format(created_at, "%d/%m/%Y %H:%i:%s") as f_created_at')
            ->get();

        return DataTables::of($votes)
            ->addColumn('actions', 'admin.bingo.votes._actions')
            ->rawColumns([ 'actions' ])
            ->make(true);
    }

    public function createVote() {
        $sections = Section::getActiveSections();

        return view('admin.bingo.votes._create', [
            'sections' => $sections,
        ]);
    }

    public function storeVote(Request $request) {

        $section = $request->get('section');
        $votes = $request->get('votes');

        $m_section = Section::getBySection($section);
        
        $vote = new Vote();
        $vote->section = $section;
        $vote->region = $m_section->region;
        $vote->votes = $votes;
        $vote->save();

        Log::saver(Log::STORE_VOTE, $vote->id);

        return response()->json(array(
            'success' => true,
            'message' => 'Votos registrados correctamente',
            'votes' => self::calculateVotes(),
        ));


    }

    public function editVote(Request $request) {
        $id = $request->get('id');
        $vote = Vote::find($id);
        $sections = Section::getActiveSections();

        return view('admin.bingo.votes._edit', [
            'sections' => $sections,
            'vote' => $vote,
        ]);
    }

    public function updateVote(Request $request) {

        $id = $request->get('id');
        $section = $request->get('section');
        $votes = $request->get('votes');
        
        $m_section = Section::getBySection($section);

        $vote = Vote::find($id);
        $vote->section = $section;
        $vote->region = $m_section->region;
        $vote->votes = $votes;
        $vote->save();

        Log::saver(Log::UPDATE_VOTE, $vote->id);

        return response()->json(array(
            'success' => true,
            'message' => 'Votos actualizados correctamente',
            'votes' => self::calculateVotes(),
        ));
    }

    public function destroyVote(Request $request) 
    {
        $id = $request->get('id');
        $vote = Vote::findOrFail($id);
        $vote->delete();

        Log::saver(Log::DESTROY_VOTE, $vote->id);

        return response()->json(array(
            'success' => true,
            'message' => 'Los votos han sido eliminados correctamente',
            'votes' => self::calculateVotes(),
        ));
    }
    
    public function map() {
        
        $sections = Section::getSections()->pluck('section')->toArray();
        $regions = Region::getRegions()->pluck('region')->toArray();

        $votesbysection = Vote::getVotesbySections()->get()->pluck('votes', 'section')->toArray();

        $peopleQueryVotosbySection = Person::where('type', Person::PROMOVIDO);
        $peopleQueryPromovidosbySection = clone $peopleQueryVotosbySection;
        $peopleQueryVotosbyRegion = clone $peopleQueryVotosbySection;
        $peopleQueryPromovidosbyRegion = clone $peopleQueryVotosbySection;
        $votesQuerybyRegion = Vote::getVotesbySections();


        if (count($sections) > 0) {

            $peopleQueryVotosbySection = $peopleQueryVotosbySection->whereIn('section', $sections);
            $peopleQueryPromovidosbySection = $peopleQueryPromovidosbySection->whereIn('section', $sections);
        }

        if (count($regions) > 0) {

            $peopleQueryVotosbyRegion = $peopleQueryVotosbyRegion->whereIn('region', $regions);
            $peopleQueryPromovidosbyRegion = $peopleQueryPromovidosbyRegion->whereIn('region', $regions);
            $votesQuerybyRegion = $votesQuerybyRegion->whereIn('region', $regions);
        }

        $peopleCountBySection = $peopleQueryVotosbySection
            ->whereNotNull('voting_date')
            ->select('section', DB::raw('count(*) as count'))
            ->groupBy('section')
            ->get();

        $peopleCountByPromovidosSection = $peopleQueryPromovidosbySection
            //->whereNotNull('created_at')
            ->whereNull('deleted_at')
            ->select('section', DB::raw('count(*) as count'))
            ->groupBy('section')
            ->get();

        $peopleCountByRegion = $peopleQueryVotosbyRegion
            ->whereNotNull('voting_date')
            ->select('region', DB::raw('count(*) as count'))
            ->groupBy('region')
            ->get();

        $peopleCountByPromovidosRegion = $peopleQueryPromovidosbyRegion
            //->whereNotNull('created_at')
            ->whereNull('deleted_at')
            ->select('region', DB::raw('count(*) as count'))
            ->groupBy('region')
            ->get();

        self::setGrayGoals($peopleCountByPromovidosSection, $peopleCountByPromovidosRegion);

        $votesbyRegion = $votesQuerybyRegion
            //->whereNotNull('created_at')
            ->whereNull('deleted_at')
            ->select('region', DB::raw('SUM(votes) as total_votes'))
            ->groupBy('region')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->region => (int) $item->total_votes];
            })
            ->toArray();

        // Inicializar el arreglo con todas las secciones activas, estableciendo el conteo en 0
        $votantesBySection = array_fill_keys($sections, 0);
        $promovidosBySection = array_fill_keys($sections, 0);

        $votantesByRegion = array_fill_keys($regions, 0);
        $promovidosByRegion = array_fill_keys($regions, 0);

        foreach ($peopleCountBySection as $row) {
            $votantesBySection[$row->section] = $row->count;
        }

        foreach ($votesbysection as $section => $votes) {
            if (isset($result[$section])) {
                $votantesBySection[$section] += $votes;
            } else {
                $votantesBySection[$section] = $votes;
            }
        }

        foreach ($peopleCountByPromovidosSection as $row) {
            $promovidosBySection[$row->section] = $row->count;
        }

        /////

        foreach ($peopleCountByRegion as $row) {
            $votantesByRegion[$row->region] = $row->count;
        }
        
        foreach ($votesbyRegion as $region => $votes) {
            if (isset($result[$region])) {
                $votantesByRegion[$region] += $votes;
            } else {
                $votantesByRegion[$region] = $votes;
            }
        }

        foreach ($peopleCountByPromovidosRegion as $row) {
            $promovidosByRegion[$row->region] = $row->count;
        }

        //dd($votantesByRegion);

        return view('admin.bingo.map', [
            'title' => 'Mapa Bingo',
            'votantesBySection' => $votantesBySection,
            'promovidosBySection' => $promovidosBySection,
            'votantesByRegion' => $votantesByRegion,
            'promovidosByRegion' => $promovidosByRegion,
        ]);

    }

    
    function setGrayGoals(&$sections, &$regions) {

        $gray_sections_array = Section::where('region', 'GRIS')->pluck('section')->toArray();
        $goal = 0;
        
        foreach ( $sections as $section ) {
            if ( in_array($section->section, $gray_sections_array) ) {
                $section->count = Section::getBySection($section->section)->goal;
                $goal += $section->count;
            }
        }

        $regions[19]->count = $goal;
    }


}
