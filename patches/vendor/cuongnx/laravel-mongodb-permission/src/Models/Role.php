<?php

namespace CuongNX\MongoPermission\Models;

use CuongNX\MongoPermission\Traits\HasRoles;
use MongoDB\Laravel\Eloquent\Model;

class Role extends Model
{
    use HasRoles;

    protected $connection = 'mongodb';
    protected $collection = 'roles';

    protected $fillable = ['name', 'guard_name', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];
}