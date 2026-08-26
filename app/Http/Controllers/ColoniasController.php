<?php

namespace App\Http\Controllers;

use App\Models\Colonia;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ColoniasController extends Controller
{
    private $utils;

    public function __construct() {
        $utils = new UtilsController();
        $this->middleware('auth')->only('index');
        $this->middleware(['can:colonias']);
    }

    // Lleva a la vista principal del módulo colonias
    public function index(){
        return view('admin.suburbs.index');
    }

    // Actualiza los datos de la tabla
    public function updateTable(){
        $auxSuburbs = Colonia::whereNull('deleted_at')->get();
        $suburbs = [];

        foreach ($auxSuburbs as $key => $suburb) {
            $suburbs[] = [
                'index' => ($key + 1),
                'id' => $suburb->id,
                'name' => $suburb->name,
                'cp' => $suburb->cp,
            ];
        }

        return DataTables::of($suburbs)
                ->addColumn('actions', 'admin.suburbs._actions')
                ->rawColumns(['actions'])
                ->make(true);
    }

    // Decide cuál será el modal que se mostrará
    public function showModal(Request $request){
        $type = $request->get('type');

        return $type == "crear" 
            ? self::showCreateModal()
            : ( $type == "editar" 
                ? self::showEditModal($request) 
                : self::showDeleteModal($request));
    }

    // Retorna la vista del modal para crear una nueva colonia
    private function showCreateModal(){
        return view('admin.suburbs._create');
    }

    // Crea una nueva colonia  
    public function createSuburb(Request $request){
        $this->validate($request, [
            'name' => 'required|string|min:1',
            'cp' => 'required|string|min:5',
        ]);

        $name = $request->get('name');
        $cp = $request->get('cp');

        $suburb = new Colonia([
            'name' => $name,
            'cp' => $cp,
        ]);

        $suburb->save();
    }

    // Muestra el modal de edición con la respectiva información de la colonia a editar
    private function showEditModal(Request $request){
        $id = $request->get('id_suburb');
        $suburb = Colonia::find($id);

        return view('admin.suburbs._edit', [
            'suburb' => $suburb,
        ]);
    }

    // Actualiza la información de la colonia seleccionada
    public function updateSuburb($suburb, Request $request){
        $this->validate($request, [
            'name' => 'required|string|min:1',
            'cp' => 'required|string|min:5',
        ]);

        $name = $request->get('name');
        $cp = $request->get('cp');

        $col = Colonia::find($suburb);
        $col->update([
            'name' => $name,
            'cp' => $cp,
        ]);
    }

    // Muestra el modal de confirmación de eliminación
    private function showDeleteModal(Request $request){
        $id = $request->get('id_suburb');
        $suburb = Colonia::find($id);

        return view('admin.suburbs._delete',[
            'suburb' => $suburb,
        ]);
    }

    // 
    public function deleteSuburb($col){
        $suburb = Colonia::find($col);
        $name = $suburb->name;
        $suburb->delete();

        return response()->json([
            'message' => 'La colonia ' . $name . ' ha sido eliminada del sistema',
            'status' => 200,
        ]);
    }
}
