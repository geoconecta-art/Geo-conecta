<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\BSON\ObjectId;

class Weather extends Model
{
    use HasFactory, SoftDeletes;

    protected $collection = "weather";
    protected $connection ="mongodb";
    protected $table = 'weather';

    protected $fillable = [
        'id',
        'weather',
        'icon',
        'desription',
        'updated_at',
    ];
}
