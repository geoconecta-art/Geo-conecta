<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Area extends Model
{

    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'dependencies';
    protected $table = 'dependencies';

    protected $fillable = [
        'name',
        'id_type',
        'area_key',
    ];

    // Relación inversa
    public function types()
    {
        return $this->belongsTo(TypeDependencies::class, 'id_type');
    }

    // Relación uno a muchos
    public function subareas()
    {
        return $this->hasMany(Subdirections::class, 'id_dependency')->whereNull('deleted_at');
    }

    // Relación uno a muchos en planeaciones
    public function planeaciones(){
        return $this->hasMany(Plan::class, 'id_area')->whereNull('deleted_at');
    }

    //Relación uno a muchos en mapas
    public function mapas() {
        return $this->hasMany(Map::class, 'id_area')->whereNull('deleted_at');
    }
}
