<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Purchase\Purchase;

class Store extends Model
{
    protected $guarded=['id'];

    public function stocks(){
    	return $this->hasMany(Purchase::class, 'store_id');
    }
}
