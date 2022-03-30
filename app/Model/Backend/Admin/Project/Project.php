<?php

namespace App\Model\Backend\Admin\Project;

use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Project\Project_payment_history;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\Model\Backend\Admin\Expense\Expense;
use Illuminate\Database\Eloquent\Model;
use App\Sell;
class Project extends Model
{
    public function companies()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function type()
    {
        return $this->belongsTo('App\ProjectType','project_type_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id');
    }

    public function projectPayment(){

    		return $this->hasMany(Project_payment_history::class,'project_id','id')
                        ->orderBy('created_at','asc');
    }

    public function purchase(){

    		return $this->hasMany(Purchase::class,'project_id','id')->where('type','purchase');
    }

    public function sell(){

            return $this->hasMany(Sell::class,'project_id','id')->where('type','sell');
    }
    public function expense(){

    		return $this->hasMany(Expense::class,'project_id','id');
    }



    public function sell_return(){

            return $this->hasMany(Sell::class,'project_id','id')->where('type','stock_return');
    }

}
