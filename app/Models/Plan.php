<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;


class Plan extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'inventories';
    protected $table = 'inventories';

    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'name', 
        'id_area',
        'id_subarea',
        'address',
        'catastral',
        'georefence',
        'attributes',
        'type_plan',
        'geometry',
        'color',
        'created_at'
    ];

    protected $casts = [
        // 'attributes' => 'array',
    ];

    protected $guarded = [];

    public function area(){
        return $this->belongsTo(Area::class, 'id_area');
    }

    public function subarea()
    {
        return $this->belongsTo(Subdirections::class, 'id_subarea');
    }

    public function registros(){
        return $this->hasMany(PlanRegister::class, 'id_plan')->whereNull('deleted_at');
    }

    public static function inventoriesByArea( $id_area ){
        $plans = $id_area
            ? Plan::where('id_area', $id_area)->whereNull('deleted_at')->orderBy('id_area')->orderBy('id_subarea')->orderBy('name')->get()
            : Plan::whereNull('deleted_at')->orderBy('id_area')->orderBy('id_subarea')->orderBy('name')->get();

        return $plans;
    }

    /**
     * Obtiene los registros de la planeación que posean latitud y longitud, pero solo si son puntos
     * 
     * @return  array Retorna un arreglo de registros que posean latitud y longitud
     */
    public function registrosGeoPoints() {
        return $this->hasMany(PlanRegister::class, 'id_plan')
            ->whereNull('deleted_at')
            ->whereNotNull('georeference._Inicial.Latitud_number_0_georeference_Inicial_attr')
            ->whereNotNull('georeference._Inicial.Longitud_number_1_georeference_Inicial_attr');
    }

    /**
     * Obtiene los registros de la planeación que posean una latitud y longitud inicial, así como 
     * una latitud y longitud final.
     * 
     * @return  array Retorna un arreglo de registros que posean latitudes y longitudes
     */
    public function registrosGeoLines() {
        return $this->hasMany(PlanRegister::class, 'id_plan')
            ->whereNull('deleted_at')
            ->whereNotNull('georeference._Inicial.Latitud_number_0_georeference_Inicial_attr')
            ->whereNotNull('georeference._Inicial.Longitud_number_1_georeference_Inicial_attr')
            ->whereNotNull('georeference._Final.Latitud_number_0_georeference_Final_attr')
            ->whereNotNull('georeference._Final.Longitud_number_1_georeference_Final_attr');
    }

    /**
     * Obtiene los registros de la planeación que no posean latitud y longitud
     * 
     * @return  array Retorna un arreglo de registros que posean latitud y longitud
     */
    public function registrosNoGeoPoints(){
        return $this->hasMany(PlanRegister::class, 'id_plan')
            ->whereNull('deleted_at')
            ->whereNull('georeference._Inicial.Latitud_number_0_georeference_Inicial_attr')
            ->whereNull('georeference._Inicial.Longitud_number_1_georeference_Inicial_attr');
    }

    /**
     * Obtiene los registros de la planeación que no posean latitud y longitud
     * 
     * @return  array Retorna un arreglo de registros que posean latitud y longitud
     */
    public function registrosNoGeoLines(){
        return $this->hasMany(PlanRegister::class, 'id_plan')
            ->whereNull('deleted_at')
            ->whereNull('georeference._Inicial.Latitud_number_0_georeference_Inicial_attr')
            ->whereNull('georeference._Inicial.Longitud_number_1_georeference_Inicial_attr')
            ->whereNull('georeference._Final.Latitud_number_0_georeference_Final_attr')
            ->whereNull('georeference._Final.Longitud_number_1_georeference_Final_attr');
    }

}