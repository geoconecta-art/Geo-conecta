<?php

namespace App\Http\Controllers;

use App\Http\Requests\CorporationRequest;
use App\Models\Block;
use App\Models\Corporation;
use App\Models\CorporationPerson;
use App\Models\Log;
use App\Models\Section;
use App\Models\Suburb;
use App\Models\SuburbSection;
use App\Tools\Tools;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CorporationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['can:estadisticas'])->except('getPeopleOptions');
    }

    public function index(Request $request)
    {
        if ( $request->ajax() ) {

            $items = Corporation::whereNull('deleted_at')->get();

            return DataTables::of($items)
                ->addColumn('actions', 'admin.corporations._actions')
                ->rawColumns( [ 'actions' ] )
                ->make(true);
        }

        return view('admin.corporations.index');
    }

    public function create() {
        return view('admin.corporations.create', [
            'item' => new Corporation(),
        ]);
    }

    public function store(CorporationRequest $request)
    {
        $item = new Corporation();
        $item->fill($request->all());
        $item->short_name = Tools::getInitials($item->name);
        $item->save();

        Log::saver(Log::STORE_CORP, $item->id);

        self::updateCorporation($item);

        return redirect()->route('corporations.edit', $item->id);
    }

    public function edit($id)
    {
        $item = Corporation::findOrFail($id);
        
        return view('admin.corporations.edit', [
            'item' => $item,
        ]);
    }

    public function update(CorporationRequest $request, $id) {
        $item = Corporation::findOrFail($id);
        $item->fill($request->all());
        $item->short_name = Tools::getInitials($item->name);
        $item->save();

        Log::saver(Log::UPDATE_CORP, $item->id);

        self::updateCorporation($item);

        return redirect()->route('corporations.index');
    }

    public function destroy($id) 
    {
        $item = Corporation::findOrFail($id);
        $item->delete();

        Log::saver(Log::DESTROY_CORP, $item->id);

        return response()->json(array(
            'success' => true,
            'message' => 'La corporación ha sido eliminada correctamente'
        ));
    }


    // PEOPLE

    public function peopleTable(Request $request) {

        $corporation_id = $request->get('corporation_id');

        $items = CorporationPerson::where('corporation_id', $corporation_id)->get();

        $items = Tools::setOrder($items);

        return DataTables::of($items)
            ->addColumn('actions', 'admin.corporations.people._actions')
            ->rawColumns( [ 'actions' ] )
            ->make(true);

    }

    public function createPerson(Request $request) {

        $corporation_id = $request->get('corporation_id');

        return view('admin.corporations.people._create', [
            'corporation_id' => $corporation_id
        ]);
    }

    public function storePerson(Request $request) {

        $person = new CorporationPerson();
        $person->fill($request->all());
        $person->save();

        Log::saver(Log::STORE_CORP_PERSON, $person->id);
        
        return response()->json(array(
            'success' => true,
            'message' => 'Promotor agregado correctamente.'
        ));

    }

    public function editPerson(Request $request) {
        $id = $request->get('id');
        $person = CorporationPerson::find($id);
        
        return view('admin.corporations.people._edit', [
            'person' => $person
        ]);
    }

    public function updatePerson(Request $request) {
        $id = $request->get('id');
        $person = CorporationPerson::find($id);
        $person->fill($request->all());
        $person->save();

        Log::saver(Log::UPDATE_CORP_PERSON, $person->id);
        
        return response()->json(array(
            'success' => true,
            'message' => 'Promotor actualizado correctamente.'
        ));

    }

    public function destroyPerson(Request $request) {

        $id = $request->get('id');
        $person = CorporationPerson::find($id);
        $person->delete();

        Log::saver(Log::DESTROY_CORP_PERSON, $person->id);

        return response()->json(array(
            'success' => true,
            'message' => 'El promotor ha sido eliminado correctamente'
        ));

    }

    public function getPeopleOptions(Request $request) {
        $corporation_id = $request->get('corporation_id');
        $items = CorporationPerson::where('corporation_id', $corporation_id)->get();

        return view('admin.corporations.people._options', [
            'items' => $items
        ]);
    }

    public static function updateCorporation($corporation) {
        DB::table('people')
            ->where('corporation_id', $corporation->id)
            ->update([ 'corporation' => $corporation->short_name ]);
    }


}
