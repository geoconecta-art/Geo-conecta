<?php

namespace App\Http\Controllers;

// MODELOS
use App\Exports\InventaryExport;
use App\Imports\DataImportCSV;
use App\Models\Area;
use App\Models\Subdirections;
use App\Models\Plan;
use App\Models\PlanRegister;

// IMPORTS
use App\Imports\RegisterImport;
use App\Models\Lote;
use App\Models\Map;
use App\Models\Weather;
use App\Tools\Tools;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;

class UtilsController extends Controller
{
    private $typeData = [
        'text' => 'string',
        'number' => 'numeric',
        'email' => 'email',
        'date' => 'date',
        'time' => 'date_format:H:i',
        'datetime-local' => 'date_format:Y-m-d\TH:i',
        'textarea' => 'string',
        'image' => '',
        'file' => '',
        'select' => 'string',
        'radio' => 'string',
        'checkbox' => 'array',
        'button' => ''
    ];

    // GENERAR COLOR ALEATORIO DEL PARA EL MARCADOR
    function generarColorHexadecimal() {
        // Genera un número aleatorio entre 0 y 255 para cada componente RGB
        $rojo = dechex(rand(0, 255));
        $verde = dechex(rand(0, 255));
        $azul = dechex(rand(0, 255));

        // Asegúrate de que cada componente tenga dos dígitos
        $rojo = str_pad($rojo, 2, '0', STR_PAD_LEFT);
        $verde = str_pad($verde, 2, '0', STR_PAD_LEFT);
        $azul = str_pad($azul, 2, '0', STR_PAD_LEFT);

        return "#" . $rojo . $verde . $azul;
    }

    /**
     * Genera un color aleatorio en formato Hexadecimal en de un color "chillante"
     * 
     * @return  string El color en formato hexadecimal (ejemplo: '#FF5733).
     * 
     * */
    public static function getRandomColor() {
        $r = rand(125, 255);
        $g = rand(125, 255);
        $b = rand(125, 255);

        $color = sprintf('%02X%02X%02X', $r, $g, $b);  //str_pad(dechex(rand(0, 16777215)), 6);
        return $color;
    }

    /**
     * Crea un llave en base a los valores e un atributo.
     * 
     * @param   array $attr El atributo que se utilizará para generar la llave.
     * @param   int $index El índice se utiliza para generar la llave.
     * @param   string $suffix Es el sufijo de la llave (gen, address, georeference_Inicial, georeference_Final)
     * 
     * @return  string Regresa una cadena de texto que corresponde a la llave generada en base al atributo.
     * */
    public static function createFieldKey($attr, $index, $suffix) {
        return trim(str_replace(" ", "", str_replace(".", "_", $attr['title']))) . '_' . $attr['type'] . '_' . $index . '_' . $suffix . '_attr';
    }


    /**
     * Crea las llaves (o keys) de un conjunto de atributos
     * 
     * @param   array $attributes es el conjunto de atributos (o campos) apartir de las cuales se generarán las llaves de acceso
     * @param   string $sufix es un el sufijo que deberá ir en la llave de acceso
     * 
     * @return  array Retorna un arreglo de llaves de acceso creadas a apartir de los atributos pasados.
     */
    public static function createFieldKeys($attributes, $sufix) {
        $keys = [];

        foreach ($attributes as $key => $attribute) {
            if (is_array($attribute)) {
                if ($attribute['type'] != 'button' && !isset($attribute['deleted_at'])) {
                    $keys[] = self::createFieldKey($attribute, $key, $sufix);
                }
            }
        }

        return $keys;
    }

    /**
     * Crea las llaves para acceder a los datos en el orden que aparecen en los attributes de una planeción, para esto se hace uso de la recursividad
     * 
     * @param   array $attrs es un conjunto de atributos que se usará para obtener las llaves (o keys) de acceso a los datos
     * @param   Plan $plan es un objeto de tipo Plan el cual corresponde a la planeación de la cuál se extraerán las llaves
     * @param   string $sufix es una cadena de texto que se utilizará para generar la llave.
     * 
     * @return  array Retorna un arreglo que contiene el conujunto de llaves de acceso a los datos de una planeación.
     * 
     */
    public function getFieldKeysFromAttr($attrs, $plan, $suffix) {
        $attrKeys = [];

        foreach ($attrs as $key => $attr) {
            if (is_array($attr)) {
                if ($attr['type'] != 'button' && !isset($attr['deleted_at'])) {
                    $attrKeys[] = self::createFieldKey($attr, $key, $suffix);
                    // trim( str_replace(" ", "", str_replace(".", "_", $attr['title'] ) ) ) . '_' . $attr['type'] . '_' . $key . '_' . $suffix . '_attr';
                }
            } else {
                if ($key != "editable") {
                    $matches = explode("_", $attr);
                    $geoItems = [];

                    if (count($matches) == 2) {
                        $geoKey = $matches[0];
                        $geoIndex = '_' . $matches[1];
                        $geoItems = $plan->$geoKey[$geoIndex];
                    } else {
                        $geoItems = $plan->$attr;
                    }

                    $attrKeys[$attr] = self::getFieldKeysFromAttr($geoItems, $plan, $attr);
                }
            }
        }

        return $attrKeys;
    }


