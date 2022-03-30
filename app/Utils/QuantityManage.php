<?php
namespace App\Utils;
use App\Sell;
use App\QuantityStore;
use App\Model\Backend\Admin\Product\Product;
use App\Model\Backend\Admin\Purchase\Purchase_details;
use App\Model\Backend\Admin\Purchase\Purchase;
use DB;
class QuantityManage
{
	public function updateQuantity($product_id,$store_id,$new_quantity, $old_quantity=0){

		$stock=$new_quantity-$old_quantity;

		$quantity_store=QuantityStore::where(['product_id'=>$product_id,'store_id'=>$store_id])->first();
		$old_stock=QuantityStore::where(['product_id'=>$product_id])->sum('quantity_available');

		if ($quantity_store) {
			$quantity_store->quantity_available +=$stock;
		}else{
			
			$quantity_store=new QuantityStore();
			$quantity_store->product_id=$product_id;
			$quantity_store->store_id=$store_id;
			$quantity_store->quantity_available=$stock;
		}
		$quantity_store->save();

		return true;
	}
	
	public function PriceUpdate($product_id){
	    
	    $product=Product::find($product_id);
		$new=Purchase_details::where('product_id',$product_id)->latest()->first();
		
		if($product->unit_price != $new->unit_price){
		    $product->unit_price=$new->unit_price;
		    $product->save();
		}
		
		return true;
		
	}


	public function decreaseQuantity($product_id,$store_id,$new_quantity, $old_quantity=0){
		$stock=$new_quantity-$old_quantity;
		$quantity_store=QuantityStore::where(['product_id'=>$product_id,'store_id'=>$store_id])
						->where('quantity_available','>=',$stock)
						->first();

		if ($quantity_store) {

			$quantity_store->quantity_available -=$stock;
			$quantity_store->save();
		}else{
			throw new \Exception("Stock Not Available For Decrease");
		}
		

		return true;
	}



	public function updateQuantityNew($product_id,$store_id,$new_quantity, $old_quantity=0){

		$stock=$new_quantity-$old_quantity;

		$quantity_store=QuantityStore::where(['product_id'=>$product_id,'store_id'=>$store_id])->first();

		if ($quantity_store) {
			$quantity_store->quantity_available +=$stock;
		}else{
			
			$quantity_store=new QuantityStore();
			$quantity_store->product_id=$product_id;
			$quantity_store->store_id=$store_id;
			$quantity_store->quantity_available=$stock;
		}
		$quantity_store->save();
		return true;
	}
	
	
	public function returnQuantity($product_id,$project_id,$new_quantity, $old_quantity=0){
        
        $qty_selling = $new_quantity - $old_quantity;
        
        $rows=DB::table('sells_details')
                ->join('sells','sells.id','sells_details.sells_id')
                ->where('sells.type','sell')
                ->where('sells.project_id',$project_id)
                ->where('sells_details.product_id',$product_id)
                ->WhereRaw("(sells_details.quantity -sells_details.return_quantity) > 0")
                ->select('sells_details.id',DB::raw("(sells_details.quantity -sells_details.return_quantity) as quantity_available"))
                ->get();
        
        if($qty_selling !=0){
            foreach ($rows as $k => $row) {
                $qty_allocated = 0;
            
                //Check if qty_available is more or equal
                if ($qty_selling <= $row->quantity_available) {
                    $qty_allocated = $qty_selling;
                    $qty_selling = 0;
                    
                    
                } else {
                    $qty_selling = $qty_selling - $row->quantity_available;
                    $qty_allocated = $row->quantity_available;
                }
                
                DB::table('sells_details')->where('id',$row->id)->increment('return_quantity',$qty_allocated);
            
                if ($qty_selling == 0) {
                    break;
                }
            }
            
        }
        
    
		
	}
	
	
	public function returnUpdateQuantity($product_id,$project_id,$new_quantity){
        
        $qty_selling = $new_quantity;
        
        $rows=DB::table('sells_details')
                ->join('sells','sells.id','sells_details.sells_id')
                ->where('sells.type','sell')
                ->where('sells.project_id',$project_id)
                ->where('sells_details.product_id',$product_id)
                ->orderBy('sells_details.return_quantity','desc')
                ->get();
        
        if($qty_selling !=0){
            foreach ($rows as $k => $row) {
                $qty_allocated = 0;
            
                //Check if qty_available is more or equal
                if ($qty_selling <= $row->return_quantity) {
                    $qty_allocated = $qty_selling;
                    $qty_selling = 0;
                    
                    
                } else {
                    $qty_selling = $qty_selling - $row->return_quantity;
                    $qty_allocated = $row->return_quantity;
                }
                
                DB::table('sells_details')->where('id',$row->id)->decrement('return_quantity',$qty_allocated);
            
                if ($qty_selling == 0) {
                    break;
                }
            }
            
        }
        
    
		
	}
	
	public function sellStatusUpdate($sell_id){
	    $sell=Sell::where('type','sell')->find($sell_id);
	    
	    if($sell){
    	    $paid=$sell->payments()->sum('total_price');
    	    
    	    if($paid ==0){
    	        $status='due';
    	    }else if($sell->total_price <= $paid){
    	        $status='paid';
    	    }else if( $sell->total_price > $paid){
    	         $status='partial';
    	    }
    	    
    	    $sell->status=$status;
    	    $sell->save();
    	    return true;
	    }
	    
	    
	}
	
	public function purchaseStatusUpdate($purchase_id){
	    $purchase=Purchase::where('type','stock')->find($purchase_id);
	    
	    if($purchase){
    	    $paid=$purchase->payments()->sum('total_price');
    	    
    	    if($paid ==0){
    	        $status='due';
    	    }else if($purchase->total_price <= $paid){
    	        $status='paid';
    	    }else if( $purchase->total_price > $paid){
    	         $status='partial';
    	    }
    	    
    	    $purchase->status=$status;
    	    $purchase->save();
    	    return true;
	    }
	    
	    
	}

	
}