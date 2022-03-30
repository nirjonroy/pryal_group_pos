<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Backend\Admin\Project\Project_payment_history;
use App\Model\Backend\Admin\Project\Project;
use App\Model\Backend\Admin\Company\Company;
use App\Model\Backend\Admin\Supplier\Supplier;
use App\Model\Backend\Admin\Payment\Payment_method;
use App\Model\Backend\Admin\Purchase\Purchase_payment_history;
use App\Model\Backend\Admin\Purchase\Purchase;
use App\SupplierType;
use App\Customer;
use App\Sell;
use App\CustomerType;
class PaymentController extends Controller
{
    public function receivedPaymentList(){
    	$com=Company::orderby('id','desc');
    	       if(request()->type_id!=''){
                    $com->where('type_id',request()->type_id);
                }
        $coms=$com->get();
         $pro =Project::orderby('name','asc');
                if(request()->status !=''){
                    $pro->where('working_status',request()->status);
                }

                if(request()->company_id!=''){
                    $pro->where('company_id',request()->company_id);
                }
        $projects=$pro->paginate(30);


    	$query=Project_payment_history::with('method','project','project.companies','project.type');

                if(request()->date_start and request()->date_end !=''){
                    $query->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }
    			if (request()->company_id !='') {
    				$query->where('company_id',request()->company_id);
    			}

                if(request()->project_id!=''){
                    $query->where('project_id',request()->project_id);
                }
    	$rows=$query->latest()->paginate(50);


      if(request()->alldata){
           $rows=$query->latest()->paginate(5000);
          }elseif(request()->date_start and request()->date_end !=''){
             $rows=$query->latest()->paginate(5000);
          }else{
          $rows=$query->latest()->paginate(50);

          }


          $query_2=Project_payment_history::where('payment_accepted_by', '!=', '');
                if(request()->date_start !=''){
                        $query_2->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }
                
                if (request()->company_id !='') {
    				$query_2->where('company_id',request()->company_id);
    			}

                if(request()->project_id!=''){
                    $query_2->where('project_id',request()->project_id);
                }

        $grand=$query_2->sum('payment_amount');
    	return view('payment-list.receive_payment_list',compact('rows','coms','projects','grand'));
    }


    public function supplierPaymentList(){
        $sup=Supplier::orderby('name','asc');
            if (request()->type_id !='') {
                    $sup->where('type_id',request()->type_id);
            }
        $sups=$sup->get();
    	$types=SupplierType::all();
    	$query=Purchase::with('method','suppliers');

                if(request()->date_start and request()->date_end !=''){
                    $query->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }

    			if (request()->supplier_id !='') {
    				$query->where('supplier_id',request()->supplier_id);
    			}
    	$rows=$query->whereIn('type',['stock_payment','payment'])->latest()->paginate(30);
    
        if(request()->alldata){
           $rows=$query->latest()->paginate(5000);
          }elseif(request()->date_start and request()->date_end !=''){
             $rows=$query->latest()->paginate(5000);
          }else{
          $rows=$query->latest()->paginate(50);

          }



        $query_2=Purchase::where('type','payment');
            if(request()->date_start and request()->date_end !=''){
                    $query_2->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }
            if (request()->supplier_id !='') {
    				$query_2->where('supplier_id',request()->supplier_id);
    			}
        // dd($rows);
        $grand=$query_2->sum('total_price');
    	return view('payment-list.supplier_payment_list',compact('rows','sups','types','grand'));
    }