    /**
     * Crea las reglas de validación de un conjunto de atributos.
     * 
     * @param   array $attributes es un conjunto de atributos a partir de los cuales se generarán las reglas.
     * @param   string $sufix es el sufijo que se usará para generar la llave (key) de la regla
     * 
     * @return  array Retorna un arreglo que contiene las reglas de validación del conjunto de atributos pasados
     */
    public function createAttrRules($attributes, $sufix) {
        $rules = [];

        foreach ($attributes as $key => $attribute) {
            if (is_array($attribute)) {
                if ($attribute['type'] != 'button' && $attribute['title'] != 'No. Int' && !isset($attribute['deleted_at'])) {
                    $name = self::createFieldKey($attribute, $key, $sufix);
                    $fileRules = isset($attribute['type_file']) ? 'mimes:jpeg,png,jpg,gif|max:2048' : '';
                    $rules[$name] = 'required|' . $this->typeData[$attribute['type']] . $fileRules;
                }
            }
        }

        return $rules;
    }

    /**
     * Genera todas las reglas para una planeación mediante recursividad
     * 
     * @param   array $attrs es el conjunto de atributos del cuál se obtendrán las reglas
     * @param   Plan $plan es un objeto de tipo Plan que corresponde a la planeación
     * @param   string $suffix es una caadena de texto de la cual se hace uso para generar la llave (key) del aattributo o campo
     * 
     * @return  array Retorna un conjunto de reglas que se podrán utilizar paraa validaar los camposde un formulario
     */
    public function createPlanRules($attrs, $plan, $suffix) {
        $rules = [];

        foreach ($attrs as $key => $attr) {
            if (is_array($attr)) {
                if ($attr['type'] != 'button' && $attr['title'] != 'No. Int' && !isset($attr['deleted_at'])) {
                    $name = self::createFieldKey($attr, $key, $suffix);
                    $fileRules = isset($attr['type_file']) ? 'mimes:jpeg,png,jpg,gif|max:2048' : '';
                    $typeRule = $this->typeData[$attr['type']];
                    $rules[$name] = 'nullable|' . $typeRule . $fileRules;
                }
            } else {
                if ($key != "editable") {
                    $matches = explode("_", $attr);
                    $geoItems = [];

                    if (count($matches) == 2) {
                        $geoKey = $matches[0];
                        $geoIndex = '_' . $matches[1];
                        $geoItems = $plan->$geoKey[$geoIndex];
                    } else {
                        $geoItems = $plan->$attr;
                    }

                    $rules = array_merge($rules, self::createPlanRules($geoItems, $plan, $attr));
                }
            }
        }

        return $rules;
    }


    /**
     * Obtiene los nombres de los campos de un conjunto de atributos (atributos, georreferencia o dirección )
     * 
     * @param   array $attrs es el conjunto de atributos ed los que se extraerán dichos nombres
     * @param   string $sufix es un cadena de texto que será utilizado para aquellas ocasiones en las que será necesario concatenar un sufijo al nombre del campo
     * 
     * @return  array Retorna un arreglo que contiene los nombres de los atributos pasados.
     */
    public function getNameAttrFields($attrs, $sufix = "") {
        $fields = [];

        foreach ($attrs as $attr) {
            if (is_array($attr)) {
                if ($attr['type'] != 'button' && !isset($attr['deleted_at'])) {
                    $fields[] = trim($attr['title'] . ' ' . $sufix);
                }
            }
        }

        return $fields;
    }

    /**
     * Obtiene todos los nombres de los atributos (campos) de una planeación mediante recursividad
     * 
     * @param   array $attrs debe ser un arreglo de atributos (o campos)
     * @param   Plan $plan es un objeto tipo Plan que corresponde a la planeación
     * @param   string $suffix es un cadena de texto que será utilizado para aquellas ocasiones en las que será necesario concatenar un sufijo al nombre del campo
     * @param   boolean $flag ayuda a dfeterminar si se hará uso del sufijo
     * 
     * @return  array Retorna un arreglo conteniendo todos los nombres de los atributos (campos) de una planeación
     * 
     */
    public function getNamesPlanFields($attrs, $plan, $suffix = "", $flag) {
        $fields = [];

        foreach ($attrs as $key => $attr) {
            if (is_array($attr)) {
                if ($attr['type'] != 'button' && !isset($attr['deleted_at'])) {
                    $fields[] = trim($attr['title'] . ' ' . $suffix);
                }
            } else {
                if ($key != "editable") {
                    $matches = explode("_", $attr);
                    $geoSuffix = "";
                    $geoItems = [];

                    if (count($matches) == 2) {
                        $geoKey = $matches[0];
                        $geoIndex = '_' . $matches[1];
                        $geoItems = $plan->$geoKey[$geoIndex];
                        $geoSuffix = $flag ? $matches[1] : '';

                    } else {
                        $geoItems = $plan->$attr;
                    }

                    $fields = array_merge($fields, self::getNamesPlanFields($geoItems, $plan, $geoSuffix, $flag));
                }
            }
        }

        return $fields;
    }

    /**
     * Valida si los valores satisfacen las reglas de validación del plan
     * 
     * @param   array $rules Es un arreglo de reglas que serán utilizadas para la validación
     * @param   array $values Arreglo de valores que se van a comparar para determnar si satisface las reglas
     * 
     * @return  Validator Regresa un objeto de tipo Validator
     */
    public static function validation($rules, $values) {
        $validator = Validator::make($values, $rules);

        return $validator;
    }

