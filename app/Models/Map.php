<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class Map extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'maps';
    protected $table = 'map';

    protected $fillable = [
        'id',
        'id_user',
        'id_area',
        'name',
        'plans',
        'system_key',
        'area_key',
        'map_key',
        'editions',
        'areas',
    ];

    public static function mapsByArea( $id_area ){
        $maps = $id_area != null
            ? Map::where('id_area', $id_area)->whereNull('deleted_at')->get()
            : Map::whereNull('deleted_at')->get();

        return $maps;
    }

    public function inventories() {
        return Plan::whereIn('id', $this->plans ?? []);
    }
}
