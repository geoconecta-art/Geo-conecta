<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'last_name', 'age', 'marital_status', 'birthday', 'address', 'email', 'phone', 'latitude', 'longitude', 'code'];
    
}
