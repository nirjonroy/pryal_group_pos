<?php

namespace App\Model\Backend\Admin\Supplier;

use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
class Supplier extends Model
{
    public function purchase(){

    	return $this->hasMany(Purchase::class,'supplier_id','id')->where('type','purchase');
    }

    public function purchase_payment(){

    	return $this->hasMany(Purchase::class,'supplier_id','id')->where('type','payment');
    }
    
    public function purchaseStockpayment(){

    	return $this->hasMany(Purchase::class,'supplier_id','id')->whereIn('type',['payment','stock_payment']);
    }

    public function type(){

    	return $this->belongsTo('App\SupplierType','type_id','id');
    }

    public function stock_purchase(){

    	return $this->hasMany(Purchase::class,'supplier_id','id')->where('type','stock');
    }
    
    public function stockPurchase(){

    	return $this->hasMany(Purchase::class,'supplier_id','id')->whereIn('type',['stock']);
    }
    
}
