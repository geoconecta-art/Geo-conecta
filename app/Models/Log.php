<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    use HasFactory;

    const STORE_PERSON = 1;         
    const UPDATE_PERSON = 2;         
    const DESTROY_PERSON = 3;         
    const DISABLED_PERSON = 4;    

    const STORE_CORP = 5;    
    const UPDATE_CORP = 6;  
    const DESTROY_CORP = 7; 

    const CHANGE_LEVEL = 8;

    const STORE_CORP_PERSON = 9;         
    const UPDATE_CORP_PERSON = 10;         
    const DESTROY_CORP_PERSON = 11;
    
    const SET_VOTING_DATE = 12;
    const DELETE_VOTING_DATE = 13;

    const STORE_VOTE = 14;
    const UPDATE_VOTE = 15;
    const DESTROY_VOTE = 16;

    const UPDATE_ELECTORAL = 17;
    const UPDATE_RAPIDO = 18;


    public static function saver($type, $people_id) {
        $log = new Log();
        $log->type = $type;
        $log->person_id = $people_id;
        $log->user_id = Auth::user()->id;
        $log->save();
    }

    public static function calculatePeopleByType($user_id, $type) {
        return DB::table('logs')
            ->join('people', 'logs.person_id', 'people.id')
            ->where('people.type', $type)
            ->where('logs.user_id', $user_id)
            ->where('logs.type', 1)
            ->count();
    }

}
