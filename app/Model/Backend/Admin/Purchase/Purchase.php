<?php

namespace App\Model\Backend\Admin\Purchase;

use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Purchase\Purchase_details;
use App\Model\Backend\Admin\Supplier\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Payment\Payment_method;
use App\Store;

class Purchase extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','created_by','id');
    }
    
    public function users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function companies()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }


    public function suppliers()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function projects()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(Purchase_details::class,'purchase_id','id');
    }
   

    public function payment_amount()
    {
        return Purchase_payment_history::where('purchase_id',$this->id)->sum('payment_amount');
    }

    public function method(){
         return $this->belongsTo(Payment_method::class,'method_id','id');
    }
    
    

    public function store(){
         return $this->belongsTo(Store::class,'store_id');
    }
    
    public function payments()
    {
        return $this->hasMany(Purchase::class,'transction_id','id')->whereIn('type',['payment','stock_payment']);
    }
    
    public function purchase(){

    	return $this->hasMany(Purchase::class,'supplier_id','id')->where('type','stock');
    }

    
}
