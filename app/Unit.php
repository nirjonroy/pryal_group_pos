<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public static function getStatus(){

    	return ['Complete'=>'1','Work By Done'=>'2','Partnership Investment'=>'3','Running'=>'0'];
    }
}
