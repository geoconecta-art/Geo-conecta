<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class FolioController extends Controller
{
    private $folioPath;

    public function __construct()
    {
        $this->folioPath = storage_path('folio.txt');
        
        // Asegurarse de que el archivo existe
        if (!File::exists($this->folioPath)) {
            // Inicializa el archivo con el primer folio en el formato deseado
            File::put($this->folioPath, 'XXXXXX00000000X000');
        }
    }

    public function getFolio()
    {
        try {
            // Leer el folio actual de manera segura
            $currentFolio = File::get($this->folioPath);
            
            // Extraer y convertir la parte incrementable del folio
            $incrementablePart = substr($currentFolio, 15, 3);
            $newIncrementablePart = str_pad((int) $incrementablePart + 1, 3, '0', STR_PAD_LEFT);
            
            // Formar el nuevo folio
            $newFolio = substr($currentFolio, 0, 15) . $newIncrementablePart;
            
            // Guardar el nuevo folio en el archivo de manera segura
            $this->writeToFile($newFolio);
            
            // Pasar el nuevo número de folio a la vista
            return response()->json(['folio' => $newFolio]);
        } catch (\Exception $e) {
            // Registrar el error y mostrar un mensaje de error amigable
            \Log::error('Error incrementando el folio: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }

    private function writeToFile($newFolio)
    {
        // Abrir el archivo en modo escritura
        $file = fopen($this->folioPath, 'w');

        // Bloquear el archivo para escritura
        if (flock($file, LOCK_EX)) {
            // Escribir el nuevo folio en el archivo
            fwrite($file, $newFolio);
            fflush($file); // Asegurar que los datos se escriban en el disco
            // Desbloquear el archivo
            flock($file, LOCK_UN);
        }

        // Cerrar el archivo
        fclose($file);
    }
}
