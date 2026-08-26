<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateVotersByCSV()
    {
        $path = 'csv/ln.csv';
        $row = 0;

        if (($gestor = fopen($path, 'r')) !== false) {
            while (($columns = fgetcsv($gestor, 30000, ',')) !== false) {

                if ($row > 0) {

                    $voter = new Voter();
                    $voter->id_ext = trim($columns[0]);
                    $voter->cve = trim($columns[1]);
                    $voter->nombre = trim($columns[2]);
                    $voter->paterno = trim($columns[3]);
                    $voter->materno = trim($columns[4]);
                    $voter->fecnac = trim($columns[5]);
                    $voter->sexo = trim($columns[6]);
                    $voter->calle = trim($columns[7]);
                    $voter->int = trim($columns[8]);
                    $voter->ext = trim($columns[9]);
                    $voter->colonia = trim($columns[10]);
                    $voter->cp = trim($columns[11]);
                    $voter->e = trim($columns[12]);
                    $voter->d = trim($columns[13]);
                    $voter->m = trim($columns[14]);
                    $voter->s = trim($columns[15]);
                    $voter->l = trim($columns[16]);
                    $voter->mza = trim($columns[17]);
                    $voter->consec = trim($columns[18]);
                    $voter->cred = trim($columns[19]);
                    $voter->folio = trim($columns[20]);
                    $voter->nac = trim($columns[21]);
                    $voter->curp = trim($columns[22]);
                    $voter->save();
                }

                $row++;
            }

            fclose($gestor);
        }

        return response()->json(array(
            'success' => true,
            'message' => $row - 1 . ' votantes fueron actualizados',
        ));
    }

    public function searchINE($ine)
    {
        $path = 'csv/ln.csv';
        $row = 0;

        if (($gestor = fopen($path, 'r')) !== false) {
            while (($columns = fgetcsv($gestor, 30000, ',')) !== false) {

                if ($row > 0) {

                    $cve = trim($columns[1]);
                    if ($ine == $cve) {

                        $nombre = str_replace('√ë', 'Ñ', trim($columns[2]));
                        $paterno = str_replace('√ë', 'Ñ', trim($columns[3]));
                        $materno = str_replace('√ë', 'Ñ', trim($columns[4]));
                        $calle = str_replace('√ë', 'Ñ', trim($columns[7]));
                        $int = trim($columns[8]);
                        $ext = trim($columns[9]);
                        $colonia =  str_replace('√ë', 'Ñ', trim($columns[10]));;
                        $cp = trim($columns[11]);
                        $s = trim($columns[15]);
                        $l = trim($columns[16]);
                        $mza = trim($columns[17]);
                        $curp = trim($columns[22]);

                        return response()->json(array(
                            'success' => true,
                            'cve' => $cve,
                            'nombre' => $nombre,
                            'paterno' => $paterno,
                            'materno' => $materno,
                            'calle' => $calle,
                            'int' => $int,
                            'ext' => $ext,
                            'colonia' => $colonia,
                            'cp' => $cp,
                            'seccion' => $s,
                            'lote' => $l,
                            'mza' => $mza,
                            'curp' => $curp,
                        ));
                    }

                }

                $row++;
            }

            fclose($gestor);
        }

        return response()->json(array(
            'success' => false,
            'error' => 'Registro no encontrado',
        ));
    }

    public function searchCVE($firstName, $lastName1, $lastName2, $street)
    {
        $path = 'csv/ln.csv';
        $row = 0;

        if (($gestor = fopen($path, 'r')) !== false) {
            while (($columns = fgetcsv($gestor, 30000, ',')) !== false) {

                if ($row > 0) {

                    $nombre = str_replace('√ë', 'Ñ', trim($columns[2]));
                    $paterno = str_replace('√ë', 'Ñ', trim($columns[3]));
                    $materno = str_replace('√ë', 'Ñ', trim($columns[4]));
                    $calle = str_replace('√ë', 'Ñ', trim($columns[7]));

                    if ($firstName == $nombre && $lastName1 == $paterno && $lastName2 == $materno && trim($street) !== "") {

                        similar_text($street, $calle, $percent);
                        if ($percent >= 80 || $street == $calle || strpos($street, $calle) !== false || strpos($calle, $street) !== false) {
                            $cve = trim($columns[1]);
                            $int = trim($columns[8]);
                            $ext = trim($columns[9]);
                            $colonia =  str_replace('√ë', 'Ñ', trim($columns[10]));;
                            $cp = trim($columns[11]);
                            $s = trim($columns[15]);
                            $l = trim($columns[16]);
                            $mza = trim($columns[17]);
                            $curp = trim($columns[22]);

                            return response()->json(array(
                                'success' => true,
                                'cve' => $cve,
                                'nombre' => $nombre,
                                'paterno' => $paterno,
                                'materno' => $materno,
                                'calle' => $calle,
                                'int' => $int,
                                'ext' => $ext,
                                'colonia' => $colonia,
                                'cp' => $cp,
                                'seccion' => $s,
                                'lote' => $l,
                                'mza' => $mza,
                                'curp' => $curp,
                            ));
                        }
                    }
                }
                $row++;
            }

            fclose($gestor);
        }

        return response()->json(array(
            'success' => false,
            'error' => 'No hay coincidencias',
        ));
    }

    public function getInfo(Request $request)
    {
        $ine = $request->get('ine');

        if ($ine) {
            return self::searchINE($ine);
        } else {
            $firstName = $request->get('firstName');
            $lastName1 = $request->get('lastName1');
            $lastName2 = $request->get('lastName2');
            $street = $request->get('street');

            return self::searchCVE($firstName, $lastName1, $lastName2, $street);
        }

    }

}