    /**
     * Se encarga de generar las propiedad KML de los registros pasados por referencia.
     * 
     * @param   PlanRegister $row es el registro del cual se obtendrán los valores de las propiedades
     * @param   array $values arreglo de valores extraído directamente de $row
     * @param   array $attrs es un arreglo que contiene los atrtibutos de los cuales se obtendrá la información
     * @param   Plan $plan este objeto hace referencia al Inventario al que pertenece el registro
     * @param   string $suffix es una cadena que se utilizará para generar la llave de acceso a la información
     * @param   \DOMDocument es el documento XML al que se va a agregar la información 
     * @param   Object $parentNode es el nodo padre al que se va a enlazar la información
     * 
     * @return  \DOMDocument regresa el nodo padre en la que se ha colocado toda la información
     */
    public function getKMLProperties($row, $values, $attrs, $plan, $suffix, $dom, $parentNode) {

        if (!$parentNode)
            $parentNode = $dom->createElement('ExtendedData');

        foreach ($attrs as $key => $attr) {
            if (is_array($attr)) {
                if ($attr['type'] != 'button' && !isset($attr['deleted_at'])) {
                    $attrKey = self::createFieldKey($attr, $key, $suffix);

                    if (isset($values[$attrKey])) {
                        $kmlProperties = ( str_contains( $attrKey, "_file_" ) || str_contains( $attrKey, "_image_" ) )
                            ? $dom->createElement('Data', public_path( $values[$attrKey] ?? '') )
                            : $kmlProperties = $dom->createElement('Data', htmlspecialchars( $values[$attrKey] ?? '' ) );

                        $kmlProperties->setAttribute('name', $attr['title']);
                        $parentNode->appendChild($kmlProperties);
                    }
                }
            } else {
                if ($key != "editable") {

                    $matches = explode("_", $attr);
                    $geoAttrs = $geoValues = [];

                    if (count($matches) == 2) {
                        $geoKey = $matches[0];
                        $geoIndex = "_" . $matches[1];
                        $geoAttrs = $plan->$geoKey[$geoIndex];
                        $geoValues = $row->$geoKey[$geoIndex];
                    } else {
                        $geoAttrs = $plan->$attr;
                        $geoValues = $row->$attr;
                    }

                    self::getKMLProperties($row, $geoValues, $geoAttrs, $plan, $attr, $dom, $parentNode);
                } else {
                }
            }
        }

        return $parentNode;
    }

    /**
     * Obtiene las coordenadas de un registro en base a la geometría 
     * 
     * @param   PlanRegister $row es un objeto tipo PlanRegister del cual se extraerán las coordenadas
     * @param   string $geometry es una cadena de texto que índica la geometría del objeto (Point, LineString)
     * 
     * @return  array $coors es un arreglo que contiene todas las coordenadas en orden Longitud - Latitud
     */
    public static function getCoorsFromRow($row, $geometry) {
        $coors = [];

        $coors[0][] = floatval($row['georeference']['_Inicial']['Longitud_number_1_georeference_Inicial_attr']);
        $coors[0][] = floatval($row['georeference']['_Inicial']['Latitud_number_0_georeference_Inicial_attr']);

        if ($geometry == 'LineString') {
            $coors[1][] = floatval($row['georeference']['_Final']['Longitud_number_1_georeference_Final_attr'] ?? 0.0);
            $coors[1][] = floatval($row['georeference']['_Final']['Latitud_number_0_georeference_Final_attr'] ?? 0.0);
        }

        return $geometry == 'Point' ? $coors[0] : $coors;
    }

    /**
     * Obtiene las propiedades y los valores para ser agregadas a un archivo GeoJSON.
     * 
     * @param   PlanRegister $row es un objeto que contiene la información de un registro de un inventario
     * @param   array $values es un arreglo que contiene los valores del registro
     * @param   array $attrs es un arreglo que contiene las propiedades del registro
     * @param   Plan $plan es un objeto que contiene la información del inventario
     * @param   string $suffix es una cadena de texto que será utilizada para extraer la key del atributo
     * 
     * @return  array Retorna un arreglo de valores correspondientes al registro
     */
    public static function getGeoJSONProperties($row, $values, $attrs, $plan, $suffix) {
        $properties = [];

        foreach ($attrs as $key => $attr) {
            if (is_array($attr)) {
                if ($attr['type'] != 'button' && !isset($attr['deleted_at'])) {
                    $attrKey = self::createFieldKey($attr, $key, $suffix);
                    $properties[$attr['title']] = ( str_contains( $attrKey, "_file_" ) || str_contains( $attrKey, "_image_" ) )
                        ? public_path( $values[$attrKey] ?? '')
                        : $values[$attrKey] ?? "";
                }
            } else {
                if ($key != "editable") {
                    $matches = explode("_", $attr);
                    $geoAttrs = $geoValues = [];

                    if (count($matches) == 2) {
                        $geoKey = $matches[0];
                        $geoIndex = "_" . $matches[1];
                        $geoAttrs = $plan->$geoKey[$geoIndex];
                        $geoValues = $row[$geoKey][$geoIndex];
                    } else {
                        $geoAttrs = $plan->$attr;
                        $geoValues = $row[$attr];
                    }

                    $properties = array_merge($properties, self::getGeoJSONProperties($row, $geoValues, $geoAttrs, $plan, $attr));
                }
            }
        }

        return $properties;
    }

