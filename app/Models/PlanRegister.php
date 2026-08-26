<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class PlanRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'inventory_registers';
    protected $table = 'inventory_registers';

    protected $fillable = [
        'id_area',
        'id_subarea',
        'id_plan',
        'georefence',
        'attributes',
        'catastral',
    ];

    protected $guarded = [];

    public function planeacion()
    {
        return $this->belongsTo(Plan::class, 'id_plan');
    }
}