    public function customerPaymentList(){
        $cus=Customer::orderby('name','asc');
            if (request()->type_id !='') {
                    $cus->where('type_id',request()->type_id);
            }
        $cuss=$cus->get();
        $types=CustomerType::all();
        $query=Sell::with('method','customer');

                if(request()->date_start and request()->date_end !=''){
                    $query->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }

                if (request()->customer_id !='') {
                    $query->where('customer_id',request()->customer_id);
                }
        $rows=$query->whereIn('type',['stock_payment','payment'])->latest()->paginate(30);
    
        if(request()->alldata){
           $rows=$query->latest()->paginate(5000);
          }elseif(request()->date_start and request()->date_end !=''){
             $rows=$query->latest()->paginate(5000);
          }else{
          $rows=$query->latest()->paginate(50);

          }



        $query_2=Sell::where('type','payment');
            if(request()->date_start and request()->date_end !=''){
                    $query_2->whereBetween('created_at',[request()->date_start.' 00:00:00',request()->date_end.' 23:59:00']);
                }
            if (request()->customer_id !='') {
                    $query_2->where('customer_id',request()->customer_id);
                }
       
        $grand=$query_2->sum('total_price');
        // dd($rows);
        return view('payment-list.customer_payment_list',compact('rows','cuss','types','grand'));
    }


    public function supplierPaymentEdit($id){
        $sups=Supplier::all();
    	$methods=Payment_method::all();
    	$row=Purchase::with('method','suppliers')->find($id);

    	return view('payment-list.supplier_payment_edit',compact('row','methods','sups'));
    }

    public function customerPaymentEdit($id){
        $cuss=Customer::all();
        $methods=Payment_method::all();
        $row=Sell::with('method','customer')->find($id);

        return view('payment-list.customer_payment_edit',compact('row','methods','cuss'));
    }


    public function supplierPaymentUpdate(){

               $purchase  = Purchase::find(request()->id);
               $get_up_data = Purchase::where('id', '>', $purchase->id)->get();               
               $req_amount = request()->amount;
 
                $data=[];
                $data['supplier_id']=request('supplier_id');
              if($req_amount > $purchase->total_price)
              {  
                $grater_amount = $req_amount - $purchase->total_price;
                    $data=[
                    'method_id'=>request()->payment_method_id,
                    'total_price'=>request()->amount,
                    'description'=>request()->note,
                    'cash_hand'=>$purchase->cash_hand - $grater_amount

                   ];

                  Purchase::where('id',request()->id)->update($data);
                
                 if (!empty($get_up_data)) 
                 {
                    foreach ($get_up_data as  $data) {
                        $data->cash_hand = $data->cash_hand - $grater_amount;
                        $data->save();
                    }

                 }
              }elseif ($req_amount < $purchase->total_price) 
              {
                  $less_amount = $purchase->total_price - $req_amount;

                   $data=[
                    'method_id'=>request()->payment_method_id,
                    'total_price'=>request()->amount,
                    'description'=>request()->note,
                    'cash_hand'=>$purchase->cash_hand + $less_amount,

                   ];

                  Purchase::where('id',request()->id)->update($data);
                
                 if (!empty($get_up_data)) 
                 {
                    foreach ($get_up_data as  $data) {
                        $data->cash_hand = $data->cash_hand + $less_amount;
                        $data->save();
                    }

              }

         }elseif ($req_amount == $purchase->total_price) {
             $data=[
                    'method_id'=>request()->payment_method_id,
                    'total_price'=>request()->amount,
                    'description'=>request()->note,
                    'cash_hand'=>$purchase->cash_hand

                   ];

                Purchase::where('id',request()->id)->update($data);
        }
        
    	return back()->with('success',' Update successfully Done!');
    }