    /**
     * Genera una cadena de coordenadas codificadas
     * 
     * @param   Array $coors es un arreglo que posee un grupo de coordenadas
     * @return  string Se retorna una cadena de texto que representa las coordenadas codificadas
     */
    public static function createStringEncode( $coors ){
        $encoded = "";
        $prevLat = 0;
        $prevLng = 0;

        foreach ($coors as $key => $point) {
            $lat = floatval($point[0]);
            $lng = floatval($point[1]);

            // Calculamos la diferencia de latitud y longitud
            $dLat = $lat - $prevLat;
            $dLng = $lng - $prevLng;

            // Codificamos la diferencia usando el algoritmo de Google
            $encoded .= self::encodeCoordinate($dLat);
            $encoded .= self::encodeCoordinate($dLng);

            // Actualizamos las coordenadas previas
            $prevLat = $lat;
            $prevLng = $lng;
        }

        return $encoded;
    }

    /**
     * Aplica los cambios necesarios para que una coordenada sea adecuada para para la codificación en base 64
     * 
     * @param   double $coord es un valor flotante o doble que representa una coordenada
     * @return  string Se retorna una cadena de texto que representa la coordenada codificada
     */
    public static function encodeCoordinate($coord) {
        $coord = (int) round($coord * 1E5);
        
        if( $coord < 0 )
            $coord = ( (~$coord) << 1) + 1;
        else
            $coord = ($coord << 1);

        $coord = self::encodeBase64($coord);
        return $coord;
    }


    /**
     * Codifica en base 64 un número pasado y lo convierte en una cadena de texto
     * 
     * @param   double $num representa un número flotante que se codificará
     * @return  string Retorna una cadena de texto que representa el número codificado.
     */
    public static function encodeBase64($num) {
        $encoded = '';

        while ($num >= 0x20) {
            $encoded .= chr((($num & 0x1f) | 0x20) + 63);
            $num >>= 5;
        }

        $encoded .= chr($num + 63);

        return $encoded;
    }


    /**
     * Obtiene las coordenadas del archivo especificado
     * 
     * @param   string $json_file_path representa la ubicación del archivo
     * 
     * @return  Array Retorna un arreglo que representa las coordenadas extraídas del archivo
     */
    public static function getCityCoors($json_file_path) {
        $json_file_data = json_decode(file_get_contents($json_file_path), true);
        $coors = $json_file_data["features"][0]["geometry"]["coordinates"][0][0];

        $coors = array_map(function($coor) {
            return [ $coor[1], $coor[0] ];
        }, $coors);

        $bounds = self::createStringEncode( $coors );

        return $bounds;
    }

    /**
     * 
     */
    public static function createLinesInfoMap($rows, $color) {
        $path = "path=color:0x". ( str_contains($color, "#") ? str_replace( "#","", $color ) : $color ) ."ff|weight:3|enc:";
        $linesInfoMap = "";

        foreach ($rows as $row) {
            $coors = [
                array_values( $row->georeference['_Inicial'] ),
                array_values( $row->georeference['_Final'] ),
            ];

            $encoded = $path . self::createStringEncode( $coors );
            
            $linesInfoMap .= $encoded . "&";
        }

        return $linesInfoMap;
    }

    public static function createCityLineInfoMap( $coors ){
        $path = "path=color:0xff0000ff|weight:3|enc:";
        $coord = array_map( function($coor){
            return [ $coor["lat"], $coor["lng"] ];
        }, $coors[0] );
        $cityInfoMap = $path . self::createStringEncode( $coord );

        return $cityInfoMap;
    }

    /**
     * 
     */
    public static function createLineStringInfoMap( $lines ){
        $path = "path=color:0x";
        $linesInfoMap = "";

        foreach ($lines as $line) {
            $start_coor = [
                $line[0][0]['lat'], $line[0][0]['lng'], 
            ];
            $end_coor = [
                $line[0][1]['lat'], $line[0][1]['lng'], 
            ];
            
            $coors = [ $start_coor, $end_coor ];
            $color = str_replace("#", "", $line[1]);

            $encoded = $path . $color . "ff|weight:3|enc:" . self::createStringEncode( $coors );
            
            $linesInfoMap .= $encoded . "&";
        }

        return $linesInfoMap;
    }

    /**
     * 
     */
    public static function createPointInfoMap($rows, $color) {
        $marker = "markers=size:tiny|color:0x" . (str_contains( $color, "#" ) ? str_replace( "#", "", $color ) : $color );
        $pointInfoMap = "";
        
        foreach ($rows as $key => $row) {
            $index = $key + 1;

            if( count( $row["georeference"]["_Inicial"] ) == 2 ){
                $coors = $row["georeference"]["_Inicial"];
                $lng_str = strval( array_pop( $coors ) );
                $lat_str = strval( array_pop( $coors ) );

                $lat = preg_replace('/[^0-9.\]]/', '', $lat_str);
                $lng = preg_replace('/[^0-9.\-]/', '', $lng_str );

                $pointInfoMap .= $marker . "|label:$index|" . "$lat,$lng" . "&";
            }

            if( $index > 172 )
                break;
        }

        return $pointInfoMap;
    }

    /**
     * Le da el formato a las coordenadas de los marcadores
      */
    public static function createMarkersInfoMap( $markers ){

        $marker = "markers=size:tiny|color:0x";
        $pointInfoMap = "";
        
        foreach ($markers as $key => $mark) {
            $index = $key + 1;
            $color = str_replace("#", "", $mark["color"]);
            $lat = $mark["lat"];
            $lng = $mark["lng"];

            $pointInfoMap .= $marker . $color . "|" . "$lat,$lng" . "&";
        }

        return $pointInfoMap;
    }


