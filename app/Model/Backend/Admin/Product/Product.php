<?php

namespace App\Model\Backend\Admin\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function unit(){
        return $this->belongsTo('App\Unit', 'unit_id', 'id');
    }
}