    public function customerPaymentUpdate(){

               $sell  = Sell::find(request()->id);
               $get_up_data = Sell::where('id', '>', $sell->id)->get();               
               $req_amount = request()->amount;
 
                $data=[];
                $data['customer_id']=request('customer_id');
              if($req_amount > $sell->total_price)
              {  
                $grater_amount = $req_amount - $sell->total_price;
                    $data=[
                    'method_id'=>request()->payment_method_id,
                    'total_price'=>request()->amount,
                    'description'=>request()->note,
                    'cash_hand'=>$purchase->cash_hand - $grater_amount

                   ];

                  Sell::where('id',request()->id)->update($data);
                
                 if (!empty($get_up_data)) 
                 {
                    foreach ($get_up_data as  $data) {
                        $data->cash_hand = $data->cash_hand - $grater_amount;
                        $data->save();
                    }

                 }
              }elseif ($req_amount < $sell->total_price) 
              {
                  $less_amount = $sell->total_price - $req_amount;

                   $data=[
                    'method_id'=>request()->payment_method_id,
                    'total_price'=>request()->amount,
                    'description'=>request()->note,
                    'cash_hand'=>$sell->cash_hand + $less_amount,

                   ];

                  Sell::where('id',request()->id)->update($data);
                
                 if (!empty($get_up_data)) 
                 {
                    foreach ($get_up_data as  $data) {
                        $data->cash_hand = $data->cash_hand + $less_amount;
                        $data->save();
                    }

              }

         }elseif ($req_amount == $sell->total_price) {
             $data=[
                    'method_id'=>request()->payment_method_id,
                    'total_price'=>request()->amount,
                    'description'=>request()->note,
                    'cash_hand'=>$sell->cash_hand

                   ];

                Sell::where('id',request()->id)->update($data);
        }
        
        return back()->with('success',' Update successfully Done!');
    }



    public function receivedPaymentEdit($id){
        
        $projects=Project::all();
        $coms=Company::all();
    	$methods=Payment_method::all();
    	$row=Project_payment_history::with('method','project','project.companies')->find($id);
    	return view('payment-list.receive_payment_edit',compact('row','methods','coms','projects'));
    }

    public function receivedPaymentUpdate(){

    	$data=[
    			'company_id'=>request()->company_id,
    			'project_id'=>request()->project_id,
    			'payment_method_id'=>request()->payment_method_id,
    			'payment_amount'=>request()->amount,
    			'note'=>request()->note,
                'created_at'=>request()->date,
    		];
    		Project_payment_history::where('id',request()->id)->update($data);
    		return back()->with('success',' Update successfully Done!');
    }

    public function supplierPaymentDetails($id){
        $row=Purchase::with('method','suppliers')->find($id);
        return view('payment-list.supplier_payment_details',compact('row'));
    }

    public function customerPaymentDetails($id){
        $row=Sell::with('method','customer')->find($id);
        return view('payment-list.customer_payment_details',compact('row'));
    }
    
    public function supplierPaymentDelete($id){
            $purchase=Purchase::find($id);


                $get_up_data = Purchase::where('supplier_id', $purchase->supplier_id)
                                         ->where('id', '>', $id)
                                         ->get(); 
                                 

           if ($purchase->type== 'purchase') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand - $purchase->total_price;
                    $value->save();
                 }
             }
         }elseif($purchase->type=='payment') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand + $purchase->total_price;
                    $value->save();
                 }
             }
         }
        
        
        $purchase->delete();
        	return back()->with('success',' Deletes successfully Done!');
    }
    
     public function customerPaymentDelete ($id){
            $sell=Sell::find($id);


                $get_up_data = Sell::where('customer_id', $sell->customer_id)
                                         ->where('id', '>', $id)
                                         ->get(); 
                                 

           if ($sell->type== 'sell') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand - $sell->total_price;
                    $value->save();
                 }
             }
         }elseif($sell->type=='payment') {
             if (!empty($get_up_data)) {
                 foreach ($get_up_data as  $value) {
                    $value->cash_hand = $value->cash_hand + $sell->total_price;
                    $value->save();
                 }
             }
         }
        
        
        $sell->delete();
        	return back()->with('success',' Deletes successfully Done!');
    }
    
    
    
    public function receivePaymentDelete($id){
            Project_payment_history::find($id)->delete();
        	return back()->with('success',' Deletes successfully Done!');
    }

    public function receivePaymentDetails($id){
        $row=Project_payment_history::with('method','project','project.companies','project.type')->find($id);
        return view('payment-list.receive_payment_details',compact('row'));
    }
    
}
