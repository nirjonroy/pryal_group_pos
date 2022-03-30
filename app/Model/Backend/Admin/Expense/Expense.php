<?php

namespace App\Model\Backend\Admin\Expense;

use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\User;
use App\ExpenseCategory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded=['id'];
    public function companies()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }
    public function projects()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function expenseDetails()
    {
        return $this->hasMany(Expense_detail::class,'expense_id','id');
    }

    public function totalAmount()
    {
        return Expense_detail::where('expense_id',$this->id)->sum('total_price');
    }


    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class,'category_id','id');
    }

}
