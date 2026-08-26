<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Tools\Tools;
use App\Models\Application;
use App\Models\Code;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth')->except('store');
        $this->middleware(['can:beneficiados']);
        
    }

    public function index(Request $request) {

        if ( $request->ajax() ) {
            $applications = Application::all();
            
            foreach ( $applications as $application ) {
                $application->coordinates = $application->latitude . ';' . $application->longitude;
                $application->date = Tools::formatTimeYmdToDmy($application->created_at);
                $application->birthday = Tools::formatYmdToDmy($application->birthday);
            }

            return response()->json(['data' => $applications]);
        }

        return view('admin.applications.index');
    }
    
    public function map() {

        $applications = Application::whereNotNull('latitude')->whereNotNull('longitude')->get();
        
        $markers = [];
        
        foreach ( $applications as $application ) {
            $new_marker = [
                'lat' => (float)$application->latitude, 
                'lng' => (float)$application->longitude, 
                'title' => $application->name . ' ' . $application->last_name 
            ];
            
            $markers[] = $new_marker;
        }
          
        return view('admin.applications.map', compact('markers'));
    }
    
    public function store(Request $request) {
        
        $rules = [
            'birthday' => 'required|date_format:d/m/Y',
            'email' => 'nullable|email',
            'phone' => 'required|digits:10'
        ];
        
        $messages = [
            'birthday.required' => 'El campo Fecha de Nacimiento es obligatorio',
            'birthday.date_format' => 'El campo Fecha de Nacimiento debe estar en formato dd/mm/yyyy',
            'phone.required' => 'El campo Teléfono es obligatorio',
            'phone.digits' => 'El campo Teléfono debe ser un número de 10 dígitos',
            'email.email' => 'El campo Email debe ser una dirección de correo válida'
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);

        if ( $validator->fails() ) {

            $errors = $validator->errors()->toArray();

            return response()->json(array(
                'success' => false,
                'message' => 'No es posible realizar el registro',
                'chat' => false,
                'errors' => $errors
            ));
        }
        
        
        //$recaptcha = $request->get('recaptcha');
        
        //if ( self::validateReCaptcha($recaptcha) ) {
            
            $application = new Application();
            $application->fill($request->all());
            $application->birthday = Tools::formatDmyToYmd($application->birthday);
            $application->save();

            $r_code = $request->get('code');
            $code = Code::where('code', $r_code)->first();

            if ( isset($code) ) {

                $code->active = 1;
                $code->save();

                return response()->json(array(
                    'success' => true,
                    'chat' => true,
                    'message' => 'Gracias por tu registro, ahora puedes chatear con la candidata haciendo click en el botón de WhatsApp'
                ));

            } else {

                $application->code = null;
                $application->save();

                return response()->json(array(
                    'success' => true,
                    'chat' => false,
                    'message' => 'Gracias por tu registro, en breve nos pondremos en contacto contigo'
                ));
            }
    
            
            
       /* } else {
            
            return response()->json(array(
                'success' => false,
                'message' => 'No es posible realizar el registro'
            ));
        } */
        
    }
    
    public function validateReCaptcha($recaptcha) {
        
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=6LfUoWIpAAAAALKJQ3nMyhYAuk9055pwtLKzpIG3&response=' . $recaptcha;
        $response = file_get_contents($url);
        $responseData = json_decode($response);
        
        if ( $responseData->success ) {
            return true;
        } else {
            return false;
        }
        
    }

    public function generateCodes($n) {

        // Generar los códigos
        for ($i = 0; $i < $n; $i++) {
            $new_code = Tools::generateCode();
            $code = Code::where('code', $new_code)->first();

            if ( isset($code) ) {
                $i--;

            } else {
                $code = new Code();
                $code->code = $new_code;
                $code->save();
            }
        }

    }
    
 
    
}
