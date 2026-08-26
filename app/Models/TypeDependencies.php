<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class TypeDependencies extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'type_dependencies';
    protected $table = 'type_dependencies';

    protected $fillable = [
        'name',
    ];

    // Relación uno a muchos
    public function dependencies()
    {
        return $this->hasMany(Area::class, 'id_type')->whereNull('deleted_at');
    }
}
