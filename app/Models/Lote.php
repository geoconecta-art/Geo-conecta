<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Lote extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = "mongodb";
    protected $collection = "lotes";
    protected $table = 'lotes';
    protected $fillable = [
        'id',
        'clave',
        'superficie',
        'uso',
        'location',
        'capa',
    ];
}
