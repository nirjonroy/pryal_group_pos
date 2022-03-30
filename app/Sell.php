<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Sell_detail;
use App\Store;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\Model\Backend\Admin\Payment\Payment_method;
class Sell extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','created_by','id');
    }

    public function users()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id','id');
    }
    
    
    
    public function companies()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }


    
    public function projects()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id','id');
    }


    public function sellDetails()
    {
        return $this->hasMany(Sell_detail::class,'sells_id','id');
    }
   

    public function payment_amount()
    {
        return Purchase_payment_history::where('purchase_id',$this->id)->sum('payment_amount');
    }
    
     public function payments()
    {
        return $this->hasMany(Sell::class,'transction_id','id')->where('type','payment');
    }

    public function method(){
         return $this->belongsTo(Payment_method::class,'method_id','id');
    }
    
    
    
}
