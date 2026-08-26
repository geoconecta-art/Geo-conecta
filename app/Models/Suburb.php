<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Suburb extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $table = 'suburbs';
    protected $fillable = [
        'name', 
    ];

    public static function getName($id) {
        $suburb = DB::table('suburbs')->where('id', $id)->first();

        if ( isset($suburb) )
            return $suburb->name;

        return '';
    }

    public static function getByZipCode($zip_code) {
        return DB::table('suburbs')->where('zip_code', $zip_code)->get();
    }

    public static function getZipCodes() {
        return DB::table('suburbs')->distinct()->pluck('zip_code');
    }


}
