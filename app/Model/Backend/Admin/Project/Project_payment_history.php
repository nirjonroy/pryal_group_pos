<?php

namespace App\Model\Backend\Admin\Project;

use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Payment\Payment_method;
use App\Model\Backend\Admin\Project\Project;
class Project_payment_history extends Model
{
    public function method(){

    	return $this->belongsTo(Payment_method::class,'payment_method_id','id');
    }

    public function project(){

    	return $this->belongsTo(Project::class,'project_id','id');
    }
}
