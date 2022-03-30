<?php

namespace App\Model\Backend\Admin\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Payment\Payment_method;
class Purchase_payment_history extends Model
{
	public function method(){

    	return $this->belongsTo(Payment_method::class,'payment_method_id','id');
    }

    public function purchase(){

    	return $this->belongsTo(Purchase::class,'purchase_id','id');
    }

    public function supplier(){

    	return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
}