    /**
     * Se encarga de generar la imagen del mapa con la respectiva información de los marcadores o 
     */
    public static function getImageFromGoogleMaps($info_rows, $lat, $lng, $zoom, $typeMap, $size) {
        $api_key = "AIzaSyBqzmb8-x1yMJPF36nH_Z7fP9u00I3kdpo";
        $city_path = "path=color:0xff0000ff|weight:2|enc:";
        $city_coors = $city_path . self::getCityCoors("maps/1_LIMITE_ATIZAPÁN_DE_ZARAGOZA.geojson");
        
        $mapUrl = "https://maps.googleapis.com/maps/api/staticmap?center=$lat,$lng&zoom=$zoom&scale=2&size=$size&" . $info_rows . $city_coors . "&maptype=$typeMap&key=$api_key";
        // dd( $mapUrl );
        return $mapUrl;
    }

    /**
     * Coloca ceros a la izquierda para rellenar espacios y lograr un número de 4 dígitos
     * 
     * @param int $noPlans es un número entero 
     * @return string Retorna una cadena de texto con el formatro correcto para representar una clave de mapa, por ejemplo,
     * si se recibe el número 12 se retorna '0012'.
     */
    public static function createMapKey( $noPlans ){
        return str_pad( $noPlans, 4, 0, STR_PAD_LEFT );
    }

    /**
     * Almacena la información de los lotes
     * 
     */
    public static function saveLotes(){
        $json_file_path = "maps/CATASTRO_ATIZAPAN.geojson";
        $json_file_data = json_decode(file_get_contents($json_file_path), true);
        $features = $json_file_data['features'];
        $lotes = [];

        foreach ($features as $key => $feature) {
            $properties = $feature['properties'];

            $lotes[] = [
                'clave' => $properties['CLVE_CAT'],
                'superficie' => $properties['M2_TERRENO'],
                'uso' => $properties['USO'],
                'location' => $properties['UBI_PRED'],
                'capa' => $properties['layer'],
            ];
        }

        DB::connection( 'mongodb' )
            ->getMongoDB()
            ->selectCollection( 'lotes' )
            ->insertMany( $lotes );
    }

    /**
     * Obtiene la información de los lotes del archivo CATASTRO_ATIZAPAN
     */
    public static function getCatastroInfo(){
        $json_file_path = "maps/CATASTRO_ATIZAPAN.geojson";
        $json_file_data = json_decode( file_get_contents($json_file_path), true );
        $features = $json_file_data['features'];
        $lotes = [];

        foreach ($features as $feature) {
            $properties = $feature['properties'];
            $geometry = $feature['geometry'];
            $clave = $properties['CLVE_CAT'];
            $coordinates = $geometry['coordinates'][0][0];
            
            $lotes[$clave] = $coordinates;
        }

        return $lotes;
    }

    /**
     * Obtiene la información polígonal de un lote
     */
    public static function getCatastroInfoByLote($clve){
        $json_file_path = "maps/CATASTRO_ATIZAPAN.geojson";
        $json_file_data = json_decode( file_get_contents($json_file_path), true );
        $features = $json_file_data['features'];
        $lote = null;

        foreach ($features as $feature) {
            $properties = $feature['properties'];
            $geometry = $feature['geometry'];
            $clave = $properties['CLVE_CAT'];

            if( $clave == $clve ){
                $coordinates = $geometry['coordinates'][0][0];
                $coors = array_map(function($coord){
                    return [
                        'lat' => $coord[1], 
                        'lng' => $coord[0]
                    ];
                }, $coordinates);
                $lote = $coors;
                break;
            }
        }

        return $lote;
    }

    /**
     * Detecta la codificación de un archivo, en caso de no estar en UTF-8 se convierte a esta codificación
     * @param   string  $file_path es una cadena de texto que representa la ubicación del archivo que se quiere codificar
     * @return  string  La ruta del archivo codificado en UTF-8
     */
    public static function encodeFileContent( $file_path ){
        $content = file_get_contents( $file_path );
        $encoding = mb_detect_encoding($content, mb_list_encodings(), true);
        
        $content = $encoding != "UTF-8" ? iconv($encoding, 'UTF-8//TRANSLIT', $content) : $content;
        $tempFilePath = storage_path('app/temp_import.csv');
        
        file_put_contents( $tempFilePath, $content );

        return $tempFilePath;
    }


    /*********************************************************** IMPORTAR INFORMACIÓN DESDE ARCHIVOS CSV ***************************************************************/

    /**
     * Remueve los encabezados de un arreglo, para su posterior uso
     * 
     * @param   array   $fileHeaders es un arreglo que contiene todos los encabezados extraídos desde un archivo CSV
     * @param   array   $splitHeaders es un arreglo que contiene las posiciones a remover del arreglo de encabezados
     * 
     * @return  array   Retorna un arreglo con los encabezados filtrados
     */
    public static function splitHeaders( $fileHeaders, $splitHeaders ){
        foreach ($splitHeaders as $split) {
            if( $split ){
                unset( $fileHeaders[$split] );
            }
        }

        return $fileHeaders;
    }

    /**
     * Genera un arreglo de una dimensión, extrayendo los arreglos anidados.
     * 
     * @param   array   $attributes es un arreglo que puede contener arreglos anidados
     * 
     * @return  array   Retorna un arreglo de una sola dimensión
     */
    public static function flatte_array( $attributes ){
        $flatte = [];

        foreach ($attributes as $attr) {
            if( is_array($attr) ){
                $flatte = array_merge( $flatte, $attr );
            } else {
                $flatte[] = $attr;
            }
        }

        return $flatte;
    }

