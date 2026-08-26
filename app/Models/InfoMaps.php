<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class InfoMaps extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'info_maps';
    protected $table = 'info_maps';

    protected $fillable = [
        'id',
        'typeMap',
        'name_dependency',
        'key_map',
        'name_map',
        'id_plans',
        'info_rows'
    ];
}
