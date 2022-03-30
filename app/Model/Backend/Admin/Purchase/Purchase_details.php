<?php

namespace App\Model\Backend\Admin\Purchase;

use App\Model\Backend\Admin\Product\Product;
use App\Model\Backend\Admin\Purchase\Purchase;
use Illuminate\Database\Eloquent\Model;

class Purchase_details extends Model
{
    public function products()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
}
