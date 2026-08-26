<?php

namespace App\Models;

// use Illuminate\Auth\Authenticatable;
// use Jenssegers\Mongodb\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\Model;
// use Jenssegers\Mongodb\Auth\User as Authenticatable;

use CuongNX\MongoPermission\Models\Permission;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use MongoDB\Laravel\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
use CuongNX\MongoPermission\Traits\HasRoles;
use MongoDB\BSON\ObjectId;

// use Illuminate\Foundation\Auth\Access\Authorizable;
// use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
// // use Maklad\Permission\Traits\HasRoles;
// use CuongNX\MongoPermission\Traits\HasRoles;

// class User extends Model implements AuthenticatableContract, AuthorizableContract
class User extends Authenticatable
{
    // use Authenticatable, Authorizable, HasRoles, SoftDeletes;
    use Notifiable, HasRoles, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_area',
        'id_subarea',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    //     'email_verified_at',
    //     'deleted_at',
    // ];


    public function area(){
        return $this->belongsTo(Area::class, 'id_area', '_id');
    }
}