    /********************************************************* POSICIONES DE COORDENADAS ************************************************************* */

        /**
         * Obtiene los valores de las posiciones correspondientes a las coordenadas
         * 
         * @param   Request $request es un arreglo que contiene la información de la petición
         * @param   string  $geometry es una cadena de texto que indica la geometría del inventario (p. ej. 'Point')
         * 
         * @return  array Retorna un arreglo que contiene las posiciones
         */
        public static function getCoorsHeaderPositions(Request $request, $geometry){
            $headers = [
                '_Inicial' => [],
                '_Final' => [],
            ];

            $headers['_Inicial']['Latitud_number_0_georeference_Inicial_attr'] = $request->get('Latitud_Inicial', null);
            $headers['_Inicial']['Longitud_number_1_georeference_Inicial_attr'] = $request->get('Longitud_Inicial', null);

            if( $geometry == "LineString" ){
                $headers['_Final']['Latitud_number_0_georeference_Final_attr'] = $request->get('Latitud_Final', null);
                $headers['_Final']['Longitud_number_1_georeference_Final_attr'] = $request->get('Longitud_Final', null);
            }

            foreach ($headers as $key => $head) {
                $head = array_filter( $head, function( $head ) {
                    return !is_null( $head );
                });

                $headers[$key] = $head;
            }

            return $headers;
        }

        /**
         * Obtiene las posiciones de los encabezados referentes a las coordenadas (latitud, longitud) de un archivo CSV
         * 
         * @param   array   $fileHeaders es un arreglo que contiene todos los encabezados de un archivo CSV
         * @param   string  $geometry es una cadena de texto que indica la geometría del invent
         * 
         * @return  array   Retorna un arreglo que contiene las posiciones de los encabezados latitud, longitud.
         */
        public static function getCoorsHeaderPositionsFromCSV($fileHeaders){
            $headers = [
                '_Inicial' => [],
                '_Final' => [],
            ];

            $lats = array_keys( array_filter( $fileHeaders, function( $head ) {
                return preg_match( "/latitud/", strtolower( $head ) );
            }));

            $lngs = array_keys( array_filter( $fileHeaders, function( $head ) {
                return preg_match( "/longitud/", strtolower( $head ) );
            }));

            $indexHeaders = ['_Inicial', '_Final'];

            foreach ($lats as $key => $lat) {
                $index = $indexHeaders[ $key ];
                $headers[$index]['Latitud_number_0_georeference'.$index.'_attr'] = $lat;
            }

            foreach ($lngs as $key => $lng) {
                $index = $indexHeaders[ $key ];
                $headers[$index]['Longitud_number_1_georeference'.$index.'_attr'] = $lng;
            }

            return $headers;
        }


    /********************************************************* POSICIONES DE DIRECCIONES ************************************************************* */

        /**
         * Obtiene las posiciones de los encabezados correspondientes a la dirección
         * 
         * @param   array   $fileHeaders es un arreglo que contiene todos los encabezados de un archivo CSV
         * 
         * @return  array   Retorna un arreglo que contiene las posiciones de los encabezados referentes a las direcciones
         */
        public static function getAddressHeaderPositionsFromCSV( $fileHeaders ){
            $addressHeaders = ['calle', 'no. ext', 'no. int','c.p.', 'colonia'];
            $fileHeaders = array_map( "strtolower", $fileHeaders);
            $headers = [];

            foreach ($addressHeaders as $addHeaders) {
                $index = array_search( $addHeaders, $fileHeaders );

                if( $index ){
                    $headers[$addHeaders] = $index;
                } else {
                    $headers[$addHeaders] = null;
                }
            }

            return $headers;
        }

        public static function getCatastralHeaderPositionFromCSV( $fileHeaders ){
            $fileHeaders = array_map( "strtolower", $fileHeaders );
            $pos = [];

            foreach ($fileHeaders as $key => $header) {
                if( $header == "clave catastral" ){
                    $pos[ $header ] = $key;
                }
            }

            return $pos;
        }

        /**
         * Revisa si en un arreglo de encabezados existe algún campo correspondiente a la dirección
         * 
         * @param   array $fileHeaders Es un arreglo unidimensional de cadenas de texto que posee los encabezados que se deben revisar.
         * 
         * @return  boolean Regresa verdaderos o falso si el campo se 
         */
        public function thereAreAddressFields($addHeaders, $plan) {
            $status = false;

            $headers = array_filter( $addHeaders, function($head) {
                return $head != null;
            });

            if( !empty($headers) && empty($plan->address) ){
                $status = true;
            }

            return $status;
        }

        /**
         * Agrega los campos de dirección a la planeación
         * 
         * @param   string $id Cadena de texto que hace referencia a la planeaciión que se va a actualizar.
         * @param   int $index Posición en la que se colocarán los campos de dirección
         * @param   boolean $edit Permite definir si el los campoos de dirección se pueden editar o no.
         */
        public function addAddressField($id, $index, $edit) {
            $plan = Plan::find($id);
            $address = [
                'editable' => $edit,
                ['title' => 'Calle', 'type' => 'text', 'size' => '6', 'editable' => false],
                ['title' => 'No. Ext', 'type' => 'number', 'size' => '3', 'editable' => false],
                ['title' => 'No. Int', 'type' => 'number', 'size' => '3', 'editable' => false],
                ['title' => 'C.P.', 'type' => 'select', /*'options' => [],*/ 'size' => '4', 'editable' => false],
                ['title' => 'Colonia', 'type' => 'select', /*'options' => [],*/ 'size' => '8', 'editable' => false]
            ];

            $attrs = $plan->attributes;
            $index = $index >= 0 ? ($index + 1) : ( $plan->geometry == "LineString" ? count( $attrs ) - 2 : count( $attrs ) - 1 );
            array_splice($attrs, $index, 0, 'address');

            $plan->address = $address;
            $plan->attributes = $attrs;
            $plan->save();

            self::updateAddressFieldsInPlan( $id );

            return true;
        }

