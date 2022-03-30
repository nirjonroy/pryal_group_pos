<?php

namespace App\Model\Backend\Admin\Company;

use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\CompanyType;
use App\Sell;
use App\Model\Backend\Admin\Expense\Expense;
use App\Model\Backend\Admin\Project\Project_payment_history;

class Company extends Model
{
    public function projects(){
    	return $this->hasMany(Project::class,'company_id','id')->where("working_status",0);
    }

    public function payments(){
    	return $this->hasMany(Project_payment_history::class,'company_id','id')
                ->join('projects','projects.id','project_payment_histories.project_id')
                ->select('project_payment_histories.*')
                ->where("projects.working_status",0);
    }

    public function expense(){
    	return $this->hasMany(Expense::class,'company_id','id')
                ->join('projects','projects.id','expenses.project_id')
                ->select('expenses.*')
                ->where("projects.working_status",0);
    }

    public function purchase(){
        return $this->hasMany(Purchase::class,'company_id','id')
                ->join('projects','projects.id','purchases.project_id')
                ->select('purchases.*')
                ->where('purchases.type','purchase')
                ->where("projects.working_status",0);
    }

    public function sell(){
        return $this->hasMany(Sell::class,'company_id','id')
                ->join('projects','projects.id','sell.project_id')
                ->select('sell.*')
                ->where('sell.type','sell')
                ->where("projects.working_status",0);
    }

// complete
    public function projects_c(){
        return $this->hasMany(Project::class,'company_id','id')->where("working_status",1);
    }

    public function payments_c(){
        return $this->hasMany(Project_payment_history::class,'company_id','id')
                ->join('projects','projects.id','project_payment_histories.project_id')
                ->select('project_payment_histories.*')
                ->where("projects.working_status",1);
    }

    public function expense_c(){
        return $this->hasMany(Expense::class,'company_id','id')
                ->join('projects','projects.id','expenses.project_id')
                ->select('expenses.*')
                ->where("projects.working_status",1);
    }

    public function purchase_c(){
        return $this->hasMany(Purchase::class,'company_id','id')
                ->join('projects','projects.id','purchases.project_id')
                ->select('purchases.*')
                ->where('purchases.type','purchase')
                ->where("projects.working_status",1);
    }

// Work By Done
    public function projects_w(){
        return $this->hasMany(Project::class,'company_id','id')->where("working_status",2);
    }

    public function payments_w(){
         return $this->hasMany(Project_payment_history::class,'company_id','id')
                ->join('projects','projects.id','project_payment_histories.project_id')
                ->select('project_payment_histories.*')
                ->where("projects.working_status",2);
    }

    public function expense_w(){
        return $this->hasMany(Expense::class,'company_id','id')
                ->join('projects','projects.id','expenses.project_id')
                ->select('expenses.*')
                ->where("projects.working_status",2);
    }

    public function purchase_w(){
         return $this->hasMany(Purchase::class,'company_id','id')
                ->join('projects','projects.id','purchases.project_id')
                ->select('purchases.*')
                ->where('purchases.type','purchase')
                ->where("projects.working_status",2);
    }

// partnership Invetment
    public function projects_p(){
        return $this->hasMany(Project::class,'company_id','id')->where("working_status",3);
    }

    public function payments_p(){
       return $this->hasMany(Project_payment_history::class,'company_id','id')
                ->join('projects','projects.id','project_payment_histories.project_id')
                ->select('project_payment_histories.*')
                ->where("projects.working_status",3);
    }

    public function expense_p(){
       return $this->hasMany(Expense::class,'company_id','id')
                ->join('projects','projects.id','expenses.project_id')
                ->select('expenses.*')
                ->where("projects.working_status",3);
    }

    public function purchase_p(){
        return $this->hasMany(Purchase::class,'company_id','id')
                ->join('projects','projects.id','purchases.project_id')
                ->select('purchases.*')
                ->where('purchases.type','purchase')
                ->where("projects.working_status",3);
    }



    public function type(){

    	return $this->belongsTo(CompanyType::class,'type_id','id');
    }




}
