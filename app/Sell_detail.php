<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\Backend\Admin\Product\Product;
use App\Sell;
class Sell_detail extends Model
{
    protected $table = 'sells_details';
    public function products()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function sell()
    {
        return $this->belongsTo(Sell::class,'sells_id','id');
    }
}