        /**
         * Agrega los campos de dirección a la planeación
         * 
         * @param   string $id Cadena de texto que hace referencia a la planeaciión que se va a actualizar.
         * @param   int $index Posición en la que se colocarán los campos de dirección
         * 
         * @return  Array   Retorna un arreglo que representa el nuevo atributo agregado
         */
        public function addCatastralField($id, $index, $edit) {
            $catastral = [ 'title' => 'Clave Catastral', 'type' => 'number',  'size' => '6', 'editable' => $edit ];

            $plan = Plan::find($id);
            $plan->catastral = [$catastral];

            $attrs = $plan->attributes;
            $index = $index >= 0 ? ($index + 1) : ( $plan->geometry == "LineString" ? count( $attrs ) - 2 : count( $attrs ) - 1 );
            array_splice($attrs, $index, 0, 'catastral');
            
            $plan->attributes = $attrs;
            $plan->save();

            // $catastralKey = self::createFieldKey( $catastral, $index, "catastral" );
            self::updateCatastralFieldsInPlan( $id );

            return $catastral;
        }

        /**
         * Actualiza los registros existentes, agregando los capos referentes a la dirección
         */
        public static function updateAddressFieldsInPlan( $id_plan ){
            $plan = Plan::find($id_plan);
            $addressKey = self::createFieldKeys( $plan->address ?? [], "address" );
            $addressValues = array_fill_keys( $addressKey, "" );
            $rows = PlanRegister::where( 'id_plan', $id_plan )->get();
            
            foreach ($rows as $row) {
                $row->address = $addressValues;
                $row->save();
            }
        }

        /**
         * Actualiza los registros existentes, agregando los capos referentes a la clave catastral
         */
        public static function updateCatastralFieldsInPlan( $id_plan ){
            $plan = Plan::find($id_plan);
            $catastralKey = self::createFieldKeys( $plan->catastral ?? [], "catastral" );
            
            $catastralValues = array_fill_keys( $catastralKey, "" );
            $rows = PlanRegister::where( 'id_plan', $id_plan )->get();

            foreach ($rows as $row) {
                $row->catastral = $catastralValues;
                $row->save();
            }
        }

        /**
         * Valida que la clave catastral pasada sea un valor valido
         */
        public static function valitadateCatastralKey(Request $request){
            $catastralKey = $request->get("catastralKey");
            $lote = Lote::where('clave', $catastralKey)->get();
            $result = false;

            if( count( $lote ) >= 1 )
                $result = true;

            return $result;
        }

        /**
         * Asocia la posición de los campos de dirección del archivo CSV con las KEYS de cada campo
         * 
         */
        public static function formatHeaders( $id_plan, $headers, $suffix ){
            $plan = Plan::find($id_plan);
            $attrs = $plan->$suffix;
            $newHeaders = [];

            if( !empty($headers) ){
                foreach ($attrs as $index => $attr) {
                    if( is_array( $attr ) ){
                        $key = self::createFieldKey( $attr, $index, $suffix );
                        $name = strtolower( $attr['title'] );
                        $newHeaders[$key] = $headers[$name];
                    }
                }
            }

            return $newHeaders;
        }

    /***************************************************************** CAMPOS DE PLANEACIÓN **************************************************************/
        /**
         * Agrega los campos extra al inventario
         */
        public static function addExtraFieldsToPlan( $extraFields, $id_plan ){
            $status = false;
            $newAttrs = [];

            if( !empty( $extraFields ) ){
                foreach ($extraFields as $extraField) {
                    $newAttrs[] = [
                        'title' => $extraField,
                        'type' => 'text',
                        'size' => '12',
                        'editable' => false,
                    ];
                }

                $plan = Plan::find($id_plan);
                $attrs = $plan->attributes;
                $index = $plan->geometry == "LineString" ? count( $attrs ) - 2 : count( $attrs ) - 1;
                array_splice( $attrs, $index, 0, $newAttrs );

                self::updateExtraFieldsInPlanRows( $newAttrs, $id_plan );

                $plan->attributes = $attrs;
                $plan->save();

                $status = true;
            }

            return $status;
        }

        /** 
         * Actualiza los registros existentes con los campos nuevos/extra del archivo CSV
        */
        public static function updateExtraFieldsInPlanRows( $newAttrs, $id_plan ){
            $rows = PlanRegister::where('id_plan', $id_plan)->get();
            
            foreach ($rows as $row) {
                $rowValues = $row->attributes;
                $index = count( $rowValues );
                foreach ($newAttrs as $newAttr) {
                    $newAttrKey = self::createFieldKey( $newAttr, $index, "gen" );
                    $rowValues[ $newAttrKey ] = '';
                }
                $row->attributes = $rowValues;
                $row->save();
            }
        }

