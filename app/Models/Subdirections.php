<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Subdirections extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'areas';
    protected $table = 'areas';

    protected $fillable = ['name', 'id_dependency'];

    // Relación inversa
    public function area() {
        return $this->belongsTo(Area::class, 'id_dependency');
    }

    // Relación con planeaciones
    public function planeaciones()
    {
        return $this->hasMany(Plan::class, 'id_subarea')->whereNull('deleted_at');
    }

    // Obtiene las subdirecciones según el id del área
    public static function getSubByArea( $id_area ){
        $subareas = $id_area 
            ? self::whereNull('deleted_at')->where('id_area', $id_area)->orderBy('id_area')->get()
            : self::whereNull('deleted_at')->orderBy('id_area')->get();

        return $subareas;
    }

}