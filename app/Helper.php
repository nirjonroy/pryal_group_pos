<?php
  	
  	function blanceInHand(){  

  		$amount_in=\App\BankHistory::where('type','in')->sum('amount');
        $amount_out=\App\BankHistory::where('type','out')->sum('amount');
        return $amount_has_total = $amount_in - $amount_out;
  }


function purchaseInHand($id){

        $amount_in=\App\Model\Backend\Admin\Purchase\Purchase::where('supplier_id', $id)->where('type','purchase')->sum('total_price');
        $amount_out=\App\Model\Backend\Admin\Purchase\Purchase::where('supplier_id', $id)->where('type','payment')->sum('total_price');

        return $amount_has_total = $amount_in - $amount_out;

}


function getPurchaseStock($product_id, $store_id){

		$product =  DB::table('products')
				->join('quantity_stores','quantity_stores.product_id', 'products.id')
                ->where('products.id',$product_id)
                ->where('quantity_stores.store_id',$store_id)
                ->select('products.id', 'products.name','products.unit_id',
                    DB::raw('SUM(quantity_stores.quantity_available) as qty'))
                ->groupby('products.id', 'products.name','products.unit_id')
                ->first();

        $stock=0;

        if($product){

        	$stock=$product->qty;

        }

        return $stock;


} 


function getSellStock($product_id, $project_id){
		 $product =  DB::table('products')
							->join('sells_details','sells_details.product_id', 'products.id')
                        ->join('sells','sells_details.sells_id', 'sells.id')
                        ->where('products.id',$product_id)
                        ->where('sells.project_id',$project_id)
                        ->where('sells.type','sell')
                        ->select('products.id', 'products.name','products.unit_id',
                            DB::raw('SUM(sells_details.quantity - sells_details.return_quantity) as qty'))
                        ->groupby('products.id', 'products.name','products.unit_id')
                        ->first();
        $stock=0;
        if($product){

        	$stock=$product->qty;

        }

        return $stock;
} 
