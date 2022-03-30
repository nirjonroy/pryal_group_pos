<?php

namespace App\Model\Backend\Admin\Expense;

use Illuminate\Database\Eloquent\Model;
use App\ExpenseType;
class Expense_detail extends Model
{
	protected $guarded=['id'];


	public function type()
    {
        return $this->belongsTo(ExpenseType::class,'type_id','id');
    }

}
