<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = ['id'];
    public function sell(){

        return $this->hasMany(Sell::class,'customer_id','id')->where('type', 'sell');
    }
    
    public function type(){

    	return $this->belongsTo('App\CustomerType','type_id','id');
    }

    public function sell_payment()
    {
     return $this->hasMany(Sell::class,'customer_id','id')->where('type', 'payment');
    }
    
    public function stock_return(){

        return $this->hasMany(Sell::class,'customer_id','id')->where('type','stock_return');
    }
}
