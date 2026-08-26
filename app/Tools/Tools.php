<?php

namespace App\Tools;

use App\Models\Folio;
use App\Tools\Enums\EnumFolioTypes;
use Exception;
use ZipArchive;
use DateTime;

class Tools
{
    /**
     * -----------------------------------------------------------------------------------------------------------------
     *                                                 TABLES
     * -----------------------------------------------------------------------------------------------------------------
     */

    public static function setOrder($items)
    {
        $order = 1;
        foreach ($items as $item) {
            $item->order = $order;
            $order++;
        }

        return $items;
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     *                                                   DATES
     * -----------------------------------------------------------------------------------------------------------------
     */


    /**
     * Change the date format 'dd/mm/yyyy' => 'yyyy-mm-dd'
     * @param $date1
     * @return string
     */
    public static function formatDmyToYmd($date1) {
        $date2 = $date1;
        if ($date1 !== null and $date1 !== '') {
            $date1 = array_map('trim', explode('/', $date1));
            $date2 = $date1[2] . '-' . $date1[1] . '-' . $date1[0];
        }
        return $date2;
    }

    /**
     * Change the date format 'yyyy-mm-dd' => 'dd/mm/yyyy'
     * @param $date1
     * @return string
     */
    public static function formatYmdToDmy($date1) {
        $date2 = $date1;
        if ($date1 !== null and $date1 !== '') {
            $date1 = array_map('trim', explode('-', $date1));
            $date2 = $date1[2] . '/' . $date1[1] . '/' . $date1[0];
        }
        return $date2;
    }

    public static function formatTimeYmdToDmy($date1) {
        $date2 = $date1;
        if ($date1 !== null and $date1 !== '') {
            $date2 = array_filter(array_map('trim', explode(' ', $date1)));
            $date2 = self::formatYmdToDmy($date2[0]) . ' ' . $date2[1];
        }
        return $date2;
    }

    public static function dateDifference($date_1, $date_2)
    {
        $datetime_1 = new \DateTime($date_1);
        $datetime_2 = new \DateTime($date_2);
        return $datetime_2->diff($datetime_1);
    }

    public static function getDayName($day_en) {

        switch ($day_en) {
            case 'Monday':
                $day_es = 'Lunes';
                break;

            case 'Tuesday':
                $day_es = 'Martes';
                break;

            case 'Wednesday':
                $day_es = 'Miércoles';
                break;

            case 'Thursday':
                $day_es = 'Jueves';
                break;

            case 'Friday':
                $day_es = 'Viernes';
                break;

            case 'Saturday':
                $day_es = 'Sábado';
                break;

            case 'Sunday':
                $day_es = 'Domingo';
                break;

            default:
                $day_es = '';
        }

        return $day_es;
    }

    public static function formatPeriod($start_date, $end_date) {
        $date1 = self::formatYmdToDmy($start_date);
        $date2 = self::formatYmdToDmy($end_date);

        if ( $date1 === $date2 )
            return $date1;

        return $date1 . ' - ' . $date2;
    }
    
    public static function getShortMonthName($month) {

        switch ($month) {
            case '01':
                $day_es = 'Ene';
                break;

            case '02':
                $day_es = 'Feb';
                break;

            case '03':
                $day_es = 'Mar';
                break;

            case '04':
                $day_es = 'Abr';
                break;

            case '05':
                $day_es = 'May';
                break;

            case '06':
                $day_es = 'Jun';
                break;

            case '07':
                $day_es = 'Jul';
                break;
                
            case '08':
                $day_es = 'Ago';
                break;
                
            case '09':
                $day_es = 'Sep';
                break;
                
            case '10':
            $day_es = 'Oct';
            break;
            
            case '11':
            $day_es = 'Nov';
            break;
            
            case '12':
            $day_es = 'Dic';
            break;

            default:
                $day_es = '';
        }

        return $day_es;
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     *                                                 CODE
     * -----------------------------------------------------------------------------------------------------------------
     */

    public static function generateCode() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lenght = 10;
        $code = '';
        for ($i = 0; $i < $lenght; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     *                                                  MONEDA
     * -----------------------------------------------------------------------------------------------------------------
     */

    /**
     * Convierte una cantidad numérica en centavos, en el equivalente en pesos.
     * Ejemplo 2300.25 pesos => $2,300.25
     * @param $float
     * @return mixed|string
     */
    public static function floatToPeso($float)
    {
        $neg = $float < 0;

        if ($neg)
            $float = $float * -1;

        // Se garantiza que el numero sea cadena para poder trabajar con él
        $str_number = (string)($float);

        $n_explode = array_map('trim', explode('.', $str_number));

        $aux = strrev($n_explode[0]);

        $final_number = '';
        for ($i = 0; $i < strlen($aux); $i++) {
            $final_number .= $aux[$i];
            if (($i + 1) % 3 === 0 && $i < strlen($aux) - 1) {
                $final_number .= ',';
            }
        }

        $final_number = strrev($final_number);

        $final_number .= '.';
        if (count($n_explode) > 1) {
            $final_number .= self::padRight($n_explode[1], 2);
        }
        else {
            $final_number .= '00';
        }

        $final_number = '$' . $final_number;

        if ($neg)
            $final_number = '-' . $final_number;

        return $final_number;
    }

    public static function padRight($num, $size) {
        $s = $num . '000000000';
        return substr($s, 0, $size);
    }


    /**
     * -----------------------------------------------------------------------------------------------------------------
     *                                                  STRINGS
     * -----------------------------------------------------------------------------------------------------------------
     */

    public static function getInitials($string) {

        $initials = '';

        $string_a = array_filter(array_map('trim', explode(' ', $string)));

        for ($i = 0; $i < count($string_a); $i++) {

            $initial = $string_a[$i][0];
            $initials .= $initial;
        }

        return $initials;
    }

    public static function removeAccents($string) {
        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );

        return $string;
    }

    public static function arrayToJSON($array) {
        $json = json_encode($array);
        $json = str_replace('"', '\"', $json);
        $json = str_replace("'", "\'", $json);

        return $json;
    }

    public static function getNewName($name) {
        $date = date('YmdHis');
        $n_explode = explode('.', $name);
        $n_explode[0] = $n_explode[0] . '_' . $date;
        $new_name = implode('.', $n_explode);

        return $new_name;
    }

    public static function saveImage($file, $path) {
        $name = $file->getClientOriginalName();
        $new_name = self::getNewName($name);
        $file->move($path, $new_name);   

        return $new_name;
    }


     /**
     * ----------------------------------------------------------------------------------------------------------
     *                                          SUPER - SLUG
     * ----------------------------------------------------------------------------------------------------------
     */


    /**
     * @param $string
     * @return mixed|string
     * URL SEMANTICA
     */

     public static function convertToSlug($string)
     {
         $string = Tools::sanearString($string);
         return $string;
     }
 
     public static function convertToFileName($string)
     {
         $string = trim( $string );
         // Sanear string pero con la bandera del slug en falso (osea que no es un slug y puede tener punto)
         $string = Tools::sanearString($string, false);
         return $string;
     }
 
     /**
      * Reemplaza todos los acentos por sus equivalentes sin ellos
      *
      * @param $string
      * @param bool|true $slug true : Se trata de una URL, false : Un nombre de archivo
      * @return mixed|string
      */
     private static function sanearString($string, $slug = true)
     {
         $string = trim($string);
 
         $string = str_replace(
             array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
             array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
             $string
         );
 
         $string = str_replace(
             array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
             array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
             $string
         );
 
         $string = str_replace(
             array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
             array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
             $string
         );
 
         $string = str_replace(
             array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
             array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
             $string
         );
 
         $string = str_replace(
             array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
             array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
             $string
         );
 
         $string = str_replace(
             array('ñ', 'Ñ', 'ç', 'Ç'),
             array('n', 'N', 'c', 'C',),
             $string
         );
 
         //Esta parte se encarga de eliminar cualquier caracter extraño
         $arr= array("\\", "¨", "º", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":");
 
         if( $slug) { // los slug no llevan punto
             array_push($arr, ".");
         }
         $string = str_replace($arr, '', $string);
         $string = str_replace("  ", " ", $string);
         $string = str_replace(" ", "-", $string);
         $string = strtolower($string); 
         $string = self::removeStopWords($string);

         return $string;
     }
 
     private static function removeStopWords($string){
         //Esta parte se encarga de eliminar cualquier caracter extraño
         $arr= array(
             "-a-","-al-","-de-","-del-","-el-","-en-","-es-","-etc-","-ha-","-ir-","-me-","-mi-","-mis-","-mio-","-muy-","-ni-","-no-","-nos-","-os-","-por-",
             "-que-","-se-","-ser-","-si-","-sin-","-sino-","-so-","-sobre-","-soy-","-sr-","-sra-","-tan-","-te-","-tenemos-","-tener-","-tengo-","-ti-",
             "-tiene-","-toda-","-tomar-","-tras-","-tu-","-va-","-y-","-ya-","-yo-", "-con-", "-para-", "-la-",            
         );
 
         $string = str_replace($arr, '-', $string);
 
         $string = trim($string, "-");
 
         return $string;
     }

    public static function downloadZip($pdf_output, $file_name, $zip_name) {
        
        $date = self::getDateTime();

        // Crear un archivo temporal para el PDF
        $pdf_path = storage_path('app/public/' . $date . $file_name);
        file_put_contents($pdf_path, $pdf_output);

        // Crear un archivo zip temporal
        $zip_path = storage_path('app/public/' . $zip_name . '-' . $date . '.zip');
        $zip = new ZipArchive;

        if ( $zip->open($zip_path, ZipArchive::CREATE) === TRUE ) {
            $zip->addFile($pdf_path, $file_name);
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo zip'], 500);
        }

        // Eliminar el archivo PDF temporal
        unlink($pdf_path);

        // Copiar el archivo zip a otro directorio para conservarlo
        $new_directory = storage_path('app/permanent/'); // Cambia esto al directorio que desees
        if ( !file_exists($new_directory) ) {
            mkdir($new_directory, 0755, true);
        }
        $new_zip_path = $new_directory . $zip_name . '-' . $date . '.zip';
        copy($zip_path, $new_zip_path);

        return response()->download($zip_path)->deleteFileAfterSend(true);
    }

    public static function getDateTime() {
        $microtime = microtime(true);
        $date = new DateTime();
        $date->setTimestamp(floor($microtime));
        $microseconds = sprintf("%06d", ($microtime - floor($microtime)) * 1000000);
        return $date->format("YmdHis") . $microseconds;
    }
 
 
}
