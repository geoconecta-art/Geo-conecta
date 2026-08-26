<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Booth;
use App\Models\Section;
use Illuminate\Http\Request;

class BoothController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /******************************
     *    Modulo ELECTORAL   *
     ******************************/
    //Método que regresa la información de todas las casillas a la vista Index
    public function index($type){
        $fields = ($type == 'electoral') ?  Booth::getColumnNames(4, 29) :  Booth::getColumnNames(31, 40);
        $view = ($type == 'electoral') ?  'index' :  '_quick-count';
        $title = ($type == 'electoral') ?  'Módulo Electoral' :  'Conteo Rápido';
        $booths = Booth::select(array_merge($fields, ['id', 'section', 'letters', 'type', 'image']))
                                ->get();

        return view('admin.casillas.index' ,[
            'title' => $title,
            'booths' => $booths,
            'fields' => $fields,
            'type' => $type,
        ]);
    }

    //Método que se encarga de encontrar la información releacionada
    //a la sección seleccionada. Dependiendo del tipo se extraerán los
    //campos a editar.
    public function modalPolitical($id, Request $request){
        $type = $request->get('type');  //Extrae el tipo de conteo que se realizará (rápido o normal).

        $fields = ($type == 'electoral') ?  Booth::getColumnNames(4, 29) :  Booth::getColumnNames(31, 40);
        $booth = Booth::where('id', $id)
                    ->select(array_merge($fields, ['id', 'section', 'letters', 'type', 'image']))
                    ->first();

        return view('admin.casillas._modal-edit',[
            'title' => 'Sección ' . $booth->section . ' - Apellidos ' . $booth->letters,
            'booth' => $booth,
            'fields' => $fields,
            'type' => $type,
        ]);
    }

    //Método que se encarga de actualizar los registros de las casillas
    public function store($id, Request $request){
        $booth = Booth::find($id);
        $fields = $request->get('fields');
        $type = $request->get('type');

        if ( $type == 'electoral' ) {
            if ( $request->hasFile('image') ) {
                $file = $request->image;
                $name = $file->getClientOriginalName();
                $date = date('YmdHis');
                $n_explode = explode('.', $name);
                $n_explode[0] = $n_explode[0] . '_' . $date;
                $new_name = implode('.', $n_explode);
    
                $file->move(public_path() . '/images/booth/', $new_name);
                $booth->image = '/images/booth/' . $new_name;
            }
            else if($request->get('has_image') == 0){
                if ($booth->image != null)
                    self::destroyImage($booth->image);
                $booth->image = null;
            }
        }
        
        for($i = 0; $i < count($fields); $i++){
            $booth[$fields[$i]] = ($request->get($fields[$i]) == null) 
                ? 0 
                : $request->get($fields[$i]);

        }
        
        $booth->save();

        if ( $type == 'electoral' ) {
            Log::saver(Log::UPDATE_ELECTORAL, $booth->id);
        } else {
            Log::saver(Log::UPDATE_RAPIDO, $booth->id);
        }

        return [$booths = $booths = Booth::all(),$fields, $type];
    }

    //Esté método se encarga de eliminar las imagenes del disco.
    private function destroyImage($src){
        $image_path = public_path(). $src;
        if( file_exists($image_path)){
            unlink($image_path);
        }
    }

    public function map($type){
        $pan = [];
        $morena = [];
        $title = '';

        switch($type){
            case 'electoral':
                $pan = [
                    'pan', 'pri', 'prd', 'na', 
                    'pan_pri_prd_na', 'pan_pri_prd', 'pan_pri_na', 
                    'pan_prd_na', 'pan_pri', 'pan_prd', 'pan_na', 
                    'pri_prd_na', 'pri_prd', 'pri_na', 'prd_na',
                ];
            
                $morena = [
                    'verde', 'pt', 'morena', 'morena_verde_pt', 'verde_pt', 'verde_morena', 'pt_morena',
                ];

                $title = 'Mapa Electoral';
                break;
            
            case 'conteo-rapido':
                $pan = ['pan_2', 'pri_2', 'prd_2', 'na_2'];
                $morena = ['verde_2', 'morena_2', 'pt_2'];
                
                $title = 'Conteo Rápido';
                break;
        }

        return self::showMap($pan, $morena, $title, $type);
        
    }

    private function showMap($panFields, $moreFields, $title, $type){
        $secs = Section::getSections()->pluck('section')->toArray();

        $casillas = Booth::whereIn('section', $secs)
                        //->select(array_merge($panFields,['section'], $moreFields))
                        ->get();

        //Numero de Votos por Partido (nvp)
        $nvp = [];
        foreach (array_merge($panFields, $moreFields) as $field) {
            $nvp [] = self::countVotes($casillas, $secs, $field);
        }

        $votesA = ($type == 'electoral') 
                    ? self::countPanVotes($casillas, $secs) 
                    : self::quickCountPanVotes($casillas, $secs);

        $votesC = ($type == 'electoral') 
                    ? self::countMorVotes($casillas, $secs) 
                    : self::quickCountMorVotes($casillas, $secs);
        
        $votesMC = ($type == 'electoral') 
                    ? self::countVotes($casillas, $secs, 'mc') 
                    : self::countVotes($casillas, $secs, 'mc_2');

        $votesN = ($type == 'electoral') 
                        ? self::countVotes($casillas, $secs, 'nulos') 
                        : self::countVotes($casillas, $secs, 'nulos_2');
                
        $votesO = ($type == 'electoral') 
                    ? self::countVotes($casillas, $secs, 'otros') 
                    : self::countVotes($casillas, $secs, '');
        
        $newPanFields = [];
        foreach ($panFields as $field) {
            $newPanFields [] = ($type == 'conteo-rapido') 
                                ? strtoupper(str_replace('_2', '', $field)) 
                                : strtoupper(str_replace('_', ' ', $field));
                                /*
                                ? strtoupper(str_replace('_', ' ', $field)) 
                                : strtoupper(str_replace('2', ' ', str_replace('_', ' ', $field)));
                                */
        }

        $newMorFields = [];
        foreach ($moreFields as $field) {
            $newMorFields [] = ($type == 'conteo-rapido') 
                                ? strtoupper(str_replace('_2', '', $field)) 
                                : strtoupper(str_replace('_', ' ', $field));
                                /*
                                ? strtoupper(str_replace('_', ' ', $field)) 
                                : strtoupper(str_replace('2', ' ', str_replace('_', ' ', $field)));
                                */
        }

        return view('admin.casillas.electoral-map', [
            'title' => $title,
            'votesA' => $votesA,
            'votesC' => $votesC,
            'votesMC' => $votesMC,
            'votesN' => $votesN,
            'votesO' => $votesO,
            'nvp' => $nvp,
            'panFields' => $newPanFields,
            'morFields' => $newMorFields,
            'type' => $type,
        ]);   
    }

    
    private function countPanVotes($panCasillas, $secs){
        $votes = array_fill_keys($secs, 0);

        foreach ($panCasillas as $pc) {
            $votes[$pc->section] += $pc['pan'] + $pc['pri'] + $pc['prd'] + $pc['na'] + $pc['pan_pri_prd_na'] + $pc['pan_pri_prd'] + $pc['pan_pri_na'] + $pc['pan_prd_na'] + $pc['pan_pri'] + $pc['pan_prd'] + $pc['pan_na'] + $pc['pri_prd_na'] + $pc['pri_prd'] + $pc['pri_na'] + $pc['prd_na'];
        }

        return $votes;
    }

    private function countMorVotes($morCasillas, $secs){
        $votes = array_fill_keys($secs, 0);

        foreach ($morCasillas as $mc) {
            $votes[$mc->section] += $mc['verde'] + $mc['pt'] + $mc['morena'] + $mc['morena_verde_pt'] + $mc['verde_pt'] + $mc['verde_morena'] + $mc['pt_morena'];
        }

        return $votes;
    }

    private function countVotes($casillas, $secs, $field){
        $votes = array_fill_keys($secs, 0);
        foreach ($casillas as $key => $cas) {
            $votes[$cas->section] += $cas[$field];
        }

        return $votes;
    }

    private function quickCountPanVotes($panCasillas, $secs){
        $votes = array_fill_keys($secs, 0);

        foreach ($panCasillas as $pc) {
            $votes[$pc->section] += $pc['pan_2'] + $pc['pri_2'] + $pc['prd_2'] + $pc['na_2'];
        }

        return $votes;
    }

    private function quickCountMorVotes($morCasillas, $secs){
        $votes = array_fill_keys($secs, 0);
        
        foreach ($morCasillas as $mc) {
            $votes[$mc->section] += $mc['verde_2'] + $mc['pt_2'] + $mc['morena_2'];
        }

        return $votes;
    }


    public function updateBoothsByCSV()
    {
        $path = 'csv/casillas.csv';
        $row = 0;

        if (($gestor = fopen($path, 'r')) !== false) {
            while (($columns = fgetcsv($gestor, 30000, ',')) !== false) {

                if ( $row > 0 ) {

                    //dd($columns);

                    $section = trim($columns[0]);
                    $type = trim($columns[1]);
                    $letters = trim($columns[2]);

                    if ( $section != '' && $type != '' && $letters != '' ) {
                        $booth = new Booth();
                        $booth->section = $section;
                        $booth->type = $type;
                        $booth->letters = $letters;
                        $booth->save();
                    }
                }
                    
                    
                $row++;
            }

            fclose($gestor);
        }

        return response()->json(array(
            'success' => true,
            'message' => $row - 1 . ' casillas fueron actualizadas',
        ));
    }
    

}
