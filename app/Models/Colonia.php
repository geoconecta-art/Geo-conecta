<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colonia extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = "mongodb";
    protected $collection = "colonias";
    protected $table = 'colonias';
    protected $fillable = [
        'id',
        'name',
        'cp',
    ];
}