        /**
         * Empareja las posiciones de los campos del archivo CSV con las KEYS de cada campo
         */
        public static function formatPlanHeaders( $id_plan, $headers ){
            $newHeaders = [];
            $plan = Plan::find( $id_plan );

            foreach ($plan->attributes as $index => $attr) {
                if( is_array( $attr ) ){
                    $key = self::createFieldKey( $attr, $index, "gen" );
                    $name = $attr['title'];
                    $pos = array_search( $name, $headers );

                    if( $pos !== false ){
                        $newHeaders[ $key ] = $pos;
                    } else {
                        $newHeaders[ $key ] = null;
                    }
                }
            }

            return $newHeaders;
        }

    /***************************************************** CREAR INVENTARIOS **********************************************************************/
    public static function createInventory(Request $request){
        $response = 0;

        $plan_info = $request->all();
        $plan_info['color'] = $plan_info['plan_color'];
        unset( $plan_info['plan_color'] );

        $plan = new Plan( $plan_info );
        $plan->_id = new ObjectId();
        
        try {
            $user = Auth::user();
            $area = Area::find( $plan->id_area );
            $subarea = Subdirections::find($plan->id_subarea);

            $subarea->planeaciones()->save($plan);
            
            $geoFields = ($plan->geometry == "LineString") ? [1, 2] : (($plan->geometry == "Point") ? [1] : []);
            foreach ($geoFields as $index => $field) {
                $id = $index == 0 ? '_Inicial' : ($index == count($geoFields) - 1 ? '_Final' : $field);
                self::createGeoFields($plan, $id);
            }

            $plan_system_count = count( Plan::whereNull('deleted_at')->get() );
            $system_key = UtilsController::createMapKey( $plan_system_count );

            $plan_area_count = count( Plan::where('id_area', $plan->id_area)->whereNull('deleted_at')->get() );
            $area_key = UtilsController::createMapKey( $plan_area_count );

            $map = new Map([
                'name' => $plan->name,
                'id_area' => $plan->id_area,
                'id_user' => $user->id,
                // 'id_subarea' => $subarea->id,
                'system_key' => $system_key,
                'area_key' => $area_key,
                'map_key' => $area->area_key . '-' . $system_key . '-' . $area_key,
                'plans' => [ $plan->id ],
            ]);
            
            $area->mapas()->save( $map );

            $response = $plan->id;
        } catch (\Throwable $th) {
            $response = false;
        }
        
        return $response;
    }

    // Crea y agrega los campos de georeferencia
    private static function createGeoFields($plan, $id){
        $georeference = $plan->georeference ?? [];
        $newGeoreference = [ 
            [ 'title' => 'Latitud', 'type' => 'number', 'size' => '4', 'editable' => false],
            [ 'title' => 'Longitud', 'type' => 'number', 'size' => '4', 'editable' => false],
            [ 'title' => 'Ubicar en el mapa', 'type' => 'button', 'size' => '4', 'editable' => false],
        ];
        $georeference[$id] = $newGeoreference;
        $plan->georeference = $georeference;

        $attributes = $plan->attributes ?? [];
        $attributes[] = 'georeference' . $id;
        $plan->attributes = $attributes;

        $plan->save();
    }


    /**
     * Obtiene los valores del clima
     */
    public static function updateWeather(){
        $weather = [];

        $downBounds = date( 'Y-m-d H:00:00' );
        $upBounds = date( 'Y-m-d H:00:00', strtotime( "+60 minutes", strtotime( $downBounds ) ) );

        $downTime = strtotime( $downBounds );
        $upTime = strtotime( $upBounds );

        $auxWeather = Weather::first();
        $weatherUpdated = $auxWeather->updated_at->format('Y-m-d H:i:s');
        $wUpdatedTime = strtotime($weatherUpdated);
        $changeWeather = !( $wUpdatedTime >= $downTime && $wUpdatedTime < $upTime );
        
        if( $changeWeather ){
            $apiKey = "4d637dcebf460922f94421784f3a0365";
            $cityId = '3532624';
            
            $client = new Client();
            
            try {
                $response = $client->get("https://api.openweathermap.org/data/2.5/weather", [
                    'query' => [
                        'id' => $cityId,
                        'appid' => $apiKey,
                        'units' => 'metric',
                        'lang' => 'es',
                        ]
                    ]);
                    
                $weatherData = json_decode($response->getBody()->getContents(), true);
                $auxWeather->weather = $weatherData["main"]["temp"] . "°";
                $auxWeather->icon = $weatherData["weather"][0]["icon"];
                $auxWeather->description = $weatherData["weather"][0]["description"];
                $auxWeather->updated_at = Carbon::now('UTC');
                $auxWeather->save();
            
            } catch (\Exception $e) {
                $weather['weather'] = $auxWeather->weather;
                $weather['weatherDes'] = $auxWeather->description;
                $weather['weatherImg'] = "https://openweathermap.org/img/wn/". $auxWeather->icon ."@2x.png";
            }
        }

        $weather['weather'] = $auxWeather->weather;
        $weather['weatherDes'] = $auxWeather->description;
        $weather['weatherImg'] = "https://openweathermap.org/img/wn/". $auxWeather->icon ."@2x.png";

        return $weather;
    }

    /************************************************* MAPA ****************************************************/
    public static function getInits( $area ){
        $words = explode( " ", $area );

        $result = array_map( function( $word ){
            return substr( $word, 0, 1 );
        }, $words );

        return implode( $result );
    }
